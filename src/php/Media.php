<?php
require_once __DIR__ . '/../../data/config/database.php';

class Media
{
    private $pdo;

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
            error_log("Erreur de requête SQL : " . $e->getMessage(),3, __DIR__ . "/../../var/tmp/erreur.log");
            
            return $stmt = [];
        }
    }


    public function getAll()
    {
        try {
            // Requête SQL pour récupérer les éléments du menu avec les slugs des pages associées via une jointure et alias pour les colonnes
            $stmt = $this->pdo->query("SELECT * FROM medias ORDER BY medias.created_at");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {

             error_log("Erreur de requête SQL : " . $e->getMessage(),3,__DIR__ . "/../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }
}