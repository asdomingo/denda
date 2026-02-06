<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktuak</title>
    <link rel="stylesheet" href="css/estiloak.css">
</head>
<body>
    <h1>Produktuak</h1>
    <p><a href="index.php">Hasiera</a> &gt;</p>

    <?php
    // Mostramos un producto individual si se pasó id
    if (isset($_GET['id']) && $produktua !== null) {
        
        // NUEVO: Contenedor principal para el detalle con la imagen
        echo '<div class="produktua-detalle">';

        // NUEVO: Muestra la imagen (img/ID.jpg)
        $id = $produktua->getId();
        $ruta_imagen = 'img/' . $id . '.jpg';
        echo '<img src="' . htmlspecialchars($ruta_imagen) . '" alt="' . htmlspecialchars($produktua->getIzena()) . '">';
        
        // NUEVO: Contenedor para la información
        echo '<div class="produktua-data">';
        
        echo "<h2>" . htmlspecialchars($produktua->getIzena()) . "</h2>";
        echo "<p>Prezioa: " . htmlspecialchars($produktua->getPrezioa()) . " €</p>";
        if (isset($kategoriaren_izena)) {
            echo "<p>Kategoria: <strong>" . htmlspecialchars($kategoriaren_izena) . "</strong></p>";
            if (isset($kategoriaren_azalpena) && $kategoriaren_azalpena !== '') {
                echo "<p style='font-style:italic;'>" . htmlspecialchars($kategoriaren_azalpena) . "</p>";
            }
        } else {
            echo "<p>Kategoria ID: " . htmlspecialchars($produktua->getKategoriaId()) . "</p>";
        }
        
        echo '<p><a href="index.php" class="atzera-lotura">Itzuli zerrendara</a></p>';
        
        // NUEVO: Cierre de contenedores
        echo '</div>'; // Cierre de .produktua-data
        echo '</div>'; // Cierre de .produktua-detalle


    // Si no hay id, mostramos la lista completa de productos
    } elseif (isset($produktuak) && is_array($produktuak) && count($produktuak) > 0) {
        // NOTA: Este bloque se ejecuta si vienes de index.php SIN ID (por error) 
        // y muestra la lista simple, pero esto es manejado principalmente por produktuak_erakutsi.php.
        // Lo dejo como estaba por si acaso.
        echo '<ul>';
        foreach ($produktuak as $p) {
            echo '<li>';
            echo htmlspecialchars($p->getIzena()) . " (Prezioa: " . htmlspecialchars($p->getPrezioa()) . ")";
            echo ' [<a href="index.php?id=' . $p->getId() . '">Ikusi</a>]';
            echo ' [<a href="produktua_aldatu.php?id=' . $p->getId() . '">Aldatu</a>]';
            echo ' [<a href="produktua_ezabatu.php?id=' . $p->getId() . '">Ezabatu</a>]';
            echo '</li>';
        }
        echo '</ul>';
        echo '<p><a href="produktua_berria.php">Produktua berria gehitu</a></p>';

    // Si no hay productos
    } else {
        echo '<p>Ez da produkturik aurkitu.</p>';
    }
    ?>
    
</body>
</html>