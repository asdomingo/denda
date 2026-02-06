<?php
namespace com\leartik\daw24asdo\logs;
use PDO;

class LogDB {
    private static function getDbPath() {
        return "sqlite:C:\\xampp\\htdocs\\erronkacss\\produktuak.db";
    }

    public static function insertLog($ekintza) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("INSERT INTO logs (ekintza) VALUES (?)");
            return $stmt->execute([$ekintza]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function selectLogs() {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->query("SELECT * FROM logs ORDER BY data DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
}
?>
