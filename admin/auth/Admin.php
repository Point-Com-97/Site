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
            // Requête SQL pour récupérer les éléments du menu avec les slugs des pages associées via une jointure et alias pour les colonnes
            $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = ?");

            $stmt->execute([$username]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(),3, __DIR__ . "../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }
}
