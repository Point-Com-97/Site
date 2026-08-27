<?php

function sort_pages(array $pages): array
{
    $groupes = [];

    foreach ($pages as $page) {
        $cle = $page['menu_id'] ?? 'sans_menu';
        $groupes[$cle][] = $page;
    }

    return $groupes;
}

///SECTION RENDU DES MENU
//Rendu modal modificaiton menu
function render_modal_menu(array $item): string
{
    $id = $item['menu_id'];
    $titre = htmlspecialchars($item['menu_titre']);

    return <<<HTML
                <div class="modal fade" id="editModalMenu{$id}" tabindex="-1" aria-labelledby="editModalLabel{$id}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{$id}">Modification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                             <form class="container-fluid d-grid gap-2 mx-auto edit_form" id="edit_form_{$id}" method="post">
                                <div class="modal-body">
                                        <input type="hidden" name="id" value="{$id}">
                                        <input type="hidden" name="type" value="Menu">
                                        <input class="form-control form-control-lg" type="texte" id="Menu_titre_{$id}" name="titre" value="{$titre}">
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

//Rendu des lignes menu
function render_menu(array $item): string
{
    $id = $item['menu_id'];
    $titre = htmlspecialchars($item['menu_titre']);

    // structure liste + boutons / menu principal
    return <<<HTML
                    <div class="container text-center" data-id="menu_{$id}">
                         <div class="row align-items-start">
                            <a class="list-group-item list-group-item-action active disabled col" aria-current="true" id="Menu_label_{$id}">
                                {$titre}
                            </a>
                            <div class="btn-toolbar col" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group me-2" role="group" aria-label="First group">
                                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModalMenu{$id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                                 <div class="btn-group me-2" role="group" aria-label="Second group">
                                    <button type="button" onclick="remove_items({$id},'Menu')" class="btn btn-danger">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div> 
                    </div> 
                HTML;
}

///SECTION RENDU DES PAGES
//Rendu modal modification page
function render_modal_page(array $item): string
{
    $id = $item['id'];
    $titre = htmlspecialchars($item['titre']);

    return <<<HTML
                <div class="modal fade" id="editModalPage{$id}" tabindex="-1" aria-labelledby="editModalLabelPage{$id}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabelPage{$id}">Modification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                             <form class="container-fluid d-grid gap-2 mx-auto edit_form" id="edit_form_Page_{$id}" method="post">
                                <div class="modal-body">
                                        <input type="hidden" name="id" value="{$id}">
                                        <input type="hidden" name="type" value="Page">
                                        <input class="form-control form-control-lg" type="texte" id="Page_titre_{$id}" name="titre" value="{$titre}">
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
//Rendu des lignes pages
function render_page(array $item): string
{
    $id = $item['id'];
    $titre = htmlspecialchars($item['titre']);
    $visible =  $item['visible'];
    if($visible == 1) {
        $statue = 'bi bi-circle-fill text-success';
    } else {
        $statue = 'bi bi-circle-fill';
    }

    // structure liste + boutons / page
    return <<<HTML
                    <div class="container text-center" data-id="Page_{$id}" draggable="true">
                         <div class="row align-items-start">
                            <a class="list-group-item list-group-item-action col" href="#" id="Page_label_{$id}">
                                    {$titre}
                            </a>  
                            

                            <div class="btn-toolbar col" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group me-1" role="group" aria-label="First group">
                                    <button type="button" onclick="toggle_visible({$id},{$visible})" class="btn">
                                         <i class="{$statue}" id="visible{$id}"></i>
                                    </button>
                                </div>
                                <div class="btn-group me-2" role="group" aria-label="Second group">
                                    <button type="button" onclick="remove_items({$id},'Page')" class="btn btn-danger">
                                         <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                                <div class="btn-group me-3" role="group" aria-label="Third group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModalPage{$id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>    
                HTML;
}
