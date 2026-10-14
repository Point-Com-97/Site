<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Csrf.php';


header('Content-Type: application/json; charset=utf-8');

if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Page.php';
require_once __DIR__ . '/../../../src/config/menus.php';

$id = (int) ($_POST['id'] ?? 0);
$name = trim($_POST['titre'] ?? '');
$meta_description = $_POST['meta_description'] ?? '';
$menu_slug = $_POST['menu_slug'] ?? '';

if ($id <= 0 || $name === '') {
    echo json_encode(['success' => false, 'message' => 'ID ou titre manquant']);
    exit;
}

$page = new Page();
$info = $page->getById($id);

if (menu_validation($menu_slug)) {

    if ($info) {
        if ($info['menu_slug'] !== null) {
            if ($resultat = (new Page())->update($id, $name, $menu_slug, $meta_description)) {
                echo json_encode(['success' => true, 'result' => $resultat, 'titre' => $name,'menu_slug' => $menu_slug, 'meta' => $meta_description]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
                exit;
            }
        } else {
            if ($resultat = (new Page())->update($id, $name, $meta_description)) {
                echo json_encode(['success' => true, 'result' => $resultat, 'titre' => $name, 'meta' => $meta_description]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
                exit;
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Page introuvable']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Menu introuvable']);
    exit;
}
