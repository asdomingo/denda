<?php
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';

use com\leartik\daw24asdo\mezuak\MezuaDB;

$mezuak = MezuaDB::selectMezuak();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Mezuak Kudeatu | K.O. BOXING</title>
    <style>
        /* --- ESTILOS GENERALES (Siguiendo el patrón de la marca) --- */
        :root {
            --verde-ko: #5cb85c;
            --verde-dark: #4cae4c;
            --rojo-ko: #d9534f;
            --azul-edit: #337ab7;
            --bg-gris: #f4f7f6;
            --texto: #2d3436;
            --blanco: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-gris);
            color: var(--texto);
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 4px solid var(--verde-ko);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        /* --- CONTENEDOR DE TABLA --- */
        .table-container {
            width: 100%;
            max-width: 1100px;
            background: var(--blanco);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Ayuda a controlar anchos de columna */
        }

        th {
            background-color: #f8f9fa;
            color: #636e72;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 15px;
            border-bottom: 2px solid #eee;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            vertical-align: top;
            word-wrap: break-word; /* Para que mensajes largos no rompan la tabla */
        }

        /* Ajuste de anchos de columna */
        th:nth-child(1) { width: 50px; }  /* ID */
        th:nth-child(2) { width: 150px; } /* Izena */
        th:nth-child(3) { width: 200px; } /* Emaila */
        th:nth-child(5) { width: 180px; } /* Ekintzak */

        tr:hover {
            background-color: #f1f8f1;
        }

        /* --- BOTONES DE ACCIÓN --- */
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 5px;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .btn-edit {
            background-color: var(--azul-edit);
            color: white;
        }

        .btn-delete {
            background-color: var(--rojo-ko);
            color: white;
        }

        /* --- ENLACE VOLVER --- */
        .back-link {
            margin-top: 25px;
            text-decoration: none;
            color: var(--texto);
            font-weight: bold;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--verde-ko);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>

    <h1>Mezuak Kudeatu</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Izena</th>
                    <th>Emaila</th>
                    <th>Mezua</th>
                    <th style="text-align: center;">Ekintzak</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($mezuak): ?>
                    <?php foreach ($mezuak as $mezua): ?>
                        <tr>
                            <td><strong><?php echo $mezua->getId(); ?></strong></td>
                            <td><?php echo htmlspecialchars($mezua->getIzena()); ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($mezua->getEmaila()); ?>" style="color: var(--azul-edit);"><?php echo htmlspecialchars($mezua->getEmaila()); ?></a></td>
                            <td><?php echo nl2br(htmlspecialchars($mezua->getMezua())); ?></td>
                            <td style="text-align: center;">
                                <a href="editatu.php?id=<?php echo $mezua->getId(); ?>" class="btn btn-edit">Aldatu</a>
                                <a href="ezabatu.php?id=<?php echo $mezua->getId(); ?>" class="btn btn-delete" onclick="return confirm('Ziur zaude?')">Ezabatu</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">Ez dago mezurik jasota.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="../index.php" class="back-link">← Atzera joan</a>

</body>
</html>