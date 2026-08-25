<?php
session_start(); #inizio la sessione
require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
require "../common/funzioni.php"; #e anche il file che contiene le funzioni

//dati del carrello
$query = "SELECT p.nome, c.quantita, p.prezzo, p.immagine, p.sconto, p.id_prodotto
          FROM carrello c
          JOIN prodotto p ON c.prodotto = p.id_prodotto
          WHERE c.fan = '$_SESSION[username]'";

$result = mysqli_query($cid, $query);
$totale_generale = 0;
$numero_articoli = 0;

if ($_SESSION["page"] == "index") { 
    $url_img = 'uploads/prodotti/';
} else {
    $url_img = '../uploads/prodotti/';
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id_prodotto = $row['id_prodotto'];
        $nome = $row['nome'];
        $quantita = $row['quantita'];
        $prezzo = $row['prezzo'];
        $immagine = $row['immagine'];
        $sconto = $row['sconto'];
        
        $prezzo_scontato = number_format(($prezzo * (100 - $sconto)) / 100, 2, ',', '.');
        $prezzo_scontato= (float) str_replace(',', '.', $prezzo_scontato);
        $totale_item = $prezzo_scontato * $quantita;
        $totale_generale += $totale_item;
        $numero_articoli += $quantita;

        $totale_item =number_format($totale_item, 2, ',', '.');



        echo "<li class='cart-item' data-id='$id_prodotto'>
        <div class='dropdown-item no-hover' style='display: flex; align-items: center; gap: 10px; pointer-events: none;'>
            <img src='$url_img/$immagine' alt='$nome' style='width: 50px; height: auto; pointer-events: auto;'>
            <div style='flex-grow: 1; pointer-events: auto;'>
                <div class='item-name'>$nome</div>
                <div class='item-price'>$totale_item €</div>
                <div>
                    <label>Qta: </label>
<input type='number' class='quantity-input' value='$quantita' min='0' style='width: 50px;' onkeydown='return false;'>
                </div>
            </div>
            <button class='remove-item' style='background: none; border: none; color: red; font-size: 18px; pointer-events: auto;'>🗑️</button>
        </div>
    </li>";

    echo "<li><hr class='my-1'></li>";
    }

    echo "<li style='font-weight: bold; padding: 10px; list-style: none;'>
    Totale: " . number_format($totale_generale, 2, ',', '.') . " € (" . $numero_articoli . " articoli)
    </li>";

    echo "<li style='text-align: center; padding: 10px 0;'>
            <button style='background-color:rgb(9, 154, 173); color: white; border: none;
                        padding: 8px 16px; font-weight: bold; font-size: 15px;
                        border-radius: 5px; cursor: pointer; transition: background-color 0.2s;' data-bs-toggle='modal' data-bs-target='#checkout'>
                Checkout
            </button>
        </li>";
} else {
    echo "<li><a class='dropdown-item' href='#' style='background-color: transparent; color: inherit;'>Carrello vuoto</a></li>";

}
?>