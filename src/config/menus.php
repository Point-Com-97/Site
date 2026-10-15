<?php

// Retourne la liste de menu
function list_menus(): array
{
    $menus = [
        'centre' => ['label' => 'Centre de formation', 'icone' => 'bi-building', 'page' => 'centre-de-formation', 'dropdown' => true,'liens' => [['label' => 'Catalogue', 'icone' => 'bi-journal-text', 'url' => 'https://www.canva.com/design/DAFQI_-V5xY/7p5Fap0JhS1J8emdZRf1Lg/view?utm_content=DAFQI_-V5xY&utm_campaign=designshare&utm_medium=link&utm_source=editor']]],
        'formation' => ['label' => 'Formation', 'icone' => 'bi-book', 'page' => 'formation', 'dropdown' => true],
        'recrutement' => ['label' => 'Recrutement', 'icone' => 'bi-person-fill', 'page' => 'recrutement', 'dropdown' => true],
        'alternance' => ['label' => 'Alternance', 'icone' => 'bi-people', 'page' => 'alternance', 'dropdown' => true],
        'rhf-inclusion' => ['label' => 'RHF Inclusion', 'icone' => 'bi-universal-access', 'page' => 'rhf-inclusion', 'dropdown' => true],
        'stat' => ['label' => 'Statistiques', 'icone' => 'bi-graph-up', 'page' => 'statistiques', 'dropdown' => true],
        'bilan' => ['label' => 'Bilan de compétences', 'icone' => 'bi-bar-chart', 'page' => 'bilan-de-competences', 'dropdown' => false],
        'contact' => ['label' => 'Contact', 'icone' => 'bi-envelope', 'page' => 'contact', 'dropdown' => false],
        'portail' => ['label' => 'Portail', 'icone' => 'bi-box-arrow-in-right', 'url' => 'https://point-com-97.github.io/Portail/ ', 'dropdown' => false],
    ];
    return $menus;
}

// Retourne true si le menu existe et possède un sous-menu (dropdown)
function menu_validation(string $menu_slug): bool
{
    $menus = list_menus();

    return isset($menus[$menu_slug]) && $menus[$menu_slug]['dropdown'];
}

// Recherhce dans la liste si le slug existe et retourne true si oui
function banned_menu_slug(string $menu_slug): bool
{
    $banned_slugs = ['admin', 'search', 'login', 'logout', 'register', 'dashboard','assets', 'uploads', 'sitemap', 'robots'];
    return in_array($menu_slug, $banned_slugs, true);
}
