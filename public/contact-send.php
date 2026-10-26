<?php
/**
 * Traitement du formulaire de contact.
 * Protections : CSRF, honeypot, délai minimum, rate limiting par session, validation des champs.
 */

declare(strict_types=1);

require_once __DIR__ . '/../src/config/session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/php/Csrf.php';
require_once __DIR__ . '/../src/php/Flash.php';
require_once __DIR__ . '/../src/php/helpers.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../data/config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;

function retour(string $msg, string $type): never
{
    set_flash($msg, $type);
    header('Location: /contact', true, 303);
    exit;
}

// Vérifier la méthode et le CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    retour('Requête invalide, veuillez réessayer.', 'danger');
}

// Honeypot : champ caché rempli par les robots
if (!empty($_POST['site_web'])) {
    // Robot probable : on répond "ok" sans rien envoyer
    retour('Merci, votre message a bien été envoyé.', 'success');
}

// Rate limiting : minimum 3 secondes entre l'affichage du formulaire et l'envoi
// Note: contact_time est défini dans contact-form.php à l'affichage du formulaire
$contactTime = (int) ($_SESSION['contact_time'] ?? 0);
if ($contactTime === 0 || (time() - $contactTime) < 3) {
    // Soumission trop rapide : robot probable
    retour('Merci, votre message a bien été envoyé.', 'success');
}

// Rate limiting : maximum 3 messages par heure par session
$now = time();
if (!isset($_SESSION['contact_messages'])) {
    $_SESSION['contact_messages'] = [];
}
// Nettoyer les anciennes entrées (> 1 heure)
$_SESSION['contact_messages'] = array_filter(
    $_SESSION['contact_messages'],
    fn($t) => ($now - $t) < 3600
);
if (count($_SESSION['contact_messages']) >= 3) {
    retour('Vous avez envoyé trop de messages. Veuillez réessayer plus tard.', 'danger');
}

// Validation des champs
$nom = trim((string) ($_POST['nom'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($nom === '' || mb_strlen($nom) > 100 || mb_strlen($message) < 5 || mb_strlen($message) > 3000
    || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    retour('Veuillez vérifier les champs du formulaire.', 'danger');
}

// Empêche l'injection d'en-têtes (retours à la ligne dans le nom ou l'email)
$nom = str_replace(["\r", "\n"], ' ', $nom);
$email = str_replace(["\r", "\n"], '', $email);

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAIL_USER;
    $mail->Password = MAIL_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = MAIL_PORT;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('contact@cfa-pcom.fr', 'Site Point Com');
    $mail->addAddress('contact@cfa-pcom.fr');
    $mail->addReplyTo($email, $nom);
    $mail->Subject = 'Contact site : ' . mb_substr($nom, 0, 100);
    $mail->Body = "Nom : {$nom}\nE-mail : {$email}\n\n{$message}";
    $mail->send();

    // Enregistrer le timestamp de l'envoi pour le rate limiting
    $_SESSION['contact_messages'][] = $now;
    unset($_SESSION['contact_time']);

    retour('Merci, votre message a bien été envoyé.', 'success');
} catch (Throwable $e) {
    error_log("Erreur mail : " . $e->getMessage() . "\n", 3, __DIR__ . "/../var/tmp/erreur.log");
    retour("Le message n'a pas pu être envoyé. Écrivez-nous à contact@cfa-pcom.fr.", 'danger');
}
