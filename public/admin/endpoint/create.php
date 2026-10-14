<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Csrf.php';


// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json; charset=utf-8');

if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Page.php';
require_once __DIR__ . '/../../templates/admin/item/menu.php';
require_once __DIR__ . '/../../../src/config/menus.php';

$name = trim($_POST['titre'] ?? '');
$menu_slug = $_POST['menu_slug'] ?? '';
$meta_description = $_POST['meta_description'] ?? '';

if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Champ titre vide']);
    exit;
}


if (!menu_validation($menu_slug)) {
    if ($resultat = (new Page())->create($name, $menu_slug, $meta_description)) {
        echo json_encode(['success' => true, 'message' => 'Page créée avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Menu introuvable']);
    exit;
}
