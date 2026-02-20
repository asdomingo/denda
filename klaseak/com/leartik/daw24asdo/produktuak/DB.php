<?php

namespace com\leartik\daw24asdo;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $conn = null;

    private static function getDbPath(): string
    {
        // Detectar si estamos en local o en AWS
        $isLocal = strpos(__DIR__, 'C:\\xampp') !== false || strpos(__DIR__, 'xampp') !== false || strpos(__DIR__, 'localhost') !== false;
        
        if ($isLocal) {
            return 'sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db';
        } else {
            // Ruta para servidor Linux/AWS
            return 'sqlite:' . dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/produktuak.db';
        }
    }

    public static function getConnection(): PDO
    {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(self::getDbPath());
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Errorea DB konexioan: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}