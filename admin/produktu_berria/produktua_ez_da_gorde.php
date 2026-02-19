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
        <p>Produktua ez da gorde.</p>
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
                        if (isset($kategoria_azalpena) && $kategoria_azalpena !== '') {
                            echo '<br><span style="font-style:italic;">' . htmlspecialchars($kategoria_azalpena) . '</span>';
                        }
                    } elseif (isset($kategoria_id)) {
                        echo 'ID: ' . htmlspecialchars($kategoria_id);
                    }
                    ?>
                </td>
            </tr>
        </table>
    </body>
</html>