<?php

require __DIR__ . '/auth-check.php';

require_once __DIR__ . '/../templates/admin/header.php';
require_once __DIR__ . '/../templates/admin/item/menu.php';
try {

    require_once __DIR__ . '/../../src/php/Menu.php';
    require_once __DIR__ . '/../../src/php/Page.php';

    $menu = new Menu();
    $page = new Page();

    $all_menus = $menu->getAll();
    $all_pages = $page->getAll();
    $page_by_menu = $page->getByMenu();
    $page_group = sort_pages($page_by_menu);

    // Modal d'ajout pour les menus
    echo <<< HTML
            <div class="btn-toolbar m-1" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_menu">
                        Nouveau Menu
                    </button>
                </div>
            </div>
        HTML;

    echo <<< HTML
            <div class="modal fade" id="new_menu" tabindex="-1" aria-labelledby="new_menu_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="new_menu_label">Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <form class="container-fluid d-grid gap-2 mx-auto add_form" method="post">
                                <input class="form-control" type="text" name="titre" id="titre" value="Nouveau menu" aria-label="Nouveau menu">
                        </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        HTML;



    // Modal d'ajout pour les pages
    echo <<< HTML
            <div class="btn-toolbar m-1" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_page">
                        Nouvelle Page
                    </button>
                </div>
            </div>
        HTML;

    echo <<< HTML
            <div class="modal fade" id="new_page" tabindex="-1" aria-labelledby="new_page_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="new_page_label">Page</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <form class="container-fluid d-grid gap-2 mx-auto add_form" method="post">
                                <input class="form-control" type="text" name="titre" id="titre" value="Nouvelle Page" aria-label="Nouvelle Page">
            HTML;
                                echo "<select class='form-select' name='parent_id' aria-label='list_page'>";
                                    echo '<option selected>Sélectionnez le menu</option>'; {
                                        echo "<option value=''>...</option>";
                                        foreach ($all_menus as $m) {
                                            echo "<option value='{$m['menu_id']}'>{$m['menu_titre']}</option>";
                                        }
                                    }
                                echo '</select>';

    echo <<< HTML
                        </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        HTML;

        
    
    
    echo "<div id='menu-list'>";
        foreach ($all_menus as $m) {
            echo "<div class='list-group' draggable='true' data-id='{$m['menu_id']}'>";
            echo render_modal_menu($m);
            echo render_menu($m);
            $page_menu = $page_group[$m['menu_id']] ?? [];
            echo "<div class='list-group child-group' data-parent-id='{$m['menu_id']}'>";
                foreach ($page_menu as $p) {
                    echo render_modal_page($p);
                    echo render_page($p);
                }
            echo "</div>";
            echo "</div>";
        }
    echo "</div>";

    require_once __DIR__ . '/../templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur

    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}
