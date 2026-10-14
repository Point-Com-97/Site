<?php
require_once __DIR__ . '/../../data/config/database.php';
require_once __DIR__ . '/../config/menus.php';

class Page
{
    private PDO $pdo;

    public function __construct() // Injection direct du slug via le constructeur
    {
        $this->pdo = getConnexion(); // Connexion a la base de données

    }

    public function getBySlug(string $slug)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE slug = ?");
            $stmt->execute([$slug]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data == false) {
                return '404';
            } else {
                return $data;
            }
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return "404";
        }
    }

    public function getAll()
    {
        try {
            // Requête SQL pour récupérer les éléments du menu avec les slugs des pages associées via une jointure et alias pour les colonnes
            $stmt = $this->pdo->query("SELECT * FROM pages ORDER BY titre");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }


    // Transforme le titre en menu en slug
    public function slug_menu(string $menu): string
    {
        $slug = trim($menu);

        // Accents : é -> e, à -> a, etc. (extension intl)
        if (function_exists('transliterator_transliterate')) {
            $slug = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $slug);
        } else {
            $slug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug));
        }

        // Tout ce qui n'est pas une lettre ou un chiffre devient un tiret
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        $slug = preg_replace('/^-+|-+$-+"-+/', '', $slug);

        $slug = preg_replace("/'/", '', $slug);

        return trim($slug, '-') ?: 'page';
    }

    // Génère un slug unique en ajoutant un suffixe numérique si nécessaire
    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 2;
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pages WHERE slug = ?");

        while (true) {
            $stmt->execute([$slug]);
            if ((int) $stmt->fetchColumn() === 0) {
                return $slug;
            }
            $slug = $base . '-' . $i++;
        }
    }

    public function create(string $titre, string $menu_slug, ?string $meta_description = null)
    {

        try {
            if (!empty($titre)) {

                $page_titre = trim($titre);

                $slug = $this->uniqueSlug($this->slug_menu($page_titre));

                if (!menu_validation($menu_slug)) {
                    return false;
                }

                if (!banned_menu_slug($slug)) {
                    return false;
                }

                $stmt = $this->pdo->prepare("SELECT MAX(ordre) as max_ordre FROM pages WHERE menu_slug = ?");
                $stmt->execute([$menu_slug]);

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $order = ($result['max_ordre'] ?? 0) + 1;

                $systeme = 0;

                $stmt = $this->pdo->prepare("INSERT INTO pages (titre, slug, ordre, meta_description, menu_slug, systeme) VALUES (?, ?, ?, ?, ?,?)");

                $stmt->execute([$page_titre, $slug, $order, $meta_description, $menu_slug, $systeme]);

                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la creation " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function update(int $id, string $titre, string $menu_slug, ?string $meta_description = null)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE pages SET titre = ?, meta_description = ?, menu_slug = ? WHERE id = ?");
            $stmt->execute([$titre, $meta_description, $menu_slug, $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise a jour : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function update_ordre(int $id, int $nouvelOrdre)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE pages SET ordre = ? WHERE id = ?");
            $stmt->execute([$nouvelOrdre, $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise a jour : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function delete(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE id = ?");

            $stmt->execute([$id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return false;
            }

            $stmt = $this->pdo->prepare("DELETE FROM pages WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function getByMenu()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM pages ORDER BY menu_slug, ordre");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return [];
        }
    }

    public function getByMenuOn()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM pages WHERE visible = 1 ORDER BY menu_slug, ordre");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return [];
        }
    }


    public function toggle_visible(int $id)

    {

        try {
            $stmt = $this->pdo->prepare("UPDATE pages SET visible = NOT visible WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise a jour : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }


    public function getById(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data == false) {
                return '404';
            } else {
                return $data;
            }
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return "404";
        }
    }

    public function search(string $terme): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT titre, slug FROM pages WHERE visible = 1 AND titre LIKE ? LIMIT 10");
            $stmt->execute(['%' . $terme . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur de recherche : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return [];
        }
    }
}



// $a = New Page('acceuil');

// $a::getBySlug(); Appel une méthode static

// $a->getBySlug(); Appel une méthode public