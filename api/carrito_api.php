<?php
/**
 * Erosketa-saskia AJAX bidez kudeatzeko APIa
 * Eragiketa hauek onartzen ditu: add, remove, update_quantity, get_count, get_items, clear
 */

session_start();
header('Content-Type: application/json');

// Saskia hasieratu existitzen ez bada
if (!isset($_SESSION['karratua'])) {
    $_SESSION['karratua'] = [];
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'message' => 'Ekintza ezaguna'];

try {
    switch ($action) {
        case 'add':
            // Produktua saskira gehitu
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                if (isset($_SESSION['karratua'][$id])) {
                    $_SESSION['karratua'][$id]++;
                } else {
                    $_SESSION['karratua'][$id] = 1;
                }
                $response = [
                    'success' => true,
                    'message' => 'Produktua saskira gehitu da',
                    'count' => array_sum($_SESSION['karratua']),
                    'items' => $_SESSION['karratua']
                ];
            } else {
                $response = ['success' => false, 'message' => 'Produktuaren IDa ez da baliozkoa'];
            }
            break;

        case 'remove':
            // Produktua saskitik kendu
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                if (isset($_SESSION['karratua'][$id])) {
                    unset($_SESSION['karratua'][$id]);
                    $response = [
                        'success' => true,
                        'message' => 'Produktua saskitik kendu da',
                        'count' => array_sum($_SESSION['karratua']),
                        'items' => $_SESSION['karratua']
                    ];
                } else {
                    // Ez badago, "arrakastatzat" jotzen dugu helburua saskian ez egotea zelako
                    $response = [
                        'success' => true, 
                        'message' => 'Produktua ez zegoen saskian',
                        'count' => array_sum($_SESSION['karratua'])
                    ];
                }
            } else {
                $response = ['success' => false, 'message' => 'Produktuaren IDa ez da baliozkoa'];
            }
            break;

        case 'update_quantity':
            // Produktu baten kopurua eguneratu
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if ($id > 0) {
                if ($quantity > 0) {
                    $_SESSION['karratua'][$id] = $quantity;
                    $response = [
                        'success' => true,
                        'message' => 'Kopurua eguneratu da',
                        'count' => array_sum($_SESSION['karratua']),
                        'items' => $_SESSION['karratua']
                    ];
                } else {
                    // Kopurua 0 edo gutxiago bada, produktua ezabatuko dugu
                    unset($_SESSION['karratua'][$id]);
                    $response = [
                        'success' => true,
                        'message' => 'Produktua saskitik kendu da',
                        'count' => array_sum($_SESSION['karratua']),
                        'items' => $_SESSION['karratua']
                    ];
                }
            } else {
                $response = ['success' => false, 'message' => 'Produktuaren IDa ez da baliozkoa'];
            }
            break;

        case 'get_count':
            // Saskiaren kontagailua soilik lortu
            $count = array_sum($_SESSION['karratua']);
            $response = [
                'success' => true,
                'count' => $count,
                'isEmpty' => empty($_SESSION['karratua'])
            ];
            break;

        case 'get_items':
            // Saskiko elementu guztiak lortu
            $response = [
                'success' => true,
                'items' => $_SESSION['karratua'],
                'count' => array_sum($_SESSION['karratua']),
                'isEmpty' => empty($_SESSION['karratua'])
            ];
            break;

        case 'clear':
            // Saskia hustu
            $_SESSION['karratua'] = [];
            $response = [
                'success' => true,
                'message' => 'Saskia hustu da',
                'count' => 0,
                'items' => []
            ];
            break;

        default:
            $response = ['success' => false, 'message' => 'Ekintza ezaguna: ' . $action];
    }
} catch (Exception $e) {
    $response = ['success' => false, 'message' => 'Errorea: ' . $e->getMessage()];
}

echo json_encode($response);
exit;
?>