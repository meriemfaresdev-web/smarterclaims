<?php
// supprimer.php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit(); }

require_once 'db.php';

if (isset($_GET['id'])) {
    $id_reclam = $_GET['id'];

    // 1. الحذف من قاعدة البيانات (السؤال 9)
    $stmt = $pdo->prepare("DELETE FROM reclamations WHERE id = ? AND id_utilisateur = ?");
    $stmt->execute([$id_reclam, $_SESSION['id_user']]);

    // 2. الكتابة في التاريخ والأرشيف (السؤال 11)
    $date_actuelle = date('Y-m-d H:i:s');
    $phrase = "Réclamation supprimée le [" . $date_actuelle . "]\n";
    
    // استخدام FILE_APPEND للحفاظ على ما تم قيده سابقاً
    file_put_contents("historique.txt", $phrase, FILE_APPEND);
}

header("Location: dashboard.php");
exit();
?>