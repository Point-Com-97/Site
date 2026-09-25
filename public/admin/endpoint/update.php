<?php
require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../../src/php/Csrf.php';

if (!verifier_csrf_token($_POST['csrf_token'] ?? $_GET['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Menu.php';
require_once __DIR__ . '/../../../src/php/Page.php';

// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
$name = mb_strtoupper($_GET['titre']) ?? 'MENU';
$type = $_GET['type'];

if (!$id || !$name) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

if ($type == 'Menu') {
    $new_menu = new Menu();
    $resultat = $new_menu->update((int) $id, (string) $name);
} else {
    $new_page = new Page();
    $resultat = $new_page->update((int) $id, (string) $name);
}

echo json_encode(['success' => (bool) $resultat]);
