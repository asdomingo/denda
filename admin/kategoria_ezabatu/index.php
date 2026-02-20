<?php

session_start();

require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
use com\leartik\daw24asdo\produktuak\KategoriaDB;

$admin = false;
if (isset($_SESSION['erabiltzailea']) && $_SESSION['erabiltzailea'] == "admin") {
    $admin = true;
}

if ($admin == false) {
    header("Location: ../../index.php");
    exit();
}


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    echo '<p>IDa beharrezkoa da.</p><p><a href="../../index.php">Itzuli</a></p>';
    exit;
}


$all = KategoriaDB::selectKategorienak();
if (!isset($all[$id])) {
    echo '<p>Kategoria ez da aurkitu.</p><p><a href="../../index.php">Itzuli</a></p>';
    exit;
}

$current = $all[$id];

$products_count = KategoriaDB::countKategoriaProducts($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id_post !== $id) {
        $mezua = 'ID desberdina.';
        include 'kategoria_ezabatu.php';
        exit;
    }
    if (KategoriaDB::deleteKategoria($id) > 0) {
        header('Location: kategoria_ezabatu_da.php');
        exit;
    } else {
        header('Location: kategoria_ez_da_ezabatu.php');
        exit;
    }
}


include 'kategoria_ezabatu.php';
?>