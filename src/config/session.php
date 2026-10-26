<?php
/**
 * Configuration securisee des sessions PHP.
 */

declare(strict_types=1);

require_once __DIR__ . '/env.php';

$sessionLifetime = 7200;
$isSecure = is_prod() || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => $sessionLifetime,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('SITESESSID');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', $isSecure ? '1' : '0');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', (string) $sessionLifetime);
    ini_set('session.sid_length', '48');
    ini_set('session.sid_bits_per_character', '6');
}

if (!function_exists('session_regenerer')) {
    function session_regenerer(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
