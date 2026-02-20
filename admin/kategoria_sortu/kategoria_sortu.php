<?php
// plantilla: el controlador `index.php` establece $izena, $azalpena y $mezua
// (no procesamos POST aquí para evitar duplicar lógica).
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Kategoria sortu</title>
    <link rel="stylesheet" href="../../css/berria.css"> 
</head>
<body>
    <header class="header-admin">
        <img src="../../img/logo.png" alt="Logo" class="logo-admin">
        <h1>Kategoria berria sortu</h1>
    </header>

    <?php if (isset($mezua)) { echo '<p style="color:red;">' . htmlspecialchars($mezua, ENT_QUOTES, 'UTF-8') . '</p>'; } ?>

    <form method="post" action="">
        <p>
            <label>Izena: <input type="text" name="izena" required value="<?php echo isset($izena) ? htmlspecialchars($izena, ENT_QUOTES, 'UTF-8') : ''; ?>"></label>
        </p>
        <p>
            <label>Azalpena: <br>
                <textarea name="azalpena" rows="4" cols="60"><?php echo isset($azalpena) ? htmlspecialchars($azalpena, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </label>
        </p>
        <p>
            <input type="submit" name="gorde" value="Sortu">
            <a href="../index.php">Itzuli</a>
        </p>
    </form>
</body>
</html>
