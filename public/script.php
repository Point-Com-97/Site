<?php

require_once __DIR__ . '/../data/config/database.php';

try {
    $pdo = getConnexion();

} catch (PDOException $e) {
    error_log("Erreur de connexion PDO : " . $e->getMessage()); // Message d'erreur pour le dévellopeur
    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}

try {
    $resultat = $pdo->query("SELECT * FROM pages");

    while ($ligne = $resultat->fetch()) {
        echo $ligne['titre'];
    }

    $stmt = $pdo->prepare("INSERT INTO pages (titre, slug) VALUES (?, ?)");

    $stmt->execute(['Accueil', 'accueil']);
    $stmt->execute(['Contact', 'contact']);

} catch (PDOException $e) {
    error_log("Erreur de requête SQL : " . $e->getMessage()); // Message d'erreur pour le dévellopeur
    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteur
}