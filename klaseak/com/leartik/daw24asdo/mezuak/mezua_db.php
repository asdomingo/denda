<?php
namespace com\leartik\daw24asdo\mezuak;
use PDO;

class MezuaDB {
    private static function getDbPath() {
        return "sqlite:C:\\xampp\\htdocs\\erronkacss\\produktuak.db";
    }

    public static function selectMezuak() {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $erregistroak = $db->query("SELECT * FROM mezuak");
            $mezuak = array();
            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $mezua = new Mezua();
                $mezua->setId($erregistroa['id']);
                $mezua->setIzena($erregistroa['izena']);
                $mezua->setEmaila($erregistroa['emaila']);
                $mezua->setMezua($erregistroa['mezua']);
                $mezuak[] = $mezua;
            }
            return $mezuak;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function selectMezua($id) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("SELECT * FROM mezuak WHERE id = ?");
            $stmt->execute([$id]);
            if ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mezua = new Mezua();
                $mezua->setId($erregistroa['id']);
                $mezua->setIzena($erregistroa['izena']);
                $mezua->setEmaila($erregistroa['emaila']);
                $mezua->setMezua($erregistroa['mezua']);
                return $mezua;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function insertMezua($mezua) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("INSERT INTO mezuak (izena, emaila, mezua) VALUES (?, ?, ?)");
            return $stmt->execute([$mezua->getIzena(), $mezua->getEmaila(), $mezua->getMezua()]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function updateMezua($mezua) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("UPDATE mezuak SET izena = ?, emaila = ?, mezua = ? WHERE id = ?");
            return $stmt->execute([$mezua->getIzena(), $mezua->getEmaila(), $mezua->getMezua(), $mezua->getId()]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function deleteMezua($id) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("DELETE FROM mezuak WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
?>
