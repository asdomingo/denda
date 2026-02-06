<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Albisteak - Ezabatu</title>
    <link rel="stylesheet" href="../../css/ezabatu.css"> 
</head>
<body>

    <h1>Produktuen administrazio gunea</h1>
    <p><a href="../../index.php">Hasiera</a> &gt; Ezabatu</p>

    <h2>Produktua ezabatu (ID: <?= htmlspecialchars($id_produktua) ?>)</h2>

    <?php 
    // Para aplicar el estilo de error, envolvemos el mensaje en un contenedor con clase
    if (!empty($mensaje_error)) {
        echo '<div class="mensaje-error">' . htmlspecialchars($mensaje_error) . '</div>'; 
    }
    
    // Definimos la ruta de la imagen
    $id_producto_actual = $produktua_existente->getId();
    $ruta_imagen = "../../img/{$id_producto_actual}.jpg";
    ?>

    <div class="abisu-kaxa">
        <h3>⚠️ KONTUZ: Produktua ezabatuko da</h3>
        <p>Ziur zaude produktu hau ezabatu nahi duzula? Eragiketa hau ezin da desegin.</p>
    </div>

    <div class="producto-detalle-ezabatu">
        
        <img src="<?= htmlspecialchars($ruta_imagen) ?>" alt="Produktua Irudia" class="imagen-ezabatu">

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
                    // Intentar mostrar nombre de categoria
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

    <form action="index.php?id=<?= htmlspecialchars($id_produktua) ?>" method="post">
        <input type="hidden" name="id_produktua" value="<?= htmlspecialchars($produktua_existente->getId()) ?>">
        <p>
            <input type="submit" name="ezabatu" value="BAI, Ezabatu">
            <a href="../../index.php" class="itzuli-lotura">EZ, Itzuli</a>
        </p>
    </form>

</body>
</html>