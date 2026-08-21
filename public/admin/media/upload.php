<?php

require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../data/config/database.php';
require_once __DIR__ . '/../../../src/php/Flash.php';

// Chemin du dossier cible
$target_dir = __DIR__ . "/../../uploads";

// Taille limite 10Mo
$size_max = 10 * 1024 * 1024;

try {
    $pdo = getConnexion();
} catch (PDOException $e) {
    error_log("Erreur de connexion PDO : " . $e->getMessage(), 3, __DIR__ . "/../../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur
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

                if (str_starts_with($file_mime_type, 'image/')) {
                    $file_type = "image";
                } else {
                    $file_type = "pdf";
                }

                try {
                    $stmt = $pdo->prepare("INSERT INTO medias (titre, type, url) VALUES (?,?,?)");
                    $stmt->execute([$name, $file_type, $url_file]);
                } catch (PDOException $e) {
                    error_log("Erreur de requête SQL : " . $e->getMessage(), 3, __DIR__ . "/../../../var/tmp/erreur.log"); // Message d'erreur pour le dévellopeur
                    die(" Une erreur est survenue, veuillez réessayer plus tard."); // Message d'erreur pour les visiteur
                }
                set_flash('Média ajouté avec succès.', 'success');
                header('Location: /admin/media/index.php');
                exit;
            } else {
                set_flash('Seuls les fichiers JPEG, PNG et PDF sont autorisés.', 'danger');
                header('Location: /admin/media/index.php');
                exit;
            }
        } else {
            set_flash('La taille de la pièce jointe dépasse la limite maximale de 10Mo', 'danger');
            header('Location: /admin/media/index.php');
            exit;
        }
    } else {
        set_flash('Une erreur est survenue', 'danger');
        header('Location: /admin/media/index.php');
        exit;
    }
}
