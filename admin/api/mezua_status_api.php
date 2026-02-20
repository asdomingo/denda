<?php
session_start();
header('Content-Type: application/json');

require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
use com\leartik\daw24asdo\mezuak\MezuaDB;

// Verificar que es admin
if (!isset($_SESSION['erabiltzailea']) || $_SESSION['erabiltzailea'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'Baimenik gabea']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id_mezua']) ? (int)$_POST['id_mezua'] : 0;
    $estatua = isset($_POST['estatua']) ? trim($_POST['estatua']) : '';

    if ($id > 0 && !empty($estatua)) {
        if (MezuaDB::updateEstatua($id, $estatua)) {
            echo json_encode(['success' => true, 'id' => $id, 'estatua' => $estatua]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errorea datu-basea eguneratzean']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datu eskaera baliogabea']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo baliogabea']);
}
