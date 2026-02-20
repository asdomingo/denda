<!DOCTYPE html>
<html lang="eu">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Errorea - Produktua ez da gorde</title>
    </head>
    <body>
        <h1>Administrazio gunea</h1>
        <p><a href="../../index.php">Hasiera</a> &gt;</p>
        <h2>Produktua ez da gorde</h2>
        <p>Ezin izan da produktua gorde. Mesedez egiaztatu datuak eta probatu berriro.</p>
        <?php if (isset($produktua_errorea) && is_array($produktua_errorea)): ?>
            <table cellspacing="5" cellpadding="5" border="1">
                <tr>
                    <td align="right">Izena</td>
                    <td><?php echo htmlspecialchars($produktua_errorea['izena'] ?? ''); ?></td>
                </tr>
                <tr>
                    <td align="right">Prezioa</td>
                    <td><?php echo htmlspecialchars($produktua_errorea['prezioa'] ?? ''); ?></td>
                </tr>
                <tr>
                    <td align="right">Kategoria</td>
                    <td><?php echo htmlspecialchars($produktua_errorea['kategoria_izena'] ?? ($produktua_errorea['kategoria_id'] ?? '')); ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </body>
</html>