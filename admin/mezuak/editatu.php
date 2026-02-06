<?php
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/logs/log_db.php';

use com\leartik\daw24asdo\mezuak\MezuaDB;
use com\leartik\daw24asdo\logs\LogDB;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mezua = MezuaDB::selectMezua($_POST['id']);
    if ($mezua) {
        $mezua->setIzena($_POST['izena']);
        $mezua->setEmaila($_POST['emaila']);
        $mezua->setMezua($_POST['mezua']);
        
        if (MezuaDB::updateMezua($mezua)) {
            LogDB::insertLog("Mezua aldatu: " . $mezua->getId());
            header("Location: ../../index.php");
            exit;
        } else {
            $error = "Errorea mezua gordetzerakoan.";
        }
    }
} else {
    if (isset($_GET['id'])) {
        $mezua = MezuaDB::selectMezua($_GET['id']);
    }
}

if (!isset($mezua) || !$mezua) {
    die("Mezua ez da aurkitu.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mezua Aldatu</title>
</head>
<body>
    <h1>Mezua Aldatu</h1>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $mezua->getId(); ?>">
        <label>Izena:</label><br>
        <input type="text" name="izena" value="<?php echo htmlspecialchars($mezua->getIzena()); ?>"><br>
        <label>Emaila:</label><br>
        <input type="email" name="emaila" value="<?php echo htmlspecialchars($mezua->getEmaila()); ?>"><br>
        <label>Mezua:</label><br>
        <textarea name="mezua"><?php echo htmlspecialchars($mezua->getMezua()); ?></textarea><br>
        <button type="submit">Gorde</button>
    </form>
    <a href="index.php">Utzi</a>
</body>
</html>
