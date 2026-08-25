<!DOCTYPE html>
<?php
    session_start();
    $_SESSION["page"]="gestione_utenti"; #dico che la variabile di sessione page è settata su inserimento_admin (per dire che ci troviamo nella pagina di inserimento admin)
    require "../common/setup.php";
    require "../common/funzioni.php";


    //controllo per impedire accesso dall'url
    if (!isset($_SESSION["logged"]) || $_SESSION["tipologia"] != "admin") {
        header("Location: ../index.php");
        exit;
    }

?>

<html>
    
    <?php include "../common/head.php"; ?> <!--includo l'header-->
    <!--il css lo devo aggiungere manualemnte perchè nell'header importato non posso sapere dove è situato il file nel percorso-->
    <link rel="stylesheet" href="../css/style.css">

    <body class='bg-image'>
        <?php include "../common/navbar.php"; ?> <!--includo la navbar-->

        <!--prima parte che dà messaggio di avvenuto aggiornamento o errore-->
        <?php
        if (isset($_GET["status"])) { #guardo se settato status (quindi sto entrando nella pagina da un altra pagina -> in questo caso capita se arrivo da gestione_utenti-exe.php)
            if ($_GET["status"]=="ko") { #e nel caso lo status fosse ko
                $messaggio = unserialize($_GET["messaggio"]); #se ko allora c'era errore -> lo prendo
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>risultato:</strong> <?php echo "$messaggio"; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            } else if ($_GET["status"]=="ok") {
                $messaggio = unserialize($_GET["messaggio"]);
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>risultato:</strong> <?php echo "$messaggio"; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            unset($messaggio);
        }
        ?>
        <!--parte di approvazione-->
        <div class="container">
            <div class="text-center mt-3"> 
                <img src="../media/approvazione.png" class="img-fluid" style="width: 50%;">
                <h6 class="text-white">lista di utenti in pending, puoi approvarli o rifiutarli</h6> 
            </div>
            <div class="table-responsive">
                <table class="table overflow-auto mt-3 table-light">
                    <thead class="thead-dark">
                        <tr>
                            <th class="col-4">username</th>
                            <th class="col-4">email</th>
                            <th class="col-2">tipologia</th>
                            <th class="col-2 text-end">azione</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //controllo della connessione 
                        if ($cid->connect_error) { 
                            die("Connessione fallita: " . $cid->connect_error); 
                        }
                        //query per ottenere gli utenti in pending 
                        $sql = "SELECT username, email, tipologia FROM utente WHERE approvazione = 'pending'";
                        $res = $cid->query($sql);
                        
                        //mostro le righe in base a quello che si trova
                        if ($res->num_rows > 0) { 
                            while($row = $res->fetch_assoc()) { 
                                echo "<tr id='row-{$row['username']}'> 
                                    <td>{$row['username']}</td> 
                                    <td>{$row['email']}</td>
                                    <td>{$row['tipologia']}</td>  
                                    <td class='text-right d-flex justify-content-end flex-column flex-md-row'> 
                                        <form action='../backend/gestione_utenti-exe.php' method='POST'>
                                            <input type='hidden' name='username' value='{$row['username']}'>
                                            <input type='hidden' name='operazione' value='approvato'>
                                            <button type='submit' class='btn btn-success' data-id='{$row['username']}'>Approva</button> 
                                        </form>
                                        <form action='../backend/gestione_utenti-exe.php' method='POST'>
                                            <input type='hidden' name='username' value='{$row['username']}'>
                                            <input type='hidden' name='operazione' value='rifiutato'>
                                            <button type='submit' class='btn btn-danger' data-id='{$row['username']}'>Rifiuta</button> 
                                        </form>
                                    </td> 
                                </tr>"; 
                            } 
                        } else { 
                            echo "<tr>
                                <td colspan='3'>Nessun utente in pending</td>
                            </tr>"; 
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!--LISTA UTENTI da eliminare o rifiutare-->
        <div class="container">
            <div class="text-center mt-3"> 
                <img src="../media/lista.png" class="img-fluid" style="width: 20%;"> 
                <h6 class="text-white">lista completa di utenti (non in pending)</h6> 
            </div>
            <div class="table-responsive">
                <table class="table overflow-auto mt-3 table-light">
                    <thead class="thead-dark">
                        <tr>
                            <th class="col-3">username</th>
                            <th class="col-3">email</th>
                            <th class="col-2">tipologia</th>
                            <th class="col-2">stato</th>
                            <th class="col-2 text-end">azione</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //controllo della connessione 
                        if ($cid->connect_error) { 
                            die("Connessione fallita: " . $cid->connect_error); 
                        }
                        //query per ottenere gli utenti in pending, escludendo l'utente loggato
                        $sql = "SELECT username, email, tipologia, approvazione, immagine_profilo FROM utente WHERE approvazione != 'pending' AND username != '" . $_SESSION['username'] . "'";
                        $res = $cid->query($sql);
                        
                        //mostro le righe in base a quello che si trova
                        if ($res->num_rows > 0) { 
                            while($row = $res->fetch_assoc()) { 
                                echo "<tr id='row-{$row['username']}'> 
                                    <td>{$row['username']}</td> 
                                    <td>{$row['email']}</td>
                                    <td>{$row['tipologia']}</td>
                                    <td>{$row['approvazione']}</td>  
                                    <td class='text-right d-flex justify-content-end flex-column flex-md-row'>"; 
                                    if ($row['approvazione'] == 'approvato') { 
                                        echo "<form action='../backend/gestione_utenti-exe.php' method='POST'> 
                                            <input type='hidden' name='username' value='{$row['username']}'> 
                                            <input type='hidden' name='operazione' value='rifiutato'>
                                            <input type='hidden' name='immagine_profilo' value='{$row['immagine_profilo']}'> 
                                            <button type='submit' class='btn btn-danger' data-id='{$row['username']}'>Blocca</button> 
                                        </form> 
                                        <form action='../backend/gestione_utenti-exe.php' method='POST'> 
                                            <input type='hidden' name='username' value='{$row['username']}'> 
                                            <input type='hidden' name='operazione' value='elimina'> 
                                            <input type='hidden' name='immagine_profilo' value='{$row['immagine_profilo']}'> 
                                            <button type='submit' class='btn btn-warning' data-id='{$row['username']}'>Elimina</button> 
                                        </form>"; 
                                    } else if ($row['approvazione'] == 'rifiutato') { 
                                        echo "<form action='../backend/gestione_utenti-exe.php' method='POST'> 
                                            <input type='hidden' name='username' value='{$row['username']}'> 
                                            <input type='hidden' name='operazione' value='approvato'> 
                                            <input type='hidden' name='immagine_profilo' value='{$row['immagine_profilo']}'> 
                                            <button type='submit' class='btn btn-success' data-id='{$row['username']}'>Sblocca</button> 
                                        </form> 
                                        <form action='../backend/gestione_utenti-exe.php' method='POST'> 
                                            <input type='hidden' name='username' value='{$row['username']}'> 
                                            <input type='hidden' name='operazione' value='elimina'> 
                                            <input type='hidden' name='immagine_profilo' value='{$row['immagine_profilo']}'> 
                                            <button type='submit' class='btn btn-warning' data-id='{$row['username']}'>Elimina</button> 
                                        </form>"; 
                                    } 
                                    echo "</td> 
                                    </tr>";  
                            } 
                        } else { 
                            echo "<tr>
                                <td colspan='3'>Nessun utente</td>
                            </tr>"; 
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        

        <?php include "../common/footer.php"; ?> <!--includo il footer-->

    </body>

</html>
