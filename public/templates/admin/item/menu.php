<?php
function render_modal(array $item): string
{
    $id = $item['menu_id'];
    $titre = htmlspecialchars($item['menu_titre']);

    return <<<HTML
                <div class="modal fade" id="editModal{$id}" tabindex="-1" aria-labelledby="editModalLabel{$id}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{$id}">Modification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                             <form class="container-fluid d-grid gap-2 mx-auto edit_form" id="edit_form_{$id}" method="post">
                                <div class="modal-body">
                                        <input type="hidden" name="id" value="{$id}">
                                        <input class="form-control form-control-lg" type="texte" id="menu_titre_{$id}" name="menu_titre" value="{$titre}">
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
}

function render_menu(array $item, bool $is_child = false): string
{
    $id = $item['menu_id'];
    $titre = htmlspecialchars($item['menu_titre']);

    if ($is_child) {
        // structure liste + boutons / sous-menu
        return <<<HTML
                    <div class="container text-center" data-id="{$id}" draggable="true">
                         <div class="row align-items-start">
                            <a class="list-group-item list-group-item-action col" href="#" id="menu_label_{$id}">
                                    {$titre}
                            </a>  

                            <div class="btn-toolbar col" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group me-2" role="group" aria-label="First group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{$id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                                <div class="btn-group me-2" role="group" aria-label="Second group">
                                    <button type="button" onclick="remove_menu({$id})" class="btn btn-danger">
                                         <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>    
                HTML;
    } else {
        // structure liste + boutons / menu principal
        return <<<HTML
                    <div class="container text-center" data-id="{$id}">
                         <div class="row align-items-start">
                            <a class="list-group-item list-group-item-action active disabled col" aria-current="true" id="menu_label_{$id}">
                                {$titre}
                            </a>
                            <div class="btn-toolbar col" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group me-2" role="group" aria-label="First group">
                                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{$id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                                 <div class="btn-group me-2" role="group" aria-label="Second group">
                                    <button type="button" onclick="remove_menu({$id})" class="btn btn-danger">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div> 
                    </div> 
                HTML;
    }
}
