<?php
namespace com\leartik\daw24asdo\eskariak;
use PDO;

class EskariaDB {
    private static function getDbPath() {
        return "sqlite:C:\\xampp\\htdocs\\erronkacss\\produktuak.db";
    }

    public static function selectEskariak() {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $erregistroak = $db->query("SELECT * FROM eskariak");
            $eskariak = array();
            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $eskaria = new Eskaria();
                $eskaria->setId($erregistroa['id']);
                $eskaria->setBezeroa($erregistroa['bezeroa']);
                $eskaria->setHelbidea($erregistroa['helbidea']);
                $eskaria->setProduktuaId($erregistroa['produktua_id']);
                $eskaria->setKantitatea($erregistroa['kantitatea']);
                $eskariak[] = $eskaria;
            }
            return $eskariak;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function selectEskaria($id) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("SELECT * FROM eskariak WHERE id = ?");
            $stmt->execute([$id]);
            if ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eskaria = new Eskaria();
                $eskaria->setId($erregistroa['id']);
                $eskaria->setBezeroa($erregistroa['bezeroa']);
                $eskaria->setHelbidea($erregistroa['helbidea']);
                $eskaria->setProduktuaId($erregistroa['produktua_id']);
                $eskaria->setKantitatea($erregistroa['kantitatea']);
                return $eskaria;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function insertEskaria($eskaria) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("INSERT INTO eskariak (bezeroa, helbidea, produktua_id, kantitatea) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$eskaria->getBezeroa(), $eskaria->getHelbidea(), $eskaria->getProduktuaId(), $eskaria->getKantitatea()]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function updateEskaria($eskaria) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("UPDATE eskariak SET bezeroa = ?, helbidea = ?, produktua_id = ?, kantitatea = ? WHERE id = ?");
            return $stmt->execute([$eskaria->getBezeroa(), $eskaria->getHelbidea(), $eskaria->getProduktuaId(), $eskaria->getKantitatea(), $eskaria->getId()]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function deleteEskaria($id) {
        try {
            $db = new PDO('sqlite:/var/www/html/produktuak.db');
            $stmt = $db->prepare("DELETE FROM eskariak WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
?>
