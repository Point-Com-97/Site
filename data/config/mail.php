<?php
/**
 * Configuration SMTP pour l'envoi d'emails.
 * Les credentials sont chargés depuis le fichier .env (hors versioning).
 */

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/config/env.php';

if (!defined('MAIL_HOST')) {
    define('MAIL_HOST', env('MAIL_HOST', 'smtp.mail.ovh.net'));
}
if (!defined('MAIL_USER')) {
    define('MAIL_USER', env('MAIL_USER', ''));
}
if (!defined('MAIL_PASS')) {
    define('MAIL_PASS', env('MAIL_PASS', ''));
}
if (!defined('MAIL_PORT')) {
    define('MAIL_PORT', (int) env('MAIL_PORT', 587));
}
