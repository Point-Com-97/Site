<?php

require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../templates/admin/header.php';

try {
    require_once __DIR__ . '/../../templates/admin/item/media.php';
    require_once __DIR__ . '/../../../src/php/Media.php';
    

    $new_media = new Media();

    $all_medias = $new_media->getAll();

    echo <<< HTML
            <div class="btn-toolbar m-1" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        Ajouter un média
                    </button>
                </div>
                <div class="btn-group me-2" role="group" aria-label="Second group">
                    <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Trier par 
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Date</a></li>
                        <li><a class="dropdown-item" href="#">Nom</a></li>
                    </ul>
                    </div>
                </div>
            </div>
        HTML;

    echo <<< HTML
            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Ajouter un média</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <form class="container-fluid d-grid gap-2 mx-auto" action="/admin/media/upload.php" method="post" enctype="multipart/form-data">
                                <label for="media_id" class="form-label">Fichier.jpeg/png/webp/pdf</label>
                                <input class="form-control form-control-lg" type="file" id="media_id" name="media">
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

    echo '<div class="row row-cols-1 row-cols-md-4 g-4 m-2" id="media-grid">';
    foreach ($all_medias as $media) {
     echo render_media($media);
    }
    echo '</div>';

    require_once __DIR__ . '/../../templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur

    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}
