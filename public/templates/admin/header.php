<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/image/ico.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/scss/main.css">

    <title><?= htmlspecialchars($current_page['titre'] ?? 'Accueil') ?> - Point Com</title>
</head>


<body>
    <nav class="navbar sticky-top navbar-expand-lg bg-primary" id="navbar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
                aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarToggler">
                <a class="navbar-brand" href="/admin/dashboard.php">
                    <img src="/assets/image/logo.jpeg" alt="Logo" width="200" height="50" class="d-inline-block align-text-top">
                </a>
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                        <a class="nav-link" id="nav-item" aria-current="page" href="/accueil" target="_blank">Voir Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-item" aria-current="page" href="/admin/dashboard.php">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-item" href="/admin/media/index.php">Médiathèque</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 nav-underline">
                    <li class="nav-item">
                        <a class="btn btn-danger" id="nav-item" href="/admin/logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container"></div>
    <main>