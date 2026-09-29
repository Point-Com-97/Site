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
require_once __DIR__ . '/../../templates/admin/item/menu.php';

$name = mb_strtoupper(trim($_GET['titre'] ?? ''));
$type = $_GET['type'] ?? '';
$menu = !empty($_GET['menu_id']) ? (int) $_GET['menu_id'] : null;

if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Champ titre vide']);
    exit;
}

if ($type === 'Menu') {
    $resultat = (new Menu())->create($name);

    if (!$resultat) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
        exit;
    }

    $main = ['menu_id' => $resultat, 'menu_titre' => $name];
    $modal = render_modal_menu($main);
    $menu_html = render_menu($main);
    $html_complet = "<div class='list-group' draggable='true' id='Menu_{$resultat}' data-id='{$resultat}'>{$modal}{$menu_html}<div class='list-group'></div></div>";
} elseif ($type === 'Page') {
    $resultat = (new Page())->create($name, $menu);

    if (!$resultat) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
        exit;
    }

    $main = ['id' => $resultat, 'titre' => $name, 'menu_id' => $menu, 'visible' => 1];
    $html_complet = render_modal_page($main) . render_page($main);
} else {
    echo json_encode(['success' => false, 'message' => 'Type invalide']);
    exit;
}

echo json_encode(['success' => true, 'id' => $resultat, 'html' => $html_complet]);