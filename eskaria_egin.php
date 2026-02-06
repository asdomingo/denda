<?php
require_once 'klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once 'klaseak/com/leartik/daw24asdo/produktuak/produktua.php';
require_once 'klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once 'klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
require_once 'klaseak/com/leartik/daw24asdo/logs/log_db.php';

use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\eskariak\Eskaria;
use com\leartik\daw24asdo\eskariak\EskariaDB;
use com\leartik\daw24asdo\logs\LogDB;

$produktuak = ProduktuaDB::selectProduktuak();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eskaria = new Eskaria();
    $eskaria->setBezeroa($_POST['bezeroa']);
    $eskaria->setHelbidea($_POST['helbidea']);
    $eskaria->setProduktuaId($_POST['produktua_id']);
    $eskaria->setKantitatea($_POST['kantitatea']);

    if (EskariaDB::insertEskaria($eskaria)) {
        LogDB::insertLog("Eskaria sortu: " . $_POST['bezeroa']);
        $mezua = "Eskaria ondo gorde da!";
    } else {
        $error = "Errorea eskaria gordetzerakoan.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eskaria Egin</title>
</head>
<body>
    <h1>Eskaria Egin</h1>
    <?php if (isset($mezua)) echo "<p style='color:green'>$mezua</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post">
        <label>Bezeroa:</label><br>
        <input type="text" name="bezeroa" required><br>
        <label>Helbidea:</label><br>
        <input type="text" name="helbidea" required><br>
        <label>Produktua:</label><br>
        <select name="produktua_id">
            <?php foreach ($produktuak as $p): ?>
                <option value="<?php echo $p->getId(); ?>">
                    <?php echo htmlspecialchars($p->getIzena()); ?> (<?php echo $p->getPrezioa(); ?> €)
                </option>
            <?php endforeach; ?>
        </select><br>
        <label>Kantitatea:</label><br>
        <input type="number" name="kantitatea" min="1" value="1" required><br>
        <button type="submit">Eskatu</button>
    </form>
    <a href="index.php">Hasiera</a>
</body>
</html>
