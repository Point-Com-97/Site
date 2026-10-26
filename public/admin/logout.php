<?php
/**
 * Déconnexion admin.
 * Protection CSRF pour éviter la déconnexion forcée par un attaquant.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../src/config/session.php';
require_once __DIR__ . '/../../src/php/Csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier le token CSRF (en POST ou GET avec confirmation)
$token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? null;

if (!verifier_csrf_token($token)) {
    // Token invalide : rediriger sans déconnecter
    header('Location: /admin/dashboard.php');
    exit;
}

// Détruire proprement la session
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

// Rediriger vers l'accueil (pas /accueil qui redirige vers /)
header('Location: /');
exit;
