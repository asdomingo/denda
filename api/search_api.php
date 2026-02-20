<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once __DIR__ . '/../klaseak/com/leartik/daw24asdo/produktuak/produktua.php';
require_once __DIR__ . '/../klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';

use com\leartik\daw24asdo\produktuak\ProduktuaDB as PDB;
use com\leartik\daw24asdo\produktuak\KategoriaDB as KDB;

$term = isset($_GET['q']) ? $_GET['q'] : '';

$produktuak = PDB::searchProduktuak($term);
$kategoriak = KDB::selectKategorienak();

$results = [];

if ($produktuak) {
    foreach ($produktuak as $p) {
        $precioFinal = $p->getDeskontua() > 0 ? $p->getPrezioa() * (1 - $p->getDeskontua() / 100) : $p->getPrezioa();
        $results[] = [
            'id' => $p->getId(),
            'izena' => $p->getIzena(),
            'prezioa' => $p->getPrezioa(),
            'precioFinal' => round($precioFinal, 2),
            'deskontua' => $p->getDeskontua(),
            'irudia' => $p->getIrudia(),
            'kategoria_id' => $p->getKategoriaId(),
            'kategoria_izena' => isset($kategoriak[$p->getKategoriaId()]) ? $kategoriak[$p->getKategoriaId()]['izena'] : 'Kategoria ' . $p->getKategoriaId(),
            'nobedadeak' => $p->getNobedadeak(),
            'eskaintzak' => $p->getEskaintzak()
        ];
    }
}

echo json_encode($results);
?>
