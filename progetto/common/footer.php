<footer class="footer bg-dark text-white">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <?php #grazie alla variabile di sessione "page", si può capire in che pagina si trova il footer e quindi come e se linkare la home
            if(isset($_SESSION["page"])) {
                if ($_SESSION["page"]!="index") {
                    ?> 
                    <li class="nav-item"><a href="../index.php" class="nav-link px-2 text-body-secondary">Torna alla Home</a></li>
                    <?php
                } elseif ($_SESSION["page"]=="index") {
                    ?> 
                    <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">Ti trovi nella Home</a></li>
                    <?php
                }
            }
        ?>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Assistenza</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Dona!</a></li>
    </ul>
    <p class="text-center text-body-secondary">© 2024 ECHOEDEN, Inc</p>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script> 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</footer>