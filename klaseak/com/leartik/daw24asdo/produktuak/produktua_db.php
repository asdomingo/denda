<?php

namespace com\leartik\daw24asdo\produktuak;
use PDO;

class ProduktuaDB
{
    private static function getDbPath() {
        return "sqlite:C:\\xampp\\htdocs\\denda\\produktuak.db";
    }

    
    public static function selectProduktuak()
    {
        try {
            $db = new PDO(self::getDbPath());
            $erregistroak = $db->query("SELECT * FROM produktuak");
            $produktuak = array();

            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $p = new Produktua();
                $p->setId($erregistroa['id']);
                $p->setIzena($erregistroa['izena']);
                $p->setPrezioa($erregistroa['prezioa']);
                $p->setKategoriaId($erregistroa['kategoria_id']);
                $p->setIrudia($erregistroa['irudia']);
                
                if (isset($erregistroa['eskaintzak'])) {
                    $p->setEskaintzak($erregistroa['eskaintzak']);
                }
                if (isset($erregistroa['nobedadeak'])) {
                    $p->setNobedadeak($erregistroa['nobedadeak']);
                }
                $produktuak[] = $p;
            }
            return $produktuak;
        } catch (\Exception $e) {
            echo "<p>Salbuespena (SelectAll): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }

    
    public static function selectProduktuakEskaintzak()
    {
        try {
            $db = new PDO(self::getDbPath());
            // Usamos la columna real: eskaintza
            $erregistroak = $db->query("SELECT * FROM produktuak WHERE eskaintzak = 1");
            $produktuak = array();

            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $p = new Produktua();
                $p->setId($erregistroa['id']);
                $p->setIzena($erregistroa['izena']);
                $p->setPrezioa($erregistroa['prezioa']);
                $p->setIrudia($erregistroa['irudia']);
                $p->setEskaintzak($erregistroa['eskaintzak']);
                $produktuak[] = $p;
            }
            return $produktuak;
        } catch (\Exception $e) {
            echo "<p>Salbuespena (SelectEskaintzak): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }

    
    public static function selectProduktuakNobedadeak()
    {
        try {
            $db = new PDO(self::getDbPath());
            
            $erregistroak = $db->query("SELECT * FROM produktuak WHERE nobedadeak = 1");
            $produktuak = array();

            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $p = new Produktua();
                $p->setId($erregistroa['id']);
                $p->setIzena($erregistroa['izena']);
                $p->setPrezioa($erregistroa['prezioa']);
                $p->setIrudia($erregistroa['irudia']);
                $p->setNobedadeak($erregistroa['nobedadeak']);
                $produktuak[] = $p;
            }
            return $produktuak;
        } catch (\Exception $e) {
            echo "<p>Salbuespena (SelectNobedadeak): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }

    
    public static function selectProduktua($id)
    {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("SELECT * FROM produktuak WHERE id = ?");
            $stmt->execute([$id]);

            if ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $p = new Produktua();
                $p->setId($erregistroa['id']);
                $p->setIzena($erregistroa['izena']);
                $p->setPrezioa($erregistroa['prezioa']);
                $p->setIrudia($erregistroa['irudia']);
                $p->setEskaintzak($erregistroa['eskaintzak']);
                $p->setNobedadeak($erregistroa['nobedadeak']);
                return $p;
            }
            return null;
        } catch (\Exception $e) {
            echo "<p>Salbuespena (SelectOne): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }
}