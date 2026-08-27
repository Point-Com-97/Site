<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Page.php';
require_once __DIR__ . '/../../../src/php/Bloc.php';
require_once __DIR__ . '/../../templates/admin/item/menu.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

$page = new Page();
$original = $page->getById((int) $id);

if ($original == '404') {
    echo json_encode(['success' => false, 'message' => 'Page introuvable']);
    exit;
}

$random = rand();
$new_slug = $original['slug']. $random;

$titre =  ucfirst($new_slug);
$menu = $original['menu_id'];


$duplicate = $page->create((string) $titre, (int) $menu);

$bloc = new Bloc();
$all_blocs = $bloc->getByPageId((int) $id);

foreach($all_blocs as $b) {

    $donnees = json_decode($b['donnees']);

    $new_bloc = $bloc->create((int) $duplicate, (string) $b['type'], $donnees, (int) $b['ordre']);
}
    $main = array('id' => $duplicate, 'titre' => $titre, 'menu_id' => $menu, 'visible' => 1);
    $modal = render_modal_page($main);
    $page_html = render_page($main);
    $html_complet = "{$modal}{$page_html}";

echo json_encode(['success' => (bool) $duplicate, 'html' => $html_complet]);