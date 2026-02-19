<?php
session_start();

require('../klaseak/com/leartik/daw24asdo/produktuak/produktua.php');
require('../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php');
require_once '../klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once '../klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
require_once '../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
require_once '../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';

use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\eskariak\EskariaDB;
use com\leartik\daw24asdo\mezuak\MezuaDB;

$admin = false;

if (isset($_SESSION['erabiltzailea']) && $_SESSION['erabiltzailea'] === "admin") {
    $admin = true;
}

if (isset($_POST['sartu'])) {
    if ($_POST['erabiltzailea'] === "admin" && $_POST['pasahitza'] === "admin") {
        $admin = true;
        $_SESSION["erabiltzailea"] = "admin";
    }
}

if ($admin === true) {
    $produktuak = ProduktuaDB::selectProduktuak();
    // Procesar acciones de eskariak
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Cambiar estado de eskaria
        if (isset($_POST['cambiar_estado_eskaria'])) {
            $id_eskaria = (int)$_POST['id_eskaria'];
            $estatua = trim($_POST['estatua']);
            EskariaDB::updateEstatua($id_eskaria, $estatua);
        } elseif (isset($_POST['eliminar_eskaria'])) {
            $id_eskaria = (int)$_POST['id_eskaria'];
            EskariaDB::deleteEskaria($id_eskaria);
        }
    }

    $eskariak = EskariaDB::selectEskariak();
    if ($produktuak === null) {
        echo "<p>Errorea: ezin izan da datu-basea ireki edo produkturik ez dago.</p>";
        exit;
    }

    $produktuak_by_kategoria = [];
    foreach ($produktuak as $p) {
        $kategoria = $p->getKategoriaId();
        if (!isset($produktuak_by_kategoria[$kategoria])) {
            $produktuak_by_kategoria[$kategoria] = [];
        }
        $produktuak_by_kategoria[$kategoria][] = $p;
    }

    require_once __DIR__ . '/../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
    $kats = \com\leartik\daw24asdo\produktuak\KategoriaDB::selectKategorienak();
    $nombres_kategorias = [];
    foreach ($kats as $kid => $kinfo) {
        $nombres_kategorias[(int)$kid] = $kinfo['izena'];
    }

    foreach (array_keys($nombres_kategorias) as $kid) {
        if (!isset($produktuak_by_kategoria[$kid])) {
            $produktuak_by_kategoria[$kid] = [];
        }
    }

    include('produktuak_erakutsi.php');

} else {
    $mezua = isset($_POST['sartu']) ? "Datuak ez dira zuzenak" : "";
    include('login.php');
}
