<?php
// albisteak.php
require '../api_vue/db.php';
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albisteak - K.O. BOXING</title>
    <style>
        /* Importar fuente similar a la del diseño */
        
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Cabecera con el estilo de la imagen */
        .header-box {
            background: white;
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #4CAF50; /* Línea verde superior */
            margin-bottom: 40px;
        }

        .header-box img {
            max-width: 250px;
        }

        .albistenak-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .albiste-lista {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        /* Tarjeta principal */
        .albiste-txartela {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            padding: 30px;
            border: 1px solid #eee;
            position: relative;
        }

        /* El borde lateral de color que aparece en la imagen */
        .borde-lateral {
            position: absolute;
            left: 20px;
            top: 35px;
            bottom: 35px;
            width: 4px;
            border-radius: 2px;
        }

        .verde { background-color: #4CAF50; }
        .azul { background-color: #007bff; }

        .albiste-gorputza {
            padding-left: 15px; /* Espacio para el borde lateral */
        }

        .albiste-izenburua {
            font-size: 1.4em;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 15px;
            line-height: 1.2;
            text-align: left;
        }

        .albiste-laburpena {
            color: #555;
            line-height: 1.5;
            margin-bottom: 15px;
            font-size: 0.95em;
        }

        /* Caja gris de detalles */
        .albiste-xehetasunak {
            background: #f1f3f5;
            padding: 15px;
            border-radius: 4px;
            color: #666;
            font-size: 0.85em;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        /* Botón estilo K.O. Boxing */
        .albiste-botoia {
            align-self: flex-start;
            background: #007bff;
            color: white;
            padding: 8px 18px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.85em;
            transition: opacity 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-verde { background: #4CAF50; }
        .btn-azul { background: #007bff; }

        .albiste-botoia:hover {
            opacity: 0.8;
        }

        .ez-dago-albisterik {
            text-align: center;
            grid-column: 1 / -1;
            padding: 50px;
            color: #888;
        }
    </style>
</head>
<body>

    <header class="header-box">
        <div class="header logo"><img src="https://asdo-s3.s3.eu-north-1.amazonaws.com/logo.png" alt="Denda Logo" class="logo"></div>
        <div class="nav">
            <a href="katalogoa.php">Katalogoa</a>
            <a href="mediateka.php">Mediateka</a>
            <a href="kontaktua.php">Kontaktua</a>
            <a href="albisteak.php">Albisteak</a>            
        </div>
    </header>

    <div class="albistenak-container">
        <div class="albiste-lista">
            <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
                    $albistenak = $stmt->fetchAll();
                    
                    if (!empty($albistenak)) {
                        $count = 0;
                        foreach ($albistenak as $albiste) {
                            // Alternar colores entre verde y azul como en la imagen
                            $esPar = ($count % 2 == 0);
                            $colorClase = $esPar ? 'verde' : 'azul';
                            $btnClase = $esPar ? 'btn-verde' : 'btn-azul';
                            
                            echo '<div class="albiste-txartela">';
                                // Borde decorativo lateral
                                echo '<div class="borde-lateral ' . $colorClase . '"></div>';
                                
                                echo '<div class="albiste-gorputza">';
                                    echo '<h2 class="albiste-izenburua">' . htmlspecialchars($albiste['izenburua']) . '</h2>';
                                    echo '<p class="albiste-laburpena">' . htmlspecialchars($albiste['laburpena']) . '</p>';
                                    
                                    if (!empty($albiste['xehetasunak'])) {
                                        echo '<div class="albiste-xehetasunak">' . htmlspecialchars($albiste['xehetasunak']) . '</div>';
                                    }
                                    
                                    echo '</div>';
                            echo '</div>';
                            $count++;
                        }
                    } else {
                        echo '<div class="ez-dago-albisterik">Ez dago albisterik aurkezteko.</div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="ez-dago-albisterik">Errorea: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            ?>
        </div>
    </div>
</body>
</html>