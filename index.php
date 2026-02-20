<?php
session_start();

require('klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php');
require('klaseak/com/leartik/daw24asdo/produktuak/produktua.php');

use com\leartik\daw24asdo\produktuak\ProduktuaDB as PDB;


$ofertas = PDB::selectProduktuakEskaintzak() ?? [];
$novedades = PDB::selectProduktuakNobedadeak() ?? [];

// Inicializar carrito si no existe
if (!isset($_SESSION['karratua'])) {
    $_SESSION['karratua'] = [];
}
$carritoCount = array_sum($_SESSION['karratua']);
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Denda - Hasiera</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/carrito.css">
</head>
<body>
    <div class="header logo"><img src="https://asdo-s3.s3.eu-north-1.amazonaws.com/logo.png" alt="Denda Logo" class="logo"></div>
    <div class="container">
        <div class="nav">
            <a href="katalogoa.php">Katalogoa</a>
            <a href="mediateka.php">Mediateka</a>
            <a href="kontaktua.php">Kontaktua</a>
            <a href="albisteak.php">Albisteak</a>            
        </div>

        <?php if ($carritoCount > 0): ?>
            <a href="eskaria.php" class="carrito-icon" id="cart-link">
                <img src="img/carrito.png" alt="Carrito">
                <span id="carrito-contador" style="display: block;"><?php echo $carritoCount; ?></span>
            </a>
        <?php else: ?>
            <a href="javascript:void(0);" class="carrito-icon" id="cart-link" onclick="alert('Saskia hutsik dago. Produktuak gehitu katalogotik.');">
                <img src="img/carrito.png" alt="Carrito">
                <span id="carrito-contador" style="display: none;">0</span>
            </a>
        <?php endif; ?>

        <div class="section">
            <h2>Eskaintzak</h2>
            <div class="produktuak-grid">
                <?php if (count($ofertas) > 0): ?>
                    <?php foreach ($ofertas as $p): ?>
                        <div class="produktua-card">
                            <?php $img = $p->getIrudia() ? 'img/'.htmlspecialchars($p->getIrudia()) : 'img/'.htmlspecialchars($p->getId()).'.jpg'; ?>
                            <img src="<?php echo $img; ?>" class="produktua-img" alt="imagen">
                            <div class="produktua-info">
                                <h3><?php echo htmlspecialchars($p->getIzena()); ?></h3>
                                <div class="prezioa">
                                    <?php if ($p->getDeskontua() > 0): ?>
                                        <span style="text-decoration: line-through;"><?php echo number_format($p->getPrezioa(), 2, ',', '.'); ?>€</span> <strong style="color: #e74c3c; font-size: 1.1em;"><?php echo number_format($p->getPrezioaDeskontuarekin(), 2, ',', '.'); ?>€</strong>
                                    <?php else: ?>
                                        <?php echo number_format($p->getPrezioa(), 2, ',', '.'); ?> €
                                    <?php endif; ?>
                                </div>
                                <a href="javascript:void(0);" class="btn-saskira" onclick="window.carritos.agregarProducto(<?php echo $p->getId(); ?>); return false;">Gehitu saskira</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ez dago eskaintzarik momentu honetan.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2>Nobedadeak</h2>
            <div class="produktuak-grid">
                <?php if (count($novedades) > 0): ?>
                    <?php foreach ($novedades as $p): ?>
                        <div class="produktua-card">
                            <?php $img = $p->getIrudia() ? 'img/'.htmlspecialchars($p->getIrudia()) : 'img/'.htmlspecialchars($p->getId()).'.jpg'; ?>
                            <img src="<?php echo $img; ?>" class="produktua-img" alt="imagen">
                            <div class="produktua-info">
                                <h3><?php echo htmlspecialchars($p->getIzena()); ?></h3>
                                <div class="prezioa">
                                    <?php if ($p->getDeskontua() > 0): ?>
                                        <span style="text-decoration: line-through;"><?php echo number_format($p->getPrezioa(), 2, ',', '.'); ?>€</span> <strong style="color: #e74c3c; font-size: 1.1em;"><?php echo number_format($p->getPrezioaDeskontuarekin(), 2, ',', '.'); ?>€</strong>
                                    <?php else: ?>
                                        <?php echo number_format($p->getPrezioa(), 2, ',', '.'); ?> €
                                    <?php endif; ?>
                                </div>
                                <a href="javascript:void(0);" class="btn-saskira" onclick="window.carritos.agregarProducto(<?php echo $p->getId(); ?>); return false;">Gehitu saskira</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ez dago nobedaderik momentu honetan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="js/carrito.js"></script>
</body>
</html>