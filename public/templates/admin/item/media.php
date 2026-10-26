<?php
/**
 * Rendu d'un média dans l'interface d'administration.
 */

require_once __DIR__ . '/../../../src/php/helpers.php';

function render_media(array $media)
{
    $csrf = generer_csrf_token();
    $titre = htmlspecialchars($media['titre'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $url = htmlspecialchars($media['url'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $date = htmlspecialchars($media['created_at'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $mediaId = (int) ($media['id'] ?? 0);
    $type = $media['type'] ?? 'image';

    if ($type === 'pdf') {
        return <<< HTML
            <div class="col" data-id="{$mediaId}">
                <div class="card">
                    <img src="/assets/image/pdf.png" class="card-img-top" alt="{$titre}">
                    <div class="card-body">
                        <h5 class="card-title">{$titre}</h5>
                        <p class="card-text">Date de création : {$date}</p>
                    </div>
                    <div class="btn-group">
                        <button onclick="remove_media({$mediaId}, '{$csrf}')" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
                    </div>
                </div>
            </div>
        HTML;
    }

    // Par défaut : image
    return <<< HTML
        <div class="col" data-id="{$mediaId}">
            <div class="card">
                <img src="{$url}" class="card-img-top img-thumbnail" alt="{$titre}">
                <div class="card-body">
                    <h5 class="card-title">{$titre}</h5>
                    <p class="card-text">Date de création : {$date}</p>
                </div>
                <div class="btn-group">
                    <button onclick="remove_media({$mediaId}, '{$csrf}')" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
                </div>
            </div>
        </div>
    HTML;
}
