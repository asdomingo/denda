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
        <h2>Kategoria berria</h2>
        <p>Kategoria ondo gorde da.</p>
        <table cellspacing="5" cellpadding="5" border="1">
            <tr>
                <td align="right">Izena</td>
                <td><?php echo isset($izena) ? htmlspecialchars($izena, ENT_QUOTES, 'UTF-8') : ''; ?></td>
            </tr>
            <tr>
                <td align="right">Azalpena</td>
                <td><?php echo isset($azalpena) ? nl2br(htmlspecialchars($azalpena, ENT_QUOTES, 'UTF-8')) : ''; ?></td>
            </tr>
        </table>
    </body>
</html>