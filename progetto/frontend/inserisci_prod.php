<!DOCTYPE html>
<?php
    session_start();
    require "../common/setup.php";
    require "../common/funzioni.php";
    $_SESSION["page"]="inserisci_prod";
    if (isset($_GET["status"])) { #guardo se settato status (quindi sto entrando nella pagina da un'altra pagina -> in questo caso capita se arrivo da signup-exe.php)
        if ($_GET["status"]=="ko") { #e nel caso lo status fosse ko
            $errore_insert = unserialize($_GET["errore_insert"]); #se ko allora c'era errore -> lo prendo
        }
    }

    //controllo per impedire accesso dall'url
    if (!isset($_SESSION["logged"]) || $_SESSION["tipologia"] != "artista") {
        header("Location: ../index.php");
        exit;
    }
    
?>

<html>
    <?php include "../common/head.php"; ?>
    <link rel="stylesheet" href="../css/style.css">
<body class='bg-image'>
    <?php include "../common/navbar.php"; ?>
    <div class="container d-flex p-2 p-sm-1 p-md-3 p-lg-5 p-xl-5 justify-content-center align-items-center">
        <div class="card p-4 shadow-lg" style="width: 500px; max-width: 100%;">
            <h5 class="card-title text-center">Inserimento prodotto</h5>
            <?php
                if(isset($errore_insert["msg"])) { #se c'era errore, indico con un badge-danger che tipo di errore c'era
                    echo "<span class='badge badge-pill badge-danger' style='background-color: red'>". $errore_insert["msg"] ."</span>";
                }?>
            <form method="POST" action="../backend/inserisci_prod-exe.php" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="productName">Nome prodotto</label>
                    <input type="text" class="form-control" id="productName" name="name" placeholder="Inserisci il nome del prodotto" required>
                </div>
                <div class="form-group mb-3">
                    <label for="categoria">Categoria</label>
                    <select class="form-control" id="categoria" name ="categoria">
                        <option>CD</option>
                        <option>Vinile</option>
                        <option>T-shirt</option>
                        <option>Calze</option>
                        <option>Cappello</option>
                        <option>Felpa</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="productPrice">Prezzo</label>
                    <input type="number" class="form-control" id="productPrice" name="price" placeholder="Inserisci il prezzo" min="0" step="0.01" required>
                </div>
                <div class="form-group mb-3">
                    <label for="sconto">Vuoi inserire uno sconto?</label>
                    <input type="number" class="form-control" id="sconto" name="sconto" placeholder="0%" min="0" step="0.01">
                </div>
                <div class="form-group mb-3">
                    <label for="descProdotto">Descrizione</label>
                    <textarea class="form-control" id="descProdotto" rows="3" name= "descrizione" placeholder="Inserisci una descrizione del prodotto"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="imgProdotto">Immagine prodotto</label>
                    <input type="file" class="form-control" id="imgProdotto" name= "immagine_prodotto">
                    <?php
                        if(isset($errore_insert) AND $errore_insert["tipo"]=="1") { #se c'era errore -> le password non corrispondono
                            echo "<p style='color:red;'><small>". $errore_insert["msg"] ."</small></p>";
                        }
                    ?>
                </div>
                <button type="submit" class="btn btn-primary w-100 gradient-custom-2">Inserisci</button>
            </form>
        </div>
    </div>
    <?php include "../common/footer.php"; ?> <!--includo il footer-->

    <!--limite dello sconto -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var scontoField = document.getElementById("sconto");

            scontoField.addEventListener("input", function() {
                var scontoValue = parseFloat(scontoField.value);

                if (scontoValue > 95) {
                    scontoField.value = 95;
                }
            });
        });
    </script>
</body>
</html>
