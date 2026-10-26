<?php
/**
 * Upload de média (image ou PDF) via formulaire classique.
 * Validation stricte : extension + MIME réel + taille.
 */

declare(strict_types=1);

require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../../data/config/database.php';
require_once __DIR__ . '/../../../src/php/Flash.php';
require_once __DIR__ . '/../../../src/php/Csrf.php';
require_once __DIR__ . '/../../../src/php/UploadSecurise.php';
require_once __DIR__ . '/../../../src/php/helpers.php';

// Vérification CSRF (POST uniquement, pas de GET)
if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('Requête invalide, veuillez réessayer.', 'danger');
    header('Location: /admin/media/media-index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/media/media-index.php');
    exit;
}

// Vérifier qu'un fichier a été envoyé
if (empty($_FILES['media'])) {
    set_flash('Aucun fichier reçu.', 'danger');
    header('Location: /admin/media/media-index.php');
    exit;
}

// Validation sécurisée du fichier
$validation = UploadSecurise::valider($_FILES['media']);

if (!$validation['success']) {
    set_flash($validation['message'], 'danger');
    header('Location: /admin/media/media-index.php');
    exit;
}

$extension = $validation['extension'];
$categorie = $validation['type'];
$nomOriginal = $validation['nom_original'];

// Chemin du dossier cible (uploads dans public)
$targetDir = __DIR__ . '/../../uploads';

// Vérifier que le dossier existe et est accessible en écriture
if (!is_dir($targetDir) || !is_writable($targetDir)) {
    error_log("Dossier uploads non accessible en écriture : {$targetDir}\n", 3, __DIR__ . "/../../../var/tmp/erreur.log");
    set_flash('Une erreur est survenue, veuillez réessayer plus tard.', 'danger');
    header('Location: /admin/media/media-index.php');
    exit;
}

// Génération d'un nom unique sécurisé (pas de uniqid() seul, utiliser random_bytes)
$newName = UploadSecurise::nomUnique($extension);
$targetFile = $targetDir . '/' . $newName;
$urlFile = "/uploads/{$newName}";

// Déplacer le fichier uploadé
if (!move_uploaded_file($_FILES['media']['tmp_name'], $targetFile)) {
    error_log("Échec du déplacement du fichier uploadé vers {$targetFile}\n", 3, __DIR__ . "/../../../var/tmp/erreur.log");
    set_flash('Une erreur est survenue lors de l\'enregistrement.', 'danger');
    header('Location: /admin/media/media-index.php');
    exit;
}

// Définir les permissions restrictives sur le fichier (lecture seule pour le web)
chmod($targetFile, 0644);

// Enregistrer en base de données
try {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO medias (titre, type, url) VALUES (?, ?, ?)");
    $stmt->execute([$nomOriginal, $categorie, $urlFile]);

    set_flash('Média ajouté avec succès.', 'success');
} catch (PDOException $e) {
    // En cas d'erreur BDD, supprimer le fichier uploadé
    @unlink($targetFile);
    error_log("Erreur BDD lors de l'upload : " . $e->getMessage() . "\n", 3, __DIR__ . "/../../../var/tmp/erreur.log");
    set_flash('Une erreur est survenue, veuillez réessayer plus tard.', 'danger');
}

header('Location: /admin/media/media-index.php');
exit;
