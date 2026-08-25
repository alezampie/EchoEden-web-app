<?php
    session_start(); #come prima cosa inizio sessione
    $_SESSION["page"]="signup"; #dico che la variabile di sessione page è settata su signup (per dire che ci troviamo nella pagina di signup)

    if (isset($_GET["status"])) { #guardo se settato status (quindi sto entrando nella pagina da un altra pagina -> in questo caso capita se arrivo da signup-exe.php)
        if ($_GET["status"]=="ko") { #e nel caso lo status fosse ko
            $errore_signup = unserialize($_GET["errore_signup"]); #se ko allora c'era errore -> lo prendo
        }
    }

    #ricordiamo i tipi di errore
    #0 errore database
    #1 errore toggle --> devo selezionare qualcosa
    #2 errore mail --> mail già presente
    #3 errore username --> username già presente
    #4 errore password --> password troppo corta
    #5 errore conferma_password --> le due password non corrispondono
    #6 errore immagine non conforme --> dimensioni, peso, formato sbagliato
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
                                <img class="rounded-circle" style="width: 100px;" alt="avatar1" src="../media/ECHOEDEN_logo_quadrato.png"> <!--immagine logo-->
                                <h4 class="mt-1 mb-5 pb-1">Crea il tuo profilo su EchoEden</h4> <!--nome-->
                                <?php
                                    if(isset($errore_signup["msg"])) { #se c'era errore, indico con un badge-danger che tipo di errore c'era
                                        echo "<span class='badge badge-pill badge-danger' style='background-color: red'>". $errore_signup["msg"] ."</span>";
                                    }
                                ?>
                            </div>

                            <form method="POST" action="../backend/signup-exe.php" enctype="multipart/form-data"> <!--form che invia i dati a signup-exe.php-->

                                <!--innanzitutto capiamo se fan o artista, con javascript la pagina cambierà in base a questo-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <p>innanzitutto, sei un fan o un artista?</p>
                                    <input type="radio" id="fan" name="tipologia_utente" value="fan" onclick="toggleArtistFields()" required> fan
                                    <input type="radio" id="artist" name="tipologia_utente" value="artista" onclick="toggleArtistFields()" required> artista

                                    <!--controllo se c'erano errori-->
                                    <?php
                                        if(isset($errore_signup) AND $errore_signup["tipo"]=="1") { #se c'era errore -> toggle non selezionato, devo selezionare qualcosa
                                            echo "<p style='color:red;'><small>". $errore_signup["msg"] ."</small></p>"; 
                                        }
                                    ?>
                                </div>

                                <!--ELEMENTI COMUNI A FAN E ARTISTA-->
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

                                <!--inserimento imamgine profilo con id "immagine_profilo"-->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <p><small>Metti anche una foto profilo dove sei venutə bene dai!</small></p>
                                    <input type="file" id="immagine_profilo_utente" name="immagine_profilo_utente"  class="form-control" data-show-preview="false">
                                </div>

                                <?php
                                        if(isset($errore_signup) AND $errore_signup["tipo"]=="6") { #se c'era errore -> le password non corrispondono
                                            echo "<p style='color:red;'><small>". $errore_signup["msg"] ."</small></p>";
                                        }
                                    ?>

                                <!--ELEMENTI AGGIUNTIVI CHE HANNO GLI ARTISTI-->
                                <div id="artistFields" data-mdb-input-init class="form-outline mb-4 hidden">
                                    <p><small>Sempre bello avere nuovi artisti con noi! Raccontaci un po' di te, o di voi se siete una band. Di dove siete? Cosa fate? Cosa volete lasciare in questo mondo?</small></p>
                                    <textarea class="form-control" id="descrizione_utente" name ="descrizione_utente" maxlength="500" rows="3" placeholder="descrizione"></textarea>
                                </div>
                                
                                <input type="hidden" name="approvazione_utente" value="pending">

                                <!--pulsante signup-->
                                <div class="text-center pt-1 mb-5 pb-1">
                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Signup</button>
                                </div>
                
                                <!--nel caso si avesse già account-->
                                <div class="d-flex align-items-center justify-content-center pb-4">
                                    <p class="mb-0 me-2">Hai già un account?</p>
                                    <a href="login.php" class="btn btn-outline-success" data-mdb-button-init data-mdb-ripple-init>Accedi!</a>
                                </div>      
                            </form>

                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center bg-custom-1-signup">
                        <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                            <h4 class="mb-4 h1">Benvenuto!</h4>
                            <img id="dynamicImage" src="" alt="Dynamic Image" style="max-width: 100%; display: none;">
                            <p id="dynamicText" class="small mb-0 text-justify">
                                Pronto a comprare o vendere qualcosa? Continua a supportare i tuoi artisti preferiti o a diffondere la tua arte. Noi siamo qui per te.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
        <?php include "../common/footer.php"; ?>



        <script> 
            function toggleArtistFields() { 
                var isArtist = document.getElementById('artist').checked; 
                var artistFields = document.getElementById('artistFields'); 
                var dynamicImage = document.getElementById('dynamicImage');
                var dynamicText = document.getElementById('dynamicText');
                if (isArtist) { 
                    artistFields.classList.remove('hidden'); 
                    dynamicImage.src = "../media/artist_signup.png"; //percorso dell'immagine per l'artista 
                    dynamicImage.style.display = 'block';
                    dynamicText.innerHTML = "Un nuovo artista!<br>Diffondi la tua arte con i tuoi fan. Siamo qui per aiutarti a brillare!";
                } else { 
                    artistFields.classList.add('hidden'); 
                    dynamicImage.src = "../media/fan_signup.png"; //percorso dell'immagine per il fan 
                    dynamicImage.style.display = 'block';
                    dynamicText.innerHTML = "Un nuovo fan!<br>Continua a supportare i tuoi artisti preferiti e scopri nuova musica. Siamo qui per te!";
                }
            } 
        </script>
    </body>


</html>