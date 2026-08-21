<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Menu.php';

// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

$new_menu = new Menu();
$resultat = $new_menu->delete((int) $id);

echo json_encode(['success' => $resultat]);