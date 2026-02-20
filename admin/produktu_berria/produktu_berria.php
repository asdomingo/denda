<?php
require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/produktua.php';

use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\produktuak\KategoriaDB;
use com\leartik\daw24asdo\produktuak\Produktua;

$izena = '';
$prezioa = '';
$kategoria_id = '';
$mezua = '';
$eskaintza_balioa = 0;
$nobedadea = 0;
$gorde_ondo = false; // Variable de control para mostrar la tabla

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gorde'])) {
    $izena = trim($_POST['izena'] ?? '');
    $prezioa = trim($_POST['prezioa'] ?? '');
    $kategoria_id = $_POST['kategoria_id'] ?? '';
    
    // 1. Capturamos los valores de los checkboxes
    $nobedadea = isset($_POST['nobedadea']) ? 1 : 0;
    $eskaintza_balioa = (isset($_POST['eskaintza_bai']) && !empty($_POST['eskaintza_kopurua'])) 
                        ? (int)$_POST['eskaintza_kopurua'] 
                        : 0;

    if ($izena !== '' && $prezioa !== '') {
        $p = new Produktua();
        $p->setIzena($izena);
        $p->setPrezioa($prezioa);
        $p->setKategoriaId($kategoria_id);
        $p->setNobedadeak($nobedadea);
        $p->setEskaintzak($eskaintza_balioa);
        $p->setIrudia('default.png'); 

        $ok = ProduktuaDB::insertProduktua($p);
        
        if ($ok) {
            $gorde_ondo = true; // Activamos la vista de confirmación
            $mezua = "Produktua ondo gorde da.";
        } else {
            $mezua = "Errorea gertatu da datu-basean gordetzean.";
        }
    } else {
        $mezua = "Mesedez, bete eremu guztiak.";
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Produktua berria - K.O BOXING</title>
    <link rel="stylesheet" href="../../css/berria.css">
    <style>
        .opzioak-group { background: #f9f9f9; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #ddd; color: #333; }
        .checkbox-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; cursor: pointer; }
        .hidden { display: none; }
        .input-zenbat { width: 70px; padding: 5px; margin-left: 10px; }
        .confirm-table { margin-top: 20px; background: white; color: black; width: 100%; border-collapse: collapse; }
        .confirm-table td { padding: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <header class="header-admin">
        <img src="../../img/logo.png" alt="Logo" class="logo-admin">
        <h1>Produktuen administrazio gunea</h1>
    </header>

    <main class="main-content">
        <nav class="breadcrumb">
            <a href="../index.php" class="btn-hasiera">Hasiera</a> <span>&gt;</span>
        </nav>

        <h2 class="form-title">Produktua berria</h2>

        <?php if ($mezua): ?>
            <div style="color: white; background: <?php echo $gorde_ondo ? '#28a745' : 'red'; ?>; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                <?php echo htmlspecialchars($mezua); ?>
            </div>
        <?php endif; ?>

        <?php if (!$gorde_ondo): ?>
            <div class="card">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="izena">Izena:</label>
                        <input type="text" id="izena" name="izena" value="<?php echo htmlspecialchars($izena); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="prezioa">Prezioa (€):</label>
                        <input type="number" step="0.01" id="prezioa" name="prezioa" value="<?php echo htmlspecialchars($prezioa); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="kategoria_id">Kategoria:</label>
                        <select id="kategoria_id" name="kategoria_id">
                            <?php
                            $kats = KategoriaDB::selectKategorienak();
                            foreach ($kats as $kid => $kinfo) {
                                echo '<option value="' . $kid . '">' . htmlspecialchars($kinfo['izena']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="opzioak-group">
                        <label class="checkbox-row">
                            <input type="checkbox" name="nobedadea" value="1"> 
                            ✨ Nobedadea da
                        </label>

                        <label class="checkbox-row">
                            <input type="checkbox" name="eskaintza_bai" id="eskaintza_bai" onchange="toggleOferta()"> 
                            🔥 Eskaintza 
                        </label>

                        <div id="oferta_kopurua_div" class="hidden" style="margin-top: 10px; padding-left: 25px;">
                            <label for="eskaintza_kopurua">Zenbateko eskaintza? (%)</label>
                            <input type="number" name="eskaintza_kopurua" id="eskaintza_kopurua" class="input-zenbat" value="0" min="0" max="100">
                        </div>
                    </div>

                    <input type="submit" name="gorde" value="Gorde" class="btn-gorde">
                </form>
            </div>
        <?php else: ?>
            <div class="card">
                <table class="confirm-table">
                    <tr>
                        <td align="right"><b>Izena</b></td>
                        <td><?php echo htmlspecialchars($izena); ?></td>
                    </tr>
                    <tr>
                        <td align="right"><b>Prezioa</b></td>
                        <td><?php echo htmlspecialchars($prezioa); ?> €</td>
                    </tr>
                    <tr>
                        <td align="right"><b>Kategoria ID</b></td>
                        <td><?php echo htmlspecialchars($kategoria_id); ?></td>
                    </tr>
                    <tr>
                        <td align="right"><b>Nobedadea?</b></td>
                        <td><?php echo ($nobedadea == 1) ? 'BAI (✨)' : 'EZ'; ?></td>
                    </tr>
                    <tr>
                        <td align="right"><b>Eskaintza</b></td>
                        <td><?php echo ($eskaintza_balioa > 0) ? htmlspecialchars($eskaintza_balioa) . '% (🔥)' : 'Ez du eskaintzarik'; ?></td>
                    </tr>
                </table>
                <br>
                <a href="produktua_berria.php" class="btn-hasiera" style="background:#666; text-decoration:none; padding:10px; color:white; border-radius:5px;">Beste bat gehitu</a>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function toggleOferta() {
            const checkbox = document.getElementById('eskaintza_bai');
            const div = document.getElementById('oferta_kopurua_div');
            div.classList.toggle('hidden', !checkbox.checked);
        }
    </script>
</body>
</html>