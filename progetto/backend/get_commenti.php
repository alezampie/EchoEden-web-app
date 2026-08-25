<?php
session_start();
require "../common/setup.php";
require "../common/funzioni.php";

if (isset($_GET['id_prodotto'])) {
    $id_prodotto = intval($_GET['id_prodotto']);

    $result = getCommenti($cid, $id_prodotto);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="commento mb-3 p-2 border rounded d-flex justify-content-between align-items-start">';
            echo '<div>';
            echo '<strong>' . htmlspecialchars($row['fan']) . '</strong>';
            echo '<small style="font-size: 0.5rem;">' . "     "  . htmlspecialchars($row['data_commento']) . '</small>';
            echo '<p style="font-size: 0.85rem; margin-bottom: 2px;">' . nl2br(htmlspecialchars($row['descrizione'])) . '</p>';
            echo '<small><strong>valutazione:</strong> ' . htmlspecialchars($row['voto']) . '</small>';
            echo '</div>';
        
            //se admin loggato, mostra il cestino
            if (
                isset($_SESSION["logged"]) && $_SESSION["logged"] === true &&
                (
                    (isset($_SESSION["tipologia"]) && $_SESSION["tipologia"] === "admin") ||
                    (isset($_SESSION["tipologia"]) && $_SESSION["tipologia"] === "fan" && $_SESSION["username"] === $row['fan'])
                )
            ) {
                //bottone elimina (admin o fan proprietario)
                echo '<button class="btn btn-danger ms-2 elimina-commento" data-fan="' . htmlspecialchars($row['fan']) . '" data-prodotto="' . $id_prodotto . '" data-data-commento="' . htmlspecialchars($row['data_commento']) . '">';
                echo 'Elimina';
                echo '</button>';

                //bottone modifica (solo se è il proprio commento)
                if ($_SESSION["tipologia"] === "fan" && $_SESSION["username"] === $row['fan']) {
                    if (isset($_SESSION["page"]) && $_SESSION["page"] == "index") {
                        echo '<a href="frontend/modifica_commento.php?id_prodotto=' . $id_prodotto . '&data_commento=' . urlencode($row["data_commento"]) . '" class="btn btn-warning ms-2">Modifica</a>';
                    } else {
                        echo '<a href="../frontend/modifica_commento.php?id_prodotto=' . $id_prodotto . '&data_commento=' . urlencode($row["data_commento"]) . '" class="btn btn-warning ms-2">Modifica</a>';
                    }
                }
            }
        
            echo '</div>';
        }
    } else {
        echo '<p>Nessun commento disponibile.</p>';
    }
} else {
    echo '<p>ID prodotto non fornito.</p>';
    header("Location: ../index.php");
    exit();
}
if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true && $_SESSION["tipologia"] === "fan") {
    $fan_username = $_SESSION["username"];
    if (fanPuòCommentare($cid, $fan_username, $id_prodotto)) {
        $pre_path = '';
        if (isset($_SESSION["page"]) && $_SESSION["page"] != "index") {
            $pre_path = "../";
        }
        echo '<a href="' . $pre_path . 'frontend/aggiungi_commento.php?id_prodotto=' . $id_prodotto . '" class="btn btn-success mt-3">Aggiungi commento</a>';
    }
}

?>
