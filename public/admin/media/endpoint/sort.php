<?php
declare(strict_types=1);

require __DIR__ . '/../../auth-check.php';

require_once __DIR__ . '/../../../../src/php/Csrf.php';

header('Content-Type: application/json; charset=utf-8');

// CSRF : POST uniquement
if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../../src/php/Media.php';
require_once __DIR__ . '/../../../templates/admin/item/media.php';

// Récupérer le paramètre de tri (whitelist)
$tri = $_POST['tri'] ?? 'created_at';
$colonnesAutorisees = ['created_at', 'titre'];
if (!in_array($tri, $colonnesAutorisees, true)) {
    $tri = 'created_at';
}

$new_media = new Media();
$medias = $new_media->getAll($tri);

$html = '';
foreach ($medias as $media) {
    $html .= render_media($media);
}

echo json_encode(['html' => $html]);
