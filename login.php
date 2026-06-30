<?php
require_once 'db.php';
session_start();
$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['email_user'] = $user['email'];
            
            if ($user['email'] === 'admin@chikaya.ma') {
                $_SESSION['role'] = 'admin';
                header("Location: admin.php");
            } else {
                $_SESSION['role'] = 'user';
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $erreur = "البيانات المدخلة غير صحيحة! / Identifiants incorrects !";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | SmarterClaims</title>
    <style>
        :root { --bg: #f8fafc; --text: #0f172a; --primary: #0ea5e9; --primary-hover: #0284c7; --nav-bg: #1e293b; --footer-bg: #0f172a; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: var(--bg); display: flex; flex-direction: column; min-height: 100vh; direction: rtl; }
        
        nav { background: var(--nav-bg); padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .logo { font-size: 22px; font-weight: 800; color: white; text-decoration: none; }
        .logo span { color: var(--primary); }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a { color: #cbd5e1; text-decoration: none; font-size: 15px; }
        .lang-btn { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        
        main { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }
        .login-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); width: 100%; max-width: 400px; box-sizing: border-box; border-top: 4px solid var(--nav-bg); }
        .brand { text-align: center; font-size: 24px; font-weight: 800; margin-bottom: 5px; }
        .brand span { color: #0ea5e9; }
        .subtitle { text-align: center; color: #64748b; font-size: 14px; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 600; color: #334155; }
        input { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        button.btn-submit { width: 100%; padding: 12px; background: #1e293b; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        button.btn-submit:hover { background: #0f172a; }
        .error { background: #fef2f2; color: #991b1b; padding: 10px; border-radius: 6px; text-align: center; font-size: 14px; margin-bottom: 15px; }
        .back-home { text-align: center; margin-top: 20px; font-size: 14px; color: #64748b; }
        .back-home a { color: #0ea5e9; text-decoration: none; font-weight: 600; }
        
        footer { background: var(--footer-bg); color: #94a3b8; text-align: center; padding: 25px; font-size: 14px; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Smarter<span>Claims</span></a>
        <div class="nav-links">
            <a href="index.php" id="navHome">الرئيسية</a>
            <a href="register.php" class="btn-accent" id="navSubmit">تقديم شكاية</a>
            <button class="lang-btn" onclick="switchLanguage()" id="langBtn">Français</button>
        </div>
    </nav>

    <main>
        <div class="login-card">
            <div class="brand">Smarter<span>Claims</span></div>
            <div class="subtitle" id="subTitle">مرحباً بك، يرجى تسجيل الدخول لحسابك</div>
            
            <?php if(!empty($erreur)): ?> <div class="error"><?= $erreur ?></div> <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label id="lblEmail">البريد الإلكتروني</label>
                    <input type="email" name="email" required placeholder="name@example.com">
                </div>
                <div class="form-group">
                    <label id="lblPass">كلمة المرور</label>
                    <input type="password" name="mot_de_passe" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-submit" id="btnSubmit">تسجيل الدخول</button>
            </form>
            <div class="back-home" id="footerBox">
                ليس لديك حساب؟ <a href="register.php">أنشئ حساباً الآن</a>
            </div>
        </div>
    </main>

    <footer>
        <p id="footerText">© 2026 منصة SmarterClaims الرقمية. جميع الحقوق محفوظة.</p>
    </footer>

    <script>
        let lang = 'ar';
        function switchLanguage() {
            const body = document.body;
            if (lang === 'ar') {
                body.style.direction = 'ltr';
                document.getElementById('langBtn').innerText = 'العربية';
                document.getElementById('navHome').innerText = 'Accueil';
                document.getElementById('navSubmit').innerText = 'Déposer une réclamation';
                document.getElementById('subTitle').innerText = 'Bon retour, veuillez vous connecter à votre compte';
                document.getElementById('lblEmail').innerText = 'Adresse e-mail';
                document.getElementById('lblPass').innerText = 'Mot de passe';
                document.getElementById('btnSubmit').innerText = 'Se connecter';
                document.getElementById('footerBox').innerHTML = 'Vous n\'avez pas de compte ? <a href="register.php">Créez-en un maintenant</a>';
                document.getElementById('footerText').innerText = '© 2026 Plateforme Digitale SmarterClaims. Tous droits réservés.';
                lang = 'fr';
            } else {
                body.style.direction = 'rtl';
                document.getElementById('langBtn').innerText = 'Français';
                document.getElementById('navHome').innerText = 'الرئيسية';
                document.getElementById('navSubmit').innerText = 'تقديم شكاية';
                document.getElementById('subTitle').innerText = 'مرحباً بك، يرجى تسجيل الدخول لحسابك';
                document.getElementById('lblEmail').innerText = 'البريد الإلكتروني';
                document.getElementById('lblPass').innerText = 'كلمة المرور';
                document.getElementById('btnSubmit').innerText = 'تسجيل الدخول';
                document.getElementById('footerBox').innerHTML = 'ليس لديك حساب؟ <a href="register.php">أنشئ حساباً الآن</a>';
                document.getElementById('footerText').innerText = '© 2026 منصة SmarterClaims الرقمية. جميع الحقوق محفوظة.';
                lang = 'ar';
            }
        }
    </script>
</body>
</html>