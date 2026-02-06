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
    <nav>
        <ul>
            <li><a href="kontaktua.php">Kontaktua</a></li>
            <li><a href="egunkaria.php">Egunkaria</a></li>
            <li><a href="eskaria_egin.php">Eskaria Egin</a></li>
            <li><a href="admin/">Administrazio gunea</a></li>
        </ul>
    </nav>
    <dl>

        <?php
        if (!isset($produktuak) || !is_array($produktuak)) {
            echo "<p>Produkurik ez dago.</p>";
            exit;
        }
        ?>

        <?php
        require_once __DIR__ . '/klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
        use com\leartik\daw24asdo\produktuak\KategoriaDB;
        $nombres_kategorias = KategoriaDB::selectKategorienak();

        $by_kategoria = [];
        foreach ($produktuak as $p) {
            $kid = $p->getKategoriaId();
            if (!isset($by_kategoria[$kid])) $by_kategoria[$kid] = [];
            $by_kategoria[$kid][] = $p;
        }
        ksort($by_kategoria);

        foreach ($by_kategoria as $kid => $list) {
            if (isset($nombres_kategorias[$kid])) {
                $izena = $nombres_kategorias[$kid]['izena'];
            } else {
                $izena = "Kategoria " . $kid;
            }
            echo '<h2><strong>' . htmlspecialchars($izena, ENT_QUOTES, 'UTF-8') . '</strong></h2>';
            echo '<dl>'; 
            foreach ($list as $produktua) {
                $id = $produktua->getId(); 
                $ruta_imagen = 'img/' . $id . '.jpg';
                
                echo '<section class="produktua-item">';
                echo '<img src="' . htmlspecialchars($ruta_imagen) . '" alt="' . htmlspecialchars($produktua->getIzena()) . '">';
                
                echo '<div class="produktua-info">';
                echo '<dt><a href="./?id=' . $id . '">' . htmlspecialchars($produktua->getIzena(), ENT_QUOTES, 'UTF-8') . '</a></dt>';
                echo '<dd>Prezioa: ' . htmlspecialchars($produktua->getPrezioa(), ENT_QUOTES, 'UTF-8') . ' €</dd>';
                echo '</div>';
                echo '</section>';
            }
            echo '</dl>';
        }
        ?>

    </dl>
</body>

</html>