<?php


session_start();
require "../common/setup.php";
require "../common/funzioni.php";


$parametro = isset($_GET['search_ordini']) ? $_GET['search_ordini'] : '';
$stato = isset($_GET['stato']) ? $_GET['stato'] : '';

$utente = $_SESSION['username'];

$queryParams = http_build_query([
    'search_ordini' => $parametro,
    'stato' => $stato
]);

header("Location: ../frontend/ricerca_ordini.php?" . $queryParams);
exit();


?>