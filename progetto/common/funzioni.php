<?php

function controllaUtente($cid, $email, $password)
{
    #ricordiamo che questi erano gli errori
    #0 errore database
    #1 errore mail
    #2 errore password
    $risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"", "tipo_errore"=>"0");
    #di default status ok, se ko lo setto. msg conterrà messaggio di errore. contenuto conterrà tipo di utente. tipo di errore inizializzato a zero

    if ($cid == null || $cid->connect_errno) {
        $risultato["status"]="ko"; #setto status ko, non c'è stata connessione al db
        if (!is_null($cid)) {
            $risultato["msg"]="errore nella connessione al db: " . $cid->connect_error; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="0";
        } else {
            $risultato["msg"]="errore nella connessione al db "; #scrivo solo che c'è errore
            $risultato["tipo_errore"]="0";
        }
        return $risultato; #ritorno il risultato in cui si dice che c'è errore
    } #se non ci sono errori eseguo la query
    $sql= "SELECT * FROM utente WHERE email = '$email';"; #questa la domanda sql che faccio
    $res = $cid->query($sql); #la eseguo
    if ($res==null) {
        $msg = "Si sono verificati i seguenti errori:<br/>" . $res->error; #se la query non la esegue per degli errori, in msg dico che errori ci sono stati
        $risultato["status"]="ko"; #e setto status ko
        $risultato["msg"]=$msg;
    } elseif ($res->num_rows==0){ #se no errore ma non ottengo nulla, vuol dire che l'utente non esiste nel db
        $msg = "email sbagliata"; #setto messaggio di errore
        $risultato["status"]="ko"; #setto status ko
        $risultato["msg"]=$msg;
        $risultato["tipo_errore"]="1"; #setto tipo_errore = 1. che equivale a mail sbagliata
    } else { #se c'è allora controllo anche la mail
        $sql= "SELECT * FROM utente WHERE email = '$email' AND password = '$password';";
        $res = $cid->query($sql);
        if ($res->num_rows==0) { #se non ottengo nulla qui è perchè la mail va bene ma la password no
            $msg = "password sbagliata"; #setto messaggio di errore
            $risultato["status"]="ko"; #setto status ko
            $risultato["msg"]=$msg;
            $risultato["tipo_errore"]="2"; #setto tipo_errore = 2. che equivale a password sbagliata
        } else { #se qui allora è andato tutto a buon fine, controllo stato di approvazione
            while ($row=$res->fetch_assoc()) { #allora leggo le righe, che tendenzialmente... è una sola
                #dentro utente metto i dati
                if ($row["approvazione"] == "approvato") { #se era approvato allora no problem, eseguo login
                    $msg = "Login eseguitao con successo";
                    $risultato["status"]="ok";
                    $risultato["msg"]=$msg;
                    $utente=array("email" => $row["email"],"username" =>$row["username"],"tipologia" =>$row["tipologia"]);
                } else { #sennò vuol dire che non era approvato!
                    $msg = "stato di approvazione: ".$row["approvazione"]; #setto messaggio di errore
                    $risultato["status"]="ko"; #setto status ko
                    $risultato["msg"]=$msg;
                    $risultato["tipo_errore"]="3"; #setto tipo_errore = 3. che equivale a "approvazione sbagliata"
                }
            }
            $risultato["contenuto"]=$utente;#metto questa variabile dentro contenuto
        }    
    }
    return $risultato;
}

function inserisciUtente($cid, $tipologia, $email, $username, $password, $conferma_password, $immagine_profilo, $descrizione, $approvazione) {
    #ricordiamo i tipi di errore
    #0 errore database
    #1 errore toggle --> devo selezionare qualcosa
    #2 errore mail --> mail già presente
    #3 errore username --> username già presente
    #4 errore password --> password troppo corta
    #5 errore conferma_password --> le due password non corrispondono
    #6 errore immagine non conforme --> dimensioni, peso, formato sbagliato
    $risultato = array("status"=>"ok","msg"=>"", "tipo_errore"=>"0");
    #di default status ok, se ko lo setto. msg conterrà messaggio di errore. contenuto conterrà dati preliminari utente ancora pending. tipo di errore inizializzato a zero

    if ($cid == null || $cid->connect_errno) {
        $risultato["status"]="ko"; #setto status ko, non c'è stata connessione al db
        if (!is_null($cid)) {
            $risultato["msg"]="errore nella connessione al db: " . $cid->connect_error; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="0";
        } else {
            $risultato["msg"]="errore nella connessione al db "; #scrivo solo che c'è errore
            $risultato["tipo_errore"]="0";
        }
        return $risultato; #ritorno il risultato in cui si dice che c'è errore
    }
    #inizio controlli su inserimento
    if($tipologia == null) { #non ho selezionato la tipologia
        $risultato["status"]="ko"; #setto status ko, bisogna per forza selezionare la tipologia
        $risultato["msg"]="bisogna selezionare qualcosa!"; #scrivo in msg che tipo di errore c'è stato
        $risultato["tipo_errore"]="1";
        return $risultato;
    } else {
        #eseguo query con mail o username --> se trovo qualcosa vuol dire che utente già registrato
        $sql= "SELECT * FROM utente WHERE email ='$email' or username='$username';";
        $res = $cid->query($sql);
        if($res->num_rows>0) { #se già presente qualcosa
            while ($row=$res->fetch_assoc()) { #allora leggo le righe
                if ($row["email"] == $email) {
                    $risultato["status"]="ko"; #setto status ko, email già presente
                    $risultato["msg"]="email già presente"; #scrivo in msg che tipo di errore c'è stato
                    $risultato["tipo_errore"]="2";
                    return $risultato;
                } else if ($row["username"] == $username) {
                    $risultato["status"]="ko"; #setto status ko, username già presente
                    $risultato["msg"]="username già presente"; #scrivo in msg che tipo di errore c'è stato
                    $risultato["tipo_errore"]="3";
                    return $risultato;
                }
            }
        } else { #se non presente allora posso inserire, ma procedo con i controlli
            if (strlen($password) < 8) {
                $risultato["status"]="ko"; #setto status ko, password troppo corta
                $risultato["msg"]="password troppo corta, deve avere almeno 8 caratteri"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="4";
                return $risultato;
            } else if ($password != $conferma_password) {
                $risultato["status"]="ko"; #setto status ko, le due password non corrispondono
                $risultato["msg"]="le due password non corrispondono"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="5";
                return $risultato;
            }

            #ora faccio i controlli per l'immagine, se c'è
            if ((!isset($immagine_profilo)) || ($immagine_profilo=="") || ($immagine_profilo==null) || ($immagine_profilo['error'] == UPLOAD_ERR_NO_FILE)) {
                $risultato["status"]="ko"; #setto status ko, bisogna inserire un'immagine
                $risultato["msg"]="non hai inserito nessuna immagine!"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="6";
                return $risultato;
            }
            $directory="../uploads/profile_pictures/"; 
            #creo nome immagine costituito dall'username
            $file=$directory.basename($username).".".pathinfo($directory.basename($immagine_profilo["name"]), PATHINFO_EXTENSION); 
            $imageFileType=strtolower(pathinfo($directory.basename($immagine_profilo["name"]), PATHINFO_EXTENSION));

            #verifico che file sia immagine
            if((getimagesize($immagine_profilo["tmp_name"])) == false) {
                $risultato["status"]="ko"; #setto status ko, il file inserito non è un'immagine
                $risultato["msg"]="il file inserito non è un'immagine"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="6";
                return $risultato;
            } else if (file_exists($file)) { #verifico se file esiste già
                $risultato["status"]="ko"; #setto status ko, il file è già presente
                $risultato["msg"]="l'immagine inserita è già presente nel database"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="6";
                return $risultato;
            } else if ($_FILES["immagine_profilo_utente"]["size"] > 500000) { #controllo se file troppo grande
                $risultato["status"]="ko"; #setto status ko, il file è troppo grande
                $risultato["msg"]="l'immagine inserita è troppo grande"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="6";
                return $risultato;
            } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") { #controllo estensione
                $risultato["status"]="ko"; #setto status ko, il file non è nel giusto formato
                $risultato["msg"]="l'immagine inserita deve essere: jpg, png, jpeg o gif"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="6";
                return $risultato;
            } else { #se ho superato tutti i controlli
                if (move_uploaded_file($immagine_profilo["tmp_name"], $file)) { #inserisco e controllo
                    #inserimento avvenuto con successo
                    if (($descrizione=="") || (!isset($descrizione)) || ($descrizione==null)) {
                        $sql= "INSERT INTO utente (approvazione, email, immagine_profilo,  password, tipologia, username) VALUES ('$approvazione', '$email', '$file', '$password', '$tipologia', '$username');";
                    } else {
                        $sql= "INSERT INTO utente (approvazione, descrizione, email, immagine_profilo,  password, tipologia, username) VALUES ('$approvazione', '$descrizione', '$email', '$file',  '$password', '$tipologia', '$username');";
                    }
                    #posso ora inserire tutto nel database!
                    $res=$cid->query($sql);
                    if ($res==1) {
                        $risultato["status"]="ok";
                        $risultato["msg"]="signup avvenuto con successo, ora devi solo aspettare l'approvazione! Effettua il login tra poco.";
                    } else {
                        $risultato["status"]="ko";
                        $risultato["msg"]="Operazione di inserimento è fallita";
                    }
                    return $risultato;

                } else {
                    $risultato["status"]="ko"; #setto status ko, non è stato possibile caricare l'immagine
                    $risultato["msg"]="errore nel caricamento dell'immagine"; #scrivo in msg che tipo di errore c'è stato
                    $risultato["tipo_errore"]="6";
                    return $risultato;
                }
            }
        }
    }

}

function approvazioneUtente($cid, $username, $approvazione)
{
    $risultato = array("status"=>"ok","msg"=>"");
    #di default status ok, se ko lo setto. msg conterrà messaggio di errore

    if ($cid == null || $cid->connect_errno) {
        $risultato["status"]="ko"; #setto status ko, non c'è stata connessione al db
        if (!is_null($cid)) {
            $risultato["msg"]="errore nella connessione al db: " . $cid->connect_error; #scrivo in msg che tipo di errore c'è stato
        } else {
            $risultato["msg"]="errore nella connessione al db "; #scrivo solo che c'è errore
        }
        return $risultato; #ritorno il risultato in cui si dice che c'è errore
    } #se non ci sono errori eseguo la query
    $sql= "SELECT * FROM utente WHERE username = '$username';"; #questa la domanda sql che faccio
    $res = $cid->query($sql); #la eseguo
    if ($res==null) {
        $msg = "Si sono verificati i seguenti errori:<br/>" . $res->error; #se la query non la esegue per degli errori, in msg dico che errori ci sono stati
        $risultato["status"]="ko"; #e setto status ko
        $risultato["msg"]=$msg;
        return $risultato;
    } elseif ($res->num_rows==0){ #se no errore ma non ottengo nulla, vuol dire che l'utente non esiste nel db
        $msg = "non è stato trovato l'utente nel database"; #setto messaggio di errore
        $risultato["status"]="ko"; #setto status ko
        $risultato["msg"]=$msg;
        return $risultato;
    } else { #se c'è allora posso eseguire l'operazione
        if ($approvazione == 'approvato') { 
            $sql = "UPDATE utente SET approvazione = 'approvato' WHERE username = '$username'"; 
        } elseif ($approvazione == 'rifiutato') { 
            $sql = "UPDATE utente SET approvazione = 'rifiutato' WHERE username = '$username'"; 
        }

        $res=$cid->query($sql);
        if ($res==1) {
            $risultato["status"]="ok";
            $risultato["msg"]="operazione di aggiornamento avvenuta con successo";
        } else {
            $risultato["status"]="ko";
            $risultato["msg"]="Operazione di aggiornamento fallita";
        }
        return $risultato;
    }
    return $risultato;
}

function eliminaUtente($cid, $username, $immagine_profilo) {
    $risultato = array("status"=>"ok","msg"=>"");
    #di default status ok, se ko lo setto. msg conterrà messaggio di errore

    if ($cid == null || $cid->connect_errno) {
        $risultato["status"]="ko"; #setto status ko, non c'è stata connessione al db
        if (!is_null($cid)) {
            $risultato["msg"]="errore nella connessione al db: " . $cid->connect_error; #scrivo in msg che tipo di errore c'è stato
        } else {
            $risultato["msg"]="errore nella connessione al db "; #scrivo solo che c'è errore
        }
        return $risultato; #ritorno il risultato in cui si dice che c'è errore
    } #se non ci sono errori eseguo la query
    #guardo se l'utente ha prodotti
    $sql = "SELECT * FROM prodotto WHERE artista = '$username'";
    $res = $cid->query($sql);
    if($res->num_rows>0) { #se ci sono articoli in vendita
        $risultato["status"]="ko";
        $risultato["msg"]="Prima di eliminare un artista che ha in vendita i rpodotti devi eliminare i suoi prodotti";
        return $risultato;
    }

    $sql= "DELETE FROM utente WHERE username = '$username';"; #questa la domanda sql che faccio
    $res = $cid->query($sql); #la eseguo
    if ($res==1) {
        $risultato["status"]="ok";
        $risultato["msg"]="operazione di eliminazione avvenuta con successo";
    } else {
        $risultato["status"]="ko";
        $risultato["msg"]="Operazione di aggiornamento fallita";
    }
    return $risultato;
}


function InserisciProdotto($cid, $categoria, $prezzo,$nome, $descrizione, $immagine, $sconto) {
    #0 errore database
    #1 immagine non conforme
        $risultato = array("status"=>"ok","msg"=>"", "tipo_errore"=>"0");
        if ($cid == null || $cid->connect_errno) {
            $risultato["status"]="ko"; #setto status ko, non c'è stata connessione al db
            if (!is_null($cid)) {
                $risultato["msg"]="errore nella connessione al db: " . $cid->connect_error; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="0";
            } else {
                $risultato["msg"]="errore nella connessione al db "; #scrivo solo che c'è errore
                $risultato["tipo_errore"]="0";
            }
            return $risultato; #ritorno il risultato in cui si dice che c'è errore
        }

        if ((!isset($immagine)) || ($immagine=="") || ($immagine==null) || ($immagine['error'] == UPLOAD_ERR_NO_FILE)) {
            $risultato["status"]="ko"; #setto status ko, bisogna inserire un'immagine
            $risultato["msg"]="non hai inserito nessuna immagine!"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        }       
        $directory="../uploads/prodotti/"; 
        #creo nome immagine costituito dal nome
        $file=basename($nome).".".pathinfo($directory.basename($immagine["name"]), PATHINFO_EXTENSION); 
        $imageFileType=strtolower(pathinfo($directory.basename($immagine["name"]), PATHINFO_EXTENSION));
        $filePath =$directory.basename($nome).".".pathinfo($directory.basename($immagine["name"]), PATHINFO_EXTENSION); 
    
        #verifico che file sia immagine
        if((getimagesize($immagine["tmp_name"])) == false) {
            $risultato["status"]="ko"; #setto status ko, il file inserito non è un'immagine
            $risultato["msg"]="il file inserito non è un'immagine"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else if (file_exists($file)) { #verifico se file esiste già
            $risultato["status"]="ko"; #setto status ko, il file è già presente
            $risultato["msg"]="l'immagine inserita è già presente nel database"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else if ($_FILES["imgProdotto"]["size"] > 500000) { #controllo se file troppo grande
            $risultato["status"]="ko"; #setto status ko, il file è troppo grande
            $risultato["msg"]="l'immagine inserita è troppo grande"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") { #controllo estensione
            $risultato["status"]="ko"; #setto status ko, il file non è nel giusto formato
            $risultato["msg"]="l'immagine inserita deve essere: jpg, png, jpeg o gif"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else { #se ho superato tutti i controlli
            if (move_uploaded_file($immagine["tmp_name"], $filePath)) { #inserisco e controllo
                #inserimento avvenuto con successo
                $sql ="INSERT INTO prodotto (immagine, artista, categoria, prezzo, nome, descrizione, sconto) 
                VALUES ('$file', '{$_SESSION['username']}', '$categoria', $prezzo, '$nome', '$descrizione', '$sconto')";#controllare che username sia settato                
                #posso ora inserire tutto nel database!
                $res=$cid->query($sql);
                if ($res==1) {
                    $risultato["status"]="ok";
                    $risultato["msg"]="signup avvenuto con successo, ora devi solo aspettare l'approvazione! Effettua il login tra poco.";
                } else {
                    $risultato["status"]="ko";
                    $risultato["msg"]="Operazione di inserimento è fallita";
                }
                return $risultato;
        
            } else {
                $risultato["status"]="ko"; #setto status ko, non è stato possibile caricare l'immagine
                $risultato["msg"]="errore nel caricamento dell'immagine"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="1";
                return $risultato;
            }
        }
}

function modificaProdotto($cid, $id, $categoria, $prezzo, $nome,$descrizione, $sconto, $new_img, $old_img) {
    #0 errore database
    #1 immagine non conforme
    $risultato = array("status"=>"ok","msg"=>"", "tipo_errore"=>"0");
    if ($cid == null || $cid->connect_errno) {
        $risultato["status"]="ko"; #setto status ko, non c'è stata connessione al db
        if (!is_null($cid)) {
            $risultato["msg"]="errore nella connessione al db: " . $cid->connect_error; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="0";
        } else {
            $risultato["msg"]="errore nella connessione al db "; #scrivo solo che c'è errore
            $risultato["tipo_errore"]="0";
        }
        return $risultato; #ritorno il risultato in cui si dice che c'è errore
    }

    $directory="../uploads/prodotti/";

    # se c'è una nuova immagine faccio tutti i controlli, sennò tengo quella vecchia
    if ($new_img["name"] != "") {
        #creo nome immagine costituito dall'username
        $file=basename($nome).".".pathinfo($directory.basename($new_img["name"]), PATHINFO_EXTENSION); 
        $imageFileType=strtolower(pathinfo($directory.basename($new_img["name"]), PATHINFO_EXTENSION));
        $filePath =$directory.basename($nome).".".pathinfo($directory.basename($new_img["name"]), PATHINFO_EXTENSION);
        #verifico che file sia immagine
        if((getimagesize($new_img["tmp_name"])) == false) {
            $risultato["status"]="ko"; #setto status ko, il file inserito non è un'immagine
            $risultato["msg"]="il file inserito non è un'immagine"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else if (file_exists($file)) { #verifico se file esiste già
            $risultato["status"]="ko"; #setto status ko, il file è già presente
            $risultato["msg"]="l'immagine inserita è già presente nel database"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else if ($_FILES["imgProdotto"]["size"] > 500000) { #controllo se file troppo grande
            $risultato["status"]="ko"; #setto status ko, il file è troppo grande
            $risultato["msg"]="l'immagine inserita è troppo grande"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") { #controllo estensione
            $risultato["status"]="ko"; #setto status ko, il file non è nel giusto formato
            $risultato["msg"]="l'immagine inserita deve essere: jpg, png, jpeg o gif"; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="1";
            return $risultato;
        } else { #se ho superato tutti i controlli
            if(file_exists("../uploads/prodotti/".$old_img)){
                unlink("../uploads/prodotti/".$old_img);
            }
            if (move_uploaded_file($new_img["tmp_name"], $directory.basename($nome).".".pathinfo($directory.basename($new_img["name"]), PATHINFO_EXTENSION))) { #inserisco e controllo
                #se c'è, elimino l'immagine vecchia
                #inserimento avvenuto con successo
                $sql ="UPDATE prodotto SET nome ='$nome', prezzo = '$prezzo', categoria = '$categoria', immagine = '$file', descrizione = '$descrizione', sconto = '$sconto' WHERE 
                id_prodotto='$id' ";               
                #posso ora inserire tutto nel database!
                $res=$cid->query($sql);
                if ($res==1) {
                    $risultato["status"]="ok";
                    $risultato["msg"]="modifica avvenuta con successo";
                } else {
                    $risultato["status"]="ko";
                    $risultato["msg"]="Operazione di modifica fallita";
                }
                return $risultato;
        
            } else {
                $risultato["status"]="ko"; #setto status ko, non è stato possibile caricare l'immagine
                $risultato["msg"]="errore nel caricamento dell'immagine"; #scrivo in msg che tipo di errore c'è stato
                $risultato["tipo_errore"]="1";
                return $risultato;
            }
        }
            
    } else {
        $sql ="UPDATE prodotto SET nome ='$nome', prezzo = '$prezzo', categoria = '$categoria', immagine = '$old_img', descrizione = '$descrizione', sconto = '$sconto' WHERE 
        id_prodotto='$id' ";               
        #posso ora inserire tutto nel database!
        $res=$cid->query($sql);
        if ($res==1) {
            $risultato["status"]="ok";
            $risultato["msg"]="modifica avvenuta con successo";
        } else {
            $risultato["status"]="ko";
            $risultato["msg"]="Operazione di inserimento è fallita";
        }
        return $risultato;
    }

}

function eliminaProdotto($cid, $id) {
    #0 errore database
    $risultato = array("status"=>"ok","msg"=>"", "tipo_errore"=>"0");
    if ($cid == null || $cid->connect_errno) {
        $risultato["status"]="ko"; #setto status ko, non c'è stata connessione al db
        if (!is_null($cid)) {
            $risultato["msg"]="errore nella connessione al db: " . $cid->connect_error; #scrivo in msg che tipo di errore c'è stato
            $risultato["tipo_errore"]="0";
        } else {
            $risultato["msg"]="errore nella connessione al db "; #scrivo solo che c'è errore
            $risultato["tipo_errore"]="0";
        }
        return $risultato; #ritorno il risultato in cui si dice che c'è errore
    }

    $sql ="DELETE FROM prodotto WHERE id_prodotto='$id' ";               
    #posso ora inserire tutto nel database!
    $res=$cid->query($sql);
    if ($res==1) {
        $risultato["status"]="ok";
        $risultato["msg"]="modifica avvenuta con successo";
    } else {
        $risultato["status"]="ko";
        $risultato["msg"]="Operazione di inserimento è fallita";
    }
    return $risultato;
}

function getByArtist($cid, $table,$artista) {
    $query = "SELECT * FROM $table WHERE artista = '$artista'";
    $query_run = mysqli_query($cid, $query);
    return $query_run;
}

function getById($cid,$table,$id){
    $query = "SELECT * FROM $table WHERE id_prodotto='$id' ";
    $query_run = mysqli_query($cid, $query);
    return $query_run;
}

function  getSconto($cid,$table) { 
    $query = "SELECT * FROM $table WHERE sconto!=0 ORDER BY sconto DESC LIMIT 4";
    $query_run = mysqli_query($cid, $query);
    return $query_run;
}

function  getCD($cid,$table) { 
    $query = "SELECT * FROM $table WHERE categoria = 'CD' LIMIT 4";
    $query_run = mysqli_query($cid, $query);
    return $query_run;
}

function  getVinili($cid,$table) { 
    $query = "SELECT * FROM $table WHERE categoria = 'Vinile' LIMIT 4";
    $query_run = mysqli_query($cid, $query);
    return $query_run;
}

function  getShirt($cid,$table) { 
    $query = "SELECT * FROM $table WHERE categoria = 'T-shirt' LIMIT 4";
    $query_run = mysqli_query($cid, $query);
    return $query_run;
}


function getCarrello($cid, $table, $fan) {
    // La query esegue una JOIN tra la tabella carrello e prodotto
    $query = "SELECT p.nome, c.quantita, p.prezzo
              FROM $table c
              JOIN prodotto p ON c.prodotto = p.id_prodotto
              WHERE c.fan = '$fan'";
    $query_run = mysqli_query($cid, $query);
    return $query_run;
}


function getFilteredProducts($cid,$table,$parameter,$categorie, $prezzo_min, $prezzo_max, $sconto) {
    $query = "SELECT * FROM $table WHERE CONCAT(nome,artista,descrizione) LIKE '%$parameter%' ";

    if (!empty($categorie)) {
        $query .= " AND categoria = '" . mysqli_real_escape_string($cid, $categorie) . "'";
    }

    if ($prezzo_min > 0) {
        $query .= " AND (prezzo * (1 - sconto / 100)) >= " . (float)$prezzo_min;
    }
    
    if ($prezzo_max > 0) {
        $query .= " AND (prezzo * (1 - sconto / 100)) <= " . (float)$prezzo_max;
    }

    if ($sconto) {
        $query .= " AND sconto > 0";
    }

    $result = mysqli_query($cid, $query);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function getOrders($cid, $utente, $parameter, $stato) {
    // Pulizia input
    $utente = mysqli_real_escape_string($cid, $utente);
    $parameter = mysqli_real_escape_string($cid, $parameter);

    $query = "SELECT DISTINCT o.*
        FROM ordine o
        JOIN dettagli_ordini d ON o.id_ordine = d.ordine
        JOIN prodotto p ON d.prodotto = p.id_prodotto
        WHERE o.fan = '$utente' 
        AND CONCAT(p.nome, p.artista) LIKE '%$parameter%'";

    // Filtro per stato, se fornito
    if (!empty($stato)) {
        $stato = mysqli_real_escape_string($cid, $stato);
        $query .= " AND o.stato = '$stato'";
    }

    // Ordinamento
    $query .= " ORDER BY o.id_ordine DESC";

    $result = mysqli_query($cid, $query);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function getOrderByArtist($cid, $utente, $parameter, $stato) {
    // Pulizia input
    $utente = mysqli_real_escape_string($cid, $utente);
    $parameter = mysqli_real_escape_string($cid, $parameter);

    $query = "SELECT DISTINCT o.*
        FROM ordine o
        JOIN dettagli_ordini d ON o.id_ordine = d.ordine
        JOIN prodotto p ON d.prodotto = p.id_prodotto
        WHERE p.artista = '$utente' 
        AND CONCAT(p.nome, p.artista) LIKE '%$parameter%'";

    // Filtro per stato, se fornito
    if (!empty($stato)) {
        $stato = mysqli_real_escape_string($cid, $stato);
        $query .= " AND o.stato = '$stato'";
    }

    // Ordinamento
    $query .= " ORDER BY o.id_ordine DESC";

    $result = mysqli_query($cid, $query);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}



function getProductByOrder($cid,$ordine,$utente) {
    $query = "SELECT d.quantita,
        d.prezzo_unitario,
        p.id_prodotto,
        p.nome,
        p.artista,
        p.immagine,
        p.sconto,
        p.descrizione
        FROM ordine o
        JOIN dettagli_ordini d ON o.id_ordine = d.ordine
        JOIN prodotto p ON d.prodotto = p.id_prodotto
        WHERE o.fan = '$utente' AND d.ordine = '$ordine';
        ";
    $result = mysqli_query($cid, $query);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function getProductOrderByArtist($cid,$ordine,$utente) {
    $query = "SELECT d.quantita,
        d.prezzo_unitario,
        p.id_prodotto,
        p.nome,
        p.artista,
        p.immagine,
        p.sconto,
        p.descrizione
        FROM ordine o
        JOIN dettagli_ordini d ON o.id_ordine = d.ordine
        JOIN prodotto p ON d.prodotto = p.id_prodotto
        WHERE p.artista = '$utente' AND d.ordine = '$ordine';
        ";
    $result = mysqli_query($cid, $query);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function getCommenti($cid, $id_prodotto) {
    $query = "SELECT fan, voto, descrizione, data_commento FROM commenti WHERE prodotto = ? ORDER BY data_commento DESC";
    $stmt = $cid->prepare($query);
    $stmt->bind_param("i", $id_prodotto);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function aggiungiCommento($cid, $fan, $prodotto, $descrizione, $voto) {
    $voto = strval($voto);  

    $stmt = $cid->prepare("INSERT INTO commenti (fan, prodotto, descrizione, voto, data_commento) VALUES (?, ?, ?, ?, NOW())");
    if (!$stmt) {
        return ["status" => "ko", "msg" => "Errore nella preparazione della query.", "tipo_errore" => 0];
    }

    $stmt->bind_param("siss", $fan, $prodotto, $descrizione, $voto);

    if ($stmt->execute()) {
        return ["status" => "ok", "msg" => "Commento aggiunto con successo."];
    } else {
        return ["status" => "ko", "msg" => "Errore nell'inserimento del commento.", "tipo_errore" => 1];
    }
}

function getCommentoSingolo($cid, $fan, $prodotto, $data_commento) {
    $stmt = $cid->prepare("SELECT * FROM commenti WHERE fan = ? AND prodotto = ? AND data_commento = ?");
    $stmt->bind_param("sis", $fan, $prodotto, $data_commento);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    }
    return false;
}

function modificaCommento($cid, $fan, $prodotto, $data_commento, $descrizione, $voto) {
    $voto = strval($voto);  
    $query = "UPDATE commenti SET descrizione = ?, voto = ? WHERE fan = ? AND prodotto = ? AND data_commento = ?";
    $stmt = $cid->prepare($query);
    if (!$stmt) {
        return array("status" => "ko", "msg" => "Errore nella preparazione della query.", "tipo_errore" => 0);
    }

    $stmt->bind_param("sssis", $descrizione, $voto, $fan, $prodotto, $data_commento);

    if ($stmt->execute()) {
        return array("status" => "ok");
    } else {
        return array("status" => "ko", "msg" => "Errore nell'esecuzione della modifica.", "tipo_errore" => 0);
    }
}

function fanPuòCommentare($cid, $fan_username, $id_prodotto) {
    //controlla se ha già commentato quel prodotto
    $stmt1 = $cid->prepare("SELECT COUNT(*) AS num FROM commenti WHERE fan = ? AND prodotto = ?");
    if (!$stmt1) return false;
    $stmt1->bind_param("si", $fan_username, $id_prodotto);
    $stmt1->execute();
    $result1 = $stmt1->get_result()->fetch_assoc();
    $ha_commentato_prodotto = $result1["num"] > 0;
    $stmt1->close();

    //controlla quanti prodotti ha commentato in totale
    $stmt2 = $cid->prepare("SELECT COUNT(DISTINCT prodotto) AS tot FROM commenti WHERE fan = ?");
    if (!$stmt2) return false;
    $stmt2->bind_param("s", $fan_username);
    $stmt2->execute();
    $result2 = $stmt2->get_result()->fetch_assoc();
    $num_commenti_totali = $result2["tot"];
    $stmt2->close();

    //può commentare solo se non ha già commentato il prodotto e ha meno di 5 commenti totali
    return !$ha_commentato_prodotto && $num_commenti_totali < 5;
}
?>