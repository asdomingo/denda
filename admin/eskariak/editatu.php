<?php
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/produktuak/produktua.php';
require_once '../../klaseak/com/leartik/daw24asdo/logs/log_db.php';


use com\leartik\daw24asdo\eskariak\EskariaDB;
use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\logs\LogDB;

// If we also want to allow changing the product, we need the list of products
$produktuak = ProduktuaDB::selectProduktuak();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eskaria = EskariaDB::selectEskaria($_POST['id']);
    if ($eskaria) {
        $eskaria->setBezeroa($_POST['bezeroa']);
        $eskaria->setHelbidea($_POST['helbidea']);
        $eskaria->setProduktuaId($_POST['produktua_id']);
        $eskaria->setKantitatea($_POST['kantitatea']);
        
        if (EskariaDB::updateEskaria($eskaria)) {
            LogDB::insertLog("Eskaria aldatu: " . $eskaria->getId());
            header("Location: ../../index.php");
            exit;
        } else {
            $error = "Errorea eskaria gordetzerakoan.";
        }
    }
} else {
    if (isset($_GET['id'])) {
        $eskaria = EskariaDB::selectEskaria($_GET['id']);
    }
}

if (!isset($eskaria) || !$eskaria) {
    die("Eskaria ez da aurkitu.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eskaria Aldatu</title>
</head>
<body>
    <h1>Eskaria Aldatu</h1>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $eskaria->getId(); ?>">
        <label>Bezeroa:</label><br>
        <input type="text" name="bezeroa" value="<?php echo htmlspecialchars($eskaria->getBezeroa()); ?>"><br>
        <label>Helbidea:</label><br>
        <input type="text" name="helbidea" value="<?php echo htmlspecialchars($eskaria->getHelbidea()); ?>"><br>
        <label>Produktua:</label><br>
        <select name="produktua_id">
            <?php foreach ($produktuak as $p): ?>
                <option value="<?php echo $p->getId(); ?>" <?php if($p->getId() == $eskaria->getProduktuaId()) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($p->getIzena()); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <label>Kantitatea:</label><br>
        <input type="number" name="kantitatea" value="<?php echo htmlspecialchars($eskaria->getKantitatea()); ?>"><br>
        <button type="submit">Gorde</button>
    </form>
    <a href="index.php">Utzi</a>
</body>
</html>
