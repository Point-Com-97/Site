<?php
require_once __DIR__ . '/../../data/config/database.php';

class Settings
{
    private PDO $pdo;

    public function __construct() // Injection direct de page_id via le constructeur
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }

    public function get()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM site_settings WHERE id = 1");

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return false;
        }
    }

    public function update(array $donnees)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE site_settings SET
            couleur_primaire = ?, couleur_secondaire = ?, couleur_tertiaire = ?,
            police_corps = ?, couleur_texte_bouton = ?, couleur_lien = ?, css_personnalise = ?
            WHERE id = 1");

            $stmt->execute([
                $donnees['couleur_primaire'],
                $donnees['couleur_secondaire'],
                $donnees['couleur_tertiaire'],
                $donnees['police_corps'],
                $donnees['couleur_texte_bouton'],
                $donnees['couleur_lien'],
                $donnees['css_personnalise'],
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise a jour : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }
}
