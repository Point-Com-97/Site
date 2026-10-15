<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Csrf.php';

header('Content-Type: application/json; charset=utf-8');

if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Page.php';

// filter_var renvoie false pour tout ce qui n'est pas un entier >= 1 (un seul test suffit)
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id === false) {
    echo json_encode(['success' => false, 'message' => 'ID invalide']);
    exit;
}

// Page::delete refuse déjà les pages système (systeme = 0 dans la requête)
if ((new Page())->delete($id)) {
    echo json_encode(['success' => true, 'message' => 'Page supprimée avec succès']);
} else {
    echo json_encode(['success' => false, 'message' => 'Suppression impossible']);
}
