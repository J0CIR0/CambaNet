<?php
class Logger {
    private static $logFile = __DIR__ . '/../../logs/system.log';
    public static function log($message, $level = 'INFO') {
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        } 
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user = $_SESSION['user_email'] ?? 'guest';
        
        $logMessage = "[$timestamp] [$level] [$ip] [$user] $message" . PHP_EOL;
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
    public static function error($message) {
        self::log($message, 'ERROR');
    }
    public static function warning($message) {
        self::log($message, 'WARNING');
    }
    public static function info($message) {
        self::log($message, 'INFO');
    }
    public static function debug($message) {
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            self::log($message, 'DEBUG');
        }
    }
}
?>