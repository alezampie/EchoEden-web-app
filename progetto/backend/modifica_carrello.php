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

$id_prodotto = $_POST['id_prodotto'];
$quantita = (int) $_POST['quantita'];
$fan = mysqli_real_escape_string($cid, $_SESSION['username']);
$id_prodotto = mysqli_real_escape_string($cid, $id_prodotto);

//se quantità = 0 elimino
if ($quantita == 0) {
    $query = "DELETE FROM carrello WHERE fan = '$fan' AND prodotto = '$id_prodotto'";
    mysqli_query($cid, $query);
} else {
    //quantità totale attuale
    $query_totale = "SELECT SUM(quantita) AS totale FROM carrello WHERE fan = '$fan'";
    $result_totale = mysqli_query($cid, $query_totale);
    $row_totale = mysqli_fetch_assoc($result_totale);
    $totale_attuale = (int)$row_totale['totale'];

    //quantità attuale di quel prodotto
    $query_attuale = "SELECT quantita FROM carrello WHERE fan = '$fan' AND prodotto = '$id_prodotto'";
    $result_attuale = mysqli_query($cid, $query_attuale);
    $quantita_attuale_prodotto = 0;
    if (mysqli_num_rows($result_attuale) > 0) {
        $row = mysqli_fetch_assoc($result_attuale);
        $quantita_attuale_prodotto = (int)$row['quantita'];
    }

    //controllo limite
    $nuovo_totale = $totale_attuale - $quantita_attuale_prodotto + $quantita;
    if ($nuovo_totale > 99) {
        http_response_code(400);
        echo "Non puoi superare 99 articoli totali nel carrello.";
        exit;
    }

    
    $query = "UPDATE carrello SET quantita = $quantita WHERE fan = '$fan' AND prodotto = '$id_prodotto'";
    mysqli_query($cid, $query);
}

//nuovo totale per badge
$query = "SELECT SUM(quantita) AS totale FROM carrello WHERE fan = '$fan'";
$result = mysqli_query($cid, $query);
$row = mysqli_fetch_assoc($result);
echo $row['totale'] ?: 0;

?>
