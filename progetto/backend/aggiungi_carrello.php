<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

if (
    !isset($_SESSION['logged']) ||
    $_SESSION["tipologia"] != "fan" ||
    !isset($_POST["id_prodotto"])
) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Accesso non autorizzato']);
    header("Location: ../index.php");
    exit();
}

$prodotto_id = $_POST['id_prodotto'];
$quantita_da_aggiungere = (int)$_POST['quantita'];
$user_id = $_SESSION['username'];

$prodotto_id = mysqli_real_escape_string($cid, $prodotto_id);
$user_id = mysqli_real_escape_string($cid, $user_id);

//quantità totale attuale nel carrello
$query_totale = "SELECT SUM(quantita) AS totale FROM carrello WHERE fan = '$user_id'";
$result_totale = mysqli_query($cid, $query_totale);
$row_totale = mysqli_fetch_assoc($result_totale);
$totale_attuale = (int)$row_totale['totale'];

//quantità corrente di quel prodotto (se già presente)
$query_check = "SELECT quantita FROM carrello WHERE prodotto = '$prodotto_id' AND fan = '$user_id'";
$result_check = mysqli_query($cid, $query_check);

$quantita_attuale_prodotto = 0;
if (mysqli_num_rows($result_check) > 0) {
    $row = mysqli_fetch_assoc($result_check);
    $quantita_attuale_prodotto = (int)$row['quantita'];
}

//controllo per limitare quantita carrello
if ($totale_attuale - $quantita_attuale_prodotto + $quantita_da_aggiungere > 99) {
    http_response_code(400); // errore lato client
    echo json_encode([
        "status" => "error",
        "msg" => "Non puoi avere più di 99 articoli totali nel carrello."
    ]);
    exit;
}

//se già presente, somma la quantità esistente con quella nuova
if ($quantita_attuale_prodotto > 0) {
    $nuova_quantita = $quantita_attuale_prodotto + $quantita_da_aggiungere;
    $query_update = "UPDATE carrello SET quantita = '$nuova_quantita' WHERE prodotto = '$prodotto_id' AND fan = '$user_id'";
    mysqli_query($cid, $query_update);
} else {
    $query_insert = "INSERT INTO carrello (prodotto, fan, quantita) VALUES ('$prodotto_id', '$user_id', '$quantita_da_aggiungere')";
    mysqli_query($cid, $query_insert);
}

//ritorna il numero totale aggiornato (per visualizzazione asincrona di AJAX)
$query = "SELECT SUM(quantita) AS total_items FROM carrello WHERE fan = '$user_id'";
$result = mysqli_query($cid, $query);
$row = mysqli_fetch_assoc($result);
echo $row['total_items'];

?>
