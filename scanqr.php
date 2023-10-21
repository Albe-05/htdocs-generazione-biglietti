<!DOCTYPE html>
<html>
<head>
    <title>Verifica Codice</title>
</head>
<body>
    <h1>Verifica Codice</h1>
    <form method="GET">
        <label for="codice">Inserisci il Codice:</label><br>
        <input type="text" id="codice" name="codice"><br><br>
        <input type="submit" name="scan" value="Verifica">
    </form>
    <br>    
</body>
    <?php
    // Configurazione del database MySQL
    $servername = "127.0.0.1";
    $accesso = "root";
    $password = "";
    $dbname = "biglietto_db";
    if (isset($_GET['scan'])) {
        // Ottenimento del codice dalla richiesta POST
        $codice = $_GET['codice'];

        // Connessione al database MySQL
        $conn = new mysqli($servername, $accesso, $password, $dbname);
        if ($conn->connect_error) {
            die("Connessione al database fallita: " . $conn->connect_error);
        }

        // Query per verificare la presenza del codice nel database
        $sql = "SELECT * FROM biglietti WHERE codice = '$codice'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Il codice è presente nel database
            $row = $result->fetch_assoc();
            $numero = $row['numero'];
            $stato = $row['stato'];

            echo "Il codice $codice è presente nel database.<br>";
            echo "Numero: $numero<br>";
            if($stato == 0){
                echo "Biglietto ancora da vendere";
                ?>
                <br>
                <form method="POST">
                    <input type="hidden" name="recordId" value="1">
                    <input type="submit" name="cambiaStato1" value="Venduto">
                </form>
                <br>
                <form method="POST">
                    <input type="hidden" name="recordId" value="1"> <!--possibilità di dichiarare entrato anche un biglietto in stato 0-->
                    <input type="submit" name="cambiaStato2" value="Entrato">
                </form>
                <?php
            }
            if($stato == 1){
                echo "Biglietto venduto";
                ?>
                <form method="POST">
                    <input type="hidden" name="recordId" value="1">
                    <input type="submit" name="cambiaStato2" value="Entrato">
                </form>
                <?php
            }
            if($stato == 2){
                echo "Già entrato";
            }
        } else {
            // Il codice non è presente nel database
            echo "Il codice $codice non è presente nel database.";
        }

        $conn->close();
    }
    if (isset($_POST['cambiaStato1'])){
        
        $conn = new mysqli($servername, $accesso, $password, $dbname);
        if ($conn->connect_error) {
            die("Connessione al database fallita: " . $conn->connect_error);
        }
        $codice = $_GET['codice'];
        $sql = "UPDATE biglietti SET stato = 1 WHERE codice ='$codice'";
        if ($conn->query($sql) === TRUE) {
            echo "Stato cambiato con successo a 1.";
        } else {
            echo "Errore durante il cambio di stato: " . $conn->error;
        }
        $conn->close();
    }
    
    if (isset($_POST['cambiaStato2'])){

        $conn = new mysqli($servername, $accesso, $password, $dbname);
        if ($conn->connect_error) {
            die("Connessione al database fallita: " . $conn->connect_error);
        }
        $codice = $_GET['codice'];
        $sql = "UPDATE biglietti SET stato = 2 WHERE codice ='$codice'";
        if ($conn->query($sql) === TRUE) {
            echo "Stato cambiato con successo a 2.";
        } else {
            echo "Errore durante il cambio di stato: " . $conn->error;
        }
        $conn->close();
    }
    ?>
<body>
    <br>
    <!--<a href="interface.php?username=<?php echo urlencode($username); ?>">Torna alla pagina precedente</a>-->
    <a href="interface.php">Torna alla pagina precedente</a> <!-- non c'era il valore di username -->
</body>
</html>