<?php
// حطي القيم الحقيقية اللي نقلتيها من Railway ديريكت هنا:
$host   = 'mysql.railway.internal'; 
$port   = '3306';                  
$dbname = 'railway';                // تأكدي غي واش سميتها railway ف السيرفر أو سمية أخرى
$user   = 'root';                  
$pass   = 'KHSoFOeqdqdYdpXFvzGXqFKwTyWRNSSV'; 

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>