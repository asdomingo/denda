<?php
?>
<!doctype html>
<html lang="eu">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Errorea - Albistea ez da ezabatu</title>
	<style>body{font-family:Arial,Helvetica,sans-serif;background:#fff;padding:2rem} .notice{max-width:720px;margin:2rem auto;padding:1.5rem;border:1px solid #dc3545;border-radius:6px;background-color:#f8d7da;color:#721c24}</style>
</head>
<body>
	<div class="notice">
		<h2>❌ Errorea</h2>
		<p>Ezin izan da produktu hau ezabatu. Baliteke produktu hau jadanik ezabatuta egotea edo datu-basean arazo bat egotea.</p>

		<?php if (isset($produktua_errorea) && is_array($produktua_errorea)): ?>
			<p>Arazoan zeuden produktuaren datuak:</p>
			<table border="1" cellpadding="6" cellspacing="0">
				<tr><th align="right">ID</th><td><?php echo htmlspecialchars($produktua_errorea['id'] ?? ''); ?></td></tr>
				<tr><th align="right">Izena</th><td><?php echo htmlspecialchars($produktua_errorea['izena'] ?? ''); ?></td></tr>
				<tr><th align="right">Prezioa</th><td><?php echo htmlspecialchars($produktua_errorea['prezioa'] ?? ''); ?> €</td></tr>
				<tr><th align="right">Kategoria</th><td><?php echo htmlspecialchars($produktua_errorea['kategoria_izena'] ?? ($produktua_errorea['kategoria_id'] ?? '')); ?></td></tr>
			</table>
		<?php endif; ?>

		<p><a href="../../index.php" style="color:#721c24; font-weight:bold;">Itzuli kudeaketara</a></p>
	</div>
</body>
</html>