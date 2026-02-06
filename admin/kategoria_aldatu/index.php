<?php
session_start();

require_once __DIR__ . '/../../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
use com\leartik\daw24asdo\produktuak\KategoriaDB;

$admin = false;
if (isset($_SESSION['erabiltzailea']) && $_SESSION['erabiltzailea'] == "admin") {
    $admin = true;
}

if ($admin == false) {
    header("Location: ../index.php");
    exit();
}

// Obtener ID de la URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    header('Location: ../index.php');
    exit;
}

// Cargar categoría existente
$all = KategoriaDB::selectKategorienak();
if (!isset($all[$id])) {
    header('Location: ../index.php');
    exit;
}

$current = $all[$id];

// Manejo POST
if (isset($_POST['gorde'])) {
    $izena = trim($_POST['izena'] ?? '');
    $azalpena = trim($_POST['azalpena'] ?? '');

    if ($izena === '') {
        $mezua = 'Mesedez, idatzi kategoria izen bat.';
        include 'kategoria_aldatu.php';
        exit;
    } else {
        KategoriaDB::updateKategoria($id, $izena, $azalpena);
        header('Location: ../index.php');
        exit;
    }
}

include 'kategoria_aldatu.php';
?>