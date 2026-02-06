<?php
require_once '../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
require_once '../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';

use com\leartik\daw24asdo\mezuak\Mezua;
use com\leartik\daw24asdo\mezuak\MezuaDB;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mezua = new Mezua();
    $mezua->setIzena($_POST['izena']);
    $mezua->setEmaila($_POST['emaila']);
    $mezua->setMezua($_POST['mezua']);

    if (MezuaDB::insertMezua($mezua)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
