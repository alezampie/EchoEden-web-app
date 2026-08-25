<!DOCTYPE html>
<?php
    session_start();
    $_SESSION["page"]="inserimento_admin"; #dico che la variabile di sessione page è settata su inserimento_admin (per dire che ci troviamo nella pagina di inserimento admin)
    require "../common/setup.php";
    require "../common/funzioni.php";
    if (isset($_GET["status"])) { #guardo se settato status (quindi sto entrando nella pagina da un altra pagina -> in questo caso capita se arrivo da signup-exe.php)
        if ($_GET["status"]=="ko") { #e nel caso lo status fosse ko
            $errore_signup = unserialize($_GET["errore_signup"]); #se ko allora c'era errore -> lo prendo
        }
    }

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
        
        <div class="container p-2 p-sm-1 p-md-3 p-lg-5 p-xl-5">
            <div class="card rounded-3 text-black">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="card-body p-md-5 mx-md-4">

                            <div class="text-center">
                                <img class="rounded-circle" style="width: 100px;" alt="avatar1" src="../media/ECHOEDEN_logo_quadrato.png"> <!--immagine logo-->
                                <h4 class="mt-1 mb-5 pb-1">Inserisci un nuovo admin</h4> <!--nome-->
                                <?php
                                    if(isset($errore_signup["msg"])) { #se c'era errore, indico con un badge-danger che tipo di errore c'era
                                        echo "<span class='badge badge-pill badge-danger' style='background-color: red'>". $errore_signup["msg"] ."</span>";
                                    }
                                ?>
                            </div>

                            <form method="POST" action="../backend/signup-exe.php" enctype="multipart/form-data"> <!--form che invia i dati a signup-exe.php-->
                                <input type="hidden" name="tipologia_utente" value="admin">

                                <!--inserimento indirizzo email con id "email_utente"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="email" id="email_utente" name="email_utente" class="form-control" placeholder="indirizzo email"/>

                                    <?php
                                        if(isset($errore_signup) AND $errore_signup["tipo"]=="2") { #se c'era errore -> email già presente
                                            echo "<p style='color:red;'><small>". $errore_signup["msg"] ."</small></p>";
                                        }
                                    ?>
                                </div>

                                <!--inserimento username con id "username_utente"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" id="username_utente" name="username_utente" class="form-control" placeholder="username"/>

                                    <?php
                                        if(isset($errore_signup) AND $errore_signup["tipo"]=="3") { #se c'era errore -> username già presente
                                            echo "<p style='color:red;'><small>". $errore_signup["msg"] ."</small></p>";
                                        }
                                    ?>
                                </div>

                                <!--inserimento password con id "password_utente"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="password" id="password_utente" name="password_utente" class="form-control" placeholder="password"/>

                                    <?php
                                        if(isset($errore_signup) AND $errore_signup["tipo"]=="4") { #se c'era errore -> password troppo corta
                                            echo "<p style='color:red;'><small>". $errore_signup["msg"] ."</small></p>";
                                        }
                                    ?>
                                </div>

                                <!--inserimento conferma password con id "password_utente"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="password" id="conferma_password_utente" name="conferma_password_utente" class="form-control" placeholder=" conferma password"/>

                                    <?php
                                        if(isset($errore_signup) AND $errore_signup["tipo"]=="5") { #se c'era errore -> le password non corrispondono
                                            echo "<p style='color:red;'><small>". $errore_signup["msg"] ."</small></p>";
                                        }
                                    ?>
                                </div>

                                <!--inserimento immagine profilo con id "immagine_profilo"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <p><small>Sei un capo, mettici la faccia (inserisci foto profilo)</small></p>
                                    <input type="file" id="immagine_profilo_utente" name="immagine_profilo_utente"  class="form-control" data-show-preview="false">
                                </div>

                                <?php
                                        if(isset($errore_signup) AND $errore_signup["tipo"]=="6") { #se c'era errore -> le password non corrispondono
                                            echo "<p style='color:red;'><small>". $errore_signup["msg"] ."</small></p>";
                                        }
                                    ?>
                                
                                <input type="hidden" name="approvazione_utente" value="approvato">

                                <!--pulsante signup-->
                                <div class="text-center pt-1 mb-5 pb-1">
                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Signup</button>
                                </div>    
                            </form>

                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center bg-custom-1-inserimento-admin">
                        <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                            <h4 class="mb-4">Stai inserendo un nuovo admin</h4>
                            <p class="small mb-0 text-justify">
                                Non sarai più il solo ad avere potere qui. Stai attento a ciò che stai per fare. Ti fidi davvero di questa persona? Ha le competenze per gestire insieme a te questo impero? Ha buoni scopi?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "../common/footer.php"; ?> <!--includo il footer-->

    </body>

</html>