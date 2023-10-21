<?php
// Connessione al database MySQL
$servername = "127.0.0.1";
$accesso = "root";
$password = "";
$dbname = "biglietto_db";

$conn = new mysqli($servername, $accesso, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Verifica dei dati inseriti nel form di login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query per verificare le credenziali nel database
    $sql = "SELECT * FROM utenti WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        echo "<h2>Benvenuto, $username!</h2>";
        include "interface.php";
    } else {
        include "Provetta.php";
        echo "Credenziali errate,riprova";
    }
}

// Chiusura della connessione al database
$conn->close();
?>