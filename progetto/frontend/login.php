<?php
    session_start(); #come prima cosa inizio sessione
    $_SESSION["page"]="login"; #dico che la variabile di sessione page è settata su login (per dire che ci troviamo nella pagina di login)

    if (isset($_GET["status"])) { #guardo se settato status (quindi sto entrando nella pagina da un altra pagina -> in questo caso capita se arrivo da login-exe.php)
        if ($_GET["status"]=="ko") { #e nel caso lo status fosse ko
            $errore_login = unserialize($_GET["errore_login"]); #se ko allora c'era errore -> lo prendo
        }
    }

    #ricordiamo i tipi di errore
    #0 errore database
    #1 errore mail
    #2 errore password
    #3 errore stato di approvazione sbagliato -> pending o rifiutato
?>


<!DOCTYPE html>
<html>
    <?php include "../common/head.php"; ?>
    <!--il css lo devo aggiungere manualemnte perchè nell'header importato non posso sapere dove è situato il file nel percorso-->
    <link rel="stylesheet" href="../css/style.css">

    <body class='bg-image'>
        <div class="container p-2 p-sm-1 p-md-3 p-lg-5 p-xl-5">
            <div class="card rounded-3 text-black">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="card-body p-md-5 mx-md-4">

                            <div class="text-center">
                                <img class="rounded-circle" style="width: 185px;" alt="avatar1" src="../media/ECHOEDEN_logo_quadrato.png"> <!--immagine logo-->
                                <h4 class="mt-1 mb-5 pb-1">EchoEden</h4> <!--nome-->
                                <?php
                                    if(isset($errore_login["msg"])) { #se c'era errore, indico con un badge-danger che tipo di errore c'era
                                        echo "<span class='badge badge-pill badge-danger' style='background-color: red'>". $errore_login["msg"] ."</span>";
                                    }
                                ?>
                            </div>

                            <form method="POST" action="../backend/login-exe.php"> <!--form che invia i dati a login-exe.php-->
                                <p>Accedi al tuo account</p>
                                <!--inserimento indirizzo email con id "email_utente"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="email" id="email_utente" name="email_utente" class="form-control" placeholder="indirizzo email"/>
                                    <!--controllo se c'erano errori-->
                                    <?php
                                        if(isset($errore_login) AND $errore_login["tipo"]=="1") { #se c'era errore -> mail non presente, lo mostro sotto il form apposito
                                            echo "<p style='color:red;'><small>". $errore_login["msg"] ."</small></p>"; 
                                        }
                                    ?>
                                </div>
                
                                <!--inserimento password con id "password_utente"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="password" id="password_utente" name="password_utente" class="form-control" placeholder="password"/>
                                    <!--controllo se c'erano errori-->
                                    <?php
                                        if(isset($errore_login) AND $errore_login["tipo"]=="2") { #se c'era errore -> password sbagliata, lo mostro sotto il form apposito
                                            echo "<p style='color:red;'><small>". $errore_login["msg"] ."</small></p>";
                                        }
                                    ?>
                                </div>

                                <!--pulsante login-->
                                <div class="text-center pt-1 mb-5 pb-1">
                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Login</button>
                                </div>
                
                                <!--nel caso non si avesse account-->
                                <div class="d-flex align-items-center justify-content-center pb-4">
                                    <p class="mb-0 me-2">Non hai ancora un account?</p>
                                    <a href="signup.php" class="btn btn-outline-success" data-mdb-button-init data-mdb-ripple-init>Registrati!</a>
                                </div>      
                            </form>

                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center bg-custom-1-login">
                        <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                            <h4 class="mb-4 h1">Bentornato!</h4>
                            <p class="small mb-0">
                                Pronto a comprare o vendere qualcosa? Continua a supportare i tuoi artisti preferiti o a diffondere la tua arte. Noi siamo qui per te.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "../common/footer.php"; ?>
    </body>


</html>