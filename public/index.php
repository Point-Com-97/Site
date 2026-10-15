<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const ACCUEIL = 'centre-de-formation';

require_once __DIR__ . '/../src/php/Page.php';
require_once __DIR__ . '/../src/php/Bloc.php';
require_once __DIR__ . '/../src/php/Media.php';
require_once __DIR__ . '/../src/config/menus.php';
require_once __DIR__ . '/templates/admin/item/menu.php';

// Affiche la page 404 avec le vrai code HTTP, puis arrête le script
function page_404(): never
{
    http_response_code(404);
    $current_page = ['titre' => 'Page introuvable', 'meta_description' => 'Cette page n\'existe pas.'];
    $all_blocs = [];
    try { $page_group = sort_pages((new Page())->getByMenuOn()); } catch (Throwable) { $page_group = []; }
    require __DIR__ . '/templates/header.php';
    require __DIR__ . '/templates/404.php';
    require __DIR__ . '/templates/footer.php';
    exit;
}

try {
    $chemin = trim(rawurldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');

    // "/" = accueil ; "/centre-de-formation" est redirigé vers "/" (une seule URL par page)
    if ($chemin === ACCUEIL) {
        header('Location: /', true, 301);
        exit;
    }
    $slug = ($chemin === '') ? ACCUEIL : $chemin;

    $is_admin = !empty($_SESSION['admin_id']);

    $pages = new Page();
    $current_page = $pages->getBySlug($slug);

    // Test AVANT toute lecture de $current_page['...']
    if ($current_page === null || ((int) $current_page['visible'] === 0 && !$is_admin)) {
        page_404();
    }

    $page_group = sort_pages($pages->getByMenuOn());   // pages visibles groupées par menu (pour la navbar)
    $all_blocs = (new Bloc())->getByPageId((int) $current_page['id']);

    // Rubrique associée si c'est une page système (ex. "formation" -> menu "formation")
    $menus = list_menus();
    $cle_menu = null;
    foreach ($menus as $cle => $m) {
        if (($m['page'] ?? null) === $slug) {
            $cle_menu = $cle;
            break;
        }
    }

    $est_accueil = ($slug === ACCUEIL);
    $est_contact = ($slug === 'contact');

    require __DIR__ . '/templates/header.php';

    if ($est_accueil) {
        require __DIR__ . '/templates/accueil.php';
    } else {
        require __DIR__ . '/templates/page.php';
    }

    require __DIR__ . '/templates/footer.php';
} catch (Throwable $e) {
    error_log("Erreur : " . $e->getMessage() . "\n", 3, __DIR__ . "/../var/tmp/erreur.log");
    http_response_code(500);
    echo "<h1>Le site est temporairement indisponible, merci de réessayer plus tard.</h1>";
}
