<?php
require_once 'db.php';
session_start();
$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['mot_de_passe'])) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_email'] = $admin['email'];
            
            header("Location: admin.php");
            exit();
        } else {
            $erreur = "معلومات الدخول خاطئة الخاصة بالمشرف!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>دخول المشرف | SmarterClaims</title>
    <style>
        body { font-family: sans-serif; background: #0f172a; display: flex; justify-content: center; align-items: center; height: 100vh; direction: rtl; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; width: 100%; max-width: 350px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        h2 { text-align: center; color: #1e293b; margin-bottom: 20px; font-size: 20px; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #0ea5e9; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .error { color: #b91c1c; background: #fef2f2; padding: 8px; border-radius: 4px; text-align: center; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>قفل الإدارة الآمن 🔒</h2>
        <?php if(!empty($erreur)): ?> <div class="error"><?= $erreur ?></div> <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" required placeholder="بريد المشرف الإلكتروني">
            <input type="password" name="mot_de_passe" required placeholder="كلمة المرور">
            <button type="submit">ولوج البوابة</button>
        </form>
    </div>
</body>
</html>