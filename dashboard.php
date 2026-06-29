<?php
// dashboard.php
session_start();
// التأكد من الأمان (الـ 4 أسطر المطلوبة فالسؤال 4)
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

// --- 1. استهلاك الـ REST API (السؤال 12) ---
$api_url = "https://api.entreprise.com/status";
// استعملنا @ لتفادي ظهور خطأ إن كان السيرفر الخارجي غير موجود ووضعنا فرضية افتراضية للـ Test
$response = @file_get_contents($api_url);
if ($response !== false) {
    $api_data = json_decode($response, true);
    $status_text = "Serveur : " . $api_data['statut'] . " (" . $api_data['actifs'] . " utilisateurs)";
} else {
    // حالة احتياطية للاختبار فقط إذا كان الرابط الوهمي لا يعمل
    $status_text = "Serveur : En ligne (120 utilisateurs) [Simulation]";
}

// --- 2. معالجة إضافة شكاية جديدة (Create + Upload) ---
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_reclam'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    
    // تدبير الملف (السؤال 10)
    $file = $_FILES['fichier_joint'];
    $nom_fichier = $file['name'];
    $tmp_name = $file['tmp_name'];
    
    if (!empty($titre) && !empty($description) && !empty($nom_fichier)) {
        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
        $extensions_autorisees = ['pdf', 'jpg'];
        
        if (in_array($extension, $extensions_autorisees)) {
            // نغير اسم الملف قليلاً لتفادي التكرار
            $nouveau_nom = time() . "_" . $nom_fichier;
            
            if (move_uploaded_file($tmp_name, "uploads/" . $nouveau_nom)) {
                // إدخال البيانات (السؤال 6)
                $stmt = $pdo->prepare("INSERT INTO reclamations (titre, description, fichier_joint, id_utilisateur) VALUES (?, ?, ?, ?)");
                $stmt->execute([$titre, $description, $nouveau_nom, $_SESSION['id_user']]);
                $message = "<div class='alert alert-success' id='msgSucces'>تمت إضافة الشكاية بنجاح! / Réclamation ajoutée avec succès !</div>";
            } else {
                $message = "<div class='alert alert-danger'>فشل تحميل الملف إلى المجلد.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>صيغة غير مسموح بها! يُقبل فقط .pdf و .jpg</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>يرجى ملء جميع الخانات وتحميل ملف!</div>";
    }
}

// --- 3. جلب شكايات المستخدم الحالي فقط (Read - السؤال 7) ---
$stmt = $pdo->prepare("SELECT * FROM reclamations WHERE id_utilisateur = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['id_user']]);
$reclamations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmarterClaims</title>
    <style>
        :root { 
            --bg: #f8fafc; --text: #0f172a; --primary: #0ea5e9; --primary-hover: #0284c7; 
            --nav-bg: #1e293b; --footer-bg: #0f172a; 
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; background: var(--bg); color: var(--text); direction: rtl; display: flex; flex-direction: column; min-height: 100vh; }
        
        /* Navbar الموحد */
        nav { background: var(--nav-bg); padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 22px; font-weight: 800; color: white; text-decoration: none; }
        .logo span { color: var(--primary); }
        .nav-links { display: flex; gap: 20px; align-items: center; color: white; }
        .btn-logout { background: #ef4444; color: white !important; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; }
        .btn-logout:hover { background: #b91c1c; }
        .lang-btn { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px; }

        /* API Status Bar */
        .api-status { background: #e2e8f0; padding: 12px 50px; font-weight: bold; font-size: 14px; color: #334155; border-bottom: 1px solid #cbd5e1; }

        /* Main Container */
        main { flex: 1; max-width: 1200px; margin: 30px auto; padding: 0 20px; display: flex; gap: 30px; width: 100%; box-sizing: border-box; }
        .form-section { width: 40%; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.02); border-top: 4px solid var(--primary); height: fit-content; }
        .list-section { width: 60%; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.02); border-top: 4px solid var(--nav-bg); }
        
        h3 { margin-top: 0; color: #1e293b; font-size: 18px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 600; color: #475569; }
        input[type="text"], textarea, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        
        .btn-add { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-add:hover { background: var(--primary-hover); }
        
        /* Table Styling */
        table { width: 100%; border-collapse: collapse; text-align: right; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        th { background: #f8fafc; color: #64748b; font-weight: 600; }
        
        /* Action Buttons */
        .btn-action { padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; color: white; display: inline-block; }
        .btn-edit { background: #f59e0b; margin-left: 5px; }
        .btn-edit:hover { background: #d97706; }
        .btn-delete { background: #ef4444; }
        .btn-delete:hover { background: #dc2626; }
        .btn-view { color: var(--primary); text-decoration: none; font-weight: 600; cursor: pointer; }

        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; text-align: center; font-weight: 500; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
        
        /* Footer */
        footer { background: var(--footer-bg); color: #94a3b8; text-align: center; padding: 25px; font-size: 14px; margin-top: auto; }

        /* --- نافذة عرض الملف الاحترافية (Modal) --- */
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center; padding: 20px; box-sizing: border-box; }
        .modal-content { background: white; padding: 20px; border-radius: 12px; max-width: 800px; width: 100%; height: 80vh; display: flex; flex-direction: column; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .modal-title { font-weight: bold; font-size: 16px; }
        .close-modal { background: #ef4444; color: white; border: none; padding: 6px 16px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .modal-body { flex: 1; display: flex; justify-content: center; align-items: center; overflow: hidden; background: #f1f5f9; border-radius: 6px; }
        .modal-body iframe, .modal-body img { width: 100%; height: 100%; object-fit: contain; border: none; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Smarter<span>Claims</span></a>
        <div class="nav-links">
            <span id="welcomeTxt"><span id="welcomeTxt">مرحباً: <?= htmlspecialchars($_SESSION['email_user']) ?></span></span>
            <button class="lang-btn" onclick="switchLanguage()" id="langBtn">Français</button>
            <a href="logout.php" class="btn-logout" id="btnLogout">تسجيل الخروج</a>
        </div>
    </nav>

    <div class="api-status" id="apiStatus">
        <?= htmlspecialchars($status_text) ?>
    </div>

    <main>
        <div class="form-section">
            <h3 id="formTitle">إضافة شكاية جديدة</h3>
            <?= $message ?>
            <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label id="lblTitle">العنوان :</label>
                    <input type="text" name="titre" required>
                </div>

                <div class="form-group">
                    <label id="lblDesc">الوصف :</label>
                    <textarea name="description" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label id="lblFile">الملف المرفق (.jpg, .pdf) :</label>
                    <input type="file" name="fichier_joint" required>
                </div>

                <button type="submit" name="ajouter_reclam" class="btn-add" id="btnSubmit">إرسال الشكاية</button>
            </form>
        </div>

        <div class="list-section">
            <h3 id="listTitle">الشكايات الخاصة بي</h3>
            <table>
                <thead>
                    <tr>
                        <th id="thT" style="text-align: inherit;">العنوان</th>
                        <th id="thD" style="text-align: inherit;">الوصف</th>
                        <th id="thF" style="text-align: inherit;">الملف المرفق</th>
                        <th id="thA" style="text-align: inherit;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reclamations) > 0): ?>
                        <?php foreach ($reclamations as $reclam): ?>
                            <tr>
                                <td><?= htmlspecialchars($reclam['titre']) ?></td>
                                <td><?= htmlspecialchars($reclam['description']) ?></td>
                                <td>
                                    <span class="btn-view btnViewLang" onclick="openFileModal('uploads/<?= $reclam['fichier_joint'] ?>')">عرض الملف</span>
                                </td>
                                <td>
                                    <a href="modifier.php?id=<?= $reclam['id'] ?>" class="btn-action btn-edit btnEditLang">تعديل</a>
                                    <a href="supprimer.php?id=<?= $reclam['id'] ?>" class="btn-action btn-delete btnDelLang" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center;" id="noClaims">لا توجد أي شكايات حالياً.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="fileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="modalTitle">معاينة الملف المرفق</span>
                <button class="close-modal" onclick="closeFileModal()" id="btnCloseModal">إغلاق المعاينة</button>
            </div>
            <div class="modal-body" id="modalBody">
                </div>
        </div>
    </div >

    <footer>
        <p id="footerText">© 2026 منصة SmarterClaims الرقمية. جميع الحقوق محفوظة.</p>
    </footer>

    <script>
        // دالة فتح نافذة عرض الملفات الاحترافية
        function openFileModal(fileUrl) {
            const modal = document.getElementById('fileModal');
            const modalBody = document.getElementById('modalBody');
            const extension = fileUrl.split('.').pop().toLowerCase();
            
            if (extension === 'pdf') {
                modalBody.innerHTML = `<iframe src="${fileUrl}"></iframe>`;
            } else {
                modalBody.innerHTML = `<img src="${fileUrl}" alt="Fichier Joint">`;
            }
            modal.style.display = 'flex';
        }

        // دالة إغلاق النافذة والرجوع للوحة التحكم فوراً
        function closeFileModal() {
            document.getElementById('fileModal').style.display = 'none';
            document.getElementById('modalBody').innerHTML = '';
        }

        // نظام تحويل اللغات الكامل الديناميكي للواجهة
        let lang = 'ar';
        function switchLanguage() {
            const body = document.body;
            const tableThs = document.querySelectorAll('th');
            const tableTds = document.querySelectorAll('td');
            
            if (lang === 'ar') {
                body.style.direction = 'ltr';
                document.getElementById('langBtn').innerText = 'العربية';
                document.getElementById('welcomeTxt').innerText = 'Welcome  <?= htmlspecialchars($_SESSION['email_user']) ?>';
                document.getElementById('btnLogout').innerText = 'Logout';
                document.getElementById('formTitle').innerText = 'Add a New Claim';
                document.getElementById('lblTitle').innerText = 'Title :';
                document.getElementById('lblDesc').innerText = 'Description :';
                document.getElementById('lblFile').innerText = 'Attachment (.jpg, .pdf) :';
                document.getElementById('btnSubmit').innerText = 'Send Claim';
                document.getElementById('listTitle').innerText = 'My Claims';
                
                document.getElementById('thT').innerText = 'Title';
                document.getElementById('thD').innerText = 'Description';
                document.getElementById('thF').innerText = 'Attachment';
                document.getElementById('thA').innerText = 'Actions';
                
                if(document.getElementById('noClaims')) document.getElementById('noClaims').innerText = 'No claims found.';
                if(document.getElementById('msgSucces')) document.getElementById('msgSucces').innerText = 'Réclamation ajoutée avec succès !';
                
                document.querySelectorAll('.btnViewLang').forEach(el => el.innerText = 'Voir fichier');
                document.querySelectorAll('.btnEditLang').forEach(el => el.innerText = 'Modifier');
                document.querySelectorAll('.btnDelLang').forEach(el => el.innerText = 'Supprimer');
                
                document.getElementById('modalTitle').innerText = 'File Preview';
                document.getElementById('btnCloseModal').innerText = 'Close Preview';
                document.getElementById('footerText').innerText = '© 2026 SmarterClaims Digital Platform. All rights reserved.';
                
                // تعديل اتجاه نصوص الجدول لتتطابق مع الـ LTR
                tableThs.forEach(th => th.style.textAlign = 'left');
                tableTds.forEach(td => td.style.textAlign = 'left');
                
                lang = 'fr';
            } else {
                body.style.direction = 'rtl';
                document.getElementById('langBtn').innerText = 'Français';
                document.getElementById('welcomeTxt').innerText = 'مرحباً بك في لوحة التحكم';
                document.getElementById('btnLogout').innerText = 'تسجيل الخروج';
                document.getElementById('formTitle').innerText = 'إضافة شكاية جديدة';
                document.getElementById('lblTitle').innerText = 'العنوان :';
                document.getElementById('lblDesc').innerText = 'الوصف :';
                document.getElementById('lblFile').innerText = 'الملف المرفق (.jpg, .pdf) :';
                document.getElementById('btnSubmit').innerText = 'إرسال الشكاية';
                document.getElementById('listTitle').innerText = 'الشكايات الخاصة بي';
                
                document.getElementById('thT').innerText = 'العنوان';
                document.getElementById('thD').innerText = 'الوصف';
                document.getElementById('thF').innerText = 'الملف المرفق';
                document.getElementById('thA').innerText = 'الإجراءات';
                
                if(document.getElementById('noClaims')) document.getElementById('noClaims').innerText = 'لا توجد أي شكايات حالياً.';
                if(document.getElementById('msgSucces')) document.getElementById('msgSucces').innerText = 'تمت إضافة الشكاية بنجاح!';
                
                document.querySelectorAll('.btnViewLang').forEach(el => el.innerText = 'عرض الملف');
                document.querySelectorAll('.btnEditLang').forEach(el => el.innerText = 'تعديل');
                document.querySelectorAll('.btnDelLang').forEach(el => el.innerText = 'حذف');
                
                document.getElementById('modalTitle').innerText = 'معاينة الملف المرفق';
                document.getElementById('btnCloseModal').innerText = 'إغلاق المعاينة';
                document.getElementById('footerText').innerText = '© 2026 منصة SmarterClaims الرقمية. جميع الحقوق محفوظة.';
                
                // إعادة اتجاه نصوص الجدول لتتطابق مع الـ RTL
                tableThs.forEach(th => th.style.textAlign = 'right');
                tableTds.forEach(td => td.style.textAlign = 'right');
                
                lang = 'ar';
            }
        }
    </script>
</body>
</html>