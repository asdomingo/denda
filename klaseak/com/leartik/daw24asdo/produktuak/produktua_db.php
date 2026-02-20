<?php

namespace com\leartik\daw24asdo\produktuak;

use PDO;
use Exception;

// Asumo que la clase Produktua está en el mismo namespace o importada
class ProduktuaDB
{
    /**
     * Ruta centralizada de la base de datos
     */
    private static function getDbPath() {
        return "sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db";
    }

    /**
     * Obtiene todos los productos de la tabla
     */
    public static function selectProduktuak()
    {
        try {
            $db = new PDO(self::getDbPath());
            $erregistroak = $db->query("SELECT * FROM produktuak");
            $produktuak = array();

            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $produktuak[] = self::mapearProduktua($erregistroa);
            }
            return $produktuak;
        } catch (Exception $e) {
            echo "<p>Salbuespena (SelectAll): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }

    /**
     * Obtiene productos marcados como oferta
     */
    public static function selectProduktuakEskaintzak()
    {
        try {
            $db = new PDO(self::getDbPath());
            $erregistroak = $db->query("SELECT * FROM produktuak WHERE eskaintzak > 0");
            $produktuak = array();

            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $produktuak[] = self::mapearProduktua($erregistroa);
            }
            return $produktuak;
        } catch (Exception $e) {
            echo "<p>Salbuespena (SelectEskaintzak): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }

    /**
     * Obtiene productos marcados como novedad
     */
    public static function selectProduktuakNobedadeak()
    {
        try {
            $db = new PDO(self::getDbPath());
            $erregistroak = $db->query("SELECT * FROM produktuak WHERE nobedadeak = 1");
            $produktuak = array();

            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $produktuak[] = self::mapearProduktua($erregistroa);
            }
            return $produktuak;
        } catch (Exception $e) {
            echo "<p>Salbuespena (SelectNobedadeak): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }

    /**
     * Obtiene un único producto por su ID
     */
    public static function selectProduktua($id)
    {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("SELECT * FROM produktuak WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return self::mapearProduktua($erregistroa);
            }
            return null;
        } catch (Exception $e) {
            echo "<p>Salbuespena (SelectOne): " . $e->getMessage() . "</p>\n";
            return null;
        }
    }

    /**
     * Inserta un nuevo producto en la BD
     */
    public static function insertProduktua($p)
{
    try {
        $db = new PDO(self::getDbPath());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Especificamos las columnas explícitamente para evitar errores de orden
        $sql = "INSERT INTO produktuak (izena, prezioa, irudia, kategoria_id, eskaintzak, nobedadeak, deskontua) 
                VALUES (:izena, :prezioa, :irudia, :kategoria_id, :eskaintzak, :nobedadeak, :deskontua)";
        
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([
            ':izena'        => $p->getIzena(),
            ':prezioa'      => $p->getPrezioa(),
            ':irudia'       => $p->getIrudia() ?? 'default.png',
            ':kategoria_id' => (int)$p->getKategoriaId(),
            ':eskaintzak'   => (int)$p->getEskaintzak(), 
            ':nobedadeak'   => (int)$p->getNobedadeak(), 
            ':deskontua'    => (float)($p->getDeskontua() ?? 0.0)
        ]);
    } catch (Exception $e) {
        return false;
    }
}

    /**
     * Elimina un producto por ID
     */
    public static function deleteProduktua($id)
    {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("DELETE FROM produktuak WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            echo "Error en DB (Delete): " . $e->getMessage();
            return false;
        }
    }

    /**
     * Bilatu produktuak izenaren arabera (LIKE 'testua%')
     */
    public static function searchProduktuak($term)
    {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("SELECT * FROM produktuak WHERE izena LIKE ?");
            $stmt->execute([$term . '%']);
            $produktuak = array();

            while ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produktuak[] = self::mapearProduktua($erregistroa);
            }
            return $produktuak;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Mapea un array asociativo de la BD a un objeto Produktua
     */
    private static function mapearProduktua($erregistroa) {
        $p = new Produktua();
        $p->setId($erregistroa['id']);
        $p->setIzena($erregistroa['izena']);
        $p->setPrezioa($erregistroa['prezioa']);
        $p->setIrudia($erregistroa['irudia']);
        $p->setKategoriaId($erregistroa['kategoria_id'] ?? null);
        $p->setEskaintzak($erregistroa['eskaintzak'] ?? 0);
        $p->setNobedadeak($erregistroa['nobedadeak'] ?? 0);
        $p->setDeskontua($erregistroa['deskontua'] ?? 0);
        return $p;
    }
}