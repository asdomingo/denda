<?php
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/produktuak/produktua.php';

use com\leartik\daw24asdo\eskariak\EskariaDB;
use com\leartik\daw24asdo\produktuak\ProduktuaDB;

$eskariak = EskariaDB::selectEskariak();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eskariak Kudeatu</title>
</head>
<body>
    <h1>Eskariak</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Bezeroa</th>
            <th>Helbidea</th>
            <th>Produktua</th>
            <th>Kantitatea</th>
            <th>Ekintzak</th>
        </tr>
        <?php if ($eskariak): ?>
            <?php foreach ($eskariak as $eskaria): ?>
                <?php $produktua = ProduktuaDB::selectProduktua($eskaria->getProduktuaId()); ?>
                <tr>
                    <td><?php echo $eskaria->getId(); ?></td>
                    <td><?php echo htmlspecialchars($eskaria->getBezeroa()); ?></td>
                    <td><?php echo htmlspecialchars($eskaria->getHelbidea()); ?></td>
                    <td><?php echo $produktua ? htmlspecialchars($produktua->getIzena()) : 'Produktua ezabatu da'; ?></td>
                    <td><?php echo htmlspecialchars($eskaria->getKantitatea()); ?></td>
                    <td>
                        <a href="editatu.php?id=<?php echo $eskaria->getId(); ?>">Aldatu</a>
                        <a href="ezabatu.php?id=<?php echo $eskaria->getId(); ?>" onclick="return confirm('Ziur zaude?')">Ezabatu</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Ez dago eskaririk.</td></tr>
        <?php endif; ?>
    </table>
    <a href="../../index.php">Atzera</a>
</body>
</html>
