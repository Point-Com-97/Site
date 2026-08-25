<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Menu.php';
require_once __DIR__ . '/../../templates/admin/item/menu.php';


// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

$name = $_GET['titre'];
$page = (!empty($_GET['page_id'])) ? (int) $_GET['page_id'] : null;
$parent = (!empty($_GET['parent_id'])) ? (int) $_GET['parent_id'] : null;

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Champs titre vide']);
    exit;
}

$new_menu = new Menu();
$resultat = $new_menu->create((string) $name, $page, $parent);

$main = array('menu_id' => $resultat, 'menu_titre' => $name, 'page_id' => $page, 'parent_id' => $parent);

if (!$parent) {
    $modal = render_modal($main);
    $menu_html = render_menu($main, false);
    $html_complet = "<div class='list-group'>{$modal}{$menu_html}<div class='list-group'></div></div>";
} else {
    $modal = render_modal($main);
    $menu_html = render_menu($main, true);
    $html_complet = "{$modal}{$menu_html}";
}

echo json_encode(['success' => $resultat, 'html' => $html_complet]);