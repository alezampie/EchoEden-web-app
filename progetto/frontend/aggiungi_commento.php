<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

if (isset($_SESSION["page"]) && $_SESSION["page"] == "index") {
    $_SESSION["page"] = "aggiungi_commento_from_index";
} else {
    $_SESSION["page"] = "aggiungi_commento";
}

//controllo per impedire accesso dall'url
if (!isset($_SESSION["logged"]) || $_SESSION["tipologia"] != "fan" || !isset($_GET["id_prodotto"])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET["status"]) && $_GET["status"] == "ko") {
    if (isset($_GET["errore_commento"])) {
        $errore_commento = unserialize($_GET["errore_commento"]);
    }
}

$id_prodotto = intval($_GET["id_prodotto"]);
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html>
    <?php include "../common/head.php"; ?>
    <link rel="stylesheet" href="../css/style.css">

    <body class='bg-image'>
        <div class="container p-2 p-sm-1 p-md-3 p-lg-5 p-xl-5">
            <div class="card rounded-3 text-black">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="card-body p-md-5 mx-md-4">
                            <div class="text-center mb-4">
                                <img class="rounded-circle" style="width: 100px;" src="../media/ECHOEDEN_logo_quadrato.png" alt="logo">
                                <h4 class="mt-2">Lascia un commento</h4>
                                <?php
                                if (isset($errore_commento["msg"])) {
                                    echo "<span class='badge badge-pill badge-danger' style='background-color: red'>" . $errore_commento["msg"] . "</span>";
                                }
                                ?>
                            </div>

                            <form method="POST" action="../backend/aggiungi_commento-exe.php">
                                <input type="hidden" name="fan" value="<?php echo htmlspecialchars($username); ?>">
                                <input type="hidden" name="prodotto" value="<?php echo $id_prodotto; ?>">

                                <div class="form-group mb-3">
                                    <label for="descrizione">Commento</label>
                                    <textarea class="form-control" id="descrizione" name="descrizione" maxlength="500" rows="4" required placeholder="Scrivi la tua recensione (max 500 caratteri)"></textarea>
                                    <small class="form-text text-muted">Max 500 caratteri.</small>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="voto">Valutazione</label>
                                    <select class="form-control" id="voto" name="voto" required>
                                        <option value="" disabled selected>Seleziona un voto</option>
                                        <option value="0">0 - Pessimo</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3 - Nella media</option>
                                        <option value="4">4</option>
                                        <option value="5">5 - Ottimo</option>
                                    </select>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success">Invia commento</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex align-items-center bg-custom-1-login">
                        <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                            <h4 class="mb-4">Condividi la tua opinione</h4>
                            <p class="small mb-0">
                                Raccontaci cosa pensi di questo prodotto. Il tuo feedback aiuta altri fan e sostiene gli artisti!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "../common/footer.php"; ?>
    </body>
</html>
