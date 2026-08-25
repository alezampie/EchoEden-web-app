<?php
    session_start(); #inizio la sessione
    require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
    require "../common/funzioni.php"; #e anche il file che contiene le funzioni

    #poi prendo i dati inviati dal form del login
    $tipologia = $_POST["tipologia_utente"];
    $email = $_POST["email_utente"];
    $username = $_POST["username_utente"];
    $password = $_POST["password_utente"];
    $conferma_password = $_POST["conferma_password_utente"];
    $immagine_profilo = $_FILES["immagine_profilo_utente"];
    $descrizione = $_POST["descrizione_utente"];
    $approvazione = $_POST["approvazione_utente"];

    #metto il risultato dal database (e ricevo eventuali errori) tramite la funzione inserisciUtente
    $ris = inserisciUtente($cid, $tipologia, $email, $username, $password, $conferma_password, $immagine_profilo, $descrizione, $approvazione);

    $errore_signup = array();
    if ($ris["status"]=='ko') { #se lo status era ko
        $errore_signup["msg"]=$ris["msg"];
        $errore_signup["tipo"]=$ris["tipo_errore"];
    } else {
        $_SESSION['signup_success'] = true;
    }

    if ((count($errore_signup)>0) AND ($_SESSION["page"]=="signup")) { #se ci sono errori torno in signup.php con gli errori
        header('location:../frontend/signup.php?status=ko&errore_signup='. serialize($errore_signup)); 
    } else if ((count($errore_signup)>0) AND ($_SESSION["page"]=="inserimento_admin")) {
        header('location:../frontend/inserimento_admin.php?status=ko&errore_signup='. serialize($errore_signup));
    } else { #se non c'erano errori vado in index.php mandando i dati utente
        header('location:../index.php?status=ok'); 
    }
    
?>