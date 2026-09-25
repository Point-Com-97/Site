<?php

try {

    require_once __DIR__ . '/../src/php/Page.php';
    require_once __DIR__ . '/../src/php/Bloc.php';
    require_once __DIR__ . '/../src/php/Menu.php';
    require_once __DIR__ . '/../src/php/Media.php';
    require_once __DIR__ . '/templates/admin/item/menu.php';


    // Récupération de l'URL actuelle
    $url = $_SERVER['REQUEST_URI'];

    $parse_url = parse_url($url, PHP_URL_PATH);

    $final_url =  trim($parse_url, "/");

    if ($final_url == "") {
        $final_url = "accueil";
    }

    // Récupération de tous les éléments du menu
    $new_menu = new Menu();

    $all_menu = $new_menu->getAll();

    // Récupération de la page actuelle en fonction du slug
    $new_page = new Page();

    $all_page = $new_page->getByMenu();

    $page_group = sort_pages($all_page);

    $current_page = $new_page->getBySlug($final_url);

    if ($current_page == "404") {
        die("Page introuvable");
    }

    require_once __DIR__ . '/../public/templates/header.php';

    echo "<div class='menu col-3'><h1 class='menu-title view'>Menu</h1>";
    echo "<div class='accordion' id='accordion-menu'>";

    if (!empty($all_menu) && is_array($all_menu)) {
        foreach ($all_menu as $item) {
            $page_menu = $page_group[$item['menu_id']] ?? [];
            $collapse_id = "collapse-{$item['menu_id']}";

            echo "<div class='accordion-item'>";
            echo <<<HTML
            <h2 class="accordion-header">
                <button class="accordion-button collapsed view" type="button" data-bs-toggle="collapse"
                        data-bs-target="#{$collapse_id}" aria-expanded="false" aria-controls="{$collapse_id}">
                    {$item['menu_titre']}
                </button>
            </h2>
            HTML;

            echo "<div id='{$collapse_id}' class='accordion-collapse collapse' data-bs-parent='#accordion-menu'>";
            echo "<div class='accordion-body'>";

            if (!empty($page_menu) && is_array($page_menu)) {
                foreach ($page_menu as $page) {
                    echo "<a href='/" . htmlspecialchars($page['slug']) . "' class='d-block mb-1 menu-item'>" . htmlspecialchars($page['titre']) . "</a>";
                }
            } else {
                echo "Aucune page disponible";
            }

            echo "</div></div></div>";
        }
    }

    echo "</div></div>";


    // Récupération des blocs associés à la page actuelle
    $new_blocs = new Bloc();

    $all_blocs = $new_blocs->getByPageId($current_page['id']);

    echo '<div class="page col-8 view">';

    if (empty($all_blocs)) {
        echo '<p class="text-muted p-4">Cette page ne contient pas encore de contenu.</p>';
    } else {
        foreach ($all_blocs as $bloc) {

            $donnees = json_decode($bloc['donnees'], true);
            $classes = $donnees['classes'] ?? '';

            switch ($bloc['type']) {
                case 'texte':
                    echo <<<HTML
                    <div class="card-body {$classes} texte bloc">
                        <p class="card-text">{$donnees['contenu']}</p>
                    </div>
            HTML;
                    break;
                case 'image':
                    $new_media = new Media();

                    $url_media = $new_media->getById($donnees['media_id']);

                    if (empty($url_media)) {
                        echo 'Image non trouvé';
                    } else {
                        echo <<<HTML
                        <div class="card-body {$classes} image bloc">
                            <img src="{$url_media['url']}" alt="{$donnees['legende']}" class="img-fluid object-fit-fill border rounded">
                        </div>
            HTML;
                    }


                    break;
                case 'video':

                    echo <<< HTML
                <div class="{$classes} video bloc">
                        <iframe src="{$donnees['url']}" title="{$donnees['legende']}" ></iframe>
                    </div>
            HTML;
                    break;

                case 'stats':
                    $canvas_id = 'chart-' . $bloc['id']; // identifiant unique par bloc

                    $json_donnees = json_encode($donnees);

                    echo <<<HTML
                        <div class=" {$classes} stats bloc">
                            <canvas id="{$canvas_id}"></canvas>
                        </div>
                        <script>
                        new Chart(document.getElementById('{$canvas_id}'), {$json_donnees});
                        </script>
                    HTML;
                    break;

                case 'tableau':

                    echo <<<HTML
                        <table class="table table-hover bloc {$classes}">
                            <div class="card-header">{$donnees['nom']}</div>
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
    }
    echo '</div>';

    require_once __DIR__ . '/../public/templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur base de données : " . $e->getMessage(), 3, __DIR__ . "/../var/tmp/erreur.log");

    die("<h1> Le site est temporairement indisponible, merci de réessayer plus tard.</h1>");
}
