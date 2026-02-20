<?php
session_start();
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';

use com\leartik\daw24asdo\eskariak\EskariaDB;

// Protección básica del panel admin
$admin = false;
if (isset($_SESSION['erabiltzailea']) && $_SESSION['erabiltzailea'] === "admin") {
    $admin = true;
}

if (!$admin) {
    header('Location: ../login.php');
    exit;
}

// Procesar actualización de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_eskaria']) && isset($_POST['estatua'])) {
    $id = (int)$_POST['id_eskaria'];
    $estatua = trim($_POST['estatua']);
    
    if (EskariaDB::updateEstatua($id, $estatua)) {
        $mensaiea = "✓ Estatua eguneratuta!";
    } else {
        $errarea = "✕ Errorea estatuaren egunerakeetan.";
    }
}

// Obtener la eskaria
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$eskaria = EskariaDB::selectEskaria($id);

if (!$eskaria) {
    header('Location: index.php');
    exit;
}

$estatuak = ['Zain', 'Bidalita', 'Jasota', 'Itzulita'];
$estatua_unekoa = $eskaria->getEstatua() ?? 'Zain';
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatua Aldatu | Denda Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --verde-ko: #5cb85c;
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
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: var(--blanco);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 30px;
            text-align: center;
            border-bottom: 3px solid var(--verde-ko);
            padding-bottom: 15px;
        }

        .info-pedido {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid var(--azul-edit);
        }

        .info-pedido p {
            margin: 8px 0;
            font-size: 0.95em;
        }

        .info-pedido strong {
            color: var(--azul-edit);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--texto);
            font-size: 0.95em;
        }

        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        select:hover,
        select:focus {
            border-color: var(--verde-ko);
            outline: none;
        }

        .estatua-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85em;
            margin-top: 10px;
        }

        .estatua-zain {
            background: #fff3cd;
            color: #856404;
        }

        .estatua-bidalita {
            background: #cce5ff;
            color: #004085;
        }

        .estatua-jasota {
            background: #d4edda;
            color: #155724;
        }

        .estatua-itzulita {
            background: #f8d7da;
            color: #721c24;
        }

        .bottak {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        button, .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 0.95em;
        }

        button[type="submit"] {
            background-color: var(--verde-ko);
            color: white;
        }

        button[type="submit"]:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }

        .btn-atzera {
            background-color: #6c757d;
            color: white;
        }

        .btn-atzera:hover {
            opacity: 0.85;
        }

        .mensaiea {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .mensaiea.exito {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensaiea.errore {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pedidoaren Estatua Aldatu</h1>

        <?php if (isset($mensaiea)): ?>
            <div class="mensaiea exito"><?php echo htmlspecialchars($mensaiea); ?></div>
        <?php endif; ?>

        <?php if (isset($errarea)): ?>
            <div class="mensaiea errore"><?php echo htmlspecialchars($errarea); ?></div>
        <?php endif; ?>

        <div class="info-pedido">
            <p><strong>Pedido ID:</strong> #<?php echo $eskaria->getId(); ?></p>
            <p><strong>Bezeroa:</strong> <?php echo htmlspecialchars($eskaria->getIzena() . ' ' . $eskaria->getAbizenak()); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($eskaria->getEmail()); ?></p>
            <p><strong>Uneko Estatua:</strong> 
                <span class="estatua-badge estatua-<?php echo strtolower($estatua_unekoa); ?>">
                    <?php echo htmlspecialchars($estatua_unekoa); ?>
                </span>
            </p>
        </div>

        <form method="POST">
            <input type="hidden" name="id_eskaria" value="<?php echo $eskaria->getId(); ?>">

            <div class="form-group">
                <label for="estatua">Aukeratu Estatua:</label>
                <select id="estatua" name="estatua" required>
                    <option value="">-- Aukeratu Estatua --</option>
                    <?php foreach ($estatuak as $est): ?>
                        <option value="<?php echo htmlspecialchars($est); ?>" 
                                <?php echo ($est === $estatua_unekoa) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($est); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="bottak">
                <button type="submit">Bidali Estatua</button>
                <a href="index.php" class="btn btn-atzera">Atzera</a>
            </div>
        </form>
    </div>
</body>
</html>
