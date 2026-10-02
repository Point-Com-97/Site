<?php
require_once __DIR__ . '/../../data/config/database.php';

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

        private function slugify(string $texte): string
    {
        $texte = trim($texte);

        // Accents : é -> e, à -> a, etc. (extension intl)
        if (function_exists('transliterator_transliterate')) {
            $texte = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $texte);
        } else {
            $texte = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texte));
        }

        // Tout ce qui n'est pas une lettre ou un chiffre devient un tiret
        $texte = preg_replace('/[^a-z0-9]+/', '-', $texte);

        return trim($texte, '-') ?: 'page';
    }

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

    public function create(string $titre, ?int $menu_id, ?string $meta_description = null)
    {

        try {
            if (!empty($titre)) {

                $page_titre = trim($titre);

                $slug = $this->uniqueSlug($this->slugify($page_titre));

                $stmt = $this->pdo->prepare("SELECT MAX(ordre) as max_ordre FROM pages WHERE menu_id <=> ?");
                $stmt->execute([$menu_id]);

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $order = ($result['max_ordre'] ?? 0) + 1;

                $stmt = $this->pdo->prepare("INSERT INTO pages (titre, slug, menu_id, ordre, meta_description) VALUES (?, ?, ?, ?, ?)");

                $stmt->execute([$page_titre, $slug, $menu_id, $order, $meta_description]);

                return $this->pdo->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la creation " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function update(int $id, string $titre, ?string $meta_description = null)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE pages SET titre = ?, meta_description = ? WHERE id = ?");
            $stmt->execute([$titre, $meta_description, $id]);
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
            $stmt = $this->pdo->query("SELECT * FROM pages ORDER BY menu_id, ordre");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return [];
        }
    }

        public function getByMenuOn()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM pages WHERE visible = 1 ORDER BY menu_id, ordre");
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