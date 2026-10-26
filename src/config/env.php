<?php
/**
 * Chargeur de variables d'environnement depuis le fichier .env
 * Compatible OVH Cloud Web Eco (offre Perso) - pas de support natif des .env
 */

declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }
        return $default;
    }
}

if (!defined('ENV_LOADED')) {
    define('ENV_LOADED', true);
    $envFile = dirname(__DIR__, 2) . '/.env';
    if (is_file($envFile) && is_readable($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $value = trim($value, "\"'");
                $_ENV[$key] = $value;
                putenv("{$key}={$value}");
            }
        }
    }
}

if (!function_exists('is_prod')) {
    function is_prod(): bool
    {
        return env('APP_ENV', 'prod') === 'prod';
    }
}
