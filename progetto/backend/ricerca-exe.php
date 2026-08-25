<?php
// ricerca-exe.php

session_start();
require "../common/setup.php";
require "../common/funzioni.php";

$parametro = isset($_GET['search']) ? $_GET['search'] : '';
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';
$prezzo_min = isset($_GET['prezzo_min']) ? (float)$_GET['prezzo_min'] : 0;
$prezzo_max = isset($_GET['prezzo_max']) ? (float)$_GET['prezzo_max'] : 1000;
$sconto = isset($_GET['sconto']) ? $_GET['sconto'] : false;

$prodotti = getFilteredProducts($cid, 'prodotto', $parametro, $categorie, $prezzo_min, $prezzo_max, $sconto);

$_SESSION["prodotti"] = $prodotti;
$_SESSION["parametro"] = $parametro;
$_SESSION["categorie"] = $categorie;
$_SESSION["prezzo_min"] = $prezzo_min;
$_SESSION["prezzo_max"] = $prezzo_max;
$_SESSION["sconto"] = $sconto; 


$queryParams = http_build_query([
    'search' => $parametro,
    'categorie' => $categorie,
    'prezzo_min' => $prezzo_min,
    'prezzo_max' => $prezzo_max,
    'sconto' => $sconto ? 1 : 0  // 'sconto' sarà 1 se true, 0 se false
]);


if ((isset($_SESSION["logged"])) && isset($_SESSION["tipologia"])) {
    if ($_SESSION["tipologia"]=="admin") {
        header("Location: ../frontend/ricerca_prodotti_admin.php?" . $queryParams);
        exit();
    } else {
        header("Location: ../frontend/ricerca.php?" . $queryParams);
        exit();
    }
} else {
    header("Location: ../frontend/ricerca.php?" . $queryParams);
    exit();
}

?>