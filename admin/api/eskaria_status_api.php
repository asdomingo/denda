<?php
session_start();
header('Content-Type: application/json');

require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
use com\leartik\daw24asdo\eskariak\EskariaDB;

// Verificar que es admin
if (!isset($_SESSION['erabiltzailea']) || $_SESSION['erabiltzailea'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'Baimenik gabea']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id_eskaria']) ? (int)$_POST['id_eskaria'] : 0;
    $estatua = isset($_POST['estatua']) ? trim($_POST['estatua']) : '';

    if ($id > 0 && !empty($estatua)) {
        $result = EskariaDB::updateEstatua($id, $estatua);
        if ($result) {
            echo json_encode(['success' => true, 'id' => $id, 'estatua' => $estatua]);
        } else {
            error_log("AJAX Status Update Failed: ID=$id, Status=$estatua");
            echo json_encode(['success' => false, 'message' => 'Errorea datu-basea eguneratzean']);
        }
    } else {
        error_log("AJAX Status Update Invalid Data: ID=$id, Status=$estatua");
        echo json_encode(['success' => false, 'message' => 'Datu eskaera baliogabea']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo baliogabea']);
}
