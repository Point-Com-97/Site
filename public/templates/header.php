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
    <nav class="navbar navbar-expand-lg bg-primary navbar-animate" id="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/assets/image/logo.jpeg" alt="Logo" class="logo">
            </a>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <ul class="navbar-nav me-auto nav-underline">
                        <li class="nav-item">
                            <a class="nav-link" href="/">ACCUEIL</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="https://canva.link/8vn2d5u0lnkz6p4">CATALOGUE DE FORMATION</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://point-com-97.github.io/Portail/#link-hygiene-espace-vert">PORTAIL D'INSCRIPTION</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://location-salle-pcom.my.canva.site/">LOCATION DE SALLE</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <main class="container-fluid text-center board-container">