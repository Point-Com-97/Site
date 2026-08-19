<?php

try {

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
            <div class="card m-1 texte bloc" style="width: 18rem;">
                <div class="card-body">
                    <p class="card-text">{$donnees['contenu']}</p>
                </div>
            </div>
        HTML;
            break;
        case 'image':
            $new_media = new Media();

            $url_media = $new_media->getById($donnees['media_id']);

            echo <<<HTML
                <div class="card m-1 image bloc" style="width: 18rem;">
                    <div class="card-body">
                        <img src="{$url_media['url']}" alt="{$donnees['legende']}" class="img-fluid object-fit-fill border rounded">
                    </div>
                </div>
        HTML;
            break;
        case 'video':

            echo <<< HTML
               <div class="w-100 video bloc">
                    <iframe src="{$donnees['url']}" title="{$donnees['legende']}" ></iframe>
                </div>
        HTML;
            break;

        case 'stats':
            $canvas_id = 'chart-' . $bloc['id']; // identifiant unique par bloc

            $json_donnees = json_encode($donnees);

            echo <<<HTML
                    <div class="stats bloc">
                        <canvas id="{$canvas_id}"></canvas>
                    </div>
                    <script>
                       new Chart(document.getElementById('{$canvas_id}'), {$json_donnees});
                    </script>
                HTML;
            break;

        case 'tableau':

            echo <<<HTML
                    <table class="table table-hover bloc">
                        <thead>
                            <tr>
                    HTML;

            // Boucle forearch pour l'entête du tableau 
            foreach ($donnees['colonnes'] as $col) {
                echo "<th>" . htmlspecialchars($col) . "</th>";
            }

            echo <<<HTML
                            </tr>
                        </thead>
                        <tbody>
                    HTML;

            // Boucle forearch pour les lignes du tableau 
            foreach ($donnees['lignes'] as $ligne) {
                echo "<tr>";
                foreach ($ligne as $row) {
                    echo "<td>" . htmlspecialchars($row) . "</td>";
                }
                echo "</tr>";
            }

            echo <<<HTML
                        </tbody>
                    </table>
                    HTML;

            break;
        default:
            echo "Contenu introuvable\n";
    }
}

require_once __DIR__ . '/../public/templates/footer.php';

} catch (PDOException $e) {

    error_log("Erreur base de données : " . $e->getMessage(),3, __DIR__ . "/../var/tmp/erreur.log");

    die("<h1> Le site est temporairement indisponible, merci de réessayer plus tard.</h1>");
}