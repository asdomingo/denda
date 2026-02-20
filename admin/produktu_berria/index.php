<?php

session_start();

require('../../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php');
require('../../klaseak/com/leartik/daw24asdo/produktuak/produktua.php');

use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\produktuak\Produktua;

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

        $izena = $_POST['izena'];
        $prezioa = $_POST['prezioa'];
        $kategoria_id = $_POST['kategoria_id'];

        if (strlen($izena) > 0 && strlen($prezioa) > 0 && strlen($kategoria_id) > 0) {

            $produktua = new Produktua();
            $produktua->setIzena($izena);
            $produktua->setPrezioa($prezioa);
            $produktua->setKategoriaId($kategoria_id);

            $nobedadea = isset($_POST['nobedadea']) ? 1 : 0;
            $eskaintza_balioa = (isset($_POST['eskaintza_bai']) && !empty($_POST['eskaintza_kopurua'])) 
                                ? (int)$_POST['eskaintza_kopurua'] 
                                : 0;

            $produktua->setNobedadeak($nobedadea);
            $produktua->setEskaintzak($eskaintza_balioa);
            


            if (ProduktuaDB::insertProduktua($produktua) > 0) {
                include('produktua_gorde_da.php');
            } else {
                include('produktua_ez_da_gorde.php');
            }

        } else {
            $mezua = 'Eremu guztiak bete behar dira.';
            include('produktu_berria.php');
        }

    } else {
        
        $izena = "";
        $prezioa = "";
        $kategoria_id = "";
        $mezua = "";

        include('produktu_berria.php');
    }

} else {
    header('Location: ../index.php');
    exit();
}
