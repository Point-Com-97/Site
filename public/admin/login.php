<?php

require_once __DIR__ . '/../../admin/auth/Admin.php';
require_once __DIR__ . '/../..//src/php/Csrf.php';

// Démarrage de la session avec une durée de vie de 2 heures (7200 secondes)
session_start(['cookie_lifetime' => 7200]);

// Vérifier que la variable existe
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

$max_attempts = 5; // tentatives
$delay_pause = 30; // secondes

$lock = false;

if (!empty($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= $max_attempts) {
    $elapsed_time = time() - $_SESSION['last_attempt'];
    if ($elapsed_time < $delay_pause) {
        $time_remaining = $delay_pause - $elapsed_time;
        $lock = true;
    } else {
        $_SESSION['failed_attempts'] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$lock) {

    if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
        $message_erreur = "Requête invalide, veuillez réessayer.";
    } else {
        $new_login = new Admin();

        $login = $new_login->getByUsername($_POST["user_id"]);

        try {
            if (!$login || !password_verify($_POST["user_password"], $login['password'] ?? '')) {
                $message_erreur = "Identifiant ou mot de passe invalide";
                $_SESSION['failed_attempts']++;
                $_SESSION['last_attempt'] = time();
            } else {
                $_SESSION['admin_id'] = $login['id'];
                unset($_SESSION['failed_attempts']);
                header('Location: /admin/dashboard.php');
                exit;
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
            exit;
        }
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
    <link rel="stylesheet" href="/assets/scss/main.css">
    <title>Connexion</title>
</head>

<body>

    <?php if ($lock): ?>
        <div class="alert alert-warning container-fluid col-6 mx-auto mt-3" role="alert">
            Trop de tentatives échouées. Réessayez dans <?= $time_remaining ?> secondes.
        </div>
    <?php else: ?>
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
            <input type="hidden" name="csrf_token" value="<?= generer_csrf_token() ?>">
        </form>
    <?php endif; ?>

</body>

</html>