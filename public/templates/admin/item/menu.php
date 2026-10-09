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

///SECTION RENDU DES PAGES
//Rendu modal modification page
function render_modal_page(array $item): string
{
    $id = $item['id'];
    $titre = htmlspecialchars($item['titre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $csrf = generer_csrf_token();

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
                                        <input class="form-control form-control-lg" type="text" id="Page_titre_{$id}" name="titre" value="{$titre}">
                                        <textarea class="form-control" name="meta_description" maxlength="160" rows="2" placeholder="Description pour les moteurs de recherche">{$item['meta_description']}</textarea>
                                        <input type="hidden" name="csrf_token" value="$csrf"> 
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

    $csrf = generer_csrf_token();

    $id = $item['id'];
    $titre = htmlspecialchars($item['titre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $visible =  $item['visible'];
    $menu_id = $item['menu_id'];
    
    $menu_id_js = $menu_id ?? 'null';

    if ($visible == 1) {
        $statue = 'bi bi-circle-fill text-success';
    } else {
        $statue = 'bi bi-circle-fill';
    }

    // structure liste + boutons / page
    return <<<HTML
                    <div class="container text-center" id="Page_{$id}" draggable="true" data-id='{$id}'>
                        <div class="d-flex align-items-center justify-content-between flex-nowrap">
                            <a class="list-group-item list-group-item-action flex-grow-1 text-truncate" aria-current="true" id="Page_label_{$id}">
                                <i class="bi bi-arrows-move"></i> {$titre}
                            </a>
                            <div class="btn-toolbar flex-shrink-0 gap-1 ms-2" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group" role="group" aria-label="group 2">
                                    <button type="button" onclick="toggle_visible({$id}, '{$csrf}')" class="btn">
                                        <i class="{$statue}" id="visible{$id}"></i>
                                    </button>
                                </div>
                                <div class="btn-group" role="group" aria-label="group 2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModalPage{$id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                                <div class="btn-group" role="group" aria-label="group 3">
                                    <a href="/admin/pages/edit.php?id={$id}" class="btn btn-warning">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                </div>
                                <div class="btn-group" role="group" aria-label="group 4">
                                    <button type="button" onclick="remove_items({$id},'Page', '{$csrf}')" class="btn btn-danger">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>    
                HTML;
}
