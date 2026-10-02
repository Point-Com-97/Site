<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Csrf.php';

header('Content-Type: application/json; charset=utf-8');

if (!verifier_csrf_token($_POST['csrf_token'] ?? $_GET['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Menu.php';
require_once __DIR__ . '/../../../src/php/Page.php';

$id = (int) ($_GET['id'] ?? 0);
$name = mb_strtoupper(trim($_GET['titre'] ?? ''));
$meta_description = $_GET['meta_description'] ?? '';
$type = $_GET['type'] ?? '';

if ($id <= 0 || $name === '') {
    echo json_encode(['success' => false, 'message' => 'ID ou titre manquant']);
    exit;
}

if ($type === 'Menu') {
    $resultat = (new Menu())->update($id, $name);
} elseif ($type === 'Page') {
    $resultat = (new Page())->update($id, $name, $meta_description);
} else {
    echo json_encode(['success' => false, 'message' => 'Type invalide']);
    exit;
}

echo json_encode(['success' => (bool) $resultat, 'titre' => $name, 'meta' => $meta_description]);