<!DOCTYPE html>
<html>
<head>
    <title>Benvenuto</title>
</head>
<body>
    <?php
    // Controllo se è presente il parametro "username" nell'URL
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        echo "<h2>Benvenuto, $username!</h2>";
    }
    ?>
    <a href="createqr.php">Crea QR Code</a>
    <br><br>
    <a href="scanqr.php">Scannerizza QR Code</a>
</body>
</html>