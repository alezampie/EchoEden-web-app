<?php
require "../common/setup.php";
session_start();
if (
    isset($_SESSION["logged"]) && $_SESSION["logged"] === true &&
    (
        $_SESSION["tipologia"] === "admin" ||
        ($_SESSION["tipologia"] === "fan" && isset($_POST['fan']) && $_SESSION["username"] === $_POST['fan'])
    )
) {
    if (isset($_POST['fan'], $_POST['prodotto'], $_POST['data_commento'])) {
        $fan = $_POST['fan'];
        $prodotto = intval($_POST['prodotto']);
        $data_commento = $_POST['data_commento'];

        $stmt = $cid->prepare("DELETE FROM commenti WHERE fan = ? AND prodotto = ? AND data_commento = ?");
        $stmt->bind_param("sis", $fan, $prodotto, $data_commento);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Errore nella cancellazione.";
        }
    } else {
        echo "Dati incompleti.";
    }
} else {
    echo json_encode(['error' => 'Accesso non autorizzato']);
    header("Location: ../index.php");
    exit();
    
}
?>
