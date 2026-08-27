<?php require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../templates/admin/header.php';
require_once __DIR__ . '/../../templates/admin/item/menu.php';

try {

    require_once __DIR__ . '/../../../src/php/Menu.php';
    require_once __DIR__ . '/../../../src/php/Page.php'; 
    
    require_once __DIR__ . '/../../templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur

    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}
