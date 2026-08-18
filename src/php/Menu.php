<?php
require_once __DIR__ . '/../../data/config/database.php';

class Menu
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }

    public function getAllMenu()
    {
        try {
            // Requête SQL pour récupérer les éléments du menu avec les slugs des pages associées via une jointure et alias pour les colonnes
            $stmt = $this->pdo->query("SELECT menu_items.id AS menu_id, menu_items.titre AS menu_titre, menu_items.ordre, pages.slug AS page_slug FROM menu_items INNER JOIN pages ON menu_items.page_id = pages.id ORDER BY menu_items.ordre");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {

             error_log("Erreur de requête SQL : " . $e->getMessage(),3,__DIR__ . "/../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }
}
