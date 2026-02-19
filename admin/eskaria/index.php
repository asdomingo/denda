<?php
session_start();
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/produktuak/produktua.php';

use com\leartik\daw24asdo\eskariak\EskariaDB;
use com\leartik\daw24asdo\produktuak\ProduktuaDB;

// Protección básica del panel admin
$admin = false;
if (isset($_SESSION['erabiltzailea']) && $_SESSION['erabiltzailea'] === "admin") {
    $admin = true;
}

if (!$admin) {
    header('Location: ../login.php');
    exit;
}

$eskariak = EskariaDB::selectEskariak();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eskariak Kudeatu | Denda Admin</title>
    <style>
        /* --- ESTILOS GENERALES --- */
        :root {
            --verde-ko: #5cb85c;
            --verde-dark: #4cae4c;
            --rojo-ko: #d9534f;
            --azul-edit: #337ab7;
            --bg-gris: #f4f7f6;
            --texto: #2d3436;
            --blanco: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-gris);
            color: var(--texto);
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 4px solid var(--verde-ko);
            padding-bottom: 15px;
            margin-bottom: 30px;
            color: var(--texto);
        }

        /* --- TABLA --- */
        .table-container {
            background: var(--blanco);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            background-color: #34495e;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85rem;
            border-bottom: 2px solid var(--verde-ko);
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        tbody td {
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        tbody td strong {
            color: var(--verde-ko);
        }

        /* --- INFORMACIÓN DEL CLIENTE --- */
        .client-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .client-field {
            flex: 1;
            min-width: 150px;
        }

        .client-field-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .client-field-value {
            font-size: 0.95rem;
            color: var(--texto);
            word-break: break-word;
        }

        /* --- BOTONES --- */
        .btn {
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: bold;
            display: inline-block;
            margin-right: 5px;
            transition: opacity 0.2s, transform 0.1s;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }

        .btn-primary {
            background-color: var(--verde-ko);
            color: white;
        }

        .btn-edit {
            background-color: var(--azul-edit);
            color: white;
        }

        .btn-delete {
            background-color: var(--rojo-ko);
            color: white;
        }

        .btn-delete:hover {
            background-color: #c9423f;
        }

        /* --- ESTATUA BADGES --- */
        .estatua-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .estatua-zain {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .estatua-prozesatzen {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .estatua-bidalita {
            background: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .estatua-jasota {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .estatua-ezeztatua {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .estatua-itzulita {
            background: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }

        /* --- ACCIONES --- */
        .acciones {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        /* --- ENLACES DE NAVEGACIÓN --- */
        .nav-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
        }

        .nav-links a {
            background-color: var(--verde-ko);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 5px;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .nav-links a:hover {
            background-color: var(--verde-dark);
        }

        .back-link {
            color: var(--texto);
            text-decoration: none;
            font-weight: bold;
            font-size: 0.95rem;
        }

        .back-link:hover {
            color: var(--verde-ko);
        }

        /* --- MENSAJES --- */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            table {
                font-size: 0.85rem;
            }

            thead th,
            tbody td {
                padding: 10px;
            }

            .client-info {
                gap: 10px;
            }

            .acciones {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .table-container {
                padding: 10px;
            }
        }

        /* --- DETALLES EXPANDIBLES --- */
        .details-row {
            display: none;
            background-color: #f5f5f5;
        }

        .details-row.show {
            display: table-row;
        }

        .details-content {
            padding: 20px;
            background-color: #fafafa;
        }

        .field-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .field {
            background-color: var(--blanco);
            padding: 15px;
            border-left: 3px solid var(--verde-ko);
            border-radius: 4px;
        }

        .field-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .field-value {
            font-size: 1rem;
            color: var(--texto);
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Eskariak Kudeatu</h1>

        <div class="table-container">
            <?php if (empty($eskariak)): ?>
                <div class="alert alert-info">
                    Ez dago eskaririk erregistratuta sistema berean.
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Izena eta Abizenak</th>
                            <th>Email</th>
                            <th>Telefonoa</th>
                            <th>Produktua</th>
                            <th>Kantitatea</th>
                            <th>Estatua</th>
                            <th>Data</th>
                            <th>Ekintzak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $pedidosAgrupados = [];
                        foreach ($eskariak as $eskaria) {
                            $key = $eskaria->getId();
                            if (!isset($pedidosAgrupados[$key])) {
                                $pedidosAgrupados[$key] = [];
                            }
                            $pedidosAgrupados[$key][] = $eskaria;
                        }

                        foreach ($pedidosAgrupados as $peridoId => $items): ?>
                            <?php $primerItem = $items[0]; ?>
                            <tr>
                                <td><strong>#<?php echo $primerItem->getId(); ?></strong></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($primerItem->getIzena() . ' ' . $primerItem->getAbizenak()); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($primerItem->getEmail()); ?></td>
                                <td><?php echo htmlspecialchars($primerItem->getTelefono()); ?></td>
                                <td>
                                    <?php 
                                    $productos = [];
                                    foreach ($items as $item) {
                                        $prod = ProduktuaDB::selectProduktua($item->getProduktuaId());
                                        if ($prod) {
                                            $productos[] = htmlspecialchars($prod->getIzena());
                                        }
                                    }
                                    echo implode(', ', $productos);
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $totalCant = 0;
                                    foreach ($items as $item) {
                                        $totalCant += $item->getKantitatea();
                                    }
                                    echo $totalCant;
                                    ?>
                                </td>
                                <td id="status-cell-<?php echo $primerItem->getId(); ?>">
                                    <?php 
                                    $estatua = $primerItem->getEstatua() ?? 'Zain';
                                    $badgeClass = 'estatua-zain';
                                    if ($estatua === 'Bidalita') $badgeClass = 'estatua-bidalita';
                                    elseif ($estatua === 'Jasota') $badgeClass = 'estatua-jasota';
                                    elseif ($estatua === 'Itzulita') $badgeClass = 'estatua-itzulita';
                                    ?>
                                    <span class="estatua-badge <?php echo $badgeClass; ?>">
                                        <?php echo htmlspecialchars($estatua); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($primerItem->getData())); ?></td>
                                <td class="acciones">
                                    <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                        <form method="POST" style="display: contents;" onsubmit="return updateEstatuaAJAX(this, <?php echo $primerItem->getId(); ?>)">
                                            <input type="hidden" name="id_eskaria" value="<?php echo $primerItem->getId(); ?>">
                                            <input type="hidden" name="estatua" id="estatua_input_<?php echo $primerItem->getId(); ?>">
                                            <?php if ($estatua === 'Zain'): ?>
                                                <button type="submit" name="cambiar_estado" value="Prozesatzen" class="btn btn-primary" style="background-color: #17a2b8;" onclick="document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Prozesatzen'">Prozesatu</button>
                                            <?php endif; ?>
                                            
                                            <?php if ($estatua === 'Prozesatzen'): ?>
                                                <button type="submit" name="cambiar_estado" value="Bidalita" class="btn btn-primary" style="background-color: #007bff;" onclick="document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Bidalita'">Bidali</button>
                                            <?php endif; ?>
                                            
                                            <?php if ($estatua === 'Bidalita'): ?>
                                                <button type="submit" name="cambiar_estado" value="Jasota" class="btn btn-primary" style="background-color: #28a745;" onclick="document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Jasota'">Osatu</button>
                                            <?php endif; ?>

                                            <?php if ($estatua !== 'Jasota' && $estatua !== 'Ezeztatua'): ?>
                                                <button type="submit" name="cambiar_estado" value="Ezeztatua" class="btn btn-delete" onclick="if(confirm('Ziur zaude ezeztatu nahi duzula?')) { document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Ezeztatua'; return true; } return false;">Ezeztatu</button>
                                            <?php endif; ?>
                                        </form>
                                        <a href="editatu.php?id=<?php echo $primerItem->getId(); ?>" class="btn btn-edit">Aldatu</a>
                                        <a href="ezabatu.php?id=<?php echo $primerItem->getId(); ?>" class="btn btn-delete" onclick="return confirm('Ziur zaude?')">Ezabatu</a>
                                        <button class="btn btn-primary" onclick="toggleDetails(<?php echo $primerItem->getId(); ?>)">Xehetasunak</button>
                                    </div>
                                </td>
                            </tr>
                            <tr id="details-<?php echo $primerItem->getId(); ?>" class="details-row">
                                <td colspan="8">
                                    <div class="details-content">
                                        <h3>Xehetasunak - Faktura #<?php echo $primerItem->getId(); ?></h3>
                                        <div class="field-group">
                                            <div class="field">
                                                <div class="field-label">Izena eta Abizenak</div>
                                                <div class="field-value"><?php echo htmlspecialchars($primerItem->getIzena() . ' ' . $primerItem->getAbizenak()); ?></div>
                                            </div>
                                            <div class="field">
                                                <div class="field-label">Email</div>
                                                <div class="field-value"><a href="mailto:<?php echo htmlspecialchars($primerItem->getEmail()); ?>"><?php echo htmlspecialchars($primerItem->getEmail()); ?></a></div>
                                            </div>
                                            <div class="field">
                                                <div class="field-label">Telefonoa</div>
                                                <div class="field-value"><a href="tel:<?php echo htmlspecialchars($primerItem->getTelefono()); ?>"><?php echo htmlspecialchars($primerItem->getTelefono()); ?></a></div>
                                            </div>
                                            <div class="field">
                                                <div class="field-label">Data Pedidoa</div>
                                                <div class="field-value"><?php echo date('d/m/Y H:i:s', strtotime($primerItem->getData())); ?></div>
                                            </div>
                                        </div>

                                        <div class="field-group">
                                            <div class="field" style="grid-column: 1/-1;">
                                                <div class="field-label">Helbidea Osoa</div>
                                                <div class="field-value">
                                                    <?php echo htmlspecialchars($primerItem->getHelbidea()); ?><br>
                                                    <?php echo htmlspecialchars($primerItem->getPostapostala()); ?> - <?php echo htmlspecialchars($primerItem->getHiria()); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($primerItem->getNotak()): ?>
                                            <div class="field-group">
                                                <div class="field" style="grid-column: 1/-1;">
                                                    <div class="field-label">Notak Bezeroa</div>
                                                    <div class="field-value"><?php echo htmlspecialchars($primerItem->getNotak()); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <h4 style="margin-top: 20px; margin-bottom: 10px;">Produktuak</h4>
                                        <table style="width: 100%; border: 1px solid #ddd;">
                                            <thead>
                                                <tr style="background-color: #f5f5f5;">
                                                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Produktua</th>
                                                    <th style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">Prezioa</th>
                                                    <th style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">Kopurua</th>
                                                    <th style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">Guztira</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $totalGuztira = 0;
                                                foreach ($items as $item): 
                                                    $prod = ProduktuaDB::selectProduktua($item->getProduktuaId());
                                                    $subtotal = ($prod ? $prod->getPrezioa() : 0) * $item->getKantitatea();
                                                    $totalGuztira += $subtotal;
                                                ?>
                                                    <tr>
                                                        <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                                            <?php echo $prod ? htmlspecialchars($prod->getIzena()) : '<em>Produktu Ezabatuta</em>'; ?>
                                                        </td>
                                                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">
                                                            <?php echo $prod ? number_format($prod->getPrezioa(), 2, ',', '.') : '0,00'; ?> €
                                                        </td>
                                                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: center;">
                                                            <?php echo $item->getKantitatea(); ?>
                                                        </td>
                                                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">
                                                            <?php echo number_format($subtotal, 2, ',', '.'); ?> €
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr style="background-color: #f0f0f0; font-weight: bold;">
                                                    <td colspan="3" style="padding: 10px; text-align: right;">GUZTIRA:</td>
                                                    <td style="padding: 10px; text-align: right;"><?php echo number_format($totalGuztira, 2, ',', '.'); ?> €</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="nav-links">
            <a href="../index.php">← Atzera joan Admin Panelera</a>
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const detailsRow = document.getElementById('details-' + id);
            if (detailsRow) {
                detailsRow.classList.toggle('show');
            }
        }

        function updateEstatuaAJAX(form, id) {
            const formData = new FormData(form);
            const cell = document.getElementById('status-cell-' + id);
            const container = cell.closest('tr').querySelector('.acciones form');
            
            // Efecto visual de carga
            cell.style.opacity = '0.5';
            if (container) container.style.opacity = '0.5';
            
            // Use the relative path to the API (one level up as we are in admin/eskaria/)
            fetch('../api/eskaria_status_api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateUIAfterStatusChange(id, data.estatua);
                } else {
                    alert('Errorea: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errorea eskaera bidaltzean');
            })
            .finally(() => {
                cell.style.opacity = '1';
                if (container) container.style.opacity = '1';
            });
            
            return false;
        }

        function updateUIAfterStatusChange(id, estatua) {
            const cell = document.getElementById('status-cell-' + id);
            const container = cell.closest('tr').querySelector('.acciones form');
            
            let badgeClass = 'estatua-' + estatua.toLowerCase().replace(' ', '-');
            cell.innerHTML = `<span class="estatua-badge ${badgeClass}">${estatua}</span>`;
            
            let html = `
                <input type="hidden" name="id_eskaria" value="${id}">
                <input type="hidden" name="estatua" id="estatua_input_${id}">
            `;

            if (estatua === 'Zain') {
                html += `<button type="submit" name="cambiar_estado" value="Prozesatzen" class="btn btn-primary" style="background-color: #17a2b8;" onclick="document.getElementById('estatua_input_${id}').value='Prozesatzen'">Prozesatu</button>`;
            } else if (estatua === 'Prozesatzen') {
                html += `<button type="submit" name="cambiar_estado" value="Bidalita" class="btn btn-primary" style="background-color: #007bff;" onclick="document.getElementById('estatua_input_${id}').value='Bidalita'">Bidali</button>`;
            } else if (estatua === 'Bidalita') {
                html += `<button type="submit" name="cambiar_estado" value="Jasota" class="btn btn-primary" style="background-color: #28a745;" onclick="document.getElementById('estatua_input_${id}').value='Jasota'">Osatu</button>`;
            }

            if (estatua !== 'Jasota' && estatua !== 'Ezeztatua') {
                html += `<button type="submit" name="cambiar_estado" value="Ezeztatua" class="btn btn-delete" onclick="if(confirm('Ziur zaude ezeztatu nahi duzula?')) { document.getElementById('estatua_input_${id}').value='Ezeztatua'; return true; } return false;">Ezeztatu</button>`;
            }

            if (container) container.innerHTML = html;
        }
    </script>
</body>
</html>