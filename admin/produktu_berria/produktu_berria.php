<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Produktuak</title>
        <link rel="stylesheet" href="../../css/berria.css">
    </head>
    <body>
        <h1>Produktuen administrazio gunea</h1>
        <p><a href="../../index.php">Hasiera</a> &gt;</p>
        <h2>Produktua berria</h2>
        <p><?php echo $mezua; ?></p>
        <form action="index.php" method="post">
            <p>
                <label for="izena">Izena:</label>
                <input type="text" id="izena" name="izena" size="50" maxlength="255" value="<?php echo htmlspecialchars($izena); ?>">
            </p>
            <p>
                <label for="prezioa">Prezioa:</label>
                <input type="text" id="prezioa" name="prezioa" size="10" maxlength="20" value="<?php echo htmlspecialchars($prezioa); ?>">
            </p>
            <p>
                <label for="kategoria_id">Kategoria:</label>
                <select id="kategoria_id" name="kategoria_id">
                    <?php
                    require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
                    $kats = \com\leartik\daw24asdo\produktuak\KategoriaDB::selectKategorienak();
                    foreach ($kats as $kid => $kinfo) {
                        $sel = ($kategoria_id == $kid) ? ' selected' : '';
                        echo '<option value="' . htmlspecialchars($kid) . '"' . $sel . '>' . htmlspecialchars($kinfo['izena']) . '</option>';
                    }
                    ?>
                </select>
            </p>
            <input type="submit" name="gorde" value="Gorde">
        </form>
    </body>
</html>
