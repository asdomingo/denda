<?php
session_start();

require('klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php');
require('klaseak/com/leartik/daw24asdo/produktuak/produktua.php');

use com\leartik\daw24asdo\produktuak\ProduktuaDB as PDB;


$ofertas = PDB::selectProduktuakEskaintzak() ?? [];
$novedades = PDB::selectProduktuakNobedadeak() ?? [];
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Denda - Hasiera</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="header"><img src="https://asdo-s3.s3.eu-north-1.amazonaws.com/logo.png" alt="Denda Logo" class="logo"></div>
    <div class="container">
        <div class="nav">
            <a href="index.php">Hasiera</a>
            <a href="katalogoa.php">Katalogoa</a>
            <a href="mediateka.php">Mediateka</a>
        </div>

        <div class="section">
            <h2>Eskaintzak (Ofertas)</h2>
            <div class="produktuak-grid">
                <?php if (count($ofertas) > 0): ?>
                    <?php foreach ($ofertas as $p): ?>
                        <div class="produktua-card">
                            <?php $img = $p->getIrudia() ? 'img/'.htmlspecialchars($p->getIrudia()) : 'img/'.htmlspecialchars($p->getId()).'.jpg'; ?>
                            <img src="<?php echo $img; ?>" class="produktua-img" alt="imagen">
                            <div class="produktua-info">
                                <h3><?php echo htmlspecialchars($p->getIzena()); ?></h3>
                                <div class="prezioa"><?php echo $p->getPrezioa(); ?> €</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ez dago eskaintzarik momentu honetan.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2>Nobedadeak (Novedades)</h2>
            <div class="produktuak-grid">
                <?php if (count($novedades) > 0): ?>
                    <?php foreach ($novedades as $p): ?>
                        <div class="produktua-card">
                            <?php $img = $p->getIrudia() ? 'img/'.htmlspecialchars($p->getIrudia()) : 'img/'.htmlspecialchars($p->getId()).'.jpg'; ?>
                            <img src="<?php echo $img; ?>" class="produktua-img" alt="imagen">
                            <div class="produktua-info">
                                <h3><?php echo htmlspecialchars($p->getIzena()); ?></h3>
                                <div class="prezioa"><?php echo $p->getPrezioa(); ?> €</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ez dago nobedaderik momentu honetan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>