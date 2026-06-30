<?php
// حطي القيم الحقيقية اللي نقلتيها من Railway ديريكت هنا:
$host   = 'mysql.railway.internal'; // حطي الـ SQL_HOST ديالك هنا
$port   = '3306';                  // الـ SQL_PORT
$dbname = 'railway';               // حطي هنا السميّة الحقيقية اللي لقيتيها فـ SQL_DATABASE (غالباً railway)
$user   = 'root';                  // الـ SQL_USER
$pass   = 'KHSoFOeqdqdYdpXFvzGXqFKwTyWRNSSV'; // حطي الـ SQL_PASSWORD الحقيقي هنا فاش تفتحيه

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . " . $e->getMessage());
}
?>