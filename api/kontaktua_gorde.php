<?php
require_once '../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
require_once '../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';

use com\leartik\daw24asdo\mezuak\Mezua;
use com\leartik\daw24asdo\mezuak\MezuaDB;

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mezua = new Mezua();
        $mezua->setIzena($_POST['izena'] ?? '');
        $mezua->setEmaila($_POST['emaila'] ?? '');
        $mezua->setMezua($_POST['mezua'] ?? '');
        
        if (!empty($_POST['izena']) && !empty($_POST['emaila']) && !empty($_POST['mezua'])) {
            if (MezuaDB::insertMezua($mezua)) {
                echo json_encode(['success' => true, 'message' => 'Mezua gorde da']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errorea mezu gorde']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Eremu guztiak bete behar dituzu']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'POST metodoa behar']);
    }
} catch (Exception $e) {
    error_log("Kontaktua error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Errorea konexioan: ' . $e->getMessage()]);
}
?>
