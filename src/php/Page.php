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
            error_log("Erreur de requête SQL : " . $e->getMessage(),3, __DIR__ . "/../../var/tmp/erreur.log");

            return "404";
        }
    }

        public function getAll()
    {
        try {
            // Requête SQL pour récupérer les éléments du menu avec les slugs des pages associées via une jointure et alias pour les colonnes
            $stmt = $this->pdo->query("SELECT * FROM pages ORDER BY titre ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }
}

// $a = New Page('acceuil');

// $a::getBySlug(); Appel une méthode static

// $a->getBySlug(); Appel une méthode public