<?php
require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../../src/php/Csrf.php';

if (!verifier_csrf_token($_POST['csrf_token'] ?? $_GET['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Menu.php';
require_once __DIR__ . '/../../../src/php/Bloc.php';


header('Content-Type: application/json');

$donnees = json_decode(file_get_contents('php://input'), true);

if (!$donnees) {
    echo json_encode(['success' => false]);
    exit;
}

if ($donnees[0]['type'] === 'menu') {
    $new_menu = new Menu();
    $resultat = true;
    foreach ($donnees as $item) {
        if (!$new_menu->update_ordre((int) $item['id'], (int) $item['ordre'])) {
            $resultat = false;
        }
    }
    echo json_encode(['success' => $resultat]);

} elseif ($donnees[0]['type'] === 'bloc') {
    $new_bloc = new Bloc();
    $resultat = true;
    foreach ($donnees as $item) {
        if (!$new_bloc->update_ordre((int) $item['id'], (int) $item['ordre'])) {
            $resultat = false;
        }
    }
    echo json_encode(['success' => $resultat]);

} elseif ($donnees[0]['type'] === 'page') {
    require_once __DIR__ . '/../../../src/php/Page.php';
    $new_page = new Page();
    $resultat = true;
    foreach ($donnees as $item) {
        if (!$new_page->update_ordre((int) $item['id'], (int) $item['ordre'])) {
            $resultat = false;
        }
    }
    echo json_encode(['success' => $resultat]);

} else {
    echo json_encode(['success' => false, 'message' => 'Type inconnu']);
}