<?php
$log_verification = false;
if (isset($_SESSION["logged"])) { $log_verification = $_SESSION["logged"]; }
$tipologia = "";
if (isset($_SESSION["tipologia"])) { $tipologia = $_SESSION["tipologia"]; }
$pre_path = "";
if (isset($_SESSION["page"])) { 
    if ($_SESSION["page"] != "index") { $pre_path = "../"; }
}



if (($log_verification == false OR $tipologia=="fan") AND $_SESSION["page"]!="ricerca") { #se non sono loggato nella pagina o sono un utente = "fan", mostro i successivi elementi nella navbar
    ?>
    <!--una prima navbar "meno importante" che se scorri scompare (è sticky)-->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <!--bottone nel caso di schermo piccolo-->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent"> <!--div che "collassa" nel bottone sopra se sotto una certa dimensione-->
            <div class="container">
                <ul class="navbar-nav w-100">
                    <li class="nav-item">
                        <a class="nav-link active" href="#CD">CD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#Vinili">Vinili</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#Shirt">T-shirt</a>
                    </li>
                    <li class="nav-item ms-auto"> <!--mettendo ms-auto "sconti" va a destra-->
                        <a class="nav-link active" href="#sconti">Sconti</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
} else if ($log_verification == true AND $tipologia=="admin" AND $_SESSION["page"]!="ricerca") { #se sono loggato nella pagina come "artista" o "admin" mi limito a mostrare nella navbar il tipo di utente loggato
    ?>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container text-center"> 
            <p class="h3 text-white mx-auto">PAGINA ADMIN</p> 
        </div>
    </nav>
    <?php
} else if ($log_verification == true AND $tipologia=="artista" AND $_SESSION["page"]!="ricerca") {
    ?>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container text-center"> 
            <p class="h3 text-white mx-auto">PAGINA ARTISTA</p> 
        </div>
    </nav>
    <?php
}
?>
<!--questa è la navbar più importante che rimane "appiccicata sopra" andando a nascondere quella di prima-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark gradient-custom sticky-top">
    <a class="navbar-brand" href="<?php echo $pre_path; ?>index.php">
        <img src="<?php echo $pre_path; ?>media/ECHOEDEN_logo_piccolo_cropped.png" alt="40" width="175" height="" alt="">
    </a>

    <!--bottone nel caso di schermo piccolo-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!--parte che "collassa" nel bottone sopra se rimpicciolita"-->
    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent2">
        <!--div "fasulla" per giustificare il contenuto-->
        <?php if ($log_verification == true) { ?> <div></div> <?php } ?>
        <!--dentro ci metto innanzitutto la barra di ricerca, dentro una form, con decisione di cosa cercare-->
        <?php #ma solo se è un fan loggato. Se è un artista o un admin non metto nulla
        if ($log_verification == false OR $tipologia=="fan") {
            ?>
            
            <form class="d-flex align-items-center mx-auto form-search" method="GET" action="<?php echo $pre_path; ?>backend/ricerca-exe.php">
                <div class="input-group">
                    
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

        <?php
        }
        ?>

        <!--parte del login-->
        <div> <!--metto dentro div almeno rimane centrato-->
    <?php
    if ($log_verification == false) {
    ?>
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link active text-dark" href="<?php echo $pre_path; ?>frontend/signup.php">Sign-up</a> 
        </li>
        <li class="nav-item">
            <a class="nav-link active text-dark" href="<?php echo $pre_path; ?>frontend/login.php">Log-in</a>
        </li>
    </ul>
    <?php
} elseif ($log_verification == true AND $tipologia=="fan")  {
    $query = "SELECT SUM(quantita) AS total_items FROM carrello WHERE fan = '$_SESSION[username]'";
    $result = mysqli_query($cid, $query);
    $row = mysqli_fetch_assoc($result);
?>
<ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center">

    
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
            Ciao <?php echo $_SESSION['username']; ?>!
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="<?php echo $pre_path; ?>backend/ricerca_ordini-exe.php">I miei ordini</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo $pre_path; ?>backend/logout-exe.php">Log-out</a></li>
        </ul>
    </li>

    <!-- dropdown carrello -->
    <li class="nav-item dropdown ms-3">
        <a class="nav-link active text-dark position-relative" 
            href="#" 
            id="dropdownMenuButton" 
            role="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            style="padding: 0.5rem;">

            <i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>

            <span id="badgeCarrello" 
                class="badge position-absolute translate-middle rounded-pill text-center"
                style="background-color: rgb(9, 154, 173); color: white; min-width: 20px; font-size: 0.6rem; top: 7px; right: -8px; padding: 2px 6px;">
                <?php echo $row['total_items'] ?? 0; ?>
            </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" id="carrelloDropdown">
            <!-- contenuto dinamico del carrello -->
        </ul>
    </li>
</ul>
    <?php
    } else { ?>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <a class="nav-link active text-dark" href="#">
            Ciao <?php echo "$_SESSION[username]"; ?>!
        </a>
        </li>

        <li class="nav-item">
            <a class="nav-link active text-dark" href="<?php echo $pre_path; ?>backend/logout-exe.php">Log-out</a>
        </li>
 <?php   }
    ?>
</div>

</nav>