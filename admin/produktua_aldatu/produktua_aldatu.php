<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Albisteak - Editatu</title>
    <link rel="stylesheet" href="../../css/aldatu.css">
</head>
<body>

    <header class="header-admin">        
        <h1>Produktuen administrazio gunea</h1>
    </header>
    <p><a href="../index.php">Hasiera</a> &gt; Editatu</p>

    <h2>Produktua aldatu (ID: <?= htmlspecialchars($id_produktua) ?>)</h2>

    <?php 
    
    // Para que el CSS funcione, envolvemos el mensaje de error en un contenedor
    if (!empty($mensaje_error)) {
        echo '<p class="mensaje-error">' . htmlspecialchars($mensaje_error) . '</p>'; 
    }

    // Definimos la ruta de la imagen usando el ID del producto existente
    $id_producto_actual = $produktua_existente->getId();
    $ruta_imagen = "../../img/{$id_producto_actual}.jpg";
    ?>

    <?php if ($erakutsi_berrespena): ?>

        <h3 class="mensaje-exito">Eragiketa burututa</h3>
        <p><?= htmlspecialchars($mensaje_berrespena) ?></p>

        <div class="producto-detalle-confirmacion">
            <img src="<?= htmlspecialchars($ruta_imagen) ?>" alt="Produktua Irudia" class="imagen-edicion-confirmacion">
            <table>
                <tr>
                    <th>Izena:</th>
                    <td><?= htmlspecialchars($produktua_existente->getIzena()) ?></td>
                </tr>
                <tr>
                    <th>Prezioa:</th>
                    <td><?= htmlspecialchars($produktua_existente->getPrezioa()) ?> €</td>
                </tr>
                <tr>
                    <th style="vertical-align: top;">Kategoria:</th>
                    <td>
                        <?php
                        require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
                        $kats = \com\leartik\daw24asdo\produktuak\KategoriaDB::selectKategorienak();
                        $kid = $produktua_existente->getKategoriaId();
                        if (isset($kats[$kid])) {
                            echo htmlspecialchars($kats[$kid]['izena']);
                            if (!empty($kats[$kid]['azalpena'])) echo '<br><em>' . htmlspecialchars($kats[$kid]['azalpena']) . '</em>';
                        } else {
                            echo 'ID: ' . htmlspecialchars($kid);
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <p class="eragiketa-itzuli"><a href="../index.php">Itzuli</a></p>

    <?php else: ?>
        
        <div class="formulario-y-imagen">
            
            <form action="index.php?id=<?= htmlspecialchars($id_produktua) ?>" method="post" class="formulario-edicion">
                <input type="hidden" name="id_produktua" value="<?= htmlspecialchars($produktua_existente->getId()) ?>">
                <p>
                    <label for="izena">Izena</label><br>
                    <input type="text" id="izena" name="izena" size="50" maxlength="255" value="<?= htmlspecialchars($produktua_existente->getIzena()) ?>">
                </p>
                <p>
                    <label for="prezioa">Prezioa</label><br>
                    <input type="text" id="prezioa" name="prezioa" value="<?= htmlspecialchars($produktua_existente->getPrezioa()) ?> €">
                </p>
                <p>
                    <label for="kategoria_id">Kategoria</label><br>
                    <select id="kategoria_id" name="kategoria_id">
                        <?php
                        require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
                        $kats = \com\leartik\daw24asdo\produktuak\KategoriaDB::selectKategorienak();
                        foreach ($kats as $kid => $kinfo) {
                            $sel = ($produktua_existente->getKategoriaId() == $kid) ? ' selected' : '';
                            echo '<option value="' . htmlspecialchars($kid) . '"' . $sel . '>' . htmlspecialchars($kinfo['izena']) . '</option>';
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <input type="submit" name="gorde" value="Gorde Aldaketak">
                </p>
            </form>
            
            <div class="imagen-contenedor-edicion">
                <h2>Produktuaren Irudia</h2>
                <img src="<?= htmlspecialchars($ruta_imagen) ?>" alt="Produktua Irudia" class="imagen-edicion">
                </div>
            
        </div>
        
    <?php endif; ?>

</body>
</html>