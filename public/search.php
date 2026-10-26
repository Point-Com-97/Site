<?php
/**
 * Endpoint de recherche AJAX.
 * Rate limiting : max 30 requêtes par minute par session.
 */

declare(strict_types=1);

require_once __DIR__ . '/../src/config/session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/php/Page.php';

header('Content-Type: application/json; charset=utf-8');

// Rate limiting : 30 requêtes par minute
$now = time();
if (!isset($_SESSION['search_requests'])) {
    $_SESSION['search_requests'] = [];
}
// Nettoyer les requêtes de plus d'une minute
$_SESSION['search_requests'] = array_filter(
    $_SESSION['search_requests'],
    fn($t) => ($now - $t) < 60
);
if (count($_SESSION['search_requests']) >= 30) {
    http_response_code(429);
    echo json_encode(['error' => 'Trop de requêtes, veuillez réessayer dans quelques secondes.']);
    exit;
}
$_SESSION['search_requests'][] = $now;

$terme = $_GET['q'] ?? '';

// Valider le terme de recherche
$terme = trim((string) $terme);
if (mb_strlen($terme) < 2) {
    echo json_encode([]);
    exit;
}

// Limiter la longueur du terme (anti-abus)
if (mb_strlen($terme) > 100) {
    echo json_encode([]);
    exit;
}

$page = new Page();
echo json_encode($page->search($terme));
