<?php
/**
 * Script de Inicialización de Base de Datos para AWS
 */

$isLocal = strpos(__DIR__, 'C:\\xampp') !== false || strpos(__DIR__, 'xampp') !== false;
$dbPath = 'sqlite:' . __DIR__ . '/produktuak.db';

echo "<h1>Inicializador de Base de Datos</h1>";
echo "Ruta: $dbPath<br><br>";

try {
    $pdo = new PDO($dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Conexión establecida<br><br>";
    
    // Tablas a crear
    $tables = [
        'posts' => "CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            izenburua TEXT NOT NULL,
            laburpena TEXT NOT NULL,
            xehetasunak TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        'kategoriak' => "CREATE TABLE IF NOT EXISTS kategoriak (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            izena TEXT UNIQUE NOT NULL,
            deskribapena TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        'produktuak' => "CREATE TABLE IF NOT EXISTS produktuak (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            izena TEXT NOT NULL,
            deskribapena TEXT,
            prezioa REAL NOT NULL,
            kategoria_id INTEGER,
            irudia BLOB,
            deskontua INTEGER DEFAULT 0,
            nobedadea INTEGER DEFAULT 0,
            eskaintza INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (kategoria_id) REFERENCES kategoriak(id)
        )",
        
        'eskariak' => "CREATE TABLE IF NOT EXISTS eskariak (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            izena TEXT NOT NULL,
            emaila TEXT NOT NULL,
            telefonoa TEXT,
            helbidea TEXT,
            herria TEXT,
            kodea TEXT,
            produktua_id INTEGER,
            kantitatea INTEGER DEFAULT 1,
            prezioa REAL,
            estatua TEXT DEFAULT 'pendiente',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (produktua_id) REFERENCES produktuak(id)
        )",
        
        'mezuak' => "CREATE TABLE IF NOT EXISTS mezuak (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            izena TEXT NOT NULL,
            emaila TEXT NOT NULL,
            gaia TEXT,
            mezua TEXT NOT NULL,
            irakurrita INTEGER DEFAULT 0,
            data DATETIME DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    // Crear tablas
    foreach ($tables as $tableId => $sql) {
        try {
            $pdo->exec($sql);
            echo "✓ Tabla '$tableId' creada/verificada<br>";
        } catch (Exception $e) {
            echo "⚠ Tabla '$tableId': " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<br><h2>Datos de ejemplo (opcional)</h2>";
    
    // Insertar categoría de ejemplo si no existe
    $checkStmt = $pdo->prepare("SELECT COUNT(*) as c FROM kategoriak WHERE izena = ?");
    $checkStmt->execute(['Boxeo']);
    if ($checkStmt->fetch()['c'] == 0) {
        $stmt = $pdo->prepare("INSERT INTO kategoriak (izena, deskribapena) VALUES (?, ?)");
        $stmt->execute(['Boxeo', 'Produktuak boxearako']);
        echo "✓ Categoría 'Boxeo' creada<br>";
    }
    
    // Insertar post de ejemplo si no existe
    $checkStmt = $pdo->prepare("SELECT COUNT(*) as c FROM posts WHERE izenburua = ?");
    $checkStmt->execute(['Bienvenida']);
    if ($checkStmt->fetch()['c'] == 0) {
        $stmt = $pdo->prepare("INSERT INTO posts (izenburua, laburpena, xehetasunak) VALUES (?, ?, ?)");
        $stmt->execute(['Bienvenida', 'Bienvenido a K.O. Boxing', 'Sistema iniciado correctamente']);
        echo "✓ Post de bienvenida creado<br>";
    }
    
    echo "<br><hr>";
    echo "<button onclick='location.href=\"index.php\"'>Ir al inicio</button>";
    
} catch (PDOException $e) {
    echo "<div style='color:red'><h2>✗ Error</h2>";
    echo "No se pudo conectar/crear la BD: " . $e->getMessage();
    echo "<br>Ruta intentada: $dbPath";
    echo "<br>Directorio actual: " . __DIR__;
    echo "</div>";
}
?>
