<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktuak</title>
    <link rel="stylesheet" href="../css/login.css"> 
</head>

<body>
    <h1>Administrazio gunea</h1>
    <p><?php echo $mezua ?></p>
    <form action="" method="post">
        <p>
            <label for="erabiltzailea">Erabiltzailea</label>
            <input type="text" name="erabiltzailea" id="erabiltzailea">
        </p>
        <p>
            <label for="pasahitza">Pasahitza</label>
            <input type="password" name="pasahitza" id="pasahitza">
        </p>
        <p>
            <input type="submit" name="sartu" value="Sartu">
        </p>
    </form>
</body>

</html>