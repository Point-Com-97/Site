<?php
/**
 * Création d'un bloc de contenu (texte, image, vidéo, stats, tableau).
 * Le contenu texte est sanitisé à la réception.
 */

declare(strict_types=1);

require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../../src/php/Csrf.php';
require_once __DIR__ . '/../../../src/php/HtmlSanitizer.php';
require_once __DIR__ . '/../../../src/php/helpers.php';

header('Content-Type: application/json; charset=utf-8');

// CSRF : POST uniquement (pas de GET)
if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require __DIR__ . '/../../templates/admin/item/bloc.php';
require_once __DIR__ . '/../../../src/php/Bloc.php';
require_once __DIR__ . '/../../../src/php/Media.php';
require_once __DIR__ . '/../../../src/php/Page.php';
require_once __DIR__ . '/../../../src/config/menus.php';

$page_id = (int) ($_POST['page_id'] ?? 0);
$type = $_POST['type'] ?? '';
$classes = htmlspecialchars($_POST['classes_css'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$page = new Page();
$page_info = $page->getById($page_id);

if ($page_info === null || !page_editable($page_info)) {
    echo json_encode(['success' => false, 'message' => 'Page introuvable ou non éditable']);
    exit;
}

switch ($type) {
    case 'texte':
        // Sanitiser le HTML de l'éditeur Quill à la réception
        $donnees = [
            'contenu' => HtmlSanitizer::nettoyer($_POST['contenu'] ?? ''),
            'classes' => $classes
        ];
        break;

    case 'video':
        $url = trim($_POST['url'] ?? '');
        // Valider l'URL vidéo
        $parsed = parse_url($url);
        $hotesAutorises = ['www.youtube.com', 'www.youtube-nocookie.com', 'youtube.com', 'player.vimeo.com', 'www.dailymotion.com'];
        if (($parsed['scheme'] ?? '') !== 'https' || !in_array($parsed['host'] ?? '', $hotesAutorises, true)) {
            echo json_encode(['success' => false, 'message' => 'URL vidéo non autorisée']);
            exit;
        }
        $donnees = [
            'url' => $url,
            'legende' => htmlspecialchars($_POST['legende'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            'classes' => $classes
        ];
        break;

    case 'image':
        $donnees = [
            'media_id' => (int) ($_POST['media_id'] ?? 0),
            'legende' => htmlspecialchars($_POST['legende'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            'classes' => $classes
        ];
        break;

    case 'stats':
        $all_labels = [];
        $all_data = [];
        $count = 1;
        while (!empty($_POST["labels_{$count}"])) {
            $all_labels[] = htmlspecialchars($_POST["labels_{$count}"], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $all_data[] = (float) ($_POST["data_{$count}"] ?? 0);
            $count++;
        }
        $donnees = [
            "type" => htmlspecialchars($_POST['chart_type'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            "data" => [
                "labels" => $all_labels,
                "datasets" => [
                    [
                        "label" => htmlspecialchars($_POST['dataset_label'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                        "data" => $all_data
                    ]
                ]
            ],
            "classes" => $classes
        ];
        break;

    case 'tableau':
        $name_tab = htmlspecialchars($_POST['name_tab'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $all_col = [];
        $count = 1;
        while (!empty($_POST["col_{$count}"])) {
            $all_col[] = htmlspecialchars($_POST["col_{$count}"], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $count++;
        }
        $nb_colonnes = count($all_col);

        $all_lignes = [];
        $ligne_count = 1;
        while (isset($_POST["cell_{$ligne_count}_1"]) && $_POST["cell_{$ligne_count}_1"] !== '') {
            $ligne = [];
            for ($col_index = 1; $col_index <= $nb_colonnes; $col_index++) {
                $ligne[] = htmlspecialchars($_POST["cell_{$ligne_count}_{$col_index}"] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
            $all_lignes[] = $ligne;
            $ligne_count++;
        }

        $donnees = ["nom" => $name_tab, "colonnes" => $all_col, "lignes" => $all_lignes, "classes" => $classes];
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Type invalide']);
        exit;
}

$bloc = new Bloc();
$new_bloc = $bloc->create($page_id, $type, $donnees);

if (!$new_bloc) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
    exit;
}

$main = ['id' => $new_bloc, 'type' => $type, 'donnees' => json_encode($donnees)];
$bloc_html = render_bloc($main);

echo json_encode(['success' => $new_bloc, 'html' => $bloc_html]);
