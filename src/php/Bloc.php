<?php
require_once __DIR__ . '/../../data/config/database.php';

class Bloc {
    private $pdo;

    public function __construct(protected int $page_id) 
    {
        $this->pdo = getConnexion();
    }

    public function getByPageId()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM blocs WHERE page_id = ? ORDER BY ordre");

            $stmt->execute([$this->page_id]);

            return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage());
        }
    }
}
