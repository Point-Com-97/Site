<?php

function render_media(array $media)
{
    $titre = htmlspecialchars($media['titre']);

    switch ($media['type']) {
        case 'image':
            return <<< HTML
            <div class="col" data-id="{$media['id']}">
                <div class="card">
                    <img src="{$media['url']}" class="card-img-top img-thumbnail" alt="{$titre}">
                    <div class="card-body">
                        <h5 class="card-title">{$titre}</h5>
                        <p class="card-text">Date de création : {$media['created_at']}</p>
                    </div>
                    <div class="btn-group">
                        <button onclick="remove_media({$media['id']})" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
                    </div>
                </div>
            </div>
        HTML;
            break;
        case 'pdf':
            return <<< HTML
            <div class="col" data-id="{$media['id']}">
                <div class="card">
                    <img src="/assets/image/pdf.png" class="card-img-top" alt="{$titre}">
                    <div class="card-body">
                        <h5 class="card-title">{$titre}</h5>
                        <p class="card-text">Date de création : {$media['created_at']}</p>
                    </div>
                        <div class="btn-group">
                            <button onclick="remove_media({$media['id']})" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
                        </div>
                </div>
            </div>
        HTML;
            break;
    }
}
