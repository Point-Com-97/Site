<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Menu.php';

header('Content-Type: application/json');

$donnees = json_decode(file_get_contents('php://input'), true);

if (!$donnees) {
    echo json_encode(['success' => false]);
    exit;
}

$new_menu = new Menu();
$resultat = true;

foreach ($donnees as $item) {
    $ok = $new_menu->update_ordre((int) $item['id'], (int) $item['ordre']);
    if (!$ok) {
        $resultat = false;
    }
}

echo json_encode(['success' => $resultat]);