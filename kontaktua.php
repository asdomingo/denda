<!DOCTYPE html>
<html>
<head>
    <title>Kontaktua</title>
    <script>
        function bidaliKontaktua(event) {
            event.preventDefault();
            
            var formData = new FormData(document.getElementById('kontaktuForm'));
            
            fetch('api/kontaktua_gorde.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('mezua').innerHTML = "<p style='color:green'>Mezua ondo bidali da!</p>";
                    document.getElementById('kontaktuForm').reset();
                } else {
                    document.getElementById('mezua').innerHTML = "<p style='color:red'>Errorea mezua bidaltzean.</p>";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('mezua').innerHTML = "<p style='color:red'>Errorea konexioan.</p>";
            });
        }
    </script>
</head>
<body>
    <h1>Kontaktua</h1>
    <div id="mezua"></div>
    <form id="kontaktuForm" onsubmit="bidaliKontaktua(event)">
        <label>Izena:</label><br>
        <input type="text" name="izena" required><br>
        <label>Emaila:</label><br>
        <input type="email" name="emaila" required><br>
        <label>Mezua:</label><br>
        <textarea name="mezua" required></textarea><br>
        <button type="submit">Bidali</button>
    </form>
    <a href="index.php">Hasiera</a>
</body>
</html>
