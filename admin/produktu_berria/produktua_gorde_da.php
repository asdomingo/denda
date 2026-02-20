<!DOCTYPE html>
<html lang="eu">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Albisteak</title>
    </head>
    <body>
        <h1>Administrazio gunea</h1>
        <p><a href="../index.php">Hasiera</a> &gt;</p>
        <h2>Produktua berria</h2>
        <p>Produktua ondo gorde da.</p>
        
        <table cellspacing="5" cellpadding="5" border="1">
            <tr>
                <td align="right">Izena</td>
                <td><?php echo isset($izena) ? htmlspecialchars($izena) : ''; ?></td>
            </tr>
            <tr>
                <td align="right">Prezioa</td>
                <td><?php echo isset($prezioa) ? htmlspecialchars($prezioa) . ' €' : ''; ?></td>
            </tr>
            <tr>
                <td align="right">Kategoria</td>
                <td>
                    <?php
                    if (isset($kategoria_izena)) {
                        echo htmlspecialchars($kategoria_izena);
                    } elseif (isset($kategoria_id)) {
                        echo 'ID: ' . htmlspecialchars($kategoria_id);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td align="right">Nobedadea?</td>
                <td><?php echo (isset($nobedadea) && $nobedadea == 1) ? 'BAI (✨)' : 'EZ'; ?></td>
            </tr>
            <tr>
                <td align="right">Eskaintza</td>
                <td>
                    <?php 
                    if (isset($eskaintza_balioa) && $eskaintza_balioa > 0) {
                        echo htmlspecialchars($eskaintza_balioa) . '% (🔥)';
                    } else {
                        echo 'Ez du eskaintzarik';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </body>
</html>