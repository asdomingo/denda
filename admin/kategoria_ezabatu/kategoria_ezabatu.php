<?php
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Kategoria ezabatu</title>
    <link rel="stylesheet" href="../../css/ezabatu.css"> 
</head>
<body>
    <h1>Kategoria ezabatu</h1>

    <?php if (isset($mezua)) { echo '<p style="color:red;">' . htmlspecialchars($mezua, ENT_QUOTES, 'UTF-8') . '</p>'; } ?>

    <p>Ziur al zaude <strong><?php echo isset($current['izena']) ? htmlspecialchars($current['izena'], ENT_QUOTES, 'UTF-8') : ''; ?></strong> kategoria ezabatu nahi duzula?</p>
    <?php if (!empty($current['azalpena'])) { ?>
        <p><em><?php echo nl2br(htmlspecialchars($current['azalpena'], ENT_QUOTES, 'UTF-8')); ?></em></p>
    <?php } ?>

    <?php if (isset($products_count) && $products_count > 0): ?>
        <p style="color:darkred; font-weight:bold;">Kontuz: kategoria hau ezabatzean <strong><?php echo (int)$products_count; ?></strong> produktu ere ezabatuko dira.</p>
    <?php endif; ?>

    <form method="post" action="?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="submit" name="ezabatu" value="Bai, ezabatu">
        <a href="../../index.php">Ez, itzuli</a>
    </form>
</body>
</html>
