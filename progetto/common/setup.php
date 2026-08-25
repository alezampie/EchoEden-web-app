<?php
#FILE CHE CONTIENE FUNZIONI E COSE CHE SERVONO PER LA GESTIONE DEL DATABASE (da includere negli altri file)

    $hostname = 'localhost'; #il database è sul pc
    $username = 'root'; 
    $password = ''; #la password non c'è
    $dbname = 'database_progetto'; #questo è proprio il nome del database inserito in xampp

function connessione($hostname, $username, $password, $dbname) { 
    try {
        $cid = new mysqli($hostname,$username,$password,$dbname);
        if ($cid->connect_error) { 
        echo ("Errore di connessione al db $dbname: " . 
        $cid->connect_error);
        $cid = null; 
        } 
    } catch (Exception $e) {
        $cid=null;
    }
    return $cid;
    }

    $cid = connessione($hostname,$username,$password,$dbname);
?>