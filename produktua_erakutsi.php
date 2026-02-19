<?php
// 1. Klaseak eta fitxategiak kargatu
require_once __DIR__ . '/klaseak/com/leartik/daw24asdo/produktuak/produktua.php';
require_once __DIR__ . '/klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once __DIR__ . '/klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';

// 2. Namespace-ak erabili
use com\leartik\daw24asdo\produktuak\Produktua;
use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\produktuak\KategoriaDB;

$produktua = null;
$kategoriaren_izena = "";
$kategoriaren_azalpena = "";

// 3. Logika: IDa jaso eta produktua bilatu
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $produktua = ProduktuaDB::selectProduktua($id);

    if ($produktua) {
        // GARRANTZITSUA: Lehenik IDa lortu behar duzu objektutik
        $kategoria_id = $produktua->getKategoriaId();
        
        // Gero datu-baseari deitu kategoria horren datuak lortzeko
        

            }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktua Ikusi</title>
    <link rel="stylesheet" href="css/produktua.css">
</head>
<body>
    <header class="header produktuak">
        <img src="img/logo.png" alt="Logo" class="logo">
        <h1>Produktuen Xehetasunak</h1>
    </header>

    <div class="nav">
        <a href="index.php">Hasiera</a> &gt; <span>Produktua</span>
    </div>

    <main>
        <?php if ($produktua !== null): ?>
            <div class="produktua-detalle">
                <?php 
                $id = $produktua->getId();
                $ruta_imagen = 'img/' . $id . '.jpg';
                // Irudia ez bada existitzen, ordezko bat jarri
                if (!file_exists($ruta_imagen)) { $ruta_imagen = 'img/no-image.png'; }
                ?>
                
                <img src="<?= htmlspecialchars($ruta_imagen) ?>" alt="<?= htmlspecialchars($produktua->getIzena()) ?>" style="max-width:350px;">
                
                <div class="produktua-data">
                    <h2><?= htmlspecialchars($produktua->getIzena()) ?></h2>
                    <p class="prezioa">Prezioa: <strong>
                        <?php if ($produktua->getDeskontua() > 0): ?>
                            <span style="text-decoration: line-through;"><?= number_format($produktua->getPrezioa(), 2, ',', '.') ?>€</span> <span style="color: #e74c3c; font-size: 1.2em;"><?= number_format($produktua->getPrezioaDeskontuarekin(), 2, ',', '.') ?>€</span> <span style="color: #27ae60; font-size: 0.9em;">(-<?= round($produktua->getDeskontua()) ?>%)</span>
                        <?php else: ?>
                            <?= number_format($produktua->getPrezioa(), 2, ',', '.') ?> €
                        <?php endif; ?>
                    </strong></p>
                                   
                    
                    <div class="ekintzak" style="margin-top: 20px;">
                        <a href="katalogoa.php" class="atzera-lotura">← Itzuli</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="errorea">
                <p>⚠️ Ez da produkturik aurkitu ID horrekin.</p>
                <p><a href="index.php">Itzuli</a></p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>