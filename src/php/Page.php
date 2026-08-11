<?php
require_once __DIR__ . '/../../data/config/database.php';

class Page {
    private $pdo;

    public function __construct(protected string $slug) 
    {
        $this->pdo = getConnexion();
        $this->slug = $slug;
    }

    public function getBySlug()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE slug = ?");
            $stmt->execute([$this->slug]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data == false) {
                return '404';
            } else {
                return $data;
            }

        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage());
            return "404";
        }
    }
}

// $a = New Page('acceuil');

// $a::getBySlug(); Appel une méthode static

// $a->getBySlug(); Appel une méthode public