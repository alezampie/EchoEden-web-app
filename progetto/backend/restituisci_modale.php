<?php
session_start(); #inizio la sessione
require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
require "../common/funzioni.php"; #e anche il file che contiene le funzioni


$query = "SELECT p.nome, c.quantita, p.prezzo, p.immagine, p.sconto, p.id_prodotto
          FROM carrello c
          JOIN prodotto p ON c.prodotto = p.id_prodotto
          WHERE c.fan = '$_SESSION[username]'";

$result = mysqli_query($cid, $query);
$totale_generale = 0;
$numero_articoli = 0;

if ($_SESSION["page"] == "index") { 
    $url_img = 'uploads/prodotti/';
} else  {
    $url_img = '../uploads/prodotti/';
}


while ($row = mysqli_fetch_assoc($result)) {
    $id_prodotto = $row['id_prodotto'];
    $nome = $row['nome'];
    $quantita = $row['quantita'];
    $prezzo = $row['prezzo'];
    $immagine = $row['immagine'];
    $sconto = $row['sconto'];

    $prezzo_scontato = number_format(($prezzo * (100 - $sconto)) / 100, 2, ',', '.');
    $prezzo_scontato_val = (float) str_replace(',', '.', $prezzo_scontato);
    $totale_item = $prezzo_scontato_val * $quantita;
    $totale_generale += $totale_item;
    $numero_articoli += $quantita;

    echo "
    <div class='cart-item border-bottom py-2 d-flex align-items-center' data-id='$id_prodotto'>
        <img src='$url_img/$immagine' alt='$nome' class='me-3' style='width: 60px; height: auto; border-radius: 5px;'>
        <div class='flex-grow-1 overflow-hidden'>
            <div class='fw-bold text-truncate' style='max-width: 100%;'>$nome</div>
            <div class='text-muted'>Qta: $quantita</div>
        </div>
        <div class='text-end fw-semibold' style='min-width: 80px; white-space: nowrap;'>
            " . number_format($totale_item, 2, ',', '.') . " €
        </div>
    </div>";
    
}

echo "
<div class='pt-3 text-center fw-bold'>
    Totale: " . number_format($totale_generale, 2, ',', '.') . " € (" . $numero_articoli . " articoli)
</div>";


?>