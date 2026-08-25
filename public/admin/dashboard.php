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
                            <h5 class="modal-title" id="new_menu_label">Nouveau menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <form class="container-fluid d-grid gap-2 mx-auto add_form" method="post">
                                <input class="form-control" type="text" name="titre" id="titre" value="Nouveau menu" aria-label="Nouveau menu">
            HTML;

                                        echo "<select class='form-select' size='4' name='parent_id' aria-label='list_page'>";
                                        echo '<option selected> Sélectionnez le menu principal</option>'; {
                                            echo "<option value=''>...</option>";
                                            foreach ($all_menus as $m) {
                                                echo "<option value='{$m['menu_id']}'>{$m['menu_titre']}</option>";
                                            }
                                        }
                                        
                                        echo '</select>';

                                        echo "<select class='form-select' size='4' name='page_id' aria-label='list_page'>";
                                        echo '<option selected> Sélectionnez une page</option>'; {
                                            echo "<option value=''>...</option>";
                                            foreach ($all_pages as $p) {
                                                echo "<option value='{$p['id']}'>{$p['titre']}</option>";
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
    foreach ($all_menus as $main) {
        echo "<div class='list-group' draggable='true' data-id='{$main['menu_id']}'>";
        echo render_modal($main);
        echo render_menu($main, false);

        echo "<div class='list-group child-group' data-parent-id='{$main['menu_id']}'>";
        foreach ($main['enfants'] as $child) {
            echo render_modal($child);
            echo render_menu($child, true);
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
