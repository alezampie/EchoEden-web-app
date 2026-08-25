<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true || $_SESSION["tipologia"] !== "fan") {
    header("Location: ../index.php");
    exit();
}

$errore_commento = array();

//dati dal form
$fan = $_SESSION["username"];
$prodotto = isset($_POST["prodotto"]) ? intval($_POST["prodotto"]) : 0;
$data_commento = isset($_POST["data_commento"]) ? $_POST["data_commento"] : '';
$descrizione = trim($_POST["descrizione"]);
$voto = isset($_POST["voto"]) ? intval($_POST["voto"]) : -1;

if ($prodotto <= 0 || empty($descrizione) || strlen($descrizione) > 500 || $voto < 0 || $voto > 5 || empty($data_commento)) {
    $errore_commento["msg"] = "Dati del commento non validi.";
    $errore_commento["tipo"] = 2;
    header('Location: ../frontend/modifica_commento.php?id_prodotto=' . $prodotto . '&data_commento=' . urlencode($data_commento) . '&status=ko&errore_commento=' . serialize($errore_commento));
    exit();
}

$ris = modificaCommento($cid, $fan, $prodotto, $data_commento, $descrizione, $voto);

if ($ris["status"] == "ok") {
    if (isset($_SESSION["page"]) && $_SESSION["page"] == "modifica_commento_from_index") {
        header("Location: ../index.php?id=$prodotto&status=ok");
    } else {
        header("Location: ../frontend/ricerca.php?id=$prodotto&status=ok");
    }
} else {
    $errore_commento["msg"] = $ris["msg"];
    $errore_commento["tipo"] = $ris["tipo_errore"];
    header('Location: ../frontend/modifica_commento.php?id_prodotto=' . $prodotto . '&data_commento=' . urlencode($data_commento) . '&status=ko&errore_commento=' . serialize($errore_commento));
}
