<?php
$pre_path = "";
if (isset($_SESSION["page"])) { 
    if ($_SESSION["page"] != "index") { $pre_path = "../"; }
} ?>


<!--modale carrello-->
<div class="modal fade" id="carrello" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Seleziona quantità</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetCounter()"></button>
</div>
<div class="modal-body">
<div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin: 20px;">
<button id="btnMeno" onclick= "decrementa()" style="padding: 10px 15px; font-size: 18px; border: none; background-color: #eee; border-radius: 6px; cursor: pointer;">-</button>
<span id= "valore" style="min-width: 30px; display: inline-block; text-align: center; font-size: 18px;">1</span>
<button id="btnPiu" onclick= "incrementa()" style="padding: 10px 15px; font-size: 18px; border: none; background-color: #eee; border-radius: 6px; cursor: pointer;">+</button>
</div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal" onclick="resetCounter()">Annulla</button> 
        <input type="hidden" id="prodotto-id-input" value="">
        <button class="btn btn-primary addToCartBtn" onclick= "aggiungiAlCarrello()" data-bs-dismiss="modal"style="background-color:rgb(9, 154, 173);">Aggiungi</button>   
</div>
</div>
</div>
</div>

<!-- Modale registrazione-->
<div class="modal fade" id="iscriviti" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content rounded-4 shadow">
<div class="modal-header">
    <h5 class="modal-title" id="registerModalLabel">Vuoi cominciare ad acquistare?</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
</div>
<div class="modal-body">
    <a type="submit" class="btn btn-success w-100" href="<?php echo $pre_path; ?>frontend/signup.php">Registrati!</a>
</div>
</div>
</div>
</div>

<div class="modal fade" id="checkout" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content rounded-4 shadow">
<div class="modal-header">
    <h5 class="modal-title" id="registerModalLabel">Vuoi procedere all'ordine?</h5>
<div class="div" id = "cartModale">

</div>
</div>
<div class="modal-body">
    <div class="d-flex justify-content-between gap-2">
    <button type="button" class="btn btn-outline-danger flex-fill" data-bs-dismiss="modal">Annulla</button>
    <a type="submit" class="btn btn-success btn-procedi flex-fill" style="background-color:rgb(9, 154, 173);" href="<?php echo $pre_path; ?>backend/effettua_ordine.php">Procedi</a>
    </div>
</div>
</div>
</div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
<div id="toastAdded" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
<div class="d-flex">
<div class="toast-body">
    Articolo aggiunto al carrello
</div>
<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Chiudi"></button>
</div>
</div>
</div>

<!-- common/commenti_modal.php -->
<div class="modal fade" id="commentiModal" tabindex="-1" aria-labelledby="commentiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentiModalLabel">Commenti del prodotto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                
                <div id="contenutoCommenti">
                
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
