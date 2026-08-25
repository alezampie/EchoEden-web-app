<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

// Controllo accesso
if (!isset($_SESSION['logged']) || $_SESSION["tipologia"] != "fan") {
    $_SESSION['error'] = "Accesso non autorizzato.";
    header("Location: ../index.php");
    exit();
}

$user_id = mysqli_real_escape_string($cid, $_SESSION['username']);

// Recupero prodotti nel carrello
$query = "
    SELECT c.prodotto, c.quantita, p.prezzo, p.sconto, p.artista
    FROM carrello c
    JOIN prodotto p ON c.prodotto = p.id_prodotto
    WHERE c.fan = '$user_id'
";
$result = mysqli_query($cid, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Carrello vuoto o errore nel recupero dei prodotti.";
    header("Location: ../index.php");
    exit();
}

// Raggruppamento per artista
$ordini_per_artista = [];

while ($row = mysqli_fetch_assoc($result)) {
    $artista = $row['artista'];
    $prodotto = $row['prodotto'];
    $quantita = (int) $row['quantita'];
    $prezzo = (float) $row['prezzo'];
    $sconto = (float) $row['sconto'];
    
    $prezzo_scontato = ($prezzo * (100 - $sconto)) / 100;

    $ordini_per_artista[$artista][] = [
        'prodotto' => $prodotto,
        'quantita' => $quantita,
        'prezzo_unitario' => $prezzo,
        'prezzo_scontato' => $prezzo_scontato
    ];
}

$errore_ordini = false;

foreach ($ordini_per_artista as $artista => $prodotti) {
    $totale = 0;
    $quantita_totale = 0;

    foreach ($prodotti as $item) {
        $totale += $item['prezzo_scontato'] * $item['quantita'];
        $quantita_totale += $item['quantita'];
    }

    $queryOrdine = "
        INSERT INTO ordine (totale, data, stato, fan, quantita)
        VALUES ('$totale', NOW(), 'in attesa di conferma', '$user_id', '$quantita_totale')
    ";
    if (!mysqli_query($cid, $queryOrdine)) {
        $errore_ordini = true;
        continue;
    }

    $id_ordine = mysqli_insert_id($cid);

    foreach ($prodotti as $item) {
        $prodotto = $item['prodotto'];
        $quantita = $item['quantita'];
        $prezzo_unitario = $item['prezzo_unitario'];

        $queryDettagli = "
            INSERT INTO dettagli_ordini (prodotto, ordine, quantita, prezzo_unitario)
            VALUES ('$prodotto', '$id_ordine', '$quantita', '$prezzo_unitario')
        ";
        mysqli_query($cid, $queryDettagli);
    }
}

// Svuoto il carrello
mysqli_query($cid, "DELETE FROM carrello WHERE fan = '$user_id'");

// Risultato finale
if ($errore_ordini) {
    $_SESSION['order_error'] = true;
    header("Location: ../index.php");
} else {
    $_SESSION['order_success'] = true;
    header("Location: ../backend/ricerca_ordini-exe.php");
}
exit();
