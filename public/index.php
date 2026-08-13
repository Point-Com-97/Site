<?php
require_once __DIR__ . '/../src/php/Page.php';
require_once __DIR__ . '/../src/php/Bloc.php';
require_once __DIR__ . '/../src/php/Menu.php';
require_once __DIR__ . '/../src/php/Media.php';


// Récupération de l'URL actuelle
$url = $_SERVER['REQUEST_URI'];

$parse_url = parse_url($url, PHP_URL_PATH);

$final_url =  trim($parse_url, "/");

if ($final_url == "") {
    $final_url = "accueil";
}

// Récupération de tous les éléments du menu
$new_menu = new Menu();

$all_menu = $new_menu->getAllMenu();

// Récupération de la page actuelle en fonction du slug
$new_page = new Page($final_url);

$current_page = $new_page->getBySlug();

if ($current_page == "404") {
    die("Page introuvable");
}

require_once __DIR__ . '/../public/templates/header.php';

// Récupération des blocs associés à la page actuelle
$new_blocs = new Bloc($current_page['id']);

$all_blocs = $new_blocs->getByPageId();

foreach ($all_blocs as $bloc) {

    $donnees = json_decode($bloc['donnees'], true);

    switch ($bloc['type']) {
        case 'texte':
            echo <<<HTML
            <div class="card m-1" style="width: 18rem;">
                <div class="card-body">
                    <p class="card-text">{$donnees['contenu']}</p>
                </div>
            </div>
        HTML;
            break;
        case 'image':
            $new_media = new Media($donnees['media_id']);

            $url_media = $new_media->getById();

            echo <<<HTML
                <div class="card m-1" style="width: 18rem;">
                    <div class="card-body">
                        <img src="{$url_media['url']}" alt="{$donnees['legende']}" class="img-fluid object-fit-fill border rounded">
                    </div>
                </div>
        HTML;
            break;
        case 'video':

            echo <<< HTML
               <div class="ratio ratio-16x9">
                    <iframe src="{$donnees['url']}" title="{$donnees['legende']}" ></iframe>
                </div>
        HTML;
        case 'carte':
            print_r($donnees['items']);

            echo '<table class="table table-striped table-hover">';
            foreach ($donnees['items'] as $item) {
                echo <<<HTML
                    <thead>
                        <tr>
                        <th scope="col"></th>
                        </tr>
                    </thead>
        HTML;
            }
            echo '</table>';
            break;

        default:
            echo "Contenu introuvable\n";
    }
}

require_once __DIR__ . '/../public/templates/footer.php';
