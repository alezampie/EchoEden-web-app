<!DOCTYPE html>
<?php
    session_start();
    require "../common/setup.php";
    require "../common/funzioni.php";
    $_SESSION["page"]="modifica_prod";
    if (isset($_GET["status"])) { #guardo se settato status (quindi sto entrando nella pagina da un'altra pagina -> in questo caso capita se arrivo da signup-exe.php)
        if ($_GET["status"]=="ko") { #e nel caso lo status fosse ko
            $errore_modifica= unserialize($_GET["errore_modifica"]); #se ko allora c'era errore -> lo prendo
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
        <?php if(isset($_GET['id'])){
                $id = $_GET['id'];
                $prodotto = getById($cid,"prodotto",$id);

                if(mysqli_num_rows($prodotto) > 0){ 
                    $data = mysqli_fetch_array($prodotto);
                    ?>
            <div class="card p-4 shadow-lg" style="width: 500px; max-width: 100%;">
            <h5 class="card-title text-center">Modifica prodotto</h5>
            <?php
                if(isset($errore_modifica["msg"])) { #se c'era errore, indico con un badge-danger che tipo di errore c'era
                    echo "<span class='badge badge-pill badge-danger' style='background-color: red'>". $errore_modifica["msg"] ."</span>";
                }?>
            <form method="POST" action="../backend/modifica_prod-exe.php" enctype="multipart/form-data" id="updateProductForm">
            <div class="form-group mb-3">
            <input type="hidden" name="id_prodotto" value="<?=$data['id_prodotto']; ?>">
            <label for="productName">Nome prodotto</label>
            <input type="text" class="form-control" id="productName" name="name" value="<?= $data['nome']; ?>" placeholder="Inserisci il nome del prodotto" required>
                </div>
                <div class="form-group mb-3">
                    <label for="categoria">Categoria</label>
                    <select class="form-control" id="categoria" name ="categoria">
                        <option value="CD" <?= ($data['categoria'] == 'CD') ? 'selected' : ''; ?>>CD</option>
                        <option value="Vinile" <?= ($data['categoria'] == 'Vinile') ? 'selected' : ''; ?>>Vinile</option>
                        <option value="T-shirt" <?= ($data['categoria'] == 'T-shirt') ? 'selected' : ''; ?>>T-shirt</option>
                        <option value="Calze" <?= ($data['categoria'] == 'Calze') ? 'selected' : ''; ?>>Calze</option>
                        <option value="Cappello" <?= ($data['categoria'] == 'Cappello') ? 'selected' : ''; ?>>Cappello</option>
                        <option value="Felpa" <?= ($data['categoria'] == 'Felpa') ? 'selected' : ''; ?>>Felpa</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                <label for="productPrice">Prezzo</label>
                <input type="number" class="form-control" id="productPrice" name="price" value="<?= $data['prezzo']; ?>" placeholder="Inserisci il prezzo" min="0" step="0.01" required>
                </div>
                <div class="form-group mb-3">
                <label for="sconto">Vuoi inserire uno sconto?</label>
                <input type="number" class="form-control" id="sconto" name="sconto" value="<?= $data['sconto']; ?>" placeholder="0%" min="0" step="0.01">
                </div>
                <div class="form-group mb-3">
                    <label for="descProdotto">Descrizione</label>
                    <textarea class="form-control" id="descProdotto" rows="3" name= "descrizione" placeholder="Inserisci una descrizione del prodotto"><?= $data['descrizione']; ?></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="imgProdotto">Immagine prodotto</label>
                    <input type="file" class="form-control mb-3" id="imgProdotto" name= "imgProdotto">
                    <?php
                        if(isset($errore_modifica) AND $errore_modifica["tipo"]=="1") { 
                            echo "<p style='color:red;'><small>". $errore_modifica["msg"] ."</small></p>";
                            }
                    ?>
                    <label for="">Immagine corrente:</label>
                    <input type="hidden" name="vecchia_immagine" value="<?=$data['immagine']?>">
                    <img src="../uploads/prodotti/<?=$data['immagine']; ?>" alt ="" height="50px" width="50px">
                </div>
                <button type="button" class="btn btn-danger" onclick="window.history.back();">Annulla modifiche</button>
                <button type="submit" class="btn btn-primary" style="background-color:rgb(9, 62, 153); color: rgb(255,255,255);">Aggiorna</button>
            </form>
        </div>
               <?php } else {
                    echo "prodotto non trovato";
               }
            ?>
        <?php   } else {
                    echo "something went wrong";
        } ?>
    </div>
    <?php include "../common/footer.php"; ?> <!--includo il footer-->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var scontoField = document.getElementById("sconto");
            var updateProductForm = document.getElementById("updateProductForm");
            function validateSconto() {
                var scontoValue = parseFloat(scontoField.value);               
                if (scontoValue > 95) {
                    scontoField.value = 95;
                }
            }
            scontoField.addEventListener("input", validateSconto);

            updateProductForm.addEventListener("submit", function(event) {
                validateSconto();  
            });
        });
    </script>
</body>
</html>
