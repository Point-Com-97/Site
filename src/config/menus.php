<?php

// Retourne la liste de menu
function list_menus(): array
{
    $menus = ['Centre de formation', 'Formation', 'Recrutement', 'Alternance', 'RHF Inclusion'];
    return $menus;
}

// Transforme le nom du menu en slug
function slug_menu(string $menu): string
{
    $slug = trim($menu);
    $slug = strtolower($slug);
    $slug = str_replace(" ", "_", $slug);
    return $slug;
}

// Retourne la liste des menus avec leurs slug
function list_of_menus(): array
{
    $menus = list_menus();

    $all_menus = [];

    foreach ($menus as $m) {
        $slug = slug_menu($m);

        $all_menus[] = [
            'titre' => $m,
            'slug' => $slug,
        ];
    };
    return $all_menus;
}

// Recherche le menu dans le tableau et retourne true si trouvé
function menu_validation(string $menu_slug): bool
{
    $menus = list_menus();
    $all_slug = [];

    foreach ($menus as $m) {
        $slug = slug_menu($m);
        $all_slug[] = $slug;
    }

    return in_array($menu_slug, $all_slug, true);
}

// Recherhce dans la liste si le slug existe et retourne true si oui
function banned_menu_slug(string $menu_slug): bool
{
    $banned_slugs = ['admin', 'search', 'login', 'logout', 'register', 'dashboard'];
    return in_array($menu_slug, $banned_slugs, true);
}
