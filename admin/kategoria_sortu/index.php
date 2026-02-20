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

if ($admin == true) {

    if (isset($_POST['gorde'])) {
        $izena = trim($_POST['izena'] ?? '');
        $azalpena = trim($_POST['azalpena'] ?? '');

        if ($izena === '') {
            $mezua = 'Mesedez, idatzi kategoria izen bat.';
            include('kategoria_sortu.php');
        } elseif (KategoriaDB::existsKategoriaByName($izena)) {
            $mezua = 'Mesedez, kategoria hori jadanik existitzen da.';
            include('kategoria_sortu.php');
        } else {
            KategoriaDB::insertKategoria($izena, $azalpena);
            header('Location: ../index.php');
            exit;
        }

    } else {
        $izena = '';
        $azalpena = '';
        $mezua = '';
        include('kategoria_sortu.php');
    }

} else {
    header('Location: ../index.php');
    exit();
}
