<?php

function render_bloc(array $item)
{
    $type = $item['type'];
    $data = json_decode($item['donnees'], true);
    $csrf = generer_csrf_token();
    $classes = $data['classes'] ?? '';
    $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');

    $apercu = '';

    switch ($type) {
        case 'texte':
            $extrait = mb_substr(strip_tags($data['contenu'] ?? ''), 0, 160);
            $apercu = "<p class=\"mb-0 {$classes}\">{$extrait}...</p>";
            break;

        case 'image':
            $new_media = new Media();
            $url_media = $new_media->getById($data['media_id'] ?? 0);
            $src = !empty($url_media) ? $url_media['url'] : '/assets/image/pdf.png';
            $legende = htmlspecialchars($data['legende'] ?? '');
            $apercu = "<img src=\"{$src}\" alt=\"{$legende}\" class=\"bloc-preview-img rounded\">"
                . "<span class=\"ms-2 text-muted\">{$legende}</span>";
            break;

        case 'video':
            $legende = htmlspecialchars($data['legende'] ?? '');
            $apercu = "<i class=\"bi bi-camera-video-fill fs-3 me-2\"></i><span>{$legende}</span>";
            break;

        case 'stats':
            $nb_items = count($data['data']['labels'] ?? []);
            $chart_type = htmlspecialchars($data['type'] ?? '');
            $apercu = "<i class=\"bi bi-bar-chart-fill fs-3 me-2\"></i>"
                . "<span>{$nb_items} indicateurs · graphique {$chart_type}</span>";
            break;

        case 'tableau':
            $nb_colonnes = count($data['colonnes'] ?? []);
            $nb_lignes = count($data['lignes'] ?? []);
            $apercu = "<i class=\"bi bi-table fs-3 me-2\"></i>"
                . "<span>{$nb_colonnes} colonnes × {$nb_lignes} lignes</span>";
            break;

        default:
            $apercu = "<span class=\"text-muted\">Type de bloc inconnu</span>";
    }

    $badge = match ($type) {
        'texte' => 'primary',
        'image' => 'success',
        'video' => 'danger',
        'stats' => 'warning',
        'tableau' => 'info',
        default => 'secondary',
    };

    return <<< HTML
        <div class="container bloc-row" id="Bloc_{$item['id']}" data-id="{$item['id']}" draggable="true">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-2">
                <div class="d-flex align-items-center flex-grow-1">
                    <span class="badge bg-{$badge} text-uppercase me-3">{$type}</span>
                    <div class="bloc-apercu">{$apercu}</div>
                </div>
                <div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="remove_items_bloc({$item['id']},'{$csrf}')">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#new_bloc"
                            data-bloc-id="{$item['id']}" data-bloc-type="{$type}" data-bloc-donnees="{$json}"
                            onclick="prefill_bloc(this)">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                </div>
            </div>
        </div>
    HTML;
}