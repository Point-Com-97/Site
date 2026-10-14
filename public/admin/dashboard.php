<title>Administration</title>
<?php

require __DIR__ . '/auth-check.php';

require_once __DIR__ . '/../../src/php/Csrf.php';
require_once __DIR__ . '/../templates/admin/header.php';
require_once __DIR__ . '/../../src/config/menus.php';
require_once __DIR__ . '/../templates/admin/item/menu.php';

try {

    require_once __DIR__ . '/../../src/php/Page.php';

    $page = new Page();

    $all_pages = $page->getAll();
    $page_by_menu = $page->getByMenu();
    $page_group = sort_pages($page_by_menu);
    $csrf = generer_csrf_token();
    $menus = list_menus();

    // Modal d'ajout pour les pages
    echo <<< HTML
            <button type="button" class="btn btn-board m-1 btn-primary" data-bs-toggle="modal" data-bs-target="#new_page">
                Nouvelle Page
            </button>
            HTML;

    echo <<< HTML
                <div class="modal fade" id="new_page" tabindex="-1" aria-labelledby="new_page_label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="new_page_label">Page</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form class="container-fluid d-grid gap-2 mx-auto add_form_page" method="post">
                                <div class="modal-body">   
                                    <input type="hidden" name="type" value="Page">
                                    <input class="form-control" type="text" name="titre" id="titre" value="Nouvelle Page" aria-label="Nouvelle Page">
                                    <textarea class="form-control" name="meta_description" maxlength="160" rows="2" placeholder="Description pour les moteurs de recherche"></textarea>

                                   <select class='form-select' name='menu_slug' aria-label='list_page' required>
                                    <option selected value='' disabled>Sélectionnez le menu</option>
            HTML;
    foreach ($menus as $cle => $m) {
        if (menu_validation($cle)) {
            $titre = $m['label'];
            $slug  = $m['page'] ?? $m['url'] ?? '#'; // page, sinon url, sinon '#'

            echo "<option value='$slug'>$titre</option>";
        }
    }
    echo <<< HTML
                                   </select>

                            <input type="hidden" name="csrf_token" value="$csrf"> 
                            </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary">Valider</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            HTML;


    echo "<div class='board-menu container'>";
    echo "<div id='menu-list' data-csrf_token='{$csrf}'>";

    foreach ($menus as $cle => $m) {
        if (menu_validation($cle)) {
            $titre = $m['label'];
            $slug  = $m['page'] ?? $m['url'] ?? '#'; // page, sinon url, sinon '#'

            echo <<<HTML
                <div class='list-group mt-2' id='{$slug}' data-id='{$slug}' data-csrf_token='{$csrf}'>
                    <div class="container text-center" data-id="{$slug}">
                        <div class="d-flex align-items-center justify-content-between flex-nowrap">
                            <a class="list-group-item list-group-item-action active disabled flex-grow-1 text-truncate" aria-current="true" >
                             $titre
                            </a>
                        </div>
            HTML;
            $page_menu = $page_group[$slug] ?? [];
            echo "<div class='list-group child-group' data-csrf_token='{$csrf}'>";
            foreach ($page_menu as $p) {
                echo render_modal_page($p);
                echo render_page($p);
            }
            echo <<<HTML
                        </div>
                    </div>
                </div>
        HTML;
        }
    }

    echo <<<HTML
                <div class='list-group mt-2'id='self_page' data-id='self_page' data-csrf_token='{$csrf}'>
                    <div class="container text-center" data-id="self_page">
                        <div class="d-flex align-items-center justify-content-between flex-nowrap">
                            <a class="list-group-item list-group-item-action active disabled flex-grow-1 text-truncate" aria-current="true" >
                            Page système
                            </a>
                        </div>
            HTML;
    $page_menu = $page_group[null] ?? [];
    echo "<div class='list-group child-group' data-csrf_token='{$csrf}'>";
    foreach ($page_menu as $p) {
        echo render_modal_self_page($p);
        echo render_self_page($p);
    }
    echo <<<HTML
                        </div>
                    </div>
                </div>
        HTML;

    echo "</div>";
    echo "</div>";

    require_once __DIR__ . '/../templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur

    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}
