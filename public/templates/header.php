<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../src/config/menus.php';
require_once __DIR__ . '/../../src/php/helpers.php';

const SITE_URL = 'https://www.cfa-pcom.fr';

$intro_rubrique = ($cle_menu ?? null) ? (list_menus()[$cle_menu]['intro'] ?? '') : '';
$description = e($current_page['meta_description'] ?? '') ?: e($intro_rubrique) ?: 'Point Com - La formation tout au long de la vie';
$titre_page = e($current_page['titre'] ?? 'Accueil');
$chemin_url = strtok($_SERVER['REQUEST_URI'], '?');
$url_courante = SITE_URL . $chemin_url;
$og_image = SITE_URL . '/assets/image/Main.png';

if (!empty($all_blocs)) {
    foreach ($all_blocs as $b) {
        $d = json_decode($b['donnees'], true);
        if ($b['type'] === 'image' && !empty($d['media_id'])) {
            $media = (new Media())->getById($d['media_id']);
            if (!empty($media)) {
                $og_image = SITE_URL . $media['url'];
            }
            break;
        }
    }
}

$slug_courant = trim($chemin_url, '/');
if ($slug_courant === '') {
    $slug_courant = 'centre-de-formation';
}

// Lien d'un menu : externe (url) ou interne (page). L'accueil est servi sur "/".
function menu_href(array $m): string
{
    if (isset($m['url'])) {
        return trim($m['url']);
    }
    return $m['page'] === 'centre-de-formation' ? '/' : '/' . $m['page'];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/assets/image/ico.png">
    <link rel="stylesheet" href="assets/scss/main.css">

    <title><?= $titre_page ?> - Point Com</title>
    <meta name="description" content="<?= $description ?>">
    <link rel="canonical" href="<?= e($url_courante) ?>">
    <meta property="og:title" content="<?= $titre_page ?>">
    <meta property="og:description" content="<?= $description ?>">
    <meta property="og:image" content="<?= e($og_image) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($url_courante) ?>">
    <?php if (http_response_code() === 404): ?>
        <meta name="robots" content="noindex"><?php endif; ?>
</head>

<body>
    <nav class="navbar sticky-top navbar-expand-lg bg-primary navbar-animate" id="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><img src="/assets/image/Main.png" alt="Point Com - accueil" class="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
                aria-controls="navMain" aria-expanded="false" aria-label="Ouvrir le menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php foreach (list_menus() as $cle => $m):
                        $externe = isset($m['url']);
                        $href = menu_href($m);
                        $actif = !$externe && ($m['page'] === $slug_courant);
                        $pages_menu = $m['dropdown'] ? ($page_group[$cle] ?? []) : [];
                        $liens = $m['liens'] ?? [];
                        $a_sous_menu = !empty($pages_menu) || !empty($liens);
                        $cible = $externe ? ' target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                        <?php if ($a_sous_menu): ?>
                            <li class="nav-item dropdown nav-hover">
                                <div class="d-flex align-items-center">
                                    <a class="nav-link<?= $actif ? ' active' : '' ?>" href="<?= e($href) ?>">
                                        <i class="bi <?= e($m['icone']) ?>"></i> <?= e($m['label']) ?>
                                    </a>
                                    <!-- Bouton flèche : sert au tactile / mobile (au survol, le CSS ouvre le menu) -->
                                    <button class="btn nav-caret dropdown-toggle dropdown-toggle-split" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Ouvrir le sous-menu <?= e($m['label']) ?></span>
                                    </button>
                                </div>
                                <ul class="dropdown-menu">
                                    <?php if (!empty($m['sous_menus'])): ?>
                                        <?php foreach (group_by_sous_menu($pages_menu, $m['sous_menus']) as $label => $liste): ?>
                                            <li>
                                                <h6 class="dropdown-header"><?= e($label) ?></h6>
                                            </li>
                                            <?php foreach ($liste as $p): ?>
                                                <li><a class="dropdown-item" href="/<?= e($p['slug']) ?>"><?= e($p['titre']) ?></a></li>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php foreach ($pages_menu as $p): ?>
                                            <li><a class="dropdown-item" href="/<?= e($p['slug']) ?>"><?= e($p['titre']) ?></a></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($pages_menu) && !empty($liens)): ?>
                                        <li><hr class="dropdown-divider"></li>
                                    <?php endif; ?>
                                    <?php foreach ($liens as $l): ?>
                                        <li><a class="dropdown-item" href="<?= e(trim($l['url'])) ?>" target="_blank" rel="noopener noreferrer">
                                                <i class="bi <?= e($l['icone']) ?>"></i> <?= e($l['label']) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $actif ? ' active' : '' ?>" href="<?= e($href) ?>" <?= $cible ?>>
                                    <i class="bi <?= e($m['icone']) ?>"></i> <?= e($m['label']) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <form class="d-flex position-relative" role="search" id="search-form" autocomplete="off">
                    <input class="form-control me-2" type="search" name="q" id="search-input" placeholder="Rechercher" aria-label="Rechercher">
                    <button class="btn btn-search" type="submit">Recherche</button>
                    <ul class="list-group position-absolute w-100" id="search-results" style="top:100%;z-index:1050;display:none;"></ul>
                </form>
            </div>
        </div>
    </nav>
    <main class="container-fluid p-0">