<?php

// Retourne la liste de menu
function list_menus(): array
{
    $menus = [
        'centre' => ['label' => 'ASSOCIATION POINT COM', 'icone' => 'bi-building', 'page' => 'centre-de-formation', 'dropdown' => true, 'liens' => [['label' => 'Catalogue', 'icone' => 'bi-journal-text', 'url' => 'https://www.canva.com/design/DAFQI_-V5xY/7p5Fap0JhS1J8emdZRf1Lg/view?utm_content=DAFQI_-V5xY&utm_campaign=designshare&utm_medium=link&utm_source=editor']]],
        'nos-formations' => ['editable' => false, 'label' => 'Nos formations', 'intro' => "Découvrez l’ensemble de notre offre de formations, conçue pour répondre à vos besoins et accompagner l’évolution de vos compétences.", 'image' => '/assets/image/rubriques/formation.svg', 'icone' => 'bi-book', 'page' => 'nos-formations', 'sous_menus' => ['niveau-3' => 'Certification de niveau 3', 'niveau-4' => 'Certification de niveau 4', 'niveau-5' => 'Certification de niveau 5', 'atelier' => 'Atelier de formation'], 'dropdown' => true],
        'recrutement' => ['editable' => false, 'label' => 'Recrutement', 'intro' => "Retrouvez nos offres d’emploi ainsi que les opportunités proposées par nos entreprises partenaires à la recherche d’apprentis.", 'image' => '/assets/image/rubriques/recrutement.svg', 'icone' => 'bi-person-fill', 'page' => 'recrutement', 'dropdown' => true],
        'alternance' => ['editable' => false, 'label' => 'Formation en alternance', 'intro' => "Un diplôme, une expérience professionnelle et un premier pas dans l’emploi : l’alternance, c’est tout cela à la fois.", 'image' => '/assets/image/rubriques/alternance.svg', 'icone' => 'bi-people', 'page' => 'alternance', 'dropdown' => true],
        'rhf-inclusion' => ['editable' => false, 'label' => 'RHF Inclusion', 'intro' => "Un engagement concret pour rendre la formation accessible à toutes et à tous.", 'icone' => 'bi-universal-access', 'image' => '/assets/image/rubriques/rhf.svg', 'page' => 'rhf-inclusion', 'dropdown' => true],
        'stat' => ['label' => 'Statistiques', 'intro' => "Parce que la transparence compte, nous publions les résultats de nos formations, mis à jour chaque année.", 'image' => '/assets/image/rubriques/stats.svg', 'icone' => 'bi-graph-up', 'page' => 'statistiques', 'dropdown' => true],
        'bilan' => ['editable' => false, 'label' => 'Bilan de compétences', 'intro' => "Faites le point sur votre situation professionnelle et envisagez sereinement une évolution ou une reconversion.", 'image' => '/assets/image/rubriques/bilan.svg', 'icone' => 'bi-bar-chart', 'page' => 'bilan-de-competences', 'dropdown' => false],
        'contact' => ['label' => 'Contact', 'icone' => 'bi-envelope', 'page' => 'contact', 'dropdown' => false],
        'portail' => ['label' => 'Portail', 'icone' => 'bi-box-arrow-in-right', 'url' => 'https://point-com-97.github.io/Portail/', 'dropdown' => false],
    ];
    return $menus;
}

function list_pages_footer(): array
{
    $pages_legal = [
        'registre' => ['label' => 'Registre public', 'page' => 'registre'],
        'mentions-legales' => ['label' => 'Mentions légales', 'page' => 'mentions-legales'],
        'cgv' => ['label' => 'Conditions générales de vente', 'page' => 'cgv'],
        'cgu' => ['label' => "Conditions générales d’utilisation", 'page' => 'cgu'],
        'reglement-interieur' => ['label' => "Règlement intérieur", 'page' => 'reglement-interieur'],
    ];

    return $pages_legal;
}

// Retourne true si le menu existe et possède un sous-menu (dropdown)
function menu_validation(string $menu_slug): bool
{
    $menus = list_menus();

    return isset($menus[$menu_slug]) && $menus[$menu_slug]['dropdown'];
}

// Recherche dans la liste si le slug existe et retourne true si oui
function banned_menu_slug(string $menu_slug): bool
{
    $banned_slugs = ['admin', 'search', 'login', 'logout', 'register', 'dashboard', 'assets', 'uploads', 'sitemap', 'robots'];
    return in_array($menu_slug, $banned_slugs, true);
}

// Valide une destination "menu" ou "menu/sous-menu" reçue du <select>.
// Retourne [menu, sous_menu|null] si valide, sinon null.
function destination_validation(string $valeur): ?array
{
    [$menu_slug, $sous_menu] = array_pad(explode('/', $valeur, 2), 2, null); // Sépare $valeur au premier / avec explode. array_pad ajoute ensuite null si aucun sous-menu n’a été indiqué.
    if (!menu_validation($menu_slug)) {
        return null;                       // menu inconnu ou sans dropdown
    }

    $sous_menus = list_menus()[$menu_slug]['sous_menus'] ?? []; 

    if ($sous_menus === []) {              // menu sans sous-menus : aucun sous-menu accepté
        return $sous_menu === null ? [$menu_slug, null] : null;
    }

    // menu avec sous-menus : le sous-menu est obligatoire et doit exister
    return isset($sous_menus[$sous_menu]) ? [$menu_slug, $sous_menu] : null;
}

// Regroupe les pages d'un menu par sous-menu, dans l'ordre de la config (sous-menus vides ignorés)
function group_by_sous_menu(array $pages, array $sous_menus): array
{
    $groupes = [];
    foreach ($sous_menus as $cle => $label) {
        $liste = array_values(array_filter($pages, fn($p) => $p['sous_menu'] === $cle));
        if ($liste) {
            $groupes[$label] = $liste;
        }
    }
    return $groupes;
}

function page_editable(array $page): bool
{
    if (($page['systeme'] ?? 0) == 0) {
        return true;
    }
    foreach ([list_menus(), list_pages_footer()] as $liste) {
        foreach ($liste as $m) {
            if (($m['page'] ?? null) === $page['slug']) {
                return $m['editable'] ?? true;
            }
        }
    }
    return true;
}