<?php
/**
 * Helper centralise pour les logs d'erreurs.
 */

declare(strict_types=1);

class Logger
{
    private static ?string $logFile = null;

    private static function getLogFile(): string
    {
        if (self::$logFile === null) {
            self::$logFile = dirname(__DIR__, 2) . '/var/tmp/erreur.log';
        }
        return self::$logFile;
    }

    public static function log(string $message, string $level = 'ERROR'): void
    {
        $logFile = self::getLogFile();
        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        $timestamp = date('Y-m-d H:i:s');
        $line = "[{$timestamp}] [{$level}] {$message}\n";
        @error_log($line, 3, $logFile);
    }

    public static function exception(Throwable $e, string $context = ''): void
    {
        $message = $context !== '' ? "{$context}: " : '';
        $message .= $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        self::log($message, 'EXCEPTION');
    }
}

if (!function_exists('log_error')) {
    function log_error(string $message): void
    {
        Logger::log($message, 'ERROR');
    }
}

if (!function_exists('log_exception')) {
    function log_exception(Throwable $e, string $context = ''): void
    {
        Logger::exception($e, $context);
    }
}
