<?php
// Formulaire minimal : pas de table, pas d'IP. Anti-spam = CSRF + honeypot + délai mini en session.
session_start();
require_once __DIR__ . '/../src/php/Csrf.php';
require_once __DIR__ . '/../src/php/Flash.php';        // set_flash() : adaptez au nom réel de votre fonction
require_once __DIR__ . '/../vendor/autoload.php';       // composer require phpmailer/phpmailer
require_once __DIR__ . '/../data/config/mail.php';      // définit MAIL_HOST, MAIL_USER, MAIL_PASS (hors dépôt git)

use PHPMailer\PHPMailer\PHPMailer;

function retour(string $msg, string $type): never
{
    set_flash($msg, $type);
    header('Location: /contact', true, 303);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifier_csrf_token($_POST['csrf_token'] ?? null)) {
    retour('Requête invalide, veuillez réessayer.', 'danger');
}

// Robot probable : on répond "ok" sans rien envoyer
if (!empty($_POST['site_web']) || time() - (int) ($_SESSION['contact_time'] ?? 0) < 3) {
    retour('Merci, votre message a bien été envoyé.', 'success');
}

$nom = trim((string) ($_POST['nom'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($nom === '' || mb_strlen($nom) > 100 || mb_strlen($message) < 5 || mb_strlen($message) > 3000
    || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    retour('Veuillez vérifier les champs du formulaire.', 'danger');
}

// Empêche l'injection d'en-têtes (retours à la ligne dans le nom)
$nom = str_replace(["\r", "\n"], ' ', $nom);

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAIL_USER;
    $mail->Password = MAIL_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('contact@cfa-pcom.fr', 'Site Point Com');   // expéditeur = notre domaine (SPF)
    $mail->addAddress('contact@cfa-pcom.fr');
    $mail->addReplyTo($email, $nom);                            // "Répondre" écrit au visiteur
    $mail->Subject = 'Contact site : ' . $nom;
    $mail->Body = "Nom : $nom\nE-mail : $email\n\n$message";
    $mail->send();

    unset($_SESSION['contact_time']);
    retour('Merci, votre message a bien été envoyé.', 'success');
} catch (Throwable $e) {
    error_log("Erreur mail : " . $e->getMessage() . "\n", 3, __DIR__ . "/../var/tmp/erreur.log");
    retour("Le message n'a pas pu être envoyé. Écrivez-nous à contact@cfa-pcom.fr.", 'danger');
}
