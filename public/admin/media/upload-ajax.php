<?php
require __DIR__ . '/../auth-check.php';
require_once __DIR__ . '/../../../src/php/Csrf.php';

header('Content-Type: application/json');

if (!verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['media'])) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu']);
    exit;
}

if ($_FILES['media']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi']);
    exit;
}

$taille_max = 10 * 1024 * 1024;
if ($_FILES['media']['size'] > $taille_max) {
    echo json_encode(['success' => false, 'message' => 'Fichier trop volumineux']);
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_reel = finfo_file($finfo, $_FILES['media']['tmp_name']);

$types_autorises = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($mime_reel, $types_autorises)) {
    echo json_encode(['success' => false, 'message' => 'Type de fichier non autorisé']);
    exit;
}

$name = basename($_FILES['media']['name']);
$extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
$new_name = uniqid() . '.' . $extension;
$target_dir = __DIR__ . '/../../uploads';
$target_file = $target_dir . '/' . $new_name;

if (!move_uploaded_file($_FILES['media']['tmp_name'], $target_file)) {
    echo json_encode(['success' => false, 'message' => 'Échec de l\'enregistrement du fichier']);
    exit;
}

$url_file = "/uploads/{$new_name}";

require_once __DIR__ . '/../../../data/config/database.php';
try {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO medias (titre, type, url) VALUES (?, ?, ?)");
    $stmt->execute([$name, 'image', $url_file]);

    echo json_encode(['success' => true, 'url' => $url_file]);
} catch (PDOException $e) {
    error_log("Erreur lors de l'insertion : " . $e->getMessage(), 3, __DIR__ . "/../../../var/tmp/erreur.log");
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}