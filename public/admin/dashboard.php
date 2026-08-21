<?php

require __DIR__ . '/auth-check.php';

require_once __DIR__ . '/../templates/admin/header.php';

try {

    require_once __DIR__ . '/../../src/php/Menu.php';
    require_once __DIR__ . '/../../src/php/Page.php';

    $menu = new Menu();

    $all_menus = $menu->getAll();






    echo  '<div class="container text-center">';
    foreach ($all_menus as $main) {

    $titre = htmlspecialchars($main['menu_titre']);

        echo <<< HTML
            <div class="modal fade" id="editModal{$main['menu_id']}" tabindex="-1" aria-labelledby="editModalLabel{$main['menu_id']}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{$main['menu_id']}">Modification</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <form class="container-fluid d-grid gap-2 mx-auto" method="post" action="/admin/endpoint/update.php">
                                <label for="menu_id" class="form-label">Renommer le menu</label>
                                <input class="form-control form-control-lg" type="texte" id="menu_id" name="menu" value="{$titre}">
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

        echo "<div class=\"row align-items-start\" data-id=\"{$main['menu_id']}\">";
        echo '<div class="accordion accordion-flush col" id="accordionFlushExample">';
        echo <<< HTML
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{$main['menu_id']}" aria-expanded="false" aria-controls="flush-collapseOne">
                        {$titre}
                    </button>
                    </h2>

                    <div id="flush-collapse{$main['menu_id']}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        
            HTML;

        foreach ($main['enfants'] as $child) {

            $titre = htmlspecialchars($child['menu_titre']);

                    echo <<< HTML
            <div class="modal fade" id="editModal{$child['menu_id']}" tabindex="-1" aria-labelledby="editModalLabel{$child['menu_id']}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{$child['menu_id']}">Modification</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <form class="container-fluid d-grid gap-2 mx-auto" method="post" action="/admin/endpoint/update.php">
                                <label for="menu_id" class="form-label">Renommer le menu</label>
                                <input type="hidden" name="id" value="{$main['menu_id']}">
                                <input class="form-control form-control-lg" type="texte" id="menu_id" name="menu" value="{$titre}">
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

            echo <<< HTML
                    <div class="row align-items-start" data-id="{$child['menu_id']}">
                        <div class="accordion-body col">
                            {$titre}
                        </div>
                            <div class="btn-toolbar col" role="toolbar" aria-label="Toolbar with button groups">
                                    <div class="btn-group me-2" role="group" aria-label="First group">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{$child['menu_id']}">
                                            <i class="bi bi-pencil-square">
                                            </i></button>
                                    </div>
                                    <div class="btn-group me-2" role="group" aria-label="Second group">
                                        <button type="button" onclick="remove_menu({$child['menu_id']})" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
                                    </div>
                            </div>
                    </div>

                HTML;
        }
        echo '</div></div></div>';
        echo  <<< HTML
                <div class="p-1 col">
                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{$main['menu_id']}">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" onclick="remove_menu({$main['menu_id']})" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
                </div>
            HTML;
        echo '</div>';
    }

    echo '</div>';

    require_once __DIR__ . '/../templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur

    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}
