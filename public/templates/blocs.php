<?php
// Domaines autorisés pour les vidéos (iframes)
const VIDEO_HOTES = ['www.youtube.com', 'www.youtube-nocookie.com', 'youtube.com', 'player.vimeo.com', 'www.dailymotion.com'];

function video_url_valide(string $url): bool
{
    $p = parse_url($url);
    return ($p['scheme'] ?? '') === 'https' && in_array($p['host'] ?? '', VIDEO_HOTES, true);
}

// Affiche tous les blocs d'une page. Le texte (éditeur Quill) est du HTML écrit par l'admin : il est affiché tel quel.
// Tout le reste est échappé.
function render_blocs(array $blocs): void
{
    foreach ($blocs as $bloc) {
        $d = json_decode($bloc['donnees'], true) ?: [];
        $classes = e($d['classes'] ?? '');

        switch ($bloc['type']) {
            case 'texte':
                echo '<div class="bloc reveal ' . $classes . '">' . ($d['contenu'] ?? '') . '</div>';
                break;

            case 'image':
                $media = (new Media())->getById($d['media_id'] ?? 0);
                if (!empty($media)) {
                    echo '<div class="bloc reveal ' . $classes . '"><img src="' . e($media['url']) . '" alt="' . e($d['legende'] ?? '')
                        . '" loading="lazy" class="img-fluid rounded d-block mx-auto"></div>';
                }
                break;

            case 'video':
                // Lecture au clic : l'iframe n'est créée par public.js qu'après le clic
                $url = $d['url'] ?? '';
                if (video_url_valide($url)) {
                    echo '<div class="bloc reveal ' . $classes . '"><div class="video-clic ratio ratio-16x9" data-src="' . e($url)
                        . '" data-titre="' . e($d['legende'] ?? 'Vidéo') . '"><button type="button" class="video-play" aria-label="Lire la vidéo">'
                        . '<i class="bi bi-play-circle-fill"></i></button></div></div>';
                }
                break;

            case 'stats':
                // Le graphique est décrit dans data-chart (JSON échappé) ; public.js crée le Chart
                echo '<div class="bloc reveal ' . $classes . '"><canvas class="js-chart" data-chart="'
                    . e(json_encode($d, JSON_UNESCAPED_UNICODE)) . '"></canvas></div>';
                break;

            case 'tableau':
                echo '<div class="table-responsive bloc reveal"><table class="table table-hover table-striped table-light caption-top ' . $classes . '">';
                echo '<caption class="table-name">' . e($d['nom'] ?? '') . '</caption><thead><tr>';
                foreach ($d['colonnes'] ?? [] as $col) {
                    echo '<th>' . e($col) . '</th>';
                }
                echo '</tr></thead><tbody>';
                foreach ($d['lignes'] ?? [] as $ligne) {
                    echo '<tr>';
                    foreach ($ligne as $cell) {
                        echo '<td>' . e((string) $cell) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table></div>';
                break;
        }
    }
}
