<?php
require_once __DIR__ . '/klaseak/com/leartik/daw24asdo/produktuak/kategoria_db.php';
use com\leartik\daw24asdo\produktuak\KategoriaDB;

// Inicializar carrito si no existe
if (!isset($_SESSION['karratua'])) {
    $_SESSION['karratua'] = [];
}
$carritoCount = array_sum($_SESSION['karratua']);
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktuak</title>
    <link rel="stylesheet" href="css/produktuak.css">
    <link rel="stylesheet" href="css/carrito.css">
    <script src="jquery-3.7.1.js"></script>
</head>
<body>
    <header class="header produktuak">
        <img src="img/logo.png" alt="Logo" class="logo">
        <h1>Produktuak</h1>
    </header>

    <nav class="nav-katalogoa">
        <a href="index.php">Hasiera</a>
        <a href="mediateka.php">Mediateka</a> 
        <a href="kontaktua.php">Kontaktua</a> 
    </nav>

    <?php if ($carritoCount > 0): ?>
        <a href="eskaria.php" class="carrito-icon" id="cart-link">
            <img src="img/carrito.png" alt="Carrito">
            <span id="carrito-contador" style="display: block;"><?php echo $carritoCount; ?></span>
        </a>
    <?php else: ?>
        <a href="javascript:void(0);" class="carrito-icon" id="cart-link" onclick="alert('Saskia hutsik dago. Produktuak gehitu katalogotik.');">
            <img src="img/carrito.png" alt="Carrito">
            <span id="carrito-contador" style="display: none;">0</span>
        </a>
    <?php endif; ?>

    <div class="search-container">
        <input type="text" id="search-input" placeholder="Bilatu produktuak...">
    </div>

    <div class="container-katalogoa" id="results-container">
        <?php
        if (!isset($produktuak) || !is_array($produktuak)) {
            echo "<p style='text-align:center;'>Produkurik ez dago.</p>";
        } else {
            $nombres_kategorias = KategoriaDB::selectKategorienak();

            $by_kategoria = [];
            foreach ($produktuak as $p) {
                $kid = $p->getKategoriaId();
                if (!isset($by_kategoria[$kid])) $by_kategoria[$kid] = [];
                $by_kategoria[$kid][] = $p;
            }
            ksort($by_kategoria);

            foreach ($by_kategoria as $kid => $list) {
                $izena = isset($nombres_kategorias[$kid]) ? $nombres_kategorias[$kid]['izena'] : "Kategoria " . $kid;

                echo '<section class="section">';
                echo '<h2>' . htmlspecialchars($izena, ENT_QUOTES, 'UTF-8') . '</h2>';
                echo '<div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">'; 
                
                foreach ($list as $produktua) {
                    $id = $produktua->getId();
                    $ruta_imagen = 'img/' . $id . '.jpg';
                    $link = 'produktua_erakutsi.php?id=' . urlencode($id);

                    echo '<article class="produktua-item">';
                    
                    echo '<div class="badges-container">';
                    if ($produktua->getNobedadeak() == 1) {
                        echo '<span class="badge badge-new">BERRIA</span> ';
                    }
                    if ($produktua->getEskaintzak() > 0) {
                        $texto_oferta = ($produktua->getEskaintzak() > 1) ? $produktua->getEskaintzak() . '% OFF' : 'ESKAINTZA';
                        echo '<span class="badge badge-offer">' . $texto_oferta . '</span>';
                    }
                    echo '</div>';

                    echo '<a href="' . $link . '">';
                    echo '<img src="' . htmlspecialchars($ruta_imagen) . '" alt="' . htmlspecialchars($produktua->getIzena()) . '">';
                    echo '</a>';
                    echo '<div class="produktua-info">';
                    echo '<dl>';
                    echo '<dt><a href="' . $link . '">' . htmlspecialchars($produktua->getIzena(), ENT_QUOTES, 'UTF-8') . '</a></dt>';
                    echo '<dd>';
                    if ($produktua->getDeskontua() > 0) {
                        echo '<span style="text-decoration: line-through;">' . number_format($produktua->getPrezioa(), 2, ',', '.') . '€</span> <strong style="color: #e74c3c;">' . number_format($produktua->getPrezioaDeskontuarekin(), 2, ',', '.') . '€</strong>';
                    } else {
                        echo number_format($produktua->getPrezioa(), 2, ',', '.') . ' €';
                    }
                    echo '</dd>';
                    echo '</dl>';
                    echo '<a href="javascript:void(0);" class="btn-saskira" onclick="window.carritos.agregarProducto(' . $id . '); return false;">Gehitu saskira</a>';
                    echo '</div>';
                    echo '</article>';
                }
                echo '</div>';
                echo '</section>';
            }
        }
        ?>
    </div>

    <script src="js/carrito.js"></script>
    <script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            var term = $(this).val();
            $.ajax({
                url: 'api/search_api.php',
                type: 'GET',
                data: { q: term },
                dataType: 'json',
                success: function(data) {
                    var container = $('#results-container');
                    container.empty();

                    if (data.length === 0) {
                        container.append('<p style="text-align:center; width:100%;">Ez da produkturik aurkitu.</p>');
                        return;
                    }

                    var byCategory = {};
                    data.forEach(function(p) {
                        if (!byCategory[p.kategoria_id]) {
                            byCategory[p.kategoria_id] = {
                                izena: p.kategoria_izena,
                                produktuak: []
                            };
                        }
                        byCategory[p.kategoria_id].produktuak.push(p);
                    });

                    var keys = Object.keys(byCategory).sort();

                    keys.forEach(function(kid) {
                        var cat = byCategory[kid];
                        var sectionSql = '<section class="section">';
                        sectionSql += '<h2>' + cat.izena + '</h2>';
                        sectionSql += '<div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">';
                        
                        cat.produktuak.forEach(function(p) {
                            var imgPath = 'img/' + p.id + '.jpg';
                            var link = 'produktua_erakutsi.php?id=' + encodeURIComponent(p.id);
                            
                            var itemHtml = '<article class="produktua-item">';
                            itemHtml += '<div class="badges-container">';
                            if (p.nobedadeak == 1) {
                                itemHtml += '<span class="badge badge-new">BERRIA</span> ';
                            }
                            if (p.eskaintzak > 0) {
                                var textoOferta = (p.eskaintzak > 1) ? p.eskaintzak + '% OFF' : 'ESKAINTZA';
                                itemHtml += '<span class="badge badge-offer">' + textoOferta + '</span>';
                            }
                            itemHtml += '</div>';
                            itemHtml += '<a href="' + link + '"><img src="' + imgPath + '" alt="' + p.izena + '"></a>';
                            itemHtml += '<div class="produktua-info"><dl>';
                            itemHtml += '<dt><a href="' + link + '">' + p.izena + '</a></dt>';
                            itemHtml += '<dd>';
                            if (p.deskontua > 0) {
                                var precioFinal = p.precioFinal || (p.prezioa * (1 - p.deskontua / 100)).toFixed(2);
                                itemHtml += '<span style="text-decoration: line-through;">' + p.prezioa + '€</span> <strong style="color: #e74c3c;">' + precioFinal + '€</strong>';
                            } else {
                                itemHtml += p.prezioa + ' €';
                            }
                            itemHtml += '</dd>
                            itemHtml += '</dl><a href="javascript:void(0);" class="btn-saskira" onclick="window.carritos.agregarProducto(' + p.id + '); return false;">Gehitu saskira</a></div>';
                            itemHtml += '</article>';
                            
                            sectionSql += itemHtml;
                        });
                        
                        sectionSql += '</div></section>';
                        container.append(sectionSql);
                    });
                }
            });
        });
    });
    </script>
</body>
</html>