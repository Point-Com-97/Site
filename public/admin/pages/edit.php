<?php require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../templates/admin/header.php';
require_once __DIR__ . '/../../templates/admin/item/bloc.php';

try {
    require_once __DIR__ . '/../../../src/php/Page.php';
    require_once __DIR__ . '/../../../src/php/Bloc.php';
    require_once __DIR__ . '/../../../src/php/Media.php';

    $id = $_GET['id'];

    $page = new Page();
    $bloc = new Bloc();
    $media = new Media();

    $page_info = $page->getById($id);
    $bloc_info = $bloc->getByPageId($id);
    $all_medias = $media->getAll();

    $media_options = '';
    foreach ($all_medias as $m) {
        $media_options .= '<option value="' . htmlspecialchars($m['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($m['titre'], ENT_QUOTES, 'UTF-8') . '</option>';
    }

    echo <<< HTML
            <div class="btn-toolbar m-1" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_bloc">
                        Nouveau Bloc
                    </button>
                </div>
            </div>
        HTML;

    echo <<< HTML
            <div class="modal fade" id="new_bloc" tabindex="-1" aria-labelledby="new_bloc_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="new_bloc_label">Nouveau Bloc</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <form class="container-fluid d-grid gap-2 mx-auto add_form_bloc" method="post">

                                <select class="form-select form-select-lg mb-3" id="bloc_type" name="type">
                                    <option value="texte">Texte</option>
                                    <option value="image">Image</option>
                                    <option value="video">Vidéo</option>
                                    <option value="stats">Statistiques</option>
                                    <option value="tableau">Tableau</option>
                                </select>  

                                <input type="hidden" name="page_id" value="{$id}">
                                
                                <div class="champs-bloc" data-type="texte">
                                    <textarea name="contenu" class="form-control"></textarea>
                                </div>

                                <div class="champs-bloc" data-type="video" style="display:none;">
                                    <input type="text" name="url" class="form-control" placeholder="URL embed">
                                    <input type="text" name="legende" class="form-control" placeholder="Légende">
                                </div>

                                <div class="champs-bloc" data-type="image" style="display:none;">
                                    <select name="media_id" class="form-select">
                                        $media_options
                                    </select>
                                    <input type="text" name="legende" class="form-control" placeholder="Légende">
                                </div>

                                <div class="champs-bloc" data-type="stats" style="display:none;">

                                        <select class="form-select" name="chart_type" aria-label="Type de graphique">
                                            <option selected value="">Type de graphique</option>
                                            <option value="bar">Barres</option>
                                            <option value="doughnut">Donut</option>
                                            <option value="pie">Tarte</option>
                                            <option value="line">Ligne</option>
                                        </select>

                                        <input type="text" aria-label="datasets-label" name="dataset_label" class="form-control" placeholder="Nom du graphique">

                                        <label for="nb_label" class="form-label">Nombre de champs</label>
                                        <input type="range" class="form-range" min="2" max="10" id="nb_label">
                                        <output for="nb_label" id="label_value" aria-hidden="true" class="text"></output>

                                        <div class="input-group">
                                            <span class="input-group-text">Champs / Valeur</span>
                                            <input type="text" name="labels_1" class="form-control" placeholder="Champs">
                                            <input type="text" name="data_1" class="form-control" placeholder="Valeur">
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">Champs / Valeur</span>
                                            <input type="text" name="labels_2" class="form-control" placeholder="Champs">
                                            <input type="text" name="data_2" class="form-control" placeholder="Valeur">
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">Champs / Valeur</span>
                                            <input type="text" name="labels_3" class="form-control" placeholder="Champs">
                                            <input type="text" name="data_3" class="form-control" placeholder="Valeur">
                                        </div>

                                </div>

                                <div class="champs-bloc" data-type="tableau" style="display:none;">
                                        <label for="nb_col" class="form-label">Nombre de colonnes</label>
                                        <input type="range" class="form-range" min="2" max="10" id="nb_col">
                                        <output for="nb_col" id="col_value" aria-hidden="true" class="text"></output></br>

                                        <label for="nb_row" class="form-label">Nombre de lignes</label>
                                        <input type="range" class="form-range" min="1" max="20" value="10" id="nb_row">
                                        <output for="nb_row" id="row_value" aria-hidden="true" class="text"></output>


                                        <div class="input-group">
                                            <span class="input-group-text">Colonnes</span>
                                            <input type="text" name="col_1" class="form-control" placeholder="colonne">
                                            <input type="text" name="col_2" class="form-control" placeholder="colonne">
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">Lignes</span>
                                            <input type="text" name="row_1" class="form-control" placeholder="ligne">
                                            <input type="text" name="row_2" class="form-control" placeholder="ligne">
                                        </div>
                                </div>
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

    echo "<h1>" . htmlspecialchars($page_info['titre']) . "</h1>";
    echo "<p>Slug : " . htmlspecialchars($page_info['slug']) . "</p>";
    echo "<p>bloc : " . htmlspecialchars($page_info['menu_id']) . "</p>";
    echo "<p>Ordre: " . htmlspecialchars($page_info['ordre']) . "</p>";
    echo "<p>Visible : " . htmlspecialchars($page_info['visible']) . "</p>";

    echo "<div id='bloc-list'>";
    foreach ($bloc_info as $b) {
        echo render_bloc($b);
    }
    echo "</div>";



    require_once __DIR__ . '/../../templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur

    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}
