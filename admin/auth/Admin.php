<?php
/**
 * Classe d'authentification admin.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../data/config/database.php';
require_once __DIR__ . '/../../src/php/Logger.php';

class Admin
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getConnexion();
    }

    /**
     * Récupère un admin par son nom d'utilisateur.
     */
    public function getByUsername(string $username): array|false
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_exception($e, 'Admin::getByUsername');
            return false;
        }
    }
}
