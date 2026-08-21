<?php
require_once __DIR__ . '/../../data/config/database.php';

class Media
{
    private PDO $pdo;

    public function __construct() // Injection direct de id via le constructeur
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }

    public function getById(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM medias WHERE id = ?");

            $stmt->execute([$id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }


    public function getAll(string $tri = 'created_at')
    {
        $colonnes_autorisees = ['created_at', 'titre'];
        if (!in_array($tri, $colonnes_autorisees)) {
            $tri = 'created_at'; // valeur par défaut si quelque chose d'inattendu arrive
        }
        try {
            // Requête SQL pour récupérer les éléments du menu avec les slugs des pages associées via une jointure et alias pour les colonnes
            $stmt = $this->pdo->query("SELECT * FROM medias ORDER BY $tri");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }

    public function delete(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM medias WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return false;
            }

            $url_media = __DIR__ . '/../../public' . $data['url'];

            if (file_exists($url_media)) {
                unlink($url_media);
            }

            $stmt = $this->pdo->prepare("DELETE FROM medias WHERE id = ?");
            $stmt->execute([$id]);

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }
}
