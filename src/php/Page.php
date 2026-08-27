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

    public function create(string $titre, ?int $menu_id)
    {

        try {
            if (!empty($titre)) {

                $page_titre = trim($titre);

                $slug = strtolower(str_replace(' ', '-', $page_titre));

                $stmt = $this->pdo->prepare("INSERT INTO pages (titre, slug, menu_id) VALUES (?, ?, ?)");

                $stmt->execute([$page_titre, $slug, $menu_id]);

                return $this->pdo->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la creation " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function update(int $id, string $titre)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE pages SET titre = ? WHERE id = ?");
            $stmt->execute([$titre, $id]);
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

}



// $a = New Page('acceuil');

// $a::getBySlug(); Appel une méthode static

// $a->getBySlug(); Appel une méthode public