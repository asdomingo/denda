<?php
namespace com\leartik\daw24asdo\logs;
use PDO;

class LogDB {
    private static function getDbPath() {
        return "sqlite:" . dirname(__FILE__, 6) . DIRECTORY_SEPARATOR . 'produktuak.db';
    }

    private static function localLog($msg) {
        $logFile = dirname(__FILE__, 6) . DIRECTORY_SEPARATOR . 'debug.log';
        file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
    }

    public static function insertLog($ekintza) {
        try {
            self::localLog("Log: $ekintza");
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("INSERT INTO logs (ekintza) VALUES (?)");
            return $stmt->execute([$ekintza]);
        } catch (\Exception $e) {
            self::localLog("Log Error: " . $e->getMessage());
            return false;
        }
    }

    public static function selectLogs() {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->query("SELECT * FROM logs ORDER BY data DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
}
?>
