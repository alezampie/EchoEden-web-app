<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

if (
    !isset($_SESSION['logged']) ||
    $_SESSION["tipologia"] != "artista" ||
    !isset($_POST["id_ordine"])
) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Accesso non autorizzato']);
    header("Location: ../index.php");
    exit();
}

$id_ordine = $_POST['id_ordine'];


//controllo se l'ordine esiste
$query_check = "SELECT * FROM ordine WHERE id_ordine = $id_ordine";
$result_check = mysqli_query($cid, $query_check);

$query = "UPDATE ordine SET stato = 'confermato' WHERE id_ordine = $id_ordine";

if (mysqli_query($cid, $query)) {
    //se l'ordine è stato aggiornato
    if (mysqli_affected_rows($cid) > 0) {
        $_SESSION['conferma_success'] = true;
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