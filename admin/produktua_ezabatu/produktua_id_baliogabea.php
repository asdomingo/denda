<?php
?>
<!doctype html>
<html lang="eu">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ID baliogabea - Ezabatu</title>
	<style>body{font-family:Arial,Helvetica,sans-serif;background:#fff;padding:2rem} .notice{max-width:720px;margin:2rem auto;padding:1.5rem;border:1px solid #e0e0e0;border-radius:6px}</style>
</head>
<body>
	<div class="notice">
		<h2>ID baliogabea</h2>
		<p>Emandako produktu IDa ez da balio. Mesedez egiaztatu URL-a edo itzuli <a href="../index.php">kudeaketara</a>.</p>
		<?php if (isset($id) || isset($_GET['id'])): ?>
			<p>Saioa egin den ID-a: <strong><?php echo htmlspecialchars($id ?? $_GET['id']); ?></strong></p>
		<?php endif; ?>
	</div>
</body>
</html>
