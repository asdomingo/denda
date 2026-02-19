<?php
// Kategoria aldatu (euskaraz)
require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
use com\leartik\daw24asdo\produktuak\KategoriaDB;

if (!isset($_GET['id'])) {
    echo '<p>IDa beharrezkoa da.</p><p><a href="../index.php">Itzuli</a></p>';
    exit;
}

$id = (int)$_GET['id'];

$all = KategoriaDB::selectKategorienak();
if (!isset($all[$id])) {
    echo '<p>Kategoria ez da aurkitu.</p><p><a href="../index.php">Itzuli</a></p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $izena = trim($_POST['izena'] ?? '');
    $azalpena = trim($_POST['azalpena'] ?? '');
    if ($izena === '') {
        $mezua = 'Mesedez, idatzi kategoria izen bat.';
    } else {
        KategoriaDB::updateKategoria($id, $izena, $azalpena);
        header('Location: ../index.php');
        exit;
    }
}

$current = $all[$id];

?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Kategoria aldatu</title>
    <link rel="stylesheet" href="../../css/aldatu.css"> 
</head>
<body>
    <header class="header-admin">
        <img src="../../img/logo.png" alt="Logo" class="logo-admin">
        <h1>Kategoria aldatu</h1>
    </header>

    <?php if (isset($mezua)) { echo '<p style="color:red;">' . htmlspecialchars($mezua, ENT_QUOTES, 'UTF-8') . '</p>'; } ?>

    <form method="post" action="?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
        <p>
            <label>Izena: <input type="text" name="izena" value="<?php echo htmlspecialchars($current['izena'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
        </p>
        <p>
            <label>Azalpena:<br>
                <textarea name="azalpena" rows="4" cols="60"><?php echo htmlspecialchars($current['azalpena'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </label>
        </p>
        <p>
            <input type="submit" value="Gorde">
            <a href="../index.php">Itzuli</a>
        </p>
    </form>
</body>
</html>
