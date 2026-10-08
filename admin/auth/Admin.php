<?php
require_once __DIR__ . '/../../data/config/database.php';


class Admin

{

    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }


    public function getByUsername($username)
    {

        try {
            // Préparer la requête SQL pour récupérer l'administrateur par nom d'utilisateur
            $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = ?");

            $stmt->execute([$username]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(),3, __DIR__ . "../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }
}
