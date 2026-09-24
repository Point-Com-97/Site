<?php session_start(); ?>

<?php
require_once __DIR__ . '/../../src/php/Settings.php';
$new_settings = new Settings();
$settings = $new_settings->get();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/image/ico.png">
    <script src="/assets/vendor/chart.js/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/scss/main.css">

    <style>
        :root {
            --bs-primary: <?= $settings['couleur_primaire'] ?? '#019DD4' ?>;
            --bs-primary-rgb: <?= hex_vers_rgb($settings['couleur_primaire'] ?? '#019DD4') ?>;
            --bs-link-color: <?= $settings['couleur_lien'] ?? '#0022ff' ?>;
            --bs-body-font-family: <?= $settings['police_corps'] ?? 'Arial, sans-serif' ?>;
        }

        .btn-primary {
            --bs-btn-bg: <?= $settings['couleur_primaire'] ?? '#019DD4' ?> !important;
            --bs-btn-border-color: <?= $settings['couleur_primaire'] ?? '#019DD4' ?> !important;
            --bs-btn-color: <?= $settings['couleur_texte_bouton'] ?? '#ffffff' ?> !important;
              background-color: <?= $settings['couleur_primaire'] ?? '#019DD4' ?> !important;
        }

        .btn-secondary {
            --bs-btn-bg: <?= $settings['couleur_secondaire'] ?? '#6c757d' ?> !important;
            --bs-btn-border-color: <?= $settings['couleur_secondaire'] ?? '#6c757d' ?> !important;
            --bs-btn-color: <?= $settings['couleur_texte_bouton'] ?? '#ffffff' ?> !important;
            background-color: <?= $settings['couleur_secondaire'] ?? '#019DD4' ?> !important;
        }
             
        <?= str_replace('</style>', '', $settings['css_personnalise'] ?? '') ?>
    </style>

    <title><?= htmlspecialchars($current_page['titre'] ?? 'Accueil') ?> - Point Com</title>
</head>


<body>
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/assets/image/logo.jpeg" alt="Logo" width="200" height="50" class="d-inline-block align-text-top">
            </a>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <?php if (!empty($all_menu) && is_array($all_menu)): ?>
                        <?php foreach ($all_menu as $item): ?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= htmlspecialchars($item['menu_titre']) ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                 <?php $page_menu = $page_group[$item['menu_id']] ?? [] ?>
                                    <?php if (!empty($page_menu) && is_array($page_menu)): ?>
                                        <?php foreach ($page_menu as $page): ?>
                                            <li><a class="dropdown-item" href="<?= htmlspecialchars($page['slug']) ?>"><?= htmlspecialchars($page['titre']) ?></a></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container"></div>
    <main>