<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Produktuen administrazio gunea</title>
        <link rel="stylesheet" href="../css/admin_produktuak.css">
    </head>
    <body>
        <h1>Produktuen administrazio gunea</h1>
        <p>Produktuen zerrenda</p>

        <nav>
            <ul>
                <li><a href="eskariak/">Eskariak kudeatu</a></li>
                <li><a href="mezuak/">Mezuak kudeatu</a></li>
            </ul>
        </nav>

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
        <p><a href="irten.php">Sesiotik irten</a></p>
    </body>
</html>