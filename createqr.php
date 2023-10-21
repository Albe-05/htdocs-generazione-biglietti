<!DOCTYPE html>
<html>
<head>
    <title>Stampa Codice QR</title>
</head>
<body>
    <?php

    // Include la libreria FPDF
    require('fpdf/fpdf.php');

    // Configurazione del database MySQL
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "biglietto_db";
    
    // Ottenimento del contenuto dal form
    $content = generateRandomCode(3);
    // Creazione dell'URL per l'API di QuickChart
    $apiUrl = "https://quickchart.io/qr?text=" . urlencode($content);

    // Salvataggio del contenuto e dell'immagine QR Code nel database MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    $sql = "INSERT INTO biglietti (stato, codice, qrcode) VALUES ( 0,'$content', '$apiUrl')"; //IMPORTANTE LO STATO DEL BIGLIETTO
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Errore durante il salvataggio nel database: " . $conn->error;
    }

    // Chiamata alla funzione per generare il biglietto
    $immagine_qr = $apiUrl; // Sostituisci con il percorso corretto dell'immagine QR
    $testo_biglietto = $content; // Testo da aggiungere al biglietto
    generaBiglietto($immagine_qr, $testo_biglietto);

    $conn->close();

    // Funzione per generare il biglietto come PDF
    function generaBiglietto($immagine_qr, $testo_biglietto) {
        // Carica l'immagine del biglietto
        $file_biglietto = 'biglietto.jpg'; // Sostituisci con il percorso corretto del tuo biglietto
        $biglietto = imagecreatefromjpeg($file_biglietto);

        $qr = imagecreatefromstring(file_get_contents($immagine_qr));
        // Dimensioni del biglietto PDF (in punti)
        $pdf_width = 1172;
        $pdf_height = 759;

        // Coordinate e dimensioni del rettangolo di ritaglio
        $x_crop = 16; // Coordinata X dell'inizio del ritaglio
        $y_crop = 16; // Coordinata Y dell'inizio del ritaglio
        $width_crop = 119; // Larghezza del ritaglio
        $height_crop = 119; // Altezza del ritaglio

        // Esegui il ritaglio dell'immagine
        $qr = imagecrop($qr, ['x' => $x_crop, 'y' => $y_crop, 'width' => $width_crop, 'height' => $height_crop]);

        $nuova_larghezza = 265;
        $nuova_altezza = 265;
        
        // Ingrandisci l'immagine
        $qr = imagescale($qr, $nuova_larghezza, $nuova_altezza);        

        // Sovrapponi l'immagine QR sul biglietto
        $qr_width = 265; 
        $qr_height = 265; 
        $x = 750;
        $y = 250;
        imagecopy($biglietto, $qr, $x, $y, 0, 0, imagesx($qr), imagesy($qr));

        // Aggiungi il testo al biglietto
        $font_size = 45; // Dimensione del font per il testo
        $text_color = imagecolorallocate($biglietto, 255, 255, 255); // Colore del testo (bianco)
        $x_text = 785; // Posizione X del testo
        $y_text = 600; // Posizione Y del testo
        $font = 'arial.ttf'; // Sostituisci con il percorso e il nome del tuo font
        imagettftext($biglietto, $font_size, $angle, $x_text, $y_text, $text_color, $font, $testo_biglietto);
        
        $nome_file = 'seprova.jpg'; // Scegli un nome per il file
        $qualita = 100; // Imposta la qualità dell'immagine (0-100)

        // Salva l'immagine del biglietto come file JPEG
        imagejpeg($biglietto, $nome_file, $qualita);

        // Crea il PDF
        ob_start();
        $pdf = new FPDF('L', 'pt', array($pdf_width, $pdf_height));
        $pdf->AddPage();
        $pdf->Image($nome_file, 0, 0, $pdf_width, $pdf_height); // Sostituisci le dimensioni con quelle del tuo biglietto
        $pdf->Output('biglietto.pdf', 'D'); // Scarica il PDF come file
        ob_end_flush();

        // Pulisci la memoria
        imagedestroy($biglietto);
        imagedestroy($qr);
    }

    function generateRandomCode($length) {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
    ?>
    <h1>Codice QR</h1>
    <?php echo "$content"?>
    <img src="<?php echo $apiUrl; ?>" alt="Codice QR">
    <br><br>
    <a href="interface.php?username=<?php echo urlencode($username); ?>">Torna all'interfaccia</a>
</body>
</html>