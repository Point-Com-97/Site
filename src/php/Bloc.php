<?php
require_once __DIR__ . '/../../data/config/database.php';

class Bloc
{
    private PDO $pdo;

    public function __construct(protected int $page_id) // Injection direct de page_id via le constructeur
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }

    public function getByPageId()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM blocs WHERE page_id = ? ORDER BY ordre");

            $stmt->execute([$this->page_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(),3, __DIR__ . "/../../var/tmp/erreur.log");
            return $stmt = [];
        }
    }
}
