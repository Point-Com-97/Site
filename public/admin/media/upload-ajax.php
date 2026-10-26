<?php
/**
 * Upload de média via AJAX (retour JSON).
 * Validation stricte : extension + MIME réel + taille.
 */

declare(strict_types=1);

require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Csrf.php';
require_once __DIR__ . '/../../../src/php/UploadSecurise.php';

header('Content-Type: application/json; charset=utf-8');

// Vérification CSRF (POST uniquement)
if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['media'])) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu']);
    exit;
}

// Validation sécurisée du fichier
$validation = UploadSecurise::valider($_FILES['media']);

if (!$validation['success']) {
    echo json_encode(['success' => false, 'message' => $validation['message']]);
    exit;
}

$extension = $validation['extension'];
$nomOriginal = $validation['nom_original'];

// Génération d'un nom unique sécurisé
$newName = UploadSecurise::nomUnique($extension);
$targetDir = __DIR__ . '/../../uploads';
$targetFile = $targetDir . '/' . $newName;

// Vérifier que le dossier est accessible en écriture
if (!is_dir($targetDir) || !is_writable($targetDir)) {
    error_log("Dossier uploads non accessible : {$targetDir}\n", 3, __DIR__ . "/../../../var/tmp/erreur.log");
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
    exit;
}

if (!move_uploaded_file($_FILES['media']['tmp_name'], $targetFile)) {
    echo json_encode(['success' => false, 'message' => 'Échec de l\'enregistrement du fichier']);
    exit;
}

chmod($targetFile, 0644);

$urlFile = "/uploads/{$newName}";

// Enregistrer en base de données
require_once __DIR__ . '/../../../data/config/database.php';

try {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO medias (titre, type, url) VALUES (?, ?, ?)");
    $stmt->execute([$nomOriginal, 'image', $urlFile]);

    echo json_encode(['success' => true, 'url' => $urlFile]);
} catch (PDOException $e) {
    @unlink($targetFile);
    error_log("Erreur lors de l'insertion : " . $e->getMessage() . "\n", 3, __DIR__ . "/../../../var/tmp/erreur.log");
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
