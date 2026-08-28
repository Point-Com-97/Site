<?php require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../templates/admin/header.php';
require_once __DIR__ . '/../../templates/admin/item/menu.php';
require_once __DIR__ . '/../../templates/admin/item/bloc.php';

try {
    require_once __DIR__ . '/../../../src/php/Page.php';
    require_once __DIR__ . '/../../../src/php/Bloc.php';

    $id = $_GET['id'];

    $page = new Page();
    $bloc = new Bloc();

    $page_info = $page->getById($id);
    $bloc_info = $bloc->getByPageId($id);

    echo "<h1>" . htmlspecialchars($page_info['titre']) . "</h1>";
    echo "<p>Slug : " . htmlspecialchars($page_info['slug']) . "</p>";
    echo "<p>Menu : " . htmlspecialchars($page_info['menu_id']) . "</p>";
    echo "<p>Ordre: " . htmlspecialchars($page_info['ordre']) . "</p>";
    echo "<p>Visible : " . htmlspecialchars($page_info['visible']) . "</p>";

    foreach ($bloc_info as $b) {
        echo render_bloc($b);
    }




    require_once __DIR__ . '/../../templates/footer.php';
} catch (PDOException $e) {

    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur

    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}
