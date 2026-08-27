<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Menu.php';
require_once __DIR__ . '/../../../src/php/Page.php';
require_once __DIR__ . '/../../templates/admin/item/menu.php';


// Définir l'en-tête de réponse pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

$name = $_GET['titre'];
$type = $_GET['type'];
$menu = (!empty($_GET['menu_id'])) ? (int) $_GET['menu_id'] : null;

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Champs titre vide']);
    exit;
}

if ($type == 'Menu') {
    $new_menu = new Menu();
    $resultat = $new_menu->create((string) $name);
    $main = array('menu_id' => $resultat, 'menu_titre' => $name);
    $modal = render_modal_menu($main);
    $menu_html = render_menu($main);
    $html_complet = "<div class='list-group'>{$modal}{$menu_html}<div class='list-group'></div></div>";
} else {
    $new_page = new Page();
    $resultat = $new_page->create((string) $name, $menu);
    $main = array('id' => $resultat, 'titre' => $name);
    $modal = render_modal_page($main);
    $page_html = render_page($main);
    $html_complet = "{$modal}{$page_html}";
}

echo json_encode(['success' =>  (bool) $resultat, 'html' => $html_complet]);