<?php
require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../../src/php/Csrf.php';

if (!verifier_csrf_token($_POST['csrf_token'] ?? $_GET['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}


require __DIR__ . '/../../templates/admin/item/bloc.php';
require_once __DIR__ . '/../../../src/php/Bloc.php';

header('Content-Type: application/json');

$page_id = (int) ($_POST['page_id'] ?? 0);
$type = $_POST['type'] ?? '';
$classes = htmlspecialchars($_POST['classes_css'] ?? '', ENT_QUOTES, 'UTF-8');

switch ($type) {
    case 'texte':
        $donnees = [
            'contenu' => $_POST['contenu'],
            'classes' => $classes
        ];
        break;
    case 'video':
        $donnees = [
            'url' => $_POST['url'],
            'legende' => $_POST['legende'],
            'classes' => $classes
        ];
        break;
    case 'image':
        $donnees = [
            'media_id' => (int) $_POST['media_id'],
            'legende' => $_POST['legende'],
            'classes' => $classes
        ];
        break;
    case 'stats':

        $all_labels = [];
        $all_data = [];
        $count = 1;

        while (!empty($_POST["labels_{$count}"])) {
            $all_labels[] = $_POST["labels_{$count}"];
            $all_data[] = (float) $_POST["data_{$count}"];
            $count++;
        }

        $donnees = [
            "type" => $_POST['chart_type'],
            "data" => [
                "labels" => $all_labels,
                "datasets" => [
                    [
                        "label" => $_POST['dataset_label'],
                        "data" => $all_data
                    ]
                ]
            ],
            "classes" => $classes
        ];

        break;
    case 'tableau':
        $all_col = [];
        $all_row = [];
        $count = 1;

        while (!empty($_POST["col_{$count}"]) && !empty($_POST["row_{$count}"])) {
            $all_col[] = $_POST["col_{$count}"];
            $all_row[] = $_POST["row_{$count}"];
            $count++;
        }

        $donnees = [
            "colonnes" => $all_col,
            "lignes" => [$all_row],
            "classes" => $classes
        ];
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Type invalide']);
        exit;
}

$bloc = new Bloc();

$new_bloc = $bloc->create($page_id, $type, $donnees);

$main = array('id' => $new_bloc, 'type' => $type, 'donnees' => json_encode($donnees));

$bloc_html = render_bloc($main);

$html_complet = "{$bloc_html}";

echo json_encode(['success' => $new_bloc, 'html' => $html_complet]);
