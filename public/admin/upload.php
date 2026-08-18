<?php

require __DIR__ . '/auth-check.php';
require_once __DIR__ . '/../../data/config/database.php';

// Chemin du dossier cible
$target_dir = __DIR__ . "/../uploads";

// Taille limite 10Mo
$size_max = 10 * 1024 * 1024;

try {
    $pdo = getConnexion();
} catch (PDOException $e) {
    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur
    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteurs
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_FILES['media']['error'] == UPLOAD_ERR_OK) {

        // Nom fichier temporaire
        $target_file = $_FILES['media']['tmp_name'];

        // Nom complet l'originel du fichier
        $name = basename($_FILES['media']['name']);

        // Génération d'un nom unique
        $new_name = uniqid();

        //Récupération de l'extension du fichier
        $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        //Ouverture d'un flux pour obtenir le type MIME du fichier
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        // URL absolue du fichier
        $url_file = "/uploads/$new_name.$file_ext";

        // Récupération du type MIME du fichier
        $file_mime_type = finfo_file($finfo, $target_file);

        if ($_FILES['media']['size'] < $size_max) {

            if ($file_mime_type === "image/jpeg" || $file_mime_type === "image/png" || $file_mime_type === "image/webp" || $file_mime_type === "application/pdf") {

                move_uploaded_file($target_file, "$target_dir/$new_name.$file_ext");

                if(str_starts_with($file_mime_type, 'image/')) {
                    $file_type = "image";
                } else {
                    $file_type = "pdf";
                }

                try {
                    $stmt = $pdo->prepare("INSERT INTO medias (titre, type, url) VALUES (?,?,?)");
                    $stmt->execute([$name, $file_type, $url_file]);
                } catch (PDOException $e) {
                    error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur
                    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteur
                }

                echo '<div class="alert alert-success" role="alert">Envoie réussi</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Seuls les fichiers JPEG, PNG et PDF sont autorisés.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">La taille de la pièce jointe dépasse la limite maximale de 10Mo</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Une erreur est survenue</div>';
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
</head>

<body>
    <form class="form_login container-fluid d-grid gap-2 col-6 mx-auto" method="post" action="upload.php" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="media_id" class="form-label">Ajouter une image ou un fichier</label>
            <input class="form-control form-control-lg" type="file" id="media_id" name="media">
        </div>

        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</body>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

</html>