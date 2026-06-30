<?php
// قراءة المتغيرات من Railway، وإذا لم تكن موجودة (يعني في اللوكال) يتم استخدام القيم الافتراضية لـ XAMPP
$host = isset($_ENV['MYSQLHOST']) ? $_ENV['MYSQLHOST'] : 'localhost';
$port = isset($_ENV['MYSQLPORT']) ? $_ENV['MYSQLPORT'] : '3306';
$dbname = isset($_ENV['MYSQLDATABASE']) ? $_ENV['MYSQLDATABASE'] : 'gestion_reclamations';
$user = isset($_ENV['MYSQLUSER']) ? $_ENV['MYSQLUSER'] : 'root';
$pass = isset($_ENV['MYSQLPASSWORD']) ? $_ENV['MYSQLPASSWORD'] : '';

try {
    // السطر السحري: إضافة الـ host والـ port والـ charset بشكل صحيح لـ PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>