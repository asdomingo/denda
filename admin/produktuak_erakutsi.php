<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Produktuen administrazio gunea</title>
        <link rel="stylesheet" href="../css/admin_produktuak.css">
        <style>
            .admin-section {
                background: white;
                padding: 20px;
                border-radius: 8px;
                margin-top: 20px;
            }

            .admin-section h2 {
                margin-bottom: 15px;
                color: #333;
            }

            .admin-table {
                width: 100%;
                border-collapse: collapse;
            }

            .admin-table th {
                background: #34495e;
                color: white;
                padding: 12px;
                text-align: left;
                font-weight: bold;
                font-size: 0.9rem;
                text-transform: uppercase;
            }

            .admin-table td {
                padding: 12px;
                border-bottom: 1px solid #eee;
            }

            .admin-table tr:hover {
                background: #f8f9fa;
            }

            .estatua-badge {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 15px;
                font-weight: bold;
                font-size: 0.8rem;
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

            .btn-status {
                padding: 4px 8px;
                font-size: 0.75rem;
                border-radius: 4px;
                cursor: pointer;
                border: 1px solid transparent;
                transition: all 0.2s;
                font-weight: 600;
            }

            .btn-prozesatu { background: #17a2b8; color: white; }
            .btn-bidali { background: #007bff; color: white; }
            .btn-osatu { background: #28a745; color: white; }
            .btn-ezeztatu { background: #dc3545; color: white; }

            .estatua-berria { background: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
            .estatua-irakurrita { background: #f5f5f5; color: #616161; border: 1px solid #e0e0e0; }
            .estatua-erantzunda { background: #e8f5e9; color: #1b5e20; border: 1px solid #c8e6c9; }
            
            .btn-irakurri { background: #6c757d; color: white; }
            .btn-erantzun { background: #6f42c1; color: white; }

            .btn-status:hover {
                opacity: 0.8;
                transform: scale(1.03);
            }

            .btn-small {
                padding: 6px 12px;
                margin: 2px;
                font-size: 0.85rem;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
            }

            .btn-primary-small {
                background: #5cb85c;
                color: white;
            }

            .btn-edit-small {
                background: #337ab7;
                color: white;
            }

            .btn-delete-small {
                background: #d9534f;
                color: white;
            }

            .no-data {
                text-align: center;
                padding: 30px;
                color: #999;
                font-size: 1.1rem;
            }

            /* Estilos para formularios en tablas */
            form select {
                padding: 5px 8px;
                border: 1px solid #ddd;
                border-radius: 3px;
                font-size: 0.9rem;
            }

            form button {
                margin: 0 !important;
                padding: 5px 12px !important;
                font-size: 0.85rem !important;
            }
        </style>
    </head>
    <body>
        <header class="header">            
            <h1>Produktuen administrazio gunea</h1>
        </header>

        <div id="produktuak">
        <h3>Produktuen zerrenda</h3>
        <?php
        
        if (isset($produktuak_by_kategoria) && is_array($produktuak_by_kategoria) && count($produktuak_by_kategoria) > 0) {
            
            ksort($produktuak_by_kategoria);

            foreach ($produktuak_by_kategoria as $kategoria_id => $produktuak_list) {

                $kategoria_izena = isset($nombres_kategorias[$kategoria_id]) ? $nombres_kategorias[$kategoria_id] : "Kategoria $kategoria_id";
                ?>

                <h2>
                    <strong><?php echo htmlspecialchars($kategoria_izena, ENT_QUOTES, 'UTF-8'); ?></strong>
                    &nbsp;[<a href="kategoria_aldatu/?id=<?php echo (int)$kategoria_id; ?>">Aldatu</a>]
                    &nbsp;[<a href="kategoria_ezabatu/?id=<?php echo (int)$kategoria_id; ?>">Ezabatu</a>]
                </h2>
                <?php if (empty($produktuak_list)): ?>
                    <p><em>Ez da produkturik kategoria honetan.</em></p>
                <?php else: ?>
                    <ul class="lista-admin-produktuak">
                        <?php foreach ($produktuak_list as $p) { 
                            $id = $p->getId();
                            $ruta_imagen = '../img/' . $id . '.jpg'; // <-- RUTA DE LA IMAGEN
                            ?>
                            <li class="produktua-admin-item">
                                <div class="produktua-info-admin">
                                    <img src="<?php echo htmlspecialchars($ruta_imagen); ?>" alt="<?php echo htmlspecialchars($p->getIzena()); ?>" class="produktua-thumb">
                                    <span><?php echo htmlspecialchars($p->getIzena(), ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                
                                <div class="produktua-actions-admin">
                                    [<a href="produktua_aldatu/?id=<?php echo $p->getId(); ?>">Aldatu</a>]
                                    [<a href="produktua_ezabatu/?id=<?php echo $p->getId(); ?>">Ezabatu</a>]
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php endif; ?>

            <?php }

        } else {
            
            if (!isset($produktuak) || !is_array($produktuak)) {
                echo "<p>Produkurik ez dago.</p>";
            } else {
                echo '<ul class="lista-admin-produktuak">';
                foreach ($produktuak as $p) {
                    $id = $p->getId();
                    $ruta_imagen = '../img/' . $id . '.jpg'; // <-- RUTA DE LA IMAGEN
                    echo '<li class="produktua-admin-item">';
                    
                    echo '<div class="produktua-info-admin">';
                    echo '<img src="' . htmlspecialchars($ruta_imagen) . '" alt="' . htmlspecialchars($p->getIzena()) . '" class="produktua-thumb">';
                    echo '<span>' . htmlspecialchars($p->getIzena(), ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '</div>'; 
                    
                    echo '<div class="produktua-actions-admin">';
                    echo '[<a href="produktua_aldatu/?id=' . $p->getId() . '">Aldatu</a>] ';
                    echo '[<a href="produktua_ezabatu/?id=' . $p->getId() . '">Ezabatu</a>]';
                    echo '</div>';
                    
                    echo '</li>';
                }
                echo '</ul>';
            }
        }
        ?>
        <form action="produktu_berria/" method="post">
            <p><input type="submit" name="berria" value="Produktu berria"></p>
        </form>

        <form action="kategoria_sortu/" method="post">
            <p><input type="submit" name="berria" value="Kategoria berria"></p>
        </form>
        </div>

        <hr style="margin: 40px 0; border: none; border-top: 2px solid #ddd;">

        <?php if (isset($mensaiea_exito)): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                <?php echo htmlspecialchars($mensaiea_exito); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($mensaiea_error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                <?php echo htmlspecialchars($mensaiea_error); ?>
            </div>
        <?php endif; ?>

        <div class="admin-section">
            <h2>Eskariak Kudeatu</h2>
            <?php if (empty($eskariak)): ?>
                    <div class="no-data">Ez dago eskaririk erregistratuta sistema berean.</div>
                <?php else: ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bezeroa</th>
                                <th>Emaila</th>
                                <th>Telefonoa</th>
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
                                    $pedidosAgrupados[$key] = $eskaria;
                                }
                            }

                            foreach ($pedidosAgrupados as $id => $primerItem): 
                                $estatua = $primerItem->getEstatua() ?? 'Zain';
                                $badgeClass = 'estatua-zain';
                                if ($estatua === 'Bidalita') $badgeClass = 'estatua-bidalita';
                                elseif ($estatua === 'Jasota') $badgeClass = 'estatua-jasota';
                                ?>
                                <tr>
                                    <td><strong>#<?php echo $primerItem->getId(); ?></strong></td>
                                    <td><?php echo htmlspecialchars($primerItem->getIzena() . ' ' . $primerItem->getAbizenak()); ?></td>
                                    <td><?php echo htmlspecialchars($primerItem->getEmail()); ?></td>
                                    <td><?php echo htmlspecialchars($primerItem->getTelefono()); ?></td>
                                    <td id="status-cell-<?php echo $primerItem->getId(); ?>">
                                        <div style="display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
                                            <span class="estatua-badge <?php echo 'estatua-' . strtolower(str_replace(' ', '-', $estatua)); ?>">
                                                <?php echo htmlspecialchars($estatua); ?>
                                            </span>
                                            
                                            <form method="POST" style="display: inline-flex; gap: 4px;" onsubmit="return updateEstatuaAJAX(this, <?php echo $primerItem->getId(); ?>)">
                                                <input type="hidden" name="id_eskaria" value="<?php echo $primerItem->getId(); ?>">
                                                <input type="hidden" name="estatua" id="estatua_input_<?php echo $primerItem->getId(); ?>">
                                                
                                                <?php if ($estatua === 'Zain'): ?>
                                                    <button type="submit" name="cambiar_estado_eskaria" value="Prozesatzen" class="btn-status btn-prozesatu" onclick="document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Prozesatzen'">Prozesatu</button>
                                                <?php endif; ?>
                                                
                                                <?php if ($estatua === 'Prozesatzen'): ?>
                                                    <button type="submit" name="cambiar_estado_eskaria" value="Bidalita" class="btn-status btn-bidali" onclick="document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Bidalita'">Bidali</button>
                                                <?php endif; ?>
                                                
                                                <?php if ($estatua === 'Bidalita'): ?>
                                                    <button type="submit" name="cambiar_estado_eskaria" value="Jasota" class="btn-status btn-osatu" onclick="document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Jasota'">Osatu</button>
                                                <?php endif; ?>

                                                <?php if ($estatua !== 'Jasota' && $estatua !== 'Ezeztatua'): ?>
                                                    <button type="submit" name="cambiar_estado_eskaria" value="Ezeztatua" class="btn-status btn-ezeztatu" onclick="if(confirm('Ziur zaude eskaria ezeztatu nahi duzula?')) { document.getElementById('estatua_input_<?php echo $primerItem->getId(); ?>').value='Ezeztatua'; return true; } return false;">Ezeztatu</button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($primerItem->getData())); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id_eskaria" value="<?php echo $primerItem->getId(); ?>">
                                            <button type="submit" name="eliminar_eskaria" class="btn-small btn-delete-small" onclick="return confirm('Ziur zaude ezabatzea nahi duzu?')">Ezabatu</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="admin-section">
            <h2>Mezuak Kudeatu</h2>
            <?php if (empty($mezuak)): ?>
                    <div class="no-data">Ez dago mezurik jasota.</div>
                <?php else: ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Egoera</th>
                                <th>ID</th>
                                <th>Izena / Emaila</th>
                                <th>Mezua / Data</th>
                                <th>Ekintzak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mezuak as $mezua): ?>
                                <tr>
                                    <td id="status-mezua-cell-<?php echo $mezua->getId(); ?>">
                                        <div style="display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
                                            <span class="estatua-badge <?php echo 'estatua-' . strtolower($mezua->getEstatua()); ?>">
                                                <?php echo htmlspecialchars($mezua->getEstatua()); ?>
                                            </span>
                                            
                                            <form method="POST" style="display: inline-flex; gap: 4px;" onsubmit="return updateMezuaEstatuaAJAX(this, <?php echo $mezua->getId(); ?>)">
                                                <input type="hidden" name="id_mezua" value="<?php echo $mezua->getId(); ?>">
                                                <input type="hidden" name="estatua" id="estatua_mezua_input_<?php echo $mezua->getId(); ?>">
                                                
                                                <?php if ($mezua->getEstatua() === 'Berria'): ?>
                                                    <button type="submit" name="cambiar_estado_mezua" value="Irakurrita" class="btn-status btn-irakurri" onclick="document.getElementById('estatua_mezua_input_<?php echo $mezua->getId(); ?>').value='Irakurrita'">Irakurri</button>
                                                <?php endif; ?>
                                                
                                                <?php if ($mezua->getEstatua() !== 'Erantzunda'): ?>
                                                    <button type="submit" name="cambiar_estado_mezua" value="Erantzunda" class="btn-status btn-erantzun" onclick="document.getElementById('estatua_mezua_input_<?php echo $mezua->getId(); ?>').value='Erantzunda'">Erantzun</button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </td>
                                    <td><strong>#<?php echo $mezua->getId(); ?></strong></td>
                                    <td>
                                        <div style="font-weight: bold;"><?php echo htmlspecialchars($mezua->getIzena()); ?></div>
                                        <div style="font-size: 0.85rem;"><a href="mailto:<?php echo htmlspecialchars($mezua->getEmaila()); ?>"><?php echo htmlspecialchars($mezua->getEmaila()); ?></a></div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.9rem; margin-bottom: 5px;"><?php echo nl2br(htmlspecialchars(substr($mezua->getMezua(), 0, 150))); ?>...</div>
                                        <div style="font-size: 0.8rem; color: #777;">
                                            <?php echo $mezua->getData() ? date('d/m/Y H:i', strtotime($mezua->getData())) : '---'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id_mezua" value="<?php echo $mezua->getId(); ?>">
                                            <button type="submit" name="eliminar_mezua" class="btn-small btn-delete-small" onclick="return confirm('Ziur zaude mezua ezabatu nahi duzula?')">Ezabatu</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <p style="margin-top: 20px;"><a href="irten.php">Sesiotik irten</a></p>

        <script>
            function updateEstatuaAJAX(form, id) {
                const formData = new FormData(form);
                const estatua = formData.get('estatua');
                const cell = document.getElementById('status-cell-' + id);
                
                // Efecto visual de carga
                cell.style.opacity = '0.5';
                
                fetch('api/eskaria_status_api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Simular el cambio en el DOM para que sea instantáneo
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
                });
                
                return false; // Evitar recarga de página convencional
            }

            function updateUIAfterStatusChange(id, estatua) {
                const cell = document.getElementById('status-cell-' + id);
                let badgeClass = 'estatua-' + estatua.toLowerCase().replace(' ', '-');
                
                let html = `
                    <div style="display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
                        <span class="estatua-badge ${badgeClass}">${estatua}</span>
                        <form method="POST" style="display: inline-flex; gap: 4px;" onsubmit="return updateEstatuaAJAX(this, ${id})">
                            <input type="hidden" name="id_eskaria" value="${id}">
                            <input type="hidden" name="estatua" id="estatua_input_${id}">
                `;

                if (estatua === 'Zain') {
                    html += `<button type="submit" name="cambiar_estado_eskaria" value="Prozesatzen" class="btn-status btn-prozesatu" onclick="document.getElementById('estatua_input_${id}').value='Prozesatzen'">Prozesatu</button>`;
                } else if (estatua === 'Prozesatzen') {
                    html += `<button type="submit" name="cambiar_estado_eskaria" value="Bidalita" class="btn-status btn-bidali" onclick="document.getElementById('estatua_input_${id}').value='Bidalita'">Bidali</button>`;
                } else if (estatua === 'Bidalita') {
                    html += `<button type="submit" name="cambiar_estado_eskaria" value="Jasota" class="btn-status btn-osatu" onclick="document.getElementById('estatua_input_${id}').value='Jasota'">Osatu</button>`;
                }

                if (estatua !== 'Jasota' && estatua !== 'Ezeztatua') {
                    html += `<button type="submit" name="cambiar_estado_eskaria" value="Ezeztatua" class="btn-status btn-ezeztatu" onclick="if(confirm('Ziur zaude eskaria ezeztatu nahi duzula?')) { document.getElementById('estatua_input_${id}').value='Ezeztatua'; return true; } return false;">Ezeztatu</button>`;
                }

                html += `
                        </form>
                    </div>
                `;
                
                cell.innerHTML = html;
            }

            function updateMezuaEstatuaAJAX(form, id) {
                const formData = new FormData(form);
                const estatua = formData.get('estatua');
                const cell = document.getElementById('status-mezua-cell-' + id);
                
                cell.style.opacity = '0.5';
                
                fetch('api/mezua_status_api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateMezuaUIAfterStatusChange(id, data.estatua);
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
                });
                
                return false;
            }

            function updateMezuaUIAfterStatusChange(id, estatua) {
                const cell = document.getElementById('status-mezua-cell-' + id);
                let badgeClass = 'estatua-' + estatua.toLowerCase();
                
                let html = `
                    <div style="display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
                        <span class="estatua-badge ${badgeClass}">${estatua}</span>
                        <form method="POST" style="display: inline-flex; gap: 4px;" onsubmit="return updateMezuaEstatuaAJAX(this, ${id})">
                            <input type="hidden" name="id_mezua" value="${id}">
                            <input type="hidden" name="estatua" id="estatua_mezua_input_${id}">
                `;

                if (estatua === 'Berria') {
                    html += `<button type="submit" name="cambiar_estado_mezua" value="Irakurrita" class="btn-status btn-irakurri" onclick="document.getElementById('estatua_mezua_input_${id}').value='Irakurrita'">Irakurri</button>`;
                }
                
                if (estatua !== 'Erantzunda') {
                    html += `<button type="submit" name="cambiar_estado_mezua" value="Erantzunda" class="btn-status btn-erantzun" onclick="document.getElementById('estatua_mezua_input_${id}').value='Erantzunda'">Erantzun</button>`;
                }

                html += `
                        </form>
                    </div>
                `;
                
                cell.innerHTML = html;
            }
        </script>
    </body>
</html>