<?php

require_once __DIR__ . '/../../admin/auth/Admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_login = new Admin();

    $login = $new_login->getByUsername($_POST["user_id"]);

    try {
        if (!$login) {
            echo '<div class="alert alert-danger" role="alert">identifiant ou mot de passe invalide</div>';
        } else {
            // Vérification du mot de passe en utilisant password_verify
            if (password_verify($_POST["user_password"], $login['password'])) {

                // Démarrage de la session avec une durée de vie de 2 heures (7200 secondes)
                session_start([
                    'cookie_lifetime' => 7200,
                ]);

                // Stockage de l'ID de l'administrateur dans la session
                $_SESSION['admin_id'] = $login['id'];

                header('Location: /admin/dashboard.php');
            } else {
                echo '<div class="alert alert-danger" role="alert">Identifiant ou mot de passe invalide</div>';
            }
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/image/ico.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/scss/main.css">
    <title>Connexion</title>
</head>

<body>
    <form class="form_login container-fluid d-grid gap-2 col-6 mx-auto" method="post" action="login.php">

        <h1>Connexion</h1>

        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <div class="form-floating">
                <input name="user_id" type="text" class="form-control" id="user_id" placeholder="Identifiant" required>
                <label for="floatingInputGroup1">Identifiant*</label>
            </div>
            <div class="invalid-feedback">
                Identifiant requis
            </div>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
            <div class="form-floating">
                <input name="user_password" type="password" class="form-control" id="user_password" placeholder="Mot de passe" required>

                <label for="InputPassword">Mot de passe*</label>
            </div>
            <div class="invalid-feedback">
                Mot de passe requis
            </div>
        </div>



        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
</body>

</html>