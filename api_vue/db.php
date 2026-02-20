<?php

use PDO;
use PDOException;

// Detectar si estamos en local o en AWS
$isLocal = strpos(__DIR__, 'C:\\xampp') !== false || strpos(__DIR__, 'xampp') !== false;

if ($isLocal) {
    // Ruta local para Windows/XAMPP
    $dbPath = 'sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db';
} else {
    // Ruta para servidor Linux/AWS
    $dbPath = 'sqlite:' . __DIR__ . '/../produktuak.db';
}

try {
    $pdo = new PDO($dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errorea DB konexioan: " . $e->getMessage());
}
