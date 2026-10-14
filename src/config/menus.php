<?php

// Retourne la liste de menu
function list_menus(): array
{
    $menus = [
        'centre' => ['label' => 'Centre de formation', 'icone' => 'bi-building', 'page' => 'centre-de-formation', 'dropdown' => true],
        'formation' => ['label' => 'Formation', 'icone' => 'bi-book', 'page' => 'formation', 'dropdown' => true],
        'recrutement' => ['label' => 'Recrutement', 'icone' => 'bi-person-fill', 'page' => 'recrutement', 'dropdown' => true],
        'alternance' => ['label' => 'Alternance', 'icone' => 'bi-people', 'page' => 'alternance', 'dropdown' => true],
        'rhf-inclusion' => ['label' => 'RHF Inclusion', 'icone' => 'bi-inclusive', 'page' => 'rhf-inclusion', 'dropdown' => true],
        'stat' => ['label' => 'Statistiques', 'icone' => 'bi-graph-up', 'page' => 'statistiques', 'dropdown' => true],
        'bilan' => ['label' => 'Bilan de compétences', 'icone' => 'bi-bar-chart', 'page' => 'bilan-de-competences', 'dropdown' => false],
        'contact' => ['label' => 'Contact', 'icone' => 'bi-envelope', 'page' => 'contact', 'dropdown' => false],
        'portail' => ['label' => 'Portail', 'icone' => 'bi-box-arrow-in-right', 'url' => 'https://…', 'dropdown' => false],
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
    $banned_slugs = ['admin', 'search', 'login', 'logout', 'register', 'dashboard'];
    return in_array($menu_slug, $banned_slugs, true);
}
