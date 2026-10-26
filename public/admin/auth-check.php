<?php
/**
 * Vérification d'authentification admin.
 * Redirige vers la page de login si non connecté.
 * À inclure en début de chaque page admin.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../src/config/session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_id'])) {
    // Nettoyer la session en cas d'inactivité
    header('Location: /admin/login.php');
    exit;
}

// Vérification de l'expiration de session (2 heures d'inactivité max)
$sessionTimeout = 7200;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $sessionTimeout) {
    // Session expirée
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    header('Location: /admin/login.php?expired=1');
    exit;
}

// Mettre à jour le timestamp d'activité
$_SESSION['last_activity'] = time();
