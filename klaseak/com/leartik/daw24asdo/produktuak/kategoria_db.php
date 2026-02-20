<?php

namespace com\leartik\daw24asdo\produktuak;
use PDO;

class KategoriaDB
{
    private static function getDbPath() {
        // Detectar si estamos en local o en AWS
        $isLocal = strpos(__FILE__, 'C:\\xampp') !== false || strpos(__FILE__, 'xampp') !== false;
        
        if ($isLocal) {
            return "sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db";
        } else {
            // Para AWS/Linux
            return "sqlite:" . dirname(__FILE__, 6) . DIRECTORY_SEPARATOR . 'produktuak.db';
        }
    }

    private static function ensureTable(PDO $db)
    {
        $sql = "CREATE TABLE IF NOT EXISTS kategoriak (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            izena TEXT NOT NULL,
            azalpena TEXT DEFAULT ''
        )";
        $db->exec($sql);
    }

    public static function selectKategorienak()
    {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->query("SELECT id, izena, azalpena FROM kategoriak ORDER BY id ASC");
            $out = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $out[(int)$row['id']] = [
                    'izena' => $row['izena'],
                    'azalpena' => $row['azalpena']
                ];
            }
            return $out;
        } catch (\Exception $e) {
            echo "<p>Salbuespena (Kategoria select): " . $e->getMessage() . "</p>\n";
            return [];
        }
    }

    /**
     * Comprueba si ya existe una categoría por nombre (case-insensitive)
     * @param string $izena
     * @return bool
     */
    public static function existsKategoriaByName($izena)
    {
        try {
            $db = new PDO('sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db');
            $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM kategoriak WHERE izena = ? COLLATE NOCASE");
            $stmt->execute([$izena]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($row && $row['cnt'] > 0);
        } catch (\Exception $e) {
            // En caso de error asumimos que no existe para no bloquear la creación por fallo de DB
            return false;
        }
    }
    public static function insertKategoria($izena, $azalpena = '')
    {
        try {
            $db = new PDO('sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db');
            self::ensureTable($db);
            $stmt = $db->prepare("INSERT INTO kategoriak (izena, azalpena) VALUES (?, ?)");
            $ok = $stmt->execute([$izena, $azalpena]);
            if ($ok) return (int)$db->lastInsertId();
            return 0;
        } catch (\Exception $e) {
            echo "<p>Salbuespena (Kategoria insert): " . $e->getMessage() . "</p>\n";
            return 0;
        }
    }

    public static function updateKategoria($id, $izena, $azalpena = '')
    {
        try {
            $db = new PDO('sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db');
            self::ensureTable($db);
            $stmt = $db->prepare("UPDATE kategoriak SET izena = ?, azalpena = ? WHERE id = ?");
            $ok = $stmt->execute([$izena, $azalpena, $id]);
            return $ok ? $stmt->rowCount() : 0;
        } catch (\Exception $e) {
            echo "<p>Salbuespena (Kategoria update): " . $e->getMessage() . "</p>\n";
            return 0;
        }
    }

    public static function deleteKategoria($id)
    {
        try {
            $db = new PDO('sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Borrado en cascada: eliminar productos asociados primero dentro de una transacción
            $db->beginTransaction();
            $stmt = $db->prepare("DELETE FROM produktuak WHERE kategoria_id = ?");
            $stmt->execute([$id]);
            // $deletedProducts = $stmt->rowCount(); // opcionalmente usar

            $stmt2 = $db->prepare("DELETE FROM kategoriak WHERE id = ?");
            $stmt2->execute([$id]);
            $deletedCategory = $stmt2->rowCount();

            $db->commit();
            return $deletedCategory;
        } catch (\Exception $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            echo "<p>Salbuespena (Kategoria delete): " . $e->getMessage() . "</p>\n";
            return 0;
        }
    }

    /**
     * Cuenta los productos asociados a una categoría
     * @param int $id
     * @return int
     */
    public static function countKategoriaProducts($id)
    {
        try {
            $db = new PDO('sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db');
            $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM produktuak WHERE kategoria_id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($row ? (int)$row['cnt'] : 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public static function isKategoriaInUse($id)
    {
        try {
            $db = new PDO('sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db');
            $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM produktuak WHERE kategoria_id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($row && $row['cnt'] > 0);
        } catch (\Exception $e) {
            echo "<p>Salbuespena (Kategoria in use): " . $e->getMessage() . "</p>\n";
            return true; // si error, evitar borrar
        }
    }
}

?>
