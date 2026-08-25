<?php
    session_start(); #inizio la sessione
    require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
    require "../common/funzioni.php"; #e anche il file che contiene le funzioni

    if (
        !isset($_SESSION['logged']) ||
        $_SESSION["tipologia"] != "admin" ||
        !isset($_POST["operazione"])
    ) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Accesso non autorizzato']);
        header("Location: ../index.php");
        exit();
    }

    #poi prendo i dati inviati dal form del login
    $username = $_POST["username"];
    $operazione = $_POST["operazione"];
    $immagine_profilo = $_POST["immagine_profilo"];

    #metto il risultato dal database (e ricevo eventuali errori) tramite la funzione inserisciUtente
    if ($operazione != "elimina") {
        $ris = approvazioneUtente($cid, $username, $operazione);
    } else {
        $ris = eliminaUtente($cid, $username, $immagine_profilo);
    }

    $errore_approvazione = "";
    if ($ris["status"]=='ko') { #se lo status era ko
        $errore_approvazione=$ris["msg"];
    }

    if ($errore_approvazione!="") { #se ci sono errori torno in gestione_utenti.php con gli errori
        header('location:../frontend/gestione_utenti.php?status=ko&messaggio='. serialize($errore_approvazione)); 
    } else { #se non c'erano errori vado sempre in gestione_utenti.php ma con status=ok
        if(($operazione=="elimina") && (file_exists("$immagine_profilo"))){
            unlink("$immagine_profilo");
        }
        header('location:../frontend/gestione_utenti.php?status=ok&messaggio='.serialize($ris["msg"])); 
    }
    
?>