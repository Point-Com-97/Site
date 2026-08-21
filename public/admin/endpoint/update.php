<?php 
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Menu.php';

// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$name = $_POST['menu'];

if(!$id && !$name) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

$new_menu = new Menu();
$resultat = $new_menu->update((int) $id, (string) $name);

echo json_encode(['success' => $resultat]);