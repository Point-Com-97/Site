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


if (mb_strlen($name) > 255) {
    echo json_encode(['success' => false, 'message' => 'Le titre ne peut pas dépasser 255 caractères']);
    exit;
}

if (mb_strlen($meta_description) > 160) {
    echo json_encode(['success' => false, 'message' => 'La description ne peut pas dépasser 160 caractères']);
    exit;
}

if ($id <= 0 || $name === '') {
    echo json_encode(['success' => false, 'message' => 'ID ou titre manquant']);
    exit;
}

$page = new Page();
$info = $page->getById($id);

if (is_array($info)) {
    if ((int) $info['systeme'] === 1) {
        if ($resultat = (new Page())->update_infos($id, $name, $meta_description)) {
            echo json_encode(['success' => true, 'result' => $resultat, 'titre' => $name, 'meta' => $meta_description]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
            exit;
        }
    } else {
        $dest = destination_validation($menu_slug);
        if ($dest === null) {
            echo json_encode(['success' => false, 'message' => 'Destination introuvable']);
            exit;
        }

        if ($resultat = (new Page())->update($id, $name, $dest[0], $dest[1], $meta_description)) {
            echo json_encode(['success' => true, 'result' => $resultat, 'titre' => $name, 'menu_slug' => $menu_slug, 'meta' => $meta_description]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
            exit;
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Page introuvable']);
    exit;
}
