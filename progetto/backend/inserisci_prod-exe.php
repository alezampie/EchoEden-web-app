<?php
    session_start(); #inizio la sessione
    require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
    require "../common/funzioni.php"; #e anche il file che contiene le funzioni

    $name = $_POST["name"];
    $categoria = $_POST["categoria"];
    $prezzo = $_POST["price"];
    $descrizione = $_POST["descrizione"];
    $immagine = $_FILES["immagine_prodotto"];
    $sconto = $_POST["sconto"];

    $ris = inserisciProdotto($cid, $categoria, $prezzo, $name,$descrizione, $immagine, $sconto);
      
    $errore_insert = array();
    if ($ris["status"]=='ko') { #se lo status era ko
        $errore_insert["msg"]=$ris["msg"];
        $errore_insert["tipo"]=$ris["tipo_errore"];
    } else {
        $_SESSION['insert_success']= true;
    }

    if (count($errore_insert)>0) { #se ci sono errori torno in signup.php con gli errori
        header('location:../frontend/inserisci_prod.php?status=ko&errore_insert='. serialize($errore_insert)); 
    } else { #se non c'erano errori vado in index.php mandando i dati utente
        header('location:../index.php?status=ok'); 
    }
?>
