<?php
// albisteak.php
require '../api_vue/db.php';
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albistenak - Denda</title>
    <link rel="stylesheet" href="css/estiloak.css">
    <style>
        .albistenak-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .albistenak-titulua {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 40px;
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        
        .albiste-lista {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .albiste-txartela {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .albiste-txartela:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        
        .albiste-gorputza {
            padding: 25px;
        }
        
        .albiste-izenburua {
            font-size: 1.5em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .albiste-laburpena {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 0.95em;
        }
        
        .albiste-xehetasunak {
            background: #f8f9fa;
            padding: 15px;
            border-left: 3px solid #007bff;
            margin-bottom: 15px;
            border-radius: 4px;
            color: #555;
            line-height: 1.6;
            max-height: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .albiste-botoia {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
        }
        
        .albiste-botoia:hover {
            background: #0056b3;
        }
        
        .ez-dago-albisterik {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 1.2em;
        }
        
        .errorearen-mezu {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="albistenak-container">
        <h1 class="albistenak-titulua">Albisteen Gunea</h1>
        
        <div class="albiste-lista">
            <?php
                try {
                    // Albistenak lortu databasetik
                    $stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
                    $albistenak = $stmt->fetchAll();
                    
                    if (!empty($albistenak)) {
                        foreach ($albistenak as $albiste) {
                            echo '<div class="albiste-txartela">';
                            echo '<div class="albiste-gorputza">';
                            
                            // Izenburua
                            echo '<h2 class="albiste-izenburua">' . htmlspecialchars($albiste['izenburua']) . '</h2>';
                            
                            // Laburpena
                            echo '<p class="albiste-laburpena">' . htmlspecialchars($albiste['laburpena']) . '</p>';
                            
                            // Xehetasunak
                            if (!empty($albiste['xehetasunak'])) {
                                echo '<div class="albiste-xehetasunak">' . htmlspecialchars($albiste['xehetasunak']) . '</div>';
                            }
                                                      
                            
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div style="grid-column: 1 / -1;" class="ez-dago-albisterik">';
                        echo 'Ez dago albisterik aurkeztutzeko.';
                        echo '</div>';
                    }
                } catch (Exception $e) {
                    echo '<div style="grid-column: 1 / -1;" class="errorearen-mezu">';
                    echo 'Errorea albistenak lortzean: ' . htmlspecialchars($e->getMessage());
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</body>
</html>
