<?php
/**
 * Mise à jour d'un bloc de contenu.
 * Le contenu texte est sanitisé à la réception.
 */

declare(strict_types=1);

require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../../src/php/Csrf.php';
require_once __DIR__ . '/../../../src/php/HtmlSanitizer.php';
require_once __DIR__ . '/../../../src/php/helpers.php';

// CSRF : POST uniquement (pas de GET pour éviter la fuite du token)
if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

require_once __DIR__ . '/../../../src/php/Bloc.php';

header('Content-Type: application/json; charset=utf-8');

$id = (int) ($_POST['id'] ?? 0);
$type = $_POST['type'] ?? '';
$classes = htmlspecialchars($_POST['classes_css'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID invalide']);
    exit;
}

switch ($type) {
    case 'texte':
        // Sanitiser le HTML de l'éditeur Quill
        $donnees = [
            'contenu' => HtmlSanitizer::nettoyer($_POST['contenu'] ?? ''),
            'classes' => $classes
        ];
        break;

    case 'video':
        $url = trim($_POST['url'] ?? '');
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
$resultat = $bloc->update($id, $donnees);

echo json_encode(['success' => (bool) $resultat]);
