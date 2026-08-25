<?php
    session_start(); #inizio la sessione
    require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
    require "../common/funzioni.php"; #e anche il file che contiene le funzioni

    $email = $_POST["email_utente"]; #poi prendo i dati inviati dal form del login
    $password = $_POST["password_utente"];

    #prendo il risultato dal database (ed eventuali errori) tramite la funzione controllaUtente
    $ris = controllaUtente($cid, $email, $password);

    #ricordiamo che questi erano gli errori
    #0 errore database
    #1 errore mail
    #2 errore password

    $errore_login = array();
    if ($ris["status"]=='ok') { #se lo status era ok
        $_SESSION["email"]=$ris["contenuto"]["email"]; #setto variabile sessione email
        $_SESSION["username"]=$ris["contenuto"]["username"]; #setto variabile sessione username
        $_SESSION["tipologia"]=$ris["contenuto"]["tipologia"]; #setto variabile sessione tipologia
        $_SESSION["logged"]=true; #varibile di sessione che dice se stato eseguito login
    } else { #se lo status era ko
        $errore_login["msg"]=$ris["msg"];
        $errore_login["tipo"]=$ris["tipo_errore"];
        session_destroy(); #distruggo la sessione
    }

    if (count($errore_login)>0) { #se ci sono errori torno in login_utente.php con gli errori
        header('location:../frontend/login.php?status=ko&errore_login='. serialize($errore_login)); 
    } else { #se non c'erano errori vado in navbar_rifatta.php mandando i dati utente
        header('location:../index.php?status=ok&dati_login='. serialize($ris["msg"])); 
    }
    
?>