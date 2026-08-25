<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

if (
    !isset($_SESSION['logged']) ||
    $_SESSION["tipologia"] != "fan" ||
    !isset($_POST["id_ordine"])
) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Accesso non autorizzato']);
    header("Location: ../index.php");
    exit();
}


$id_ordine = $_POST['id_ordine'];
$username = $_SESSION['username'] ?? null;


//coontrollo se ordine esiste
$query_check = "SELECT * FROM ordine WHERE id_ordine = $id_ordine";
$result_check = mysqli_query($cid, $query_check);


$query = "DELETE FROM ordine WHERE id_ordine = $id_ordine ";

if (mysqli_query($cid, $query)) {
    //se l'ordine è stato aggiornato
    if (mysqli_affected_rows($cid) > 0) {
        $_SESSION['annulla_success'] = true;
        //redirigo alla pagina degli ordini dopo aver annullato l'ordine
        header('location:../backend/ricerca_ordini-exe.php?status=ok');
        exit();
    } else {
        echo "Debug: Nessuna riga aggiornata";
    }
} else {
    echo "Debug: Errore nell'esecuzione della query: " . mysqli_error($cid);
}
?>
