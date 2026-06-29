<?php
// modifier.php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit(); }

require_once 'db.php';

// جلب البيانات القديمة لعرضها في الفورم
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM reclamations WHERE id = ? AND id_utilisateur = ?");
    $stmt->execute([$_GET['id'], $_SESSION['id_user']]);
    $reclam = $stmt->fetch();
    if (!$reclam) { die("Réclamation introuvable !"); }
}

// معالجة التعديل فاش يضغط على Modifier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_reclam'])) {
    $id_reclam = $_POST['id_reclam'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    // تطبيق التعديل
    $stmt = $pdo->prepare("UPDATE reclamations SET titre = ?, description = ? WHERE id = ? AND id_utilisateur = ?");
    $stmt->execute([$titre, $description, $id_reclam, $_SESSION['id_user']]);

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmarterClaims | تعديل الشكاية</title>
    <style>
        :root { 
            --bg: #f8fafc; --text: #0f172a; --primary: #0ea5e9; --primary-hover: #0284c7; 
            --card-bg: #ffffff; --nav-bg: #1e293b; --border: #e2e8f0;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; background: var(--bg); color: var(--text); direction: rtl; display: flex; flex-direction: column; min-height: 100vh; transition: all 0.3s ease; }
        
        /* Navbar الموحد */
        nav { background: var(--nav-bg); padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; text-decoration: none; }
        .logo span { color: var(--primary); }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a { color: #cbd5e1; text-decoration: none; font-size: 15px; font-weight: 500; transition: 0.3s; }
        .nav-links a:hover { color: white; }
        .lang-btn { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px; transition: 0.3s; }
        .lang-btn:hover { background: rgba(255,255,255,0.2); }

        /* Container الرئيسي */
        .main-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        
        /* صندوق تعديل الشكاية الاحترافي */
        .box { background: var(--card-bg); padding: 40px; border-radius: 16px; max-width: 550px; width: 100%; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05); border: 1px solid var(--border); box-sizing: border-box; }
        .box h3 { font-size: 24px; font-weight: 800; color: #1e293b; margin: 0 0 25px 0; text-align: center; }
        
        /* تنسيق عناصر الفورم */
        form { display: flex; flex-direction: column; gap: 20px; }
        label { font-size: 14px; font-weight: 600; color: #475569; }
        
        input[type="text"], textarea { 
            width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; 
            font-size: 15px; background: #f8fafc; color: var(--text); box-sizing: border-box; 
            font-family: inherit; transition: all 0.2s ease; 
        }
        input[type="text"]:focus, textarea:focus { 
            outline: none; border-color: var(--primary); background: white; 
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15); 
        }
        
        /* أزرار التحكم */
        .btn-group { display: flex; align-items: center; gap: 15px; margin-top: 10px; }
        .btn-save { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 12px rgba(14,165,233,0.2); flex: 1; }
        .btn-save:hover { background: var(--primary-hover); transform: translateY(-1px); }
        
        .btn-cancel { background: white; color: #64748b; border: 1px solid var(--border); padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; text-align: center; transition: 0.2s; flex: 1; }
        .btn-cancel:hover { background: #f1f5f9; color: #1e293b; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo" id="logoText">Smarter<span>Claims</span></a>
        <div class="nav-links" id="navLinks">
            <a href="dashboard.php" id="navDash">شكاياتي</a>
            <button class="lang-btn" onclick="switchLanguage()" id="langBtn">Français</button>
        </div>
    </nav>

    <div class="main-container">
        <div class="box">
            <h3 id="formTitle">تعديل الشكاية</h3>
            <form action="modifier.php" method="POST">
                <input type="hidden" name="id_reclam" value="<?= $reclam['id'] ?>">
                
                <div>
                    <label id="lblTitle">العنوان الجديد :</label>
                    <input type="text" name="titre" id="inputTitre" value="<?= htmlspecialchars($reclam['titre']) ?>" required>
                </div>
                
                <div>
                    <label id="lblDesc">الوصف الجديد :</label>
                    <textarea name="description" id="inputDesc" rows="6" required><?= htmlspecialchars($reclam['description']) ?></textarea>
                </div>
                
                <div class="btn-group">
                    <button type="submit" name="modifier_reclam" class="btn-save" id="btnSave">تحديث الشكاية</button>
                    <a href="dashboard.php" class="btn-cancel" id="btnCancel">إلغاء</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let lang = 'ar';
        function switchLanguage() {
            const body = document.body;
            if (lang === 'ar') {
                body.style.direction = 'ltr';
                document.getElementById('langBtn').innerText = 'العربية';
                document.getElementById('navDash').innerText = 'Mes Réclamations';
                
                document.getElementById('formTitle').innerText = 'Modifier la Réclamation';
                document.getElementById('lblTitle').innerText = 'Nouveau Titre :';
                document.getElementById('lblDesc').innerText = 'Nouvelle Description :';
                document.getElementById('btnSave').innerText = 'Mettre à jour';
                document.getElementById('btnCancel').innerText = 'Annuler';
                
                lang = 'fr';
            } else {
                body.style.direction = 'rtl';
                document.getElementById('langBtn').innerText = 'Français';
                document.getElementById('navDash').innerText = 'شكاياتي';
                
                document.getElementById('formTitle').innerText = 'تعديل الشكاية';
                document.getElementById('lblTitle').innerText = 'العنوان الجديد :';
                document.getElementById('lblDesc').innerText = 'الوصف الجديد :';
                document.getElementById('btnSave').innerText = 'تحديث الشكاية';
                document.getElementById('btnCancel').innerText = 'إلغاء';
                
                lang = 'ar';
            }
        }
    </script>
</body>
</html>