<?php
require __DIR__ . '/../../auth-check.php';
require_once __DIR__ . '/../../../../src/php/Media.php';
require_once __DIR__ . '/../../../templates/admin/item/media.php';

// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

// Récupérer le paramètre de tri depuis la requête GET, par défaut 'created_at'
$tri = $_GET['tri'] ?? 'created_at';

$new_media = new Media();
$medias = $new_media->getAll($tri);

$html = '';
foreach ($medias as $media) {
    $html .= render_media($media);
}

echo json_encode(['html' => $html]);
