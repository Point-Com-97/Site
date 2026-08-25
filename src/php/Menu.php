<?php
require_once __DIR__ . '/../../data/config/database.php';

class Menu
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getConnexion(); // Connexion a la base de données
    }


    public function sort_menu(array $items): array
    {
        $main = [];
        $child = [];

        foreach ($items as $item) {
            if ($item['parent_id'] === null) {
                $main[] = $item;
            } else {
                $child[$item['parent_id']][] = $item;
            }
        }

        foreach ($main as &$m) {
            $m['enfants'] = $child[$m['menu_id']] ?? [];
        }

        return $main;
    }


    public function getAll()
    {
        try {
            // Requête SQL pour récupérer les éléments du menu avec des pages associées via une jointure et alias pour les colonnes
            $stmt = $this->pdo->query("SELECT menu_items.id AS menu_id, menu_items.titre AS menu_titre, menu_items.ordre, menu_items.parent_id, pages.slug AS page_slug FROM menu_items LEFT JOIN pages ON menu_items.page_id = pages.id ORDER BY menu_items.ordre");

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = $this->sort_menu($data);

            return $result;
            
        } catch (PDOException $e) {

            error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");

            return $stmt = [];
        }
    }

    public function create(string $titre, ?int $page_id = null, ?int $parent_id = null)
    {

        try {
            if (!empty($titre)) {

                $menu_titre = trim($titre);

                $stmt = $this->pdo->query("SELECT MAX(ordre) AS max_ordre FROM menu_items");

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $ordre = ($result['max_ordre'] ?? 0) + 1;

                $stmt = $this->pdo->prepare("INSERT INTO menu_items (titre, ordre, page_id, parent_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$menu_titre, $ordre, $page_id, $parent_id]);

                return $this->pdo->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la creation " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

        public function update(int $id, string $titre)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE menu_items SET titre = ? WHERE id = ?");
            $stmt->execute([$titre, $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise a jour : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }


    public function update_ordre(int $id, int $nouvelOrdre)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE menu_items SET ordre = ? WHERE id = ?");
            $stmt->execute([$nouvelOrdre, $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise a jour : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }

    public function delete(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM menu_items WHERE id = ?");

            $stmt->execute([$id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return false;
            }

            $stmt = $this->pdo->prepare("DELETE FROM menu_items WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log");
            return false;
        }
    }
}
