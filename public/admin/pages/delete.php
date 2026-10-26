<?php
declare(strict_types=1);

require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../../src/php/Csrf.php';

header('Content-Type: application/json; charset=utf-8');

// CSRF : POST uniquement
if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Bloc.php';

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

$new_bloc = new Bloc();
$resultat = $new_bloc->delete($id);

echo json_encode(['success' => (bool) $resultat]);
