<?php
session_start();

require_once 'klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once 'klaseak/com/leartik/daw24asdo/produktuak/produktua.php';
require_once 'klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once 'klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
require_once 'klaseak/com/leartik/daw24asdo/logs/log_db.php';

use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\eskariak\Eskaria;
use com\leartik\daw24asdo\eskariak\EskariaDB;
use com\leartik\daw24asdo\logs\LogDB;

// Inicializar carrito
if (!isset($_SESSION['karratua'])) {
    $_SESSION['karratua'] = [];
}

$mensajeExito = '';
$error = '';
$karratuProduktuak = [];
$totala = 0;

// Redirigir si el carrito está vacío (solo si intenta acceder directamente)
if (empty($_SESSION['karratua']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Procesar el formulario de compra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_compra'])) {
    // Sanitizar y validar datos
    $izena = trim($_POST['izena'] ?? '');
    $abizenak = trim($_POST['abizenak'] ?? '');
    $helbidea = trim($_POST['helbidea'] ?? '');
    $postapostala = trim($_POST['postapostala'] ?? '');
    $hiria = trim($_POST['hiria'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $notak = trim($_POST['notak'] ?? '');

    // Validar campos obligatorios
    $erroresValidacion = [];
    
    if (strlen($izena) < 3 || strlen($izena) > 50) {
        $erroresValidacion[] = 'Izenak 3 eta 50 karaktere artean izan behar ditu.';
    } elseif (preg_match('/[0-9]/', $izena)) {
        $erroresValidacion[] = 'Izenak ezin du zenbakirik izan.';
    }
    
    if (strlen($abizenak) < 3 || strlen($abizenak) > 50) {
        $erroresValidacion[] = 'Abizenak 3 eta 50 karaktere artean izan behar ditu.';
    } elseif (preg_match('/[0-9]/', $abizenak)) {
        $erroresValidacion[] = 'Abizenak ezin du zenbakirik izan.';
    }
    
    if (strlen($helbidea) < 5 || strlen($helbidea) > 100) {
        $erroresValidacion[] = 'Helbideak 5 eta 100 karaktere artean izan behar ditu.';
    }
    
    if (!preg_match('/^[0-9]{5,10}$/', $postapostala)) {
        $erroresValidacion[] = 'Posta-kode baliogabea (zenbakiak soilik).';
    }
    
    if (strlen($hiria) < 2 || strlen($hiria) > 50) {
        $erroresValidacion[] = 'Hiriak 2 eta 50 karaktere artean izan behar ditu.';
    } elseif (preg_match('/[0-9]/', $hiria)) {
        $erroresValidacion[] = 'Hiriak ezin du zenbakirik izan.';
    }
    
    if (!preg_match('/^[0-9]{9,}$/', str_replace([' ', '-', '(', ')'], '', $telefono))) {
        $erroresValidacion[] = 'Telefono baliogabea (gutxienez 9 zenbaki).';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroresValidacion[] = 'E-posta baliogabea.';
    }
    if (empty($_SESSION['karratua'])) {
        $erroresValidacion[] = 'El carrito está vacío.';
    }

    if (empty($erroresValidacion)) {
        // Procesar la compra: guardar todos los productos en la BD
        try {
            $denaOndo = true;
            $idPedido = null;

            foreach ($_SESSION['karratua'] as $prodId => $kantitatea) {
                $eskaria = new Eskaria();
                $eskaria->setIzena($izena);
                $eskaria->setAbizenak($abizenak);
                $eskaria->setHelbidea($helbidea);
                $eskaria->setPostapostala($postapostala);
                $eskaria->setHiria($hiria);
                $eskaria->setTelefono($telefono);
                $eskaria->setEmail($email);
                $eskaria->setNotak($notak);
                $eskaria->setProduktuaId($prodId);
                $eskaria->setKantitatea($kantitatea);

                if (!EskariaDB::insertEskaria($eskaria)) {
                    $denaOndo = false;
                    break;
                }
            }

            if ($denaOndo) {
                // Registrar en log
                LogDB::insertLog("Nuevo pedido creado por: $izena $abizenak - Email: $email");
                
                // Limpiar el carrito
                $_SESSION['karratua'] = [];
                
                $mensajeExito = "¡Pedido realizado exitosamente! Recibirás una confirmación en tu email: $email";
            } else {
                $error = 'Error al guardar el pedido. Por favor, intenta de nuevo.';
            }
        } catch (Exception $e) {
            $error = 'Error inesperado: ' . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $erroresValidacion);
    }
}

// Obtener detalles de los productos en el carrito
if (!empty($_SESSION['karratua'])) {
    foreach ($_SESSION['karratua'] as $id => $kop) {
        $p = ProduktuaDB::selectProduktua($id);
        if ($p) {
            $karratuProduktuak[] = [
                'produktua' => $p,
                'kopurua' => $kop,
                'subtotala' => $p->getPrezioa() * $kop
            ];
            $totala += $p->getPrezioa() * $kop;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saskia - Denda ETK</title>
    <link rel="stylesheet" href="css/estiloak.css">
    <link rel="stylesheet" href="css/carrito.css">
</head>
<body>
    <div class="header logo">
        <img src="https://asdo-s3.s3.eu-north-1.amazonaws.com/logo.png" alt="Denda Logo" class="logo">
    </div>

    <div class="container">
        <div class="nav">
            <a href="index.php">Hasiera</a>
            <a href="katalogoa.php">Katalogoa</a>
            <a href="mediateka.php">Mediateka</a>
            <a href="kontaktua.php">Kontaktua</a>
        </div>

        <a href="index.php" class="carrito-icon" id="cart-link" style="border: none; background: none;">
            <img src="img/carrito.png" alt="Carrito">
            <span id="carrito-contador" style="display: none;">0</span>
        </a>

        <div class="section">
            <h1>Zure Saskia</h1>

            <?php if ($mensajeExito): ?>
                <div class="mensaje mensaje-exito">
                    ✓ <?php echo htmlspecialchars($mensajeExito); ?>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="katalogoa.php" >Jarraitu erosten</a>
                    <a href="index.php" >Hasiera</a>
                </div>
            <?php elseif (empty($karratuProduktuak)): ?>
                <div class="carrito-vacio">
                    <h2>Saskia hutsik dago</h2>
                    <p>Badituzu erosketak egiteko aukera Katalogoan produktuak aukeratzean.</p>
                    <a href="katalogoa.php">Katalogoa ikusi</a>
                </div>
            <?php else: ?>
                <!-- Tabla del carrito -->
                <h2>Produktuak Saskian</h2>
                <table class="tabla-carrito">
                    <thead>
                        <tr>
                            <th>Produktua</th>
                            <th class="precio">Prezioa</th>
                            <th class="cantidad">Kopurua</th>
                            <th class="precio">Azpi-totala</th>
                            <th class="acciones">Ekintzak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($karratuProduktuak as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['produktua']->getIzena()); ?></td>
                                <td class="precio"><?php echo number_format($item['produktua']->getPrezioa(), 2, ',', '.'); ?> €</td>
                                <td class="cantidad">
                                    <div class="cantidad-selector">
                                        <button type="button" class="btn-cantidad" 
                                                data-product-id="<?php echo $item['produktua']->getId(); ?>" 
                                                data-current-qty="<?php echo $item['kopurua']; ?>" 
                                                data-action="dec">
                                            -
                                        </button>
                                        <span class="qty-num"><?php echo (int)$item['kopurua']; ?></span>
                                        <button type="button" class="btn-cantidad" 
                                                data-product-id="<?php echo $item['produktua']->getId(); ?>" 
                                                data-current-qty="<?php echo $item['kopurua']; ?>" 
                                                data-action="inc">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="precio"><?php echo number_format($item['subtotala'], 2, ',', '.'); ?> €</td>
                                <td class="acciones">
                                    <button type="button" 
                                       class="btn-eliminar-carrito" 
                                       data-product-id="<?php echo $item['produktua']->getId(); ?>"
                                       title="Kendu saskiatik">
                                        Kendu
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; padding: 15px;">Guztira:</td>
                            <td class="total-price"><?php echo number_format($totala, 2, ',', '.'); ?> €</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Errores de validación -->
                <?php if ($error): ?>
                    <div class="mensaje mensaje-error">
                        ✕ <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario de compra -->
                <form method="POST" class="form-compra" id="formulario-compra">
                    <h2>Datu Pertsonalak</h2>

                    <div class="form-group">
                        <label for="izena">Izena <span class="obligatorio">*</span></label>
                        <input type="text" id="izena" name="izena" maxlength="50" required 
                               pattern="^[^0-9]+$" title="Izenak ezin du zenbakirik izan"
                               value="<?php echo htmlspecialchars($_POST['izena'] ?? ''); ?>">
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="abizenak">Abizenak <span class="obligatorio">*</span></label>
                        <input type="text" id="abizenak" name="abizenak" maxlength="50" required 
                               pattern="^[^0-9]+$" title="Abizenak ezin du zenbakirik izan"
                               value="<?php echo htmlspecialchars($_POST['abizenak'] ?? ''); ?>">
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="helbidea">Helbidea (osoa) <span class="obligatorio">*</span></label>
                        <input type="text" id="helbidea" name="helbidea" maxlength="100" required 
                               placeholder="Kalea, Plazuela, etab..."
                               value="<?php echo htmlspecialchars($_POST['helbidea'] ?? ''); ?>">
                        <span class="error-message"></span>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="postapostala">Postapostala <span class="obligatorio">*</span></label>
                            <input type="text" id="postapostala" name="postapostala" maxlength="10" required 
                                   pattern="^[0-9]+$" title="Zenbakiak soilik" inputmode="numeric"
                                   value="<?php echo htmlspecialchars($_POST['postapostala'] ?? ''); ?>">
                            <span class="error-message"></span>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="hiria">Hiria <span class="obligatorio">*</span></label>
                            <input type="text" id="hiria" name="hiria" maxlength="50" required 
                                   pattern="^[^0-9]+$" title="Hiriak ezin du zenbakirik izan"
                                   value="<?php echo htmlspecialchars($_POST['hiria'] ?? ''); ?>">
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Telefonoa <span class="obligatorio">*</span></label>
                        <input type="tel" id="telefono" name="telefono" required 
                               pattern="^[0-9]{9,}$" title="Gutxienez 9 zenbaki" inputmode="numeric"
                               placeholder="600123456"
                               value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">E-posta <span class="obligatorio">*</span></label>
                        <input type="email" id="email" name="email" maxlength="100" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="notak">Notak (Aukerakoa)</label>
                        <textarea id="notak" name="notak" maxlength="500" 
                                  placeholder="Adibidez: Aurrera nahi dut funtzionari batean..."><?php echo htmlspecialchars($_POST['notak'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" name="procesar_compra" class="btn-comprar">
                        Eskaria Bidali
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="katalogoa.php">← Katalogoan itzuli</a>
        </div>
    </div>

    <script src="js/carrito.js"></script>
    <script>
        // Validación del formulario antes de enviar
        document.getElementById('formulario-compra')?.addEventListener('submit', function(e) {
            if (window.carritos && !window.carritos.validarFormulario('formulario-compra')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
