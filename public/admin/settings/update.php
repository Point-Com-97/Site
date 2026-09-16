<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Settings.php';

header('Content-Type: application/json');

$donnees = [
    'couleur_primaire' => $_POST['couleur_primaire'] ?? '',
    'couleur_secondaire' => $_POST['couleur_secondaire'] ?? '',
    'couleur_tertiaire' => $_POST['couleur_tertiaire'] ?? '',
    'police_corps' => $_POST['police_corps'] ?? '',
    'couleur_texte_bouton' => $_POST['couleur_texte_bouton'] ?? '',
    'couleur_lien' => $_POST['couleur_lien'] ?? '',
    'css_personnalise' => $_POST['css_personnalise'] ?? '',
];

$settings = new Settings();
$resultat = $settings->update($donnees);

echo json_encode(['success' => $resultat]);