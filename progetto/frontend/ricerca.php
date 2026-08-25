<!DOCTYPE html>
<?php
    session_start();
    require "../common/setup.php";
    require "../common/funzioni.php";
    $_SESSION["page"]="ricerca";

    //parametri dalla sessione
    $parametro = isset($_SESSION['parametro']) ? $_SESSION['parametro'] : '';
    $categorie = isset($_SESSION['categorie']) ? $_SESSION['categorie'] : '';
    $prezzo_min = isset($_SESSION['prezzo_min']) ? $_SESSION['prezzo_min'] : 0;
    $prezzo_max = isset($_SESSION['prezzo_max']) ? $_SESSION['prezzo_max'] : 1000;
    $sconto = isset($_SESSION['sconto']) ? $_SESSION['sconto'] : false;
    $prodotti = isset($_SESSION['prodotti']) ? $_SESSION['prodotti'] : [];

    $prodotti = getFilteredProducts($cid, 'prodotto', $parametro, $categorie, $prezzo_min, $prezzo_max, $sconto);
?>

<html>
    <?php include "../common/head.php"; ?>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
<body class='bg-image' data-page="<?php echo $_SESSION['page']; ?>">
    <?php include "../common/navbar.php"; ?>
    <?php include "../common/common_modal.php"; ?> <!--includo modale comuni-->
    
    <div class="container py-4">
        <div class="row mx-0">
            <div class="col-12">
                <div class="card shadow-sm m-0 p-0 custom-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Filtra prodotti</h5>
                        <form method="GET" action="../backend/ricerca-exe.php">
                            <input type="hidden" name="search" value="<?= htmlspecialchars($parametro) ?>">
                            <div class="row align-items-center">
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-grid-3x3-gap me-2"></i>
                                        Categoria
                                    </label>
                                    <select name="categorie" class="form-select">
                                        <option value="" selected disabled>Seleziona una categoria</option>
                                        <option value="" <?= $categorie == '' ? 'selected' : '' ?>>Tutte</option>
                                        <option value="CD" <?= $categorie == 'CD' ? 'selected' : '' ?>>CD</option>
                                        <option value="Vinile" <?= $categorie == 'Vinile' ? 'selected' : '' ?>>Vinili</option>
                                        <option value="T-shirt" <?= $categorie == 'T-shirt' ? 'selected' : '' ?>>T-shirt</option>
                                        <option value="Calze" <?= $categorie == 'Calze' ? 'selected' : '' ?>>Calze</option>
                                        <option value="Felpa" <?= $categorie == 'Felpa' ? 'selected' : '' ?>>Felpe</option>
                                        <option value="Cappello" <?= $categorie == 'Cappello' ? 'selected' : '' ?>>Cappelli</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-currency-dollar me-2"></i>
                                        Range di prezzo
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="prezzo_min" class="form-control" placeholder="Min" min="0" value="<?= htmlspecialchars($prezzo_min) ?>">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="prezzo_max" class="form-control" placeholder="Max" min="0" value="<?= htmlspecialchars($prezzo_max) ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="sconto" role="switch" id="saleSwitch" <?= $sconto ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="saleSwitch">
                                            <i class="bi bi-tag-fill me-2"></i>
                                            Prodotti in sconto
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn" style="background-color: rgb(0, 76, 178); border-color: rgb(0, 76, 178); color:#e7e7e7;">
                                            <i class="bi bi-funnel-fill me-2"></i>
                                            Applica filtri
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="mt-3">
                            <span class="badge bg-light text-dark" ><?= count($prodotti) ?> prodotti trovati</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--visualizzazione prodotti -->
    <div class="container mt-4 mb-5">
    <div class="row g-4">
        <?php if (count($prodotti) > 0) {
            foreach ($prodotti as $item) { ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 d-flex flex-column justify-content-between custom-card" style="min-height: 400px;">
                        <div style="position: relative; width: 100%; height: 200px; overflow: hidden; display: flex; justify-content: center;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('../uploads/prodotti/<?=$item['immagine']; ?>') center center / cover; filter: blur(10px);">
                            </div>
                            <img src="../uploads/prodotti/<?=$item['immagine']; ?>" 
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
                                    <button type="button" class="btn btn-sm btn-viola btn-commenti" data-bs-toggle="modal" data-bs-target="#commentiModal" data-prodotto-id="<?= $item['id_prodotto']; ?>">Visualizza commenti</button>
                                </div>                      
                        </div>
                    </div>
                </div>

            <?php }
        } else { ?>
            <p>Nessun prodotto trovato.</p>
        <?php } ?>
    </div>
</div>
    <?php include "../common/footer.php"; ?> <!--includo il footer-->
    <script src="../js/carrello.js"></script>
    <script> const prePath = "<?= (isset($_SESSION['page']) && $_SESSION['page'] != 'index') ? '../' : '' ?>"; </script>
    <script src="../js/commenti.js"></script>
</body>
</html>