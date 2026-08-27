<?php
require_once __DIR__ . '/../../data/config/database.php';

class Bloc
{
    private PDO $pdo;

    public function __construct() // Injection direct de page_id via le constructeur
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }

    public function getByPageId(int $page_id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM blocs WHERE page_id = ? ORDER BY ordre");

            $stmt->execute([$page_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return false;
        }
    }

    public function create(int $page_id, string $type, array $donnees, int $ordre)
    {
        try {

            $data = json_encode($donnees);

            $stmt = $this->pdo->prepare("INSERT INTO blocs (page_id, type, donnees, ordre) VALUES (?, ?, ?, ?)");

            $stmt->execute([$page_id, $type, $data, $ordre]);

            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return false;
        }
    }
}
