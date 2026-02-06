<?php
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';

use com\leartik\daw24asdo\mezuak\MezuaDB;

$mezuak = MezuaDB::selectMezuak();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mezuak Kudeatu</title>
</head>
<body>
    <h1>Mezuak</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Izena</th>
            <th>Emaila</th>
            <th>Mezua</th>
            <th>Ekintzak</th>
        </tr>
        <?php if ($mezuak): ?>
            <?php foreach ($mezuak as $mezua): ?>
                <tr>
                    <td><?php echo $mezua->getId(); ?></td>
                    <td><?php echo htmlspecialchars($mezua->getIzena()); ?></td>
                    <td><?php echo htmlspecialchars($mezua->getEmaila()); ?></td>
                    <td><?php echo htmlspecialchars($mezua->getMezua()); ?></td>
                    <td>
                        <a href="editatu.php?id=<?php echo $mezua->getId(); ?>">Aldatu</a>
                        <a href="ezabatu.php?id=<?php echo $mezua->getId(); ?>" onclick="return confirm('Ziur zaude?')">Ezabatu</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Ez dago mezurik.</td></tr>
        <?php endif; ?>
    </table>
    <a href="../../index.php">Atzera</a>
</body>
</html>
