<?php
/**
 * Inicializador de Base de Datos para AWS/production
 * Crea las tablas necesarias si no existen
 */

// Detectar entorno
$isLocal = strpos(__DIR__, 'C:\\xampp') !== false || strpos(__DIR__, 'xampp') !== false;

if ($isLocal) {
    $dbPath = 'sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db';
} else {
    $dbPath = 'sqlite:' . dirname(__FILE__) . '/produktuak.db';
}

try {
    $pdo = new PDO($dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear tabla posts si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        izenburua TEXT NOT NULL,
        laburpena TEXT NOT NULL,
        xehetasunak TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Crear tabla kategoriak si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS kategoriak (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        izena TEXT UNIQUE NOT NULL,
        deskribapena TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Crear tabla produktuak si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS produktuak (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        izena TEXT NOT NULL,
        deskribapena TEXT,
        prezioa REAL NOT NULL,
        kategoria_id INTEGER,
        irudia BLOB,
        eskaintzapean INTEGER DEFAULT 0,
        nobedadea INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (kategoria_id) REFERENCES kategoriak(id)
    )");
    
    // Crear tabla eskariak si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS eskariak (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        izena TEXT NOT NULL,
        emaila TEXT NOT NULL,
        telefonoa TEXT,
        helbidea TEXT,
        herria TEXT,
        kodea TEXT,
        produktua_id INTEGER,
        kantitatea INTEGER DEFAULT 1,
        estatua TEXT DEFAULT 'pendiente',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (produktua_id) REFERENCES produktuak(id)
    )");
    
    // Crear tabla mezuak si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS mezuak (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        izena TEXT NOT NULL,
        emaila TEXT NOT NULL,
        gaia TEXT NOT NULL,
        edukia TEXT NOT NULL,
        irakurrita INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    echo "✓ Base de datos inicializada correctamente";
    
} catch (PDOException $e) {
    echo "✗ Error inicializando BD: " . $e->getMessage();
}
?>
