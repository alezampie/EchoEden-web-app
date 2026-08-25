<!DOCTYPE html>
<?php
    session_start();
    $_SESSION["page"]="index"; #dico che la variabile di sessione page è settata su index (per dire che ci troviamo nella pagina index)
    require "common/setup.php";
    require "common/funzioni.php";
    if (isset($_GET["status"])) { #guardo se settato status (quindi sto entrando nella pagina da un altra pagina
        if ($_GET["status"]=="ko") { #e nel caso lo status fosse ko
            $errore_elimina= unserialize($_GET["errore_elimina"]); #se ko allora c'era errore -> lo prendo
        }
    }

    #se torno dalla ricerca, tolgo le variabili di sessione che riguardano i filtri, sennò quando torno 
    #alla pagina di ricerca sono ancora settati e noi vogliamo resettarli
    unset($_SESSION['parametro']);
    unset($_SESSION['categorie']);
    unset($_SESSION['prezzo_min']);
    unset($_SESSION['prezzo_max']);
    unset($_SESSION['sconto']);
    unset($_SESSION['prodotti']);
?>

<html>
    
    <?php include "common/head.php"; ?> <!--includo l'header-->
    <!--il css lo devo aggiungere manualemnte perchè nell'header importato non posso sapere dove è situato il file nel percorso-->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    

    <body class='bg-image' data-page="<?php echo $_SESSION['page']; ?>">
        <?php include "common/navbar.php"; ?> <!--includo la navbar-->
        <?php include "common/common_modal.php"; ?> <!--includo modale comuni-->

        <!--guardo se signup, inserimento o modifica per fare l'alert-->
        <?php
            if (isset($_SESSION['signup_success'])) {
                $prov = "";
                if (isset($_SESSION['tipologia'])) { $prov = $_SESSION['tipologia']; }
                if ($prov == "admin") {
                    ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>hai inserito un nuovo admin</strong> operazione di inserimento nuovo admin avvenuta con successo!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Attesa approvazione</strong> Operazione di signup avvenuta con successo! Ora devi solo aspettare l'approvazione da parte di un admin. Attendi un po' e poi esegui il login!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                }
                unset($_SESSION['signup_success']);
                unset($prov);
            }

            if (isset($_SESSION['insert_success'])) {
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Inserimento avvenuto con successo!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
                unset($_SESSION['insert_success']);
            }
            if (isset($_SESSION['order_error'])) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Qualcosa è andato storto...</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
                unset($_SESSION['order_error']);
            }

            if (isset($_SESSION['modifica_success'])) {
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Modifica avvenuta con successo!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
                unset($_SESSION['modifica_success']);
            }

            if (isset($_SESSION['elimina_success'])) {
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Eliminazione avvenuta con successo!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
                unset($_SESSION['elimina_success']);
            }
        ?>



        <?php
            $tipologia = "";
            if(isset($_SESSION["tipologia"])) { $tipologia = $_SESSION["tipologia"]; }
            if ((!isset($_SESSION["logged"])) OR ($tipologia=="fan")) { #se non entro nella pagina come loggato o entro come fan
                #home page visualizzata normalmente
                ?>
                <div class="container mt-2 mt-sm-3 mt-md-4 mn-2 mb-sm-3 mb-md-4" > 
                    <div class="row justify-content-center"> 
                        <div class="col-md-8 col-12 d-flex align-items-center justify-content-center bg-custom-1-homepage"> 
                            <img src="media/homepage_description.png" alt="Foto" class="img-fluid"> 
                        </div> 
                        <div class="col-md-4 col-12 d-flex flex-column align-items-center justify-content-center bg-custom-2-homepage"> 
                            <p class="m-0 text-justify mt-3">EchoEden è il posto dove fan e artisti si incontrano per diffondere e supportare arte e creatività. Ci potete trovare anche nei soliti social che tutti conosciamo:</p>
                            <div class="mt-3"> 
                                <a href="https://www.instagram.com/echoeden_merch/" class="text-white mx-4"><i class="fab fa-instagram fa-2x"></i></a> 
                                <a href="https://x.com/ECHOEDEN_merch" class="text-white mx-4"><i class="fab fa-twitter fa-2x"></i></a> 
                                <a href="https://www.facebook.com/profile.php?id=61569829212781" class="text-white mx-4"><i class="fab fa-facebook fa-2x"></i></a> 
                            </div> 
                            <p class="mt-3 text-center" style="font-size: 0.7rem;">Clicca sulle icone per andare sui social, oppure clicca ↝ <a href="#" data-toggle="modal" data-target="#qrModal" class="text-white h6">qui</a> ↜ per visualizzare i qr code.</p>
                        </div> 
                    </div>
                </div>

                <!-- sezione sconti -->
                <div id="sconti" class="container mt-5 mb-5"> 
                    <div class="row justify-content-center"> 
                        <div class="col-lg-10 col-md-11 col-sm-12"> 
                            <img src="media/sezione_sconti.png" class="img-fluid mx-auto d-block" style="border-width: 2px;"> 
                            <?php 
                            $prodotto = getSconto($cid, 'prodotto'); ?>
                            <div class="container mt-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="row g-4">
                                            <?php if (mysqli_num_rows($prodotto) > 0) {
                                                foreach ($prodotto as $item) { ?>
                                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div class="card h-100 d-flex flex-column justify-content-between custom-card" style="min-height: 400px;">
                                                            <div style="position: relative; width: 100%; height: 200px; overflow: hidden; display: flex; justify-content: center;">
                                                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('uploads/prodotti/<?=$item['immagine']; ?>') center center / cover; filter: blur(10px);">
                                                                </div>
                                                                <img src="uploads/prodotti/<?=$item['immagine']; ?>" 
                                                                    alt="<?= $item['nome']; ?>" 
                                                                    style="position: relative; z-index: 1; max-width: 100%; max-height: 100%; object-fit: contain;">
                                                            </div>
                                                            <div class="card-body d-flex flex-column">
                                                                <h5 class="card-title"><?= $item['nome']; ?></h5>
                                                                <p class="card-text flex-grow-1"><?= $item['descrizione']; ?></p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <?php if ($item['sconto'] == 0) { ?>
                                                                    <li class="list-group-item">Prezzo: <?= number_format($item['prezzo'], 2, ',', '.'); ?>€</li>
                                                                <?php } else { ?>
                                                                    <li class="list-group-item">Prezzo: <span class="text-decoration-line-through me-2"><?= number_format($item['prezzo'], 2, ',', '.'); ?>€</span>
                                                                        <span class="fw-bold me-2"><?= number_format(($item['prezzo'] * (100 - $item['sconto'])) / 100, 2, ',', '.'); ?>€</span>
                                                                        <span class="badge bg-success">-<?= $item['sconto']; ?>%</span></li>
                                                                <?php } ?>
                                                                <li class="list-group-item">Categoria: <?= $item['categoria']; ?></li>
                                                                <li class="list-group-item">Artista: <?= $item['artista']; ?></li>
                                                            </ul>
                                                            <div class="card-footer">
                                                            <?php if ($tipologia == "fan") { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-prodotto-id="<?= $item['id_prodotto']; ?>"  data-bs-target="#carrello">Aggiungi al carrello</button>
                                                            <?php } else  { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-bs-target="#iscriviti">Aggiungi al carrello</button>
                                                            <?php }?> 
                                                                <div class="text-center mt-2">
                                                                    <button type="button" class="btn btn-sm btn-commenti" data-bs-toggle="modal" data-bs-target="#commentiModal" data-prodotto-id="<?= $item['id_prodotto']; ?>">Visualizza commenti</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } else {
                                                echo "<div class='col-12 text-center'>Nessun prodotto in sconto :(</div>";
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pulsante per vedere tutti i prodotti -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <form class="form-search d-inline-block more" method="GET" action="backend/ricerca-exe.php">
                                            <input type="hidden" name="categorie" value="">
                                            <input type="hidden" name="prezzo_min" value="0">
                                            <input type="hidden" name="prezzo_max" value="1000">
                                            <input type="hidden" name="sconto" value="1">
                                            <button class="btn">
                                                Vedi tutti gli sconti
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sezione CD -->
                <div id="CD" class="container mt-5 mb-5"> 
                    <div class="row justify-content-center"> 
                        <div class="col-lg-10 col-md-11 col-sm-12"> 
                            <img src="media/sezione_cd.png" class="img-fluid mx-auto d-block" style="border-width: 2px;"> 
                            <?php 
                            $prodotto = getCD($cid, 'prodotto'); ?>
                            <div class="container mt-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="row g-4">
                                            <?php if (mysqli_num_rows($prodotto) > 0) {
                                                foreach ($prodotto as $item) { ?>
                                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div class="card h-100 d-flex flex-column justify-content-between custom-card" style="min-height: 400px;">
                                                            <div style="position: relative; width: 100%; height: 200px; overflow: hidden; display: flex; justify-content: center;">
                                                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('uploads/prodotti/<?=$item['immagine']; ?>') center center / cover; filter: blur(10px);">
                                                                </div>
                                                                <img src="uploads/prodotti/<?=$item['immagine']; ?>" 
                                                                    alt="<?= $item['nome']; ?>" 
                                                                    style="position: relative; z-index: 1; max-width: 100%; max-height: 100%; object-fit: contain;">
                                                            </div>
                                                            <div class="card-body d-flex flex-column">
                                                                <h5 class="card-title"><?= $item['nome']; ?></h5>
                                                                <p class="card-text flex-grow-1"><?= $item['descrizione']; ?></p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <?php if ($item['sconto'] == 0) { ?>
                                                                    <li class="list-group-item">Prezzo: <?= number_format($item['prezzo'], 2, ',', '.'); ?>€</li>
                                                                <?php } else { ?>
                                                                    <li class="list-group-item">Prezzo: <span class="text-decoration-line-through me-2"><?= number_format($item['prezzo'], 2, ',', '.'); ?>€</span>
                                                                        <span class="fw-bold me-2"><?= number_format(($item['prezzo'] * (100 - $item['sconto'])) / 100, 2, ',', '.'); ?>€</span>
                                                                        <span class="badge bg-success">-<?= $item['sconto']; ?>%</span></li>
                                                                <?php } ?>
                                                                <li class="list-group-item">Categoria: <?= $item['categoria']; ?></li>
                                                                <li class="list-group-item">Artista: <?= $item['artista']; ?></li>
                                                            </ul>
                                                            <div class="card-footer">
                                                            <?php if ($tipologia == "fan") { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-prodotto-id="<?= $item['id_prodotto']; ?>"  data-bs-target="#carrello">Aggiungi al carrello</button>
                                                            <?php } else  { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-bs-target="#iscriviti">Aggiungi al carrello</button>
                                                            <?php }?>
                                                                <div class="text-center mt-2">
                                                                    <button type="button" class="btn btn-sm btn-commenti" data-bs-toggle="modal" data-bs-target="#commentiModal" data-prodotto-id="<?= $item['id_prodotto']; ?>">Visualizza commenti</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } else {
                                                echo "Nessun CD in vendita";
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pulsante per vedere tutti prodotti -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <form class="form-search d-inline-block more" method="GET" action="backend/ricerca-exe.php">
                                            <input type="hidden" name="categorie" value="CD">
                                            <input type="hidden" name="prezzo_min" value="0">
                                            <input type="hidden" name="prezzo_max" value="1000">
                                            <input type="hidden" name="sconto" value="0">
                                            <button class="btn">
                                                Vedi tutti i CD
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sezione vinili -->
                <div id="Vinili" class="container mt-5 mb-5"> 
                    <div class="row justify-content-center"> 
                        <div class="col-lg-10 col-md-11 col-sm-12"> 
                            <img src="media/sezione_vinili.png" class="img-fluid mx-auto d-block" style="border-width: 2px;"> 
                            <?php 
                            $prodotto = getVinili($cid, 'prodotto'); ?>
                            <div class="container mt-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="row g-4">
                                            <?php if (mysqli_num_rows($prodotto) > 0) {
                                                foreach ($prodotto as $item) { ?>
                                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div class="card h-100 d-flex flex-column justify-content-between custom-card" style="min-height: 400px;">
                                                            <div style="position: relative; width: 100%; height: 200px; overflow: hidden; display: flex; justify-content: center;">
                                                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('uploads/prodotti/<?=$item['immagine']; ?>') center center / cover; filter: blur(10px);">
                                                                </div>
                                                                <img src="uploads/prodotti/<?=$item['immagine']; ?>" 
                                                                    alt="<?= $item['nome']; ?>" 
                                                                    style="position: relative; z-index: 1; max-width: 100%; max-height: 100%; object-fit: contain;">
                                                            </div>
                                                            <div class="card-body d-flex flex-column">
                                                                <h5 class="card-title"><?= $item['nome']; ?></h5>
                                                                <p class="card-text flex-grow-1"><?= $item['descrizione']; ?></p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <?php if ($item['sconto'] == 0) { ?>
                                                                    <li class="list-group-item">Prezzo: <?= number_format($item['prezzo'], 2, ',', '.'); ?>€</li>
                                                                <?php } else { ?>
                                                                    <li class="list-group-item">Prezzo: <span class="text-decoration-line-through me-2"><?= number_format($item['prezzo'], 2, ',', '.'); ?>€</span>
                                                                        <span class="fw-bold me-2"><?= number_format(($item['prezzo'] * (100 - $item['sconto'])) / 100, 2, ',', '.'); ?>€</span>
                                                                        <span class="badge bg-success">-<?= $item['sconto']; ?>%</span></li>
                                                                <?php } ?>
                                                                <li class="list-group-item">Categoria: <?= $item['categoria']; ?></li>
                                                                <li class="list-group-item">Artista: <?= $item['artista']; ?></li>
                                                            </ul>
                                                            <div class="card-footer">
                                                            <?php if ($tipologia == "fan") { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-prodotto-id="<?= $item['id_prodotto']; ?>"  data-bs-target="#carrello">Aggiungi al carrello</button>
                                                            <?php } else  { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-bs-target="#iscriviti">Aggiungi al carrello</button>
                                                            <?php }?>
                                                                <div class="text-center mt-2">
                                                                    <button type="button" class="btn btn-sm btn-commenti" data-bs-toggle="modal" data-bs-target="#commentiModal" data-prodotto-id="<?= $item['id_prodotto']; ?>">Visualizza commenti</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } else {
                                                echo "nessun vinile in vendita";
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pulsante per vedere tutti i prodotti -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <form class="form-search d-inline-block more" method="GET" action="backend/ricerca-exe.php">
                                            <input type="hidden" name="categorie" value="Vinile">
                                            <input type="hidden" name="prezzo_min" value="0">
                                            <input type="hidden" name="prezzo_max" value="1000">
                                            <input type="hidden" name="sconto" value="0">
                                            <button class="btn more-btn">
                                                Vedi tutti i vinili
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                                
                <!-- sezione magliette -->
                <div id="Shirt" class="container mt-5 mb-5"> 
                    <div class="row justify-content-center"> 
                        <div class="col-lg-10 col-md-11 col-sm-12"> 
                            <img src="media/sezione_shirt.png" class="img-fluid mx-auto d-block" style="border-width: 2px;"> 
                            <?php 
                            $prodotto = getShirt($cid, 'prodotto'); ?>
                            <div class="container mt-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="row g-4">
                                            <?php if (mysqli_num_rows($prodotto) > 0) {
                                                foreach ($prodotto as $item) { ?>
                                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div class="card h-100 d-flex flex-column justify-content-between custom-card" style="min-height: 400px;">
                                                            <div style="position: relative; width: 100%; height: 200px; overflow: hidden; display: flex; justify-content: center;">
                                                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('uploads/prodotti/<?=$item['immagine']; ?>') center center / cover; filter: blur(10px);">
                                                                </div>
                                                                <img src="uploads/prodotti/<?=$item['immagine']; ?>" 
                                                                    alt="<?= $item['nome']; ?>" 
                                                                    style="position: relative; z-index: 1; max-width: 100%; max-height: 100%; object-fit: contain;">
                                                            </div>
                                                            <div class="card-body d-flex flex-column">
                                                                <h5 class="card-title"><?= $item['nome']; ?></h5>
                                                                <p class="card-text flex-grow-1"><?= $item['descrizione']; ?></p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <?php if ($item['sconto'] == 0) { ?>
                                                                    <li class="list-group-item">Prezzo: <?= number_format($item['prezzo'], 2, ',', '.'); ?>€</li>
                                                                <?php } else { ?>
                                                                    <li class="list-group-item">Prezzo: <span class="text-decoration-line-through me-2"><?= number_format($item['prezzo'], 2, ',', '.'); ?>€</span>
                                                                        <span class="fw-bold me-2"><?= number_format(($item['prezzo'] * (100 - $item['sconto'])) / 100, 2, ',', '.'); ?>€</span>
                                                                        <span class="badge bg-success">-<?= $item['sconto']; ?>%</span></li>
                                                                <?php } ?>
                                                                <li class="list-group-item">Categoria: <?= $item['categoria']; ?></li>
                                                                <li class="list-group-item">Artista: <?= $item['artista']; ?></li>
                                                            </ul>
                                                            <div class="card-footer">
                                                            <?php if ($tipologia == "fan") { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-prodotto-id="<?= $item['id_prodotto']; ?>"  data-bs-target="#carrello">Aggiungi al carrello</button>
                                                            <?php } else  { ?>
                                                                <button type="button" class="btn btn-primary btn-buy w-100" data-bs-toggle="modal" data-bs-target="#iscriviti">Aggiungi al carrello</button>
                                                            <?php }?>
                                                                <div class="text-center mt-2">
                                                                    <button type="button" class="btn btn-sm btn-commenti" data-bs-toggle="modal" data-bs-target="#commentiModal" data-prodotto-id="<?= $item['id_prodotto']; ?>">Visualizza commenti</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } else {
                                                echo "nessuna t-shirt in vendita";
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pulsante per vedere tutti i prodotti -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <form class="form-search d-inline-block more" method="GET" action="backend/ricerca-exe.php">
                                            <input type="hidden" name="categorie" value="T-shirt">
                                            <input type="hidden" name="prezzo_min" value="0">
                                            <input type="hidden" name="prezzo_max" value="1000">
                                            <input type="hidden" name="sconto" value="0">
                                            <button class="btn">
                                                Vedi tutte le T-shirt
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal qr --> 
                 <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true"> 
                    <div class="modal-dialog modal-dialog-centered"> 
                        <div class="modal-content bg-dark text-white"> 
                            <div class="modal-header"> 
                                <h5 class="modal-title" id="qrModalLabel">QR Code dei nostri social</h5> 
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                                    <span aria-hidden="true">&times;</span> 
                                </button> 
                            </div> 
                            <div class="modal-body"> 
                                <div class="d-flex justify-content-around"> 
                                    <div class="text-center mx-2"> 
                                        <img src="media/instagram_qr.png" class="img-fluid"> 
                                        <p class="small mt-2">instagram</p> 
                                    </div> 
                                    <div class="text-center mx-2"> 
                                        <img src="media/twitter_qr.png" class="img-fluid"> 
                                        <p class="small mt-2">twitter</p> 
                                    </div>
                                    <div class="text-center mx-2"> 
                                        <img src="media/facebook_qr.png" class="img-fluid"> 
                                        <p class="small mt-2">facebook</p> 
                                    </div> 
                                </div> 
                            </div>
                            <div class="modal-footer"> 
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                            </div> 
                        </div> 
                    </div> 
                </div>

                <?php
            } else { #se ho eseguito il login, quindi sono nella homepage come "utente loggato"
                if($tipologia=="admin") { #se homepage come admin
                    ?>
                    <div class="container h-100 d-flex flex-column justify-content-center mt-4"> 
                        <div class="row justify-content-center">
                            <!-- Card 1 --> 
                            <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                                <!--gestione utenti-->
                                <div class="card w-100 d-flex flex-column bg-custom-1-admin border-dark">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">Gestione utenti</h5>
                                        <p class="card-text">Tu qui hai il potere di approvare o bandire utenti. Usa questa facoltà con cura e logica.</p>
                                        <div class="mt-auto"> 
                                            <a href="frontend/gestione_utenti.php" class="btn btn-primary mx-auto d-block" style="background-color: #c1ff72; border-color: #c1ff72; color: #000;">Gestione utenti</a> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 2 --> 
                            <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                                <!--gestione prodotti-->
                                <div class="card w-100 d-flex flex-column bg-custom-2-admin border-dark">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">Gestione prodotti</h5>
                                        <p class="card-text">Stanno vendendo qualcosa di illegale? Elimina prodotti non conformi. Elimina commenti inadeguati</p>
                                        <div class="mt-auto w-100"> 
                                        <form class="d-flex align-items-center mx-auto form-search w-100" method="GET" action="<?php echo $pre_path; ?>backend/ricerca-exe.php">
                                            <div class="input-group w-100">
                                                <input type="search" name="search" 
                                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : (isset($_SESSION['parametro']) ? htmlspecialchars($_SESSION['parametro'], ENT_QUOTES, 'UTF-8') : '') ?>" 
                                                id="parametro_principale" 
                                                class="form-control" 
                                                placeholder="Ricerca artisti o prodotti" 
                                                aria-label="Search" />
                                            </div>

                                            <input type="hidden" name="categorie" value="<?= isset($_SESSION['categorie']) ? $_SESSION['categorie'] : '' ?>">
                                            <input type="hidden" name="prezzo_min" value="<?= isset($_SESSION['prezzo_min']) ? $_SESSION['prezzo_min'] : 0 ?>">
                                            <input type="hidden" name="prezzo_max" value="<?= isset($_SESSION['prezzo_max']) ? $_SESSION['prezzo_max'] : 1000 ?>">

                                            <input type="hidden" name="sconto" value="<?= isset($_SESSION['sconto']) && $_SESSION['sconto'] ? 1 : 0 ?>">

                                            <button type="submit" class="btn btn-link text-white">
                                                <i class="fas fa-search ps-3"></i>
                                            </button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 3 --> 
                            <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                                <!--inserimento nuovo admin-->
                                <div class="card w-100 d-flex flex-column bg-custom-3-admin border-dark">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">Inserimento admin</h5>
                                        <p class="card-text">Da solo non riesci a gestire il tuo impero? Fatti aiutare da qualcuno, ma stai attento e scegli bene con chi diventare socio. Avrà i tuoi stessi poteri.</p>
                                        <div class="mt-auto"> 
                                            <a href="frontend/inserimento_admin.php" class="btn btn-primary mx-auto d-block" style="background-color: #c1ff72; border-color: #c1ff72; color: #000;">Inserisci admin</a> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <?php
                } else if($tipologia=="artista") {
                    if(isset($errore_elimina["msg"])) {?> 
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>L'eliminazione non è andata a buon fine: <?=$errore_elimina["msg"]?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> <?php }
                    $prodotto = getByArtist($cid,'prodotto',$_SESSION['username']);?>
                    <div class="container mt-4">
                        <div class="row g-4">
                            <?php if (mysqli_num_rows($prodotto) > 0) {
                                foreach ($prodotto as $item) { ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="card h-100 d-flex flex-column justify-content-between custom-card" style="min-height: 400px;">
                                        <div style="position: relative; width: 100%; height: 200px; overflow: hidden; display: flex; justify-content: center;">
                                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('uploads/prodotti/<?=$item['immagine']; ?>') center center / cover; filter: blur(10px);">
                                            </div>
                                            <img src="uploads/prodotti/<?=$item['immagine']; ?>" 
                                                alt="<?= $item['nome']; ?>" 
                                                style="position: relative; z-index: 1; max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                            <div class="card-body">
                                            <h5 class="card-title"><?= $item['nome']; ?></h5>
                                            <p class="card-text"><?= $item['descrizione']; ?></p>
                                        </div>
                                            <ul class="list-group list-group-flush">
                                                <?php if ($item['sconto']== 0){  ?>
                                                    <li class="list-group-item">Prezzo: <?= number_format($item['prezzo'], 2, ',', '.');?>€</li>
                                                <?php } else { ?>
                                                    <li class="list-group-item">Prezzo: <span class= "text-decoration-line-through me-2"><?= number_format($item['prezzo'], 2, ',', '.');?>€</span>
                                                    <span class="fw-bold me-2"> <?= number_format(($item['prezzo']*(100-$item['sconto']))/100,2,',','.'); ?>€</span>
                                                    <span class="badge bg-success">-<?= $item['sconto']; ?>%</span></li> <?php } ?>
                                                <li class="list-group-item">Categoria: <?= $item['categoria']; ?></li>
                                                <li class="list-group-item">Artista: <?= $item['artista']; ?></li>
                                            </ul>
                                            <div class="card-body d-flex flex-column align-items-center gap-2">
                                            <div class="d-flex justify-content-center gap-2 w-100">
                                                <form action="frontend/modifica_prod.php" method="GET">
                                                    <input type="hidden" name="id" value="<?= $item['id_prodotto']; ?>">
                                                    <input type="hidden" name="status" value="ok">
                                                    <button type="submit" class="btn btn-primary modifica-btn">
                                                        Modifica
                                                    </button>
                                                </form>
                                                <form action="backend/elimina_prod.php" method="POST">
                                                    <input type="hidden" name="id" value="<?= $item['id_prodotto']; ?>">
                                                    <button type="submit" class="btn btn-primary elimina-btn">
                                                        Elimina
                                                    </button>
                                                </form>
                                            </div>
                                            <button type="button" class="btn btn-viola btn-commenti w-100 mt-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#commentiModal"
                                                    data-prodotto-id="<?= $item['id_prodotto']; ?>">
                                                Commenti
                                            </button>
                                        </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Nessun prodotto in vendita, comincia a vendere!";
                            }?>
                        </div>
                    </div>
            
                    <div class="container d-flex justify-content-center align-items-start flex-wrap gap-4 mt-5">

                    <!-- Card 1 -->
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card w-100 d-flex flex-column bg-custom-1-admin border-dark">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Vendi i tuoi prodotti</h5>
                            <p class="card-text">Inserisci un prodotto da mettere in vendita!</p>
                            <div class="mt-auto"> 
                            <form action="frontend/inserisci_prod.php" method="GET">
                                <input type="hidden" name="status" value="ok">
                                <button type="submit" class="btn btn-primary mx-auto d-block" style="background-color: #c1ff72; border-color: #c1ff72; color: #000;">Vendi</button> 
                            </form>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card w-100 d-flex flex-column bg-custom-1-admin border-dark">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Ordini</h5>
                            <p class="card-text">Gestisci gli ordini dei tuoi fan!</p>
                            <div class="mt-auto"> 
                            <form action="backend/ricerca_ordini-exe.php" method="GET">
                                <input type="hidden" name="status" value="ok">
                                <button type="submit" class="btn btn-primary mx-auto d-block" style="background-color: #c1ff72; border-color: #c1ff72; color: #000;">Visualizza ordini</button> 
                            </form>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>


                    <?php
                } else { #se entro in index non attraverso altre pagine che vogliono permettere operazione, ma magari perchè prima volta che entro o dopo un normale login
                    #non c'è operazione ma sono loggato. Per debug indico email, username e tipologia di utente
                    echo "<div class=\"well\">";
                    echo "<p>ciao utente con email: $_SESSION[email] username: $_SESSION[username] tipologia: $_SESSION[tipologia]</p>";
                    echo "</div>";
                }
            }   
        ?>

        <?php include "common/footer.php"; ?> <!--includo il footer-->
        <script src="js/carrello.js"></script>
        <script> const prePath = "<?= (isset($_SESSION['page']) && $_SESSION['page'] != 'index') ? '../' : '' ?>";</script>
        <script src="js/commenti.js"></script>
    </body>

</html>