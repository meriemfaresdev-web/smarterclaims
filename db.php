<?php
// دالة ذكية كتقلب على المتغير فـ السيرفر بـ كاع الطرق الممكنة
function get_env_var($key, $default) {
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (getenv($key) !== false) return getenv($key);
    return $default;
}

// قراءة المتغيرات بأسماء Railway (مع دعم الأنماط SQL_ و MYSQL_)
$host   = get_env_var('MYSQLHOST', get_env_var('SQL_HOST', 'localhost'));
$port   = get_env_var('MYSQLPORT', get_env_var('SQL_PORT', '3306'));
$dbname = get_env_var('MYSQLDATABASE', get_env_var('SQL_DATABASE', 'gestion_reclamations'));
$user   = get_env_var('MYSQLUSER', get_env_var('SQL_USER', 'root'));
$pass   = get_env_var('MYSQLPASSWORD', get_env_var('SQL_PASSWORD', ''));

try {
    // الاتصال بـ PDO مع تحديد الـ host والـ port بشكل إجباري لتفادي مشكل الـ Socket
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>