<?php
require_once 'klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
use com\leartik\daw24asdo\eskariak\EskariaDB;

$reflection = new ReflectionClass('com\leartik\daw24asdo\eskariak\EskariaDB');
$method = $reflection->getMethod('getDbPath');
$method->setAccessible(true);
$path = $method->invoke(null);

echo "Resolved Path: " . $path . PHP_EOL;

$cleanPath = str_replace('sqlite:', '', $path);
echo "File exists: " . (file_exists($cleanPath) ? 'YES' : 'NO') . PHP_EOL;
echo "File readable: " . (is_readable($cleanPath) ? 'YES' : 'NO') . PHP_EOL;
echo "File writable: " . (is_writable($cleanPath) ? 'YES' : 'NO') . PHP_EOL;

try {
    $tempFile = 'test_write.txt';
    if (file_put_contents($tempFile, 'test') !== false) {
        echo "Root folder write test: OK" . PHP_EOL;
        unlink($tempFile);
    } else {
        echo "Root folder write test: FAILED" . PHP_EOL;
    }

    $db = new PDO($path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "PDO Connection: OK" . PHP_EOL;
    
    // Try update
    echo "Testing update on ID 13..." . PHP_EOL;
    $stmt = $db->prepare("UPDATE eskariak SET estatua = 'Zain' WHERE id = 13");
    $stmt->execute();
    echo "Update executed. Rows affected: " . $stmt->rowCount() . PHP_EOL;
    
    // Try insert log
    echo "Testing log insert..." . PHP_EOL;
    $stmt = $db->prepare("INSERT INTO logs (ekintza) VALUES ('Debug test')");
    $stmt->execute();
    echo "Log insert OK." . PHP_EOL;

} catch (Exception $e) {
    echo "PDO Error: " . $e->getMessage() . PHP_EOL;
}
