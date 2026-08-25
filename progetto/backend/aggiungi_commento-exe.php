<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true || $_SESSION["tipologia"] !== "fan") {
    //accesso non consentito
    header("Location: ../index.php");
    exit();
}

if (!fanPuòCommentare($cid, $fan, $prodotto)) {
    $errore_commento["msg"] = "Hai già commentato questo prodotto o hai raggiunto il limite di 5 commenti totali.";
    $errore_commento["tipo"] = 3;
    header('Location: ../frontend/aggiungi_commento.php?status=ko&errore_commento=' . serialize($errore_commento));
    exit();
}

$errore_commento = array();

//dati inviati dal form
$fan = $_SESSION["username"];
$prodotto = isset($_POST["prodotto"]) ? intval($_POST["prodotto"]) : 0;
$descrizione = trim($_POST["descrizione"]);
$voto = isset($_POST["voto"]) ? intval($_POST["voto"]) : -1;

//validazione base
if ($prodotto <= 0 || empty($descrizione) || strlen($descrizione) > 500 || $voto < 0 || $voto > 5) {
    $errore_commento["msg"] = "Dati del commento non validi.";
    $errore_commento["tipo"] = 2;
    header('Location: ../frontend/aggiungi_commento.php?status=ko&errore_commento=' . serialize($errore_commento));
    exit();
}

//inserimento nel DB
$ris = aggiungiCommento($cid, $fan, $prodotto, $descrizione, $voto);

if ($ris["status"] == "ok") {
    if (isset($_SESSION["page"]) && $_SESSION["page"] == "aggiungi_commento_from_index") {
        header("Location: ../index.php?id=$prodotto&status=ok");
    } else {
        header("Location: ../frontend/ricerca.php?id=$prodotto&status=ok");
    }
} else {
    $errore_commento["msg"] = $ris["msg"];
    $errore_commento["tipo"] = $ris["tipo_errore"];
    header('Location: ../frontend/aggiungi_commento.php?status=ko&errore_commento=' . serialize($errore_commento));
}
