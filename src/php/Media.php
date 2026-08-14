<?php
require_once __DIR__ . '/../../data/config/database.php';

class Media
{
    private $pdo;

    public function __construct(protected int $id) // Injection direct du slug via le constructeur
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }

    public function getById()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM medias WHERE id = ?");

            $stmt->execute([$this->id]);
            
           return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage(),3, __DIR__ . "../../var/tmp/erreur.log");
            
            return $stmt = [];
        }
    }
}