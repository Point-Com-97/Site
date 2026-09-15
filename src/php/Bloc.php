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

    public function create(int $page_id, string $type, array $donnees)
    {
        try {

            $stmt = $this->pdo->prepare("SELECT MAX(ordre) as max_ordre FROM blocs WHERE page_id = ?");

            $stmt->execute([$page_id]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $ordre = ($result['max_ordre'] ?? 0) + 1;

            $data = json_encode($donnees);

            $stmt = $this->pdo->prepare("INSERT INTO blocs (page_id, type, donnees, ordre) VALUES (?, ?, ?, ?)");

            $stmt->execute([$page_id, $type, $data, $ordre]);

            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return false;
        }
    }

        public function delete(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM blocs WHERE id = ?");

            $stmt->execute([$id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return false;
            }

            $stmt = $this->pdo->prepare("DELETE FROM blocs WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function update(int $id, array $donnees)
{
    try {
        $data = json_encode($donnees);
        $stmt = $this->pdo->prepare("UPDATE blocs SET donnees = ? WHERE id = ?");
        $stmt->execute([$data, $id]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise a jour : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
        return false;
    }
}
}
