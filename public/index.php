<?php

require_once __DIR__ . '/../src/php/Page.php';
require_once __DIR__ . '/../src/php/Bloc.php';

$url = $_SERVER['REQUEST_URI'];

$parse_url = parse_url($url, PHP_URL_PATH);

$final_url =  trim($parse_url, "/");

if ($final_url == "") {
    $final_url = "accueil";
}

$new_page = New Page($final_url);

$current_page = $new_page->getBySlug();

if ($current_page == "404") {
     die("Page introuvable");
} else {
    echo $current_page['titre'];
}

$new_blocs = New Bloc($current_page['id']);

$all_blocs = $new_blocs->getByPageId();

foreach ($all_blocs as $value) {
    echo $value['id'] . " " . $value['type'] . " " . $value['contenu'] . " " . $value['legende'] . " " . $value['ordre'] . "\n";
}



