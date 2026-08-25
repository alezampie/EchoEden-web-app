<?php
    session_start(); #inizio la sessione
    require "../common/setup.php"; #innanzitutto include il file setup.php che contiene funzioni e dati necessari per usare database in modo efficiente e unico per tutti i file del progetto
    require "../common/funzioni.php"; #e anche il file che contiene le funzioni

    if (
        !isset($_SESSION['logged']) ||
        $_SESSION["tipologia"] != "artista" ||
        !isset($_POST['id'])
    ) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Accesso non autorizzato']);
        header("Location: ../index.php");
        exit();
    }

    $id = mysqli_real_escape_string($cid, $_POST['id']);

    #lo faccio per prendere l'immagine
    $run = getById($cid,'prodotto',$id);
    $data = mysqli_fetch_array($run);
    $immagine= $data['immagine'];

    $ris = eliminaProdotto($cid, $id);

    $errore_elimina = array();
    if ($ris["status"]=='ko') { #se lo status era ko
        $errore_elimina["msg"]=$ris["msg"];
        $errore_elimina["tipo"]=$ris["tipo_errore"];
    } else {
        $_SESSION['elimina_success'] = true;
    }

    if (count($errore_elimina)>0) { #se ci sono errori torno in index.php o ricerca_prodotti_admin.php con gli 
        if ((isset($_SESSION["logged"])) && isset($_SESSION["tipologia"])) {
            if ($_SESSION["tipologia"]=="admin") {
                header('Location:../frontend/ricerca_prodotti_admin.php?id=' . $id . '&status=ko&errore_elimina=' . serialize($errore_elimina)); 
                exit();
            }
        } else {
            header('Location: ../index.php?id=' . $id . '&status=ko&errore_elimina=' . serialize($errore_elimina)); 
            exit();
        }
    } else { #se non c'erano errori vado in index.php o ricerca_prodotti_admin.php ed elimino l'immagine associata al prodotto
        if(file_exists("../uploads/prodotti/".$immagine)){
            unlink("../uploads/prodotti/".$immagine);
        }

        if ((isset($_SESSION["logged"])) && isset($_SESSION["tipologia"])) {
            if ($_SESSION["tipologia"]=="admin") {
                header('location:../backend/ricerca-exe.php?status=ok');
                exit();
            } else if ($_SESSION["tipologia"]=="artista"){
                header('location:../index.php?status=ok');
            }
        } 
    }
?>