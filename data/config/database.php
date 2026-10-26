<?php
/**
 * Connexion PDO sécurisée à la base de données.
 * Les credentials sont chargés depuis le fichier .env (hors versioning).
 */

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/config/env.php';

function getConnexion(): PDO
{
    $host = env('DB_HOST', 'localhost');
    $dbname = env('DB_NAME', 'db_pcom');
    $user = env('DB_USER', 'root');
    $password = env('DB_PASS', '');

    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ];

    try {
        return new PDO($dsn, $user, $password, $options);
    } catch (PDOException $e) {
        error_log("Erreur de connexion BDD : " . $e->getMessage() . "\n", 3, dirname(__DIR__, 2) . "/var/tmp/erreur.log");
        throw new RuntimeException("Impossible de se connecter à la base de données.");
    }
}
