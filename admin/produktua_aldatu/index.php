<?php
// produktua_aldatu.php
// Objetivo: Permitir la edición de un producto existente.
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
    header("Location: ../../index.php");
    exit();
}

// Inicialización de variables
$mensaje_error = "";
$mensaje_berrespena = "";
$erakutsi_berrespena = false;

// 1. Verificar y cargar el ID del producto desde la URL.
$id_produktua = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id_produktua <= 0) {
    header("Location: ../../index.php?message=" . urlencode("Errorea: Editatzeko produktu IDa ez da aurkitu."));
    exit;
}

// Cargar el producto existente (datu zaharrak)
$produktua_existente = ProduktuaDB::selectProduktua($id_produktua);

if (!$produktua_existente) {
    // El producto con ese ID no existe
    header("Location: ../../index.php?message=" . urlencode("Errorea: Editatzeko produktua ez da aurkitu."));
    exit;
}

// 2. Lógica para manejar el envío del formulario (cuando se hace POST)
if (isset($_POST['gorde'])) {

    // Recoger datos del formulario
    $izena_berria = trim($_POST['izena'] ?? '');
    $prezioa_berria = trim($_POST['prezioa'] ?? '');
    $kategoria_berria = filter_input(INPUT_POST, 'kategoria_id', FILTER_VALIDATE_INT);

    // Validar que el ID del formulario coincide con el de la URL
    $id_post = filter_input(INPUT_POST, 'id_produktua', FILTER_VALIDATE_INT);

    // Validación de datos: izena not empty, prezioa numeric, kategoria valid int
    $prezioa_valid = is_numeric($prezioa_berria);
    if (strlen($izena_berria) > 0 && $prezioa_valid && $kategoria_berria > 0 && $id_post === $id_produktua) {

        // Crear objeto Produktua con los datos actualizados
        $produktua = new Produktua();
        $produktua->setId($id_post);
        $produktua->setIzena($izena_berria);
        $produktua->setPrezioa((float)$prezioa_berria);
        $produktua->setKategoriaId($kategoria_berria);

        // Actualizar en la base de datos
        if (ProduktuaDB::updateProduktua($produktua) > 0) {
            $mensaje_berrespena = "Produktua ondo eguneratu da.";
        } else {
            $mensaje_berrespena = "Produktua ez da eguneratu (baliteke datuak berdinak izatea edo errore bat gertatu izana).";
        }

        // En lugar de redirigir, mostramos la tabla de confirmación
        $erakutsi_berrespena = true;
        $produktua_existente = $produktua; // Usamos el objeto actualizado para mostrar los datos nuevos

    } else {
        // Error de validación: campos vacíos o ID incorrecto.
        $mensaje_error = "<p style='color:red;'>Errorea: Eremu guztiak zuzen bete behar dira.</p>\n";
        // Para que el formulario muestre los datos incorrectos que el usuario introdujo:
        $produktua_existente->setIzena($izena_berria);
        $produktua_existente->setPrezioa($prezioa_berria);
        $produktua_existente->setKategoriaId($kategoria_berria);
    }
}
?>
<?php
// Incluir la plantilla que muestra el formulario / confirmación
require_once __DIR__ . '/produktua_aldatu.php';
