<?php
namespace com\leartik\daw24asdo\mezuak;
use PDO;

class MezuaDB {
    private static function getDbPath() {
        // Detectar si estamos en local o en AWS
        $isLocal = strpos(__FILE__, 'C:\\xampp') !== false || strpos(__FILE__, 'xampp') !== false;
        
        if ($isLocal) {
            return "sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db";
        } else {
            // Para AWS/Linux - asume que la BD está en la raíz del proyecto
            return "sqlite:" . dirname(__FILE__, 6) . DIRECTORY_SEPARATOR . 'produktuak.db';
        }
    }

    private static function localLog($msg) {
        $logFile = dirname(__FILE__, 6) . DIRECTORY_SEPARATOR . 'debug.log';
        file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
    }

    public static function selectMezuak() {
        try {
            $db = new PDO(self::getDbPath());
            $erregistroak = $db->query("SELECT * FROM mezuak ORDER BY data DESC, id DESC");
            $mezuak = array();
            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $mezuak[] = self::mapearMezua($erregistroa);
            }
            return $mezuak;
        } catch (\Exception $e) {
            self::localLog("selectMezuak Error: " . $e->getMessage());
            return null;
        }
    }

    public static function selectMezua($id) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("SELECT * FROM mezuak WHERE id = ?");
            $stmt->execute([$id]);
            if ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return self::mapearMezua($erregistroa);
            }
            return null;
        } catch (\Exception $e) {
            self::localLog("selectMezua Error: " . $e->getMessage());
            return null;
        }
    }

    public static function insertMezua($mezua) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("INSERT INTO mezuak (izena, emaila, mezua, data) VALUES (?, ?, ?, ?)");
            return $stmt->execute([
                $mezua->getIzena(), 
                $mezua->getEmaila(), 
                $mezua->getMezua(),
                date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            self::localLog("insertMezua Error: " . $e->getMessage());
            return false;
        }
    }

    public static function updateMezua($mezua) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("UPDATE mezuak SET izena = ?, emaila = ?, mezua = ?, estatua = ? WHERE id = ?");
            return $stmt->execute([
                $mezua->getIzena(), 
                $mezua->getEmaila(), 
                $mezua->getMezua(), 
                $mezua->getEstatua(),
                $mezua->getId()
            ]);
        } catch (\Exception $e) {
            self::localLog("updateMezua Error: " . $e->getMessage());
            return false;
        }
    }

    public static function updateEstatua($id, $estatua) {
        try {
            self::localLog("Trying to update message status for ID: $id to $estatua");
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("UPDATE mezuak SET estatua = ? WHERE id = ?");
            $success = $stmt->execute([$estatua, $id]);
            self::localLog("Update result: " . ($success ? "Success" : "Failed"));
            return $success;
        } catch (\Exception $e) {
            self::localLog("updateEstatua (Mezua) Error: " . $e->getMessage());
            return false;
        }
    }

    public static function deleteMezua($id) {
        try {
            self::localLog("Trying to delete message ID: $id");
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("DELETE FROM mezuak WHERE id = ?");
            $success = $stmt->execute([$id]);
            self::localLog("Delete result: " . ($success ? "Success" : "Failed"));
            return $success;
        } catch (\Exception $e) {
            self::localLog("deleteMezua Error: " . $e->getMessage());
            return false;
        }
    }

    private static function mapearMezua($erregistroa) {
        $mezua = new Mezua();
        $mezua->setId($erregistroa['id']);
        $mezua->setIzena($erregistroa['izena']);
        $mezua->setEmaila($erregistroa['emaila']);
        $mezua->setMezua($erregistroa['mezua']);
        $mezua->setEstatua($erregistroa['estatua'] ?? 'Berria');
        $mezua->setData($erregistroa['data'] ?? null);
        return $mezua;
    }
}
?>
