<?php
// Mostrar confirmación con datos del producto borrado si están disponibles
?>
<!doctype html>
<html lang="eu">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Produktua ezabatu da</title>
	<style>body{font-family:Arial,Helvetica,sans-serif;background:#fff;padding:2rem} .notice{max-width:720px;margin:2rem auto;padding:1.5rem;border:1px solid #28a745;border-radius:6px;background-color:#d4edda;color:#155724} table{margin-top:1rem}</style>
</head>
<body>
	<div class="notice">
		<h2>✅ Produktua ondo ezabatu da</h2>
		<p>Produktua behar bezala ezabatu da datu-basetik.</p>

		<?php if (isset($produktua_gorde) && is_array($produktua_gorde)): ?>
			<p>Ezabatu den produktuaren datuak:</p>
			<table border="1" cellpadding="6" cellspacing="0">
				<tr><th align="right">ID</th><td><?php echo htmlspecialchars($produktua_gorde['id'] ?? ''); ?></td></tr>
				<tr><th align="right">Izena</th><td><?php echo htmlspecialchars($produktua_gorde['izena'] ?? ''); ?></td></tr>
				<tr><th align="right">Prezioa</th><td><?php echo htmlspecialchars($produktua_gorde['prezioa'] ?? ''); ?> €</td></tr>
				<tr><th align="right">Kategoria</th><td><?php echo htmlspecialchars($produktua_gorde['kategoria_izena'] ?? ($produktua_gorde['kategoria_id'] ?? '')); ?></td></tr>
			</table>
		<?php endif; ?>

		<p><a href="../index.php" style="color:#155724; font-weight:bold;">Itzuli kudeaketara</a></p>
	</div>
</body>
</html>