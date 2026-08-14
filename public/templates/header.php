<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/image/ico.png">
    <script src="/assets/vendor/chart.js/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/scss/main.css">

    <title><?= htmlspecialchars($current_page['titre'] ?? 'Accueil') ?> - Point Com</title>
</head>


<body>
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/assets/image/logo.jpeg" alt="Logo" width="200" height="50" class="d-inline-block align-text-top">
            </a>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <?php if (!empty($all_menu) && is_array($all_menu)): ?>
                        <?php foreach ($all_menu as $item): ?>
                            <a class="nav-link" href="<?= htmlspecialchars($item['page_slug']) ?>"><?= htmlspecialchars($item['menu_titre']) ?></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <main>