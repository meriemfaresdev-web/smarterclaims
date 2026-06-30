<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit(); }

require_once 'db.php';

if (isset($_GET['id'])) {
    $id_reclam = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM reclamations WHERE id = ? AND id_utilisateur = ?");
    $stmt->execute([$id_reclam, $_SESSION['id_user']]);

    $date_actuelle = date('Y-m-d H:i:s');
    $phrase = "Réclamation supprimée le [" . $date_actuelle . "]\n";
    
    file_put_contents("historique.txt", $phrase, FILE_APPEND);
}

header("Location: dashboard.php");
exit();
?>