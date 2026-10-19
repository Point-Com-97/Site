<?php
// Fonctions utilitaires partagées par le site public ET l'administration

// Échappe une valeur avant de l'afficher dans du HTML (protection XSS)
if (!function_exists('e')) {
    function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
