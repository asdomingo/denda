<?php
/**
 * Diagnóstico y corrección para AWS
 * Ejecutar en: https://tusitio.com/diagnostico.php
 */

echo "<h1>Diagnóstico del Sistema</h1>";
echo "<hr>";

// 1. Versión PHP
echo "<h2>1. PHP Info</h2>";
echo "Version: " . phpversion() . "<br>";
echo "SAPI: " . php_sapi_name() . "<br>";

// 2. Extensiones necesarias
echo "<h2>2. Extensiones Requeridas</h2>";
$extensiones = ['pdo', 'pdo_sqlite', 'json', 'session'];
foreach ($extensiones as $ext) {
    $status = extension_loaded($ext) ? '✓' : '✗';
    echo "$status $ext<br>";
}

// 3. Estructura de directorios
echo "<h2>3. Sistema de Ficheros</h2>";
$dirs = [
    'Root Dir' => __DIR__,
    'DB File' => __DIR__ . '/produktuak.db',
    'Logs Dir' => __DIR__ . '/logs',
    'API Dir' => __DIR__ . '/api',
    'Clases Dir' => __DIR__ . '/klaseak',
];

foreach ($dirs as $name => $path) {
    if (file_exists($path)) {
        $type = is_dir($path) ? 'DIR' : 'FILE';
        $readable = is_readable($path) ? 'R' : '-';
        $writable = is_writable($path) ? 'W' : '-';
        echo "✓ $name [$readable$writable] $path<br>";
    } else {
        echo "✗ $name - NOT FOUND: $path<br>";
    }
}

// 4. Base de datos
echo "<h2>4. Base de Datos</h2>";
try {
    $dbPath = 'sqlite:' . __DIR__ . '/produktuak.db';
    $pdo = new PDO($dbPath);
    echo "✓ Conexión a BD exitosa<br>";
    
    // Verificar tablas
    $tables = ['posts', 'kategoriak', 'produktuak', 'eskariak', 'mezuak'];
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $existingTables = array_column($stmt->fetchAll(), 'name');
    
    foreach ($tables as $table) {
        $status = in_array($table, $existingTables) ? '✓' : '✗';
        echo "$status Tabla: $table<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error BD: " . $e->getMessage() . "<br>";
}

// 5. Sesiones
echo "<h2>5. Sesiones PHP</h2>";
echo "Session save path: " . ini_get('session.save_path') . "<br>";
echo "Session status: " . session_status() . "<br>";

echo "<hr>";
echo "<button onclick='location.href=\"init_database.php\"'>Inicializar Base de Datos</button>";
?>
