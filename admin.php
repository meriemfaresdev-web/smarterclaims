<?php
// admin.php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

// جلب جميع الشكايات مع بيانات أصحابها
$stmt = $pdo->query("SELECT reclamations.*, utilisateurs.email FROM reclamations JOIN utilisateurs ON reclamations.id_utilisateur = utilisateurs.id ORDER BY reclamations.id DESC");
$all_claims = $stmt->fetchAll();

// جلب بيانات ملف historique.txt لعرض المحذوفات
$log_content = "";
if (file_exists("historique.txt")) {
    $log_content = file_get_contents("historique.txt");
} else {
    $log_content = "سجل الأرشيف فارغ حالياً، لا توجد شكايات محذوفة.";
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>بوابة الإشراف والرقابة | SmarterClaims Admin</title>
    <style>
        :root { --bg: #f1f5f9; --dark: #0f172a; --primary: #0ea5e9; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: #334155; margin: 0; direction: rtl; }
        .navbar { background: var(--dark); color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .btn-logout { background: #ef4444; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; }
        .wrapper { max-width: 1300px; margin: 30px auto; padding: 0 20px; display: flex; gap: 25px; }
        .main-panel { width: 65%; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .side-panel { width: 35%; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        h3 { margin-top: 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px; font-size: 18px; color: var(--dark); }
        table { width: 100%; border-collapse: collapse; text-align: right; }
        th, td { padding: 12px; border-bottom: 1px solid #ef4444; border-color: #f1f5f9; font-size: 13.5px; }
        th { background: #f8fafc; color: #475569; }
        .user-badge { background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-weight: 600; color: #1e293b; font-size: 12px; }
        pre { background: #0f172a; color: #38bdf8; padding: 15px; border-radius: 8px; font-family: 'Courier New', Courier, monospace; font-size: 13px; max-height: 400px; overflow-y: auto; text-align: left; direction: ltr; white-space: pre-wrap; }
        .file-btn { background: #e0f2fe; color: #0369a1; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-weight: 500; font-size: 12px; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>لوحة تحكم المسؤول العام ⚖️</h2>
        <div>
            <span style="margin-left:15px; color:#94a3b8; font-size:14px;">المشرف: admin@smarterclaims.com</span>
            <a href="logout.php" class="btn-logout">تسجيل الخروج</a>
        </div>
    </div>

    <div class="wrapper">
        <div class="main-panel">
            <h3>واردات الشكايات الحالية (العموم)</h3>
            <table>
                <thead>
                    <tr>
                        <th>المرسل (المواطن)</th>
                        <th>عنوان التذكرة</th>
                        <th>تفاصيل الشكاية</th>
                        <th>الملف المرفوع</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($all_claims) > 0): ?>
                        <?php foreach ($all_claims as $claim): ?>
                            <tr>
                                <td><span class="user-badge"><?= htmlspecialchars($claim['email']) ?></span></td>
                                <td style="font-weight: 600;"><?= htmlspecialchars($claim['titre']) ?></td>
                                <td style="color: #475569; max-width: 220px;"><?= htmlspecialchars($claim['description']) ?></td>
                                <td><a href="uploads/<?= $claim['fichier_joint'] ?>" target="_blank" class="file-btn">🔎 فحص الملف</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8;">لا توجد أي شكايات واردة بالنظام حالياً.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="side-panel">
            <h3>سجل الأرشيف والمحذوفات الذكي (historique.txt)</h3>
            <p style="font-size: 13px; color:#64748b; margin-top: -5px; margin-bottom: 15px;">يتم هنا تسجيل كل عملية حذف مع إيميل المستخدم الذي قام بها والوقت بدقة.</p>
            <pre><?= htmlspecialchars($log_content) ?></pre>
        </div>
    </div>

</body>
</html>