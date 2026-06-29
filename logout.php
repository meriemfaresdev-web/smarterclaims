<?php
// logout.php
session_start();
session_destroy(); // تدمير السيسيون بالكامل
header("Location: index.php"); // التوجيه لصفحة الـ Login
exit();
?>