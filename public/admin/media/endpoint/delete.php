<?php
require __DIR__ . '/../../auth-check.php';
require_once __DIR__ . '/../../../../src/php/Media.php';

// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

$new_media = new Media();
$resultat = $new_media->delete((int) $id);

echo json_encode(['success' => $resultat]);