<?php
    session_start(); #inizio la sessione
    require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
    require "../common/funzioni.php"; #e anche il file che contiene le funzioni

    if (
        !isset($_SESSION['logged']) ||
        $_SESSION["tipologia"] != "artista" ||
        !isset($_POST["id_prodotto"])
    ) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Accesso non autorizzato']);
        header("Location: ../index.php");
        exit();
    }

    $id = $_POST["id_prodotto"];
    $name = $_POST["name"];
    $categoria = $_POST["categoria"];
    $prezzo = $_POST["price"];
    $descrizione = $_POST["descrizione"];
    $sconto = $_POST["sconto"];

    $new_img = $_FILES["imgProdotto"];
    $old_img = $_POST['vecchia_immagine'];

    $ris = modificaProdotto($cid, $id, $categoria, $prezzo, $name,$descrizione,$sconto, $new_img, $old_img);

    $errore_modifica = array();
    if ($ris["status"]=='ko') { #se lo status era ko
        $errore_modifica["msg"]=$ris["msg"];
        $errore_modifica["tipo"]=$ris["tipo_errore"];
    } else {
        $_SESSION['modifica_success'] = true;
    }

    if (count($errore_modifica)>0) { #se ci sono errori torno in signup.php con gli errori
        header('Location: ../frontend/modifica_prod.php?id=' . $id . '&status=ko&errore_modifica=' . serialize($errore_modifica)); 
    } else { #se non c'erano errori vado in index.php mandando i dati utente
        header('location:../index.php?status=ok'); 
    }

?>