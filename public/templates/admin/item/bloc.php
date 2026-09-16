<?php

function render_bloc(array $item)
{
    $type = $item['type'];
    $data = json_decode($item['donnees'], true);

    switch ($type) {
        case 'texte':
            $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
            return <<< HTML
                                <div class="container" id="Bloc_{$item['id']}" data-id="{$item['id']}" draggable="true">
                                    <p>Bloc texte: {$data['contenu']}</p>
                                    <button type="button" class="btn btn-danger" onclick="remove_items_bloc({$item['id']})">
                                        <i class="bi bi-trash3-fill"></i></button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_bloc"
                                            data-bloc-id="{$item['id']}" data-bloc-type="texte" data-bloc-donnees="{$json}"
                                            onclick="prefill_bloc(this)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            HTML;
        case 'image':
            $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
            return <<< HTML
                                <div class="container" id="Bloc_{$item['id']}" data-id="{$item['id']}" draggable="true">
                                    <p>Bloc image: Media: {$data['media_id']} Legende :{$data['legende']}</p>
                                    <button type="button" class="btn btn-danger" onclick="remove_items_bloc({$item['id']})">
                                        <i class="bi bi-trash3-fill"></i></button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_bloc"
                                            data-bloc-id="{$item['id']}" data-bloc-type="image" data-bloc-donnees="{$json}"
                                            onclick="prefill_bloc(this)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            HTML;
        case 'video':
            $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
            return <<< HTML
                                <div class="container" id="Bloc_{$item['id']}" data-id="{$item['id']}" draggable="true">
                                    <p>Bloc video: Url: {$data['url']} Legende :{$data['legende']}</p>
                                     <button type="button" class="btn btn-danger" onclick="remove_items_bloc({$item['id']})">
                                        <i class="bi bi-trash3-fill"></i></button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_bloc"
                                            data-bloc-id="{$item['id']}" data-bloc-type="video" data-bloc-donnees="{$json}"
                                            onclick="prefill_bloc(this)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            HTML;
        case 'stats':
            $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
            $nb_items = count($data['labels'] ?? []);
            return <<< HTML
                        <div class="container" id="Bloc_{$item['id']}" data-id="{$item['id']}" draggable="true">
                            <p>Bloc stats: {$nb_items} indicateurs (type: {$data['type']})</p>
                            <button type="button" class="btn btn-danger" onclick="remove_items_bloc({$item['id']})">
                                <i class="bi bi-trash3-fill"></i></button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_bloc"
                                    data-bloc-id="{$item['id']}" data-bloc-type="stats" data-bloc-donnees="{$json}"
                                    onclick="prefill_bloc(this)">
                                <i class="bi bi-pencil-square"></i>
                                </button>
                        </div>
                    HTML;

        case 'tableau':
            $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
            $nb_colonnes = count($data['colonnes'] ?? []);
            $nb_lignes = count($data['lignes'] ?? []);
            return <<< HTML
                        <div class="container" id="Bloc_{$item['id']}" data-id="{$item['id']}" draggable="true">
                            <p>Bloc tableau: {$nb_colonnes} colonnes, {$nb_lignes} lignes</p>
                        <button type="button" class="btn btn-danger" onclick="remove_items_bloc({$item['id']})">
                            <i class="bi bi-trash3-fill"></i></button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_bloc"
                                data-bloc-id="{$item['id']}" data-bloc-type="tableau" data-bloc-donnees="{$json}"
                                onclick="prefill_bloc(this)">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        </div>
                    HTML;
    };
}
