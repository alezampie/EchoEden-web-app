<!DOCTYPE html>
<?php
    session_start();
    require "../common/setup.php";
    require "../common/funzioni.php";
    $_SESSION["page"]="ricerca_ordini";

    //parametri dalla sessione
    $parametro = isset($_GET['search_ordini']) ? $_GET['search_ordini'] : '';
    $stato = isset($_GET['stato']) ? $_GET['stato'] : '';
    $utente = $_SESSION['username'];

    if ($_SESSION["tipologia"] == "artista") {
        $ordini = getOrderByArtist($cid, $utente, $parametro,$stato);
    } else if ($_SESSION["tipologia"] == "fan") {
        $ordini = getOrders($cid, $utente, $parametro,$stato);
    }

    //controllo per impedire accesso dall'url
    if (!isset($_SESSION["logged"])) {
        header("Location: ../index.php");
        exit;
    }
    
?>

<html>
<?php include "../common/head.php"; ?>
<link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
<body class='bg-image' data-page="<?php echo $_SESSION['page']; ?>">
<?php include "../common/navbar.php"; ?>
<?php include "../common/common_modal.php"; ?>

<?php if (isset($_SESSION['annulla_success'])) {
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Annullamento avvenuto con successo!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['annulla_success']);
    }  else if (isset($_SESSION['order_success'])) {?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Ordine effettuato con successo!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['order_success']);
    
     } else if (isset($_SESSION['rifiuta_success'])) {?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Ordine rifiutato con successo!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['rifiuta_success']);
        
    } else if (isset($_SESSION['conferma_success'])) {?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Ordine confermato con successo!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['conferma_success']);
    
    } ?>

<div class="container py-4">
    <div class="row mx-0">
        <div class="col-12">
            <div class="card shadow-sm custom-card m-0 p-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filtra ordini</h5>
                    <form method="GET" action="../backend/ricerca_ordini-exe.php">
                        <input type="hidden" name="search_ordini" value="<?= htmlspecialchars($parametro) ?>">

                        <div class="row g-3 align-items-end">
                           
                            <div class="col-md-4">
                                <label for="parametro_principale" class="form-label">Ricerca per prodotto</label>
                                <input type="search_ordini" name="search_ordini" 
                                    value="<?= isset($_GET['search_ordini']) ? htmlspecialchars($_GET['search_ordini'], ENT_QUOTES, 'UTF-8') : (isset($_SESSION['parametro_ordini']) ? htmlspecialchars($_SESSION['parametro'], ENT_QUOTES, 'UTF-8') : '') ?>" 
                                    id="parametro_principale" 
                                    class="form-control" 
                                    placeholder="Ricerca artisti o prodotti" 
                                    aria-label="Search" />
                            </div>

                            
                            <div class="col-md-4">
                                <label class="form-label">Stato dell'ordine</label>
                                <select name="stato" class="form-select">
                                    <option value="" <?= $stato == '' ? 'selected' : '' ?>>Vedi tutti gli ordini</option>
                                    <option value="in attesa di conferma" <?= $stato == 'in attesa di conferma' ? 'selected' : '' ?>>In attesa di conferma</option>
                                    <option value="confermato" <?= $stato == 'confermato' ? 'selected' : '' ?>>Ordini confermati</option>
                                    <option value="rifiutato" <?= $stato == 'rifiutato' ? 'selected' : '' ?>>Ordini rifiutati</option>
                                </select>
                            </div>

                            
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel-fill me-2"></i> Applica filtri
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3">
                        <span class="badge bg-light text-dark"><?= count($ordini) ?> ordini trovati</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h2 class="mb-4 text-white">I tuoi ordini</h2>
    <?php if ($_SESSION["tipologia"] == "fan") {
        if (count($ordini) > 0): ?>
            <?php foreach ($ordini as $ordine): ?>
                <?php
                $stato = strtolower($ordine['stato']);
                switch ($stato) {
                    case 'confermato':
                        $badgeClass = 'bg-success text-white';
                        break;
                    case 'rifiutato':
                        $badgeClass = 'bg-danger text-white';
                        break;
                    case 'in attesa di conferma':
                    default:
                        $badgeClass = 'bg-light text-dark';
                        break;
                }
                ?>
                <div class="card shadow-sm custom-card mb-4">
                    <div class="card-header text-white d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <strong>Ordine n° <?= $ordine['id_ordine']; ?></strong><br>
                            Data: <?= date("d/m/Y H:i:s", strtotime($ordine['data'])); ?>
                        </div>
                        <div class="text-end">
                            Totale: <strong><?= number_format($ordine['totale'], 2, ',', '.'); ?>€</strong><br>
                            Stato: <span class="badge <?= $badgeClass; ?>"><?= ucfirst($stato); ?></span>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <?php if ($ordine['stato'] == "in attesa di conferma"): ?>
                            <div class="d-flex justify-content-end gap-2 mb-3">
                                <button class="btn btn-danger btn-sm annulla-btn" data-bs-target="#modaleAnnulla" data-id="<?= $ordine['id_ordine']; ?>" data-bs-toggle="modal">Annulla</button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <?php $prodotti = getProductByOrder($cid, $ordine['id_ordine'], $utente);
                            foreach ($prodotti as $prodotto): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card h-100">
                                        <img src="../uploads/prodotti/<?= $prodotto['immagine']; ?>" class="card-img-top" alt="<?= $prodotto['nome']; ?>" style="object-fit: cover; height: 200px;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= $prodotto['nome']; ?></h5>
                                            <p class="card-text mb-1">Artista: <strong><?= $prodotto['artista']; ?></strong></p>
                                            <?php if (!empty($prodotto['descrizione'])): ?>
                                                <p class="card-text mb-1">Descrizione: <?= htmlspecialchars($prodotto['descrizione']); ?></p>
                                            <?php endif; ?>
                                            <p class="card-text mb-1">Quantità: <?= $prodotto['quantita']; ?></p>
                                            <p class="card-text mb-3">Prezzo: <?= number_format(($prodotto['prezzo_unitario'] * (100 - $prodotto['sconto'])) / 100, 2, ',', '.'); ?>€</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center text-dark">Nessun ordine trovato</div>
        <?php endif; ?>
    <?php } else if ($_SESSION["tipologia"] == "artista") {
        if (count($ordini) > 0): ?>
            <?php foreach ($ordini as $ordine): ?>
                <?php
                $stato = strtolower($ordine['stato']);
                switch ($stato) {
                    case 'confermato':
                        $badgeClass = 'bg-success text-white';
                        break;
                    case 'rifiutato':
                        $badgeClass = 'bg-danger text-white';
                        break;
                    case 'in attesa di conferma':
                    default:
                        $badgeClass = 'bg-light text-dark';
                        break;
                }
                ?>
                <div class="card shadow-sm custom-card mb-4">
                    <div class="card-header text-white d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <strong>Ordine n° <?= $ordine['id_ordine']; ?></strong><br>
                            Data: <?= date("d/m/Y H:i:s", strtotime($ordine['data'])); ?><br>
                            Utente: <?= $ordine['fan']; ?>
                        </div>
                        <div class="text-end">
                            Totale: <strong><?= number_format($ordine['totale'], 2, ',', '.'); ?>€</strong><br>
                            Stato: <span class="badge <?= $badgeClass; ?>"><?= ucfirst($stato); ?></span>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <?php if ($ordine['stato'] == "in attesa di conferma"): ?>
                            <div class="d-flex justify-content-end gap-2 mb-3">
                                <button class="btn btn-success btn-sm conferma-btn" data-bs-target="#modaleConferma" data-id="<?= $ordine['id_ordine']; ?>" data-bs-toggle="modal">Conferma</button>
                                <button class="btn btn-danger btn-sm rifiuta-btn" data-bs-target="#modaleRifiuta" data-id="<?= $ordine['id_ordine']; ?>" data-bs-toggle="modal">Rifiuta</button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <?php 
                            $prodotti = getProductOrderByArtist($cid, $ordine['id_ordine'], $utente);
                            foreach ($prodotti as $prodotto): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card h-100">
                                        <img src="../uploads/prodotti/<?= $prodotto['immagine']; ?>" class="card-img-top" alt="<?= $prodotto['nome']; ?>" style="object-fit: cover; height: 200px;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= $prodotto['nome']; ?></h5>
                                            <p class="card-text mb-1">Artista: <strong><?= $prodotto['artista']; ?></strong></p>
                                            <?php if (!empty($prodotto['descrizione'])): ?>
                                                <p class="card-text mb-1">Descrizione: <?= htmlspecialchars($prodotto['descrizione']); ?></p>
                                            <?php endif; ?>
                                            <p class="card-text mb-1">Quantità: <?= $prodotto['quantita']; ?></p>
                                            <p class="card-text mb-3">Prezzo: <?= number_format(($prodotto['prezzo_unitario'] * (100 - $prodotto['sconto'])) / 100, 2, ',', '.'); ?>€</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center text-dark">Nessun ordine trovato</div>
        <?php endif; ?>
    <?php } ?>
</div>



<div class="modal fade" id="modaleAnnulla" tabindex="-1" aria-labelledby="modaleAnnullaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Conferma annullamento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        Sei sicuro di voler annullare questo ordine?
      </div>
      <div class="modal-footer">
        <form method="POST" action="../backend/annulla_ordine.php">
          <input type="hidden" name="id_ordine" id="inputIdOrdineAnnulla">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, esci</button>
          <button type="submit" class="btn btn-danger">Sì, annulla</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modaleConferma" tabindex="-1" aria-labelledby="modaleAnnullaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Conferma ordine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        Sei sicuro di voler confermare questo ordine?
      </div>
      <div class="modal-footer">
        <form method="POST" action="../backend/conferma_ordine.php">
          <input type="hidden" name="id_ordine" id="inputIdOrdineConferma">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, esci</button>
          <button type="submit" class="btn btn-success">Sì, conferma</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modaleRifiuta" tabindex="-1" aria-labelledby="modaleAnnullaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Rifiuta ordine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        Sei sicuro di voler rifiutare questo ordine?
      </div>
      <div class="modal-footer">
        <form method="POST" action="../backend/rifiuta_ordine.php">
          <input type="hidden" name="id_ordine" id="inputIdOrdineRifiuta">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, esci</button>
          <button type="submit" class="btn btn-danger">Sì, rifiuta</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../common/footer.php"; ?>
<script src="../js/carrello.js"></script>
<script src="../js/ordini.js"></script>
</body>
</html>
