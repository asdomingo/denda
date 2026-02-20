<?php
// produktua_ezabatu.php
session_start();

require('../../klaseak/com/leartik/daw24asdo/produktuak/produktua.php');
require('../../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php');

use com\leartik\daw24asdo\produktuak\ProduktuaDB;
use com\leartik\daw24asdo\produktuak\Produktua;

$admin = false;
if (isset($_SESSION['erabiltzailea']) && $_SESSION['erabiltzailea'] == "admin") {
    $admin = true;
}

if ($admin == false) {
    header("Location: ../index.php");
    exit();
}

// Inicialización de variables
$mensaje_error = "";

// 1. Verificar y cargar el ID del producto desde la URL.
$id_produktua = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id_produktua <= 0) {
    header("Location: produktua_id_baliogabea.php");
    exit;
}

// Cargar el producto existente (para mostrar antes de eliminar)
$produktua_existente = ProduktuaDB::selectProduktua($id_produktua);

if (!$produktua_existente) {
    // El producto con ese ID no existe
    header("Location: produktua_id_baliogabea.php");
    exit;
}

// 2. Lógica para manejar el envío del formulario (cuando se hace POST)
if (isset($_POST['ezabatu'])) {

    // Validar que el ID del formulario coincide con el de la URL
    $id_post = filter_input(INPUT_POST, 'id_produktua', FILTER_VALIDATE_INT);

    // Validación de datos
    if ($id_post === $id_produktua) {

        // Eliminar de la base de datos
        if (ProduktuaDB::deleteProduktua($id_produktua) > 0) {
            header("Location: produktua_ezabatu_da.php");
            exit;
        } else {
            // Si no se afectaron filas, hubo un error
            header("Location: produktua_ez_da_ezabatu.php");
            exit;
        }

    } else {
        // Error de validación: ID incorrecto
        $mensaje_error = "<p style='color:red;'>Errorea: ID ez da zuzena.</p>\n";
    }
}

// Si llegamos aquí, mostramos el formulario de confirmación
include 'produktua_ezabatu.php';
?>