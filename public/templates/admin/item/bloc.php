<?php

function render_bloc(array $item)
{
    $type = $item['type'];
    $data = json_decode($item['donnees'], true);

    switch ($type) {
        case 'texte':
            return <<< HTML
                                 <p>Bloc texte: Media: {$data['contenu']}</p> 
                            HTML;
        case 'image':
            return <<< HTML
                                 <p>Bloc image: Media: {$data['media_id']} Legende :{$data['legende']}</p> 
                            HTML;
        case 'video':
            return <<< HTML
                                 <p>Bloc video: Url: {$data['url']} Legende :{$data['legende']}</p>
                            HTML;
        case 'stats':
            $nb_items = count($data['labels'] ?? []);
            return <<< HTML
                         <li>Bloc stats: {$nb_items} indicateurs (type: {$data['type']})</li>
                    HTML;

        case 'tableau':
            $nb_colonnes = count($data['colonnes'] ?? []);
            $nb_lignes = count($data['lignes'] ?? []);
            return <<< HTML
                         <li>Bloc tableau: {$nb_colonnes} colonnes, {$nb_lignes} lignes</li>
                    HTML;
    };
}
