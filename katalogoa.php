<?php
session_start();

require('klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php');
require('klaseak/com/leartik/daw24asdo/produktuak/produktua.php');

use com\leartik\daw24asdo\produktuak\ProduktuaDB as PDB;
use com\leartik\daw24asdo\produktuak\Produktua;

$admin = false;

if (isset($_POST['sartu'])) {
    if ($_POST['erabiltzailea'] === "admin" && $_POST['pasahitza'] === "admin") {
        $admin = true;
        $_SESSION["erabiltzailea"] = "admin";
    }
} elseif (isset($_SESSION['erabiltzailea']) && $_SESSION['erabiltzailea'] === "admin") {
    $admin = true;
}

$produktuak = PDB::selectProduktuak();

if ($produktuak === null) {
    echo "<p>Errorea: ezin izan da datu-basea ireki edo produkturik ez dago.</p>";
    exit;
}

require_once __DIR__ . '/klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
$nombres_categorias = \com\leartik\daw24asdo\produktuak\KategoriaDB::selectKategorienak();

$by_kategoria = [];
foreach ($produktuak as $p) {
    $kid = $p->getKategoriaId();
    if (!isset($by_kategoria[$kid])) $by_kategoria[$kid] = [];
    $by_kategoria[$kid][] = $p;
}
ksort($by_kategoria);

if ($admin === true) {
    header('Location: admin/index.php');
    exit;
} else {
    include('produtuak_erakutsi.php');
}
?>