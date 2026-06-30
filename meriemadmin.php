<?php
// admin.php - لوحة التحكم المباشرة والسرية
require_once 'db.php';

// 1. جلب جميع الشكايات الواردة مع إيميلات أصحابها
try {
    $stmt_claims = $pdo->query("SELECT reclamations.*, utilisateurs.email FROM reclamations JOIN utilisateurs ON reclamations.id_utilisateur = utilisateurs.id ORDER BY reclamations.id DESC");
    $all_claims = $stmt_claims->fetchAll();
} catch (PDOException $e) {
    $all_claims = [];
}

// 2. جلب قائمة جميع المستخدمين المسجلين ف النظام
try {
    $stmt_users = $pdo->query("SELECT id, email FROM utilisateurs ORDER BY id DESC");
    $all_users = $stmt_users->fetchAll();
} catch (PDOException $e) {
    $all_users = [];
}

// 3. قراءة سجل المحذوفات من ملف historique.txt
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المشرف العام | SmarterClaims Direct</title>
    <style>
        :root { --bg: #f8fafc; --dark: #0f172a; --primary: #0ea5e9; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: #334155; margin: 0; direction: rtl; }
        
        .navbar { background: var(--dark); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h2 { margin: 0; font-size: 20px; }
        .back-btn { background: rgba(255,255,255,0.1); color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; }

        .container { max-width: 1300px; margin: 25px auto; padding: 0 20px; display: flex; flex-direction: column; gap: 25px; }
        
        .grid-top { display: flex; gap: 25px; width: 100%; }
        .panel-claims { width: 70%; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-top: 4px solid var(--primary); }
        .panel-users { width: 30%; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-top: 4px solid #6366f1; }
        .panel-history { width: 100%; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-top: 4px solid #ef4444; }
        
        h3 { margin-top: 0; color: var(--dark); font-size: 16px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 15px; }
        
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: right; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 13.5px; }
        th { background: #f8fafc; color: #475569; font-weight: 600; }
        
        .badge { background: #f1f5f9; padding: 3px 6px; border-radius: 4px; font-size: 11.5px; font-weight: 600; color: #1e293b; }
        .file-link { color: var(--primary); text-decoration: none; font-weight: bold; }
        .file-link:hover { text-decoration: underline; }
        
        pre { background: #0f172a; color: #38bdf8; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 13px; max-height: 250px; overflow-y: auto; text-align: left; direction: ltr; white-space: pre-wrap; margin: 0; }

        @media (max-width: 768px) {
            .grid-top { flex-direction: column; }
            .panel-claims, .panel-users { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>لوحة المراقبة المباشرة 🔍</h2>
        <a href="index.php" class="back-btn">الرئيسية ←</a>
    </div>

    <div class="container">
        
        <div class="grid-top">
            <div class="panel-claims">
                <h3>واردات الشكايات الحالية والملفات المرفقة</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>المواطن</th>
                                <th>العنوان</th>
                                <th>الوصف</th>
                                <th>الملف المرفوع (Uploads)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($all_claims) > 0): ?>
                                <?php foreach ($all_claims as $claim): ?>
                                    <tr>
                                        <td><span class="badge"><?= htmlspecialchars($claim['email']) ?></span></td>
                                        <td style="font-weight:600;"><?= htmlspecialchars($claim['titre']) ?></td>
                                        <td style="color:#64748b; max-width:250px;"><?= htmlspecialchars($claim['description']) ?></td>
                                        <td><a href="uploads/<?= $claim['fichier_joint'] ?>" target="_blank" class="file-link">🔎 فتح الملف المرفق</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align:center; color:#94a3b8;">لا توجد شكايات بالنظام حالياً.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel-users">
                <h3>المستخدمين المسجلين (Utilisateurs)</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>البريد الإلكتروني</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($all_users) > 0): ?>
                                <?php foreach ($all_users as $user): ?>
                                    <tr>
                                        <td><code>#<?= $user['id'] ?></code></td>
                                        <td style="font-weight:500;"><?= htmlspecialchars($user['email']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" style="text-align:center; color:#94a3b8;">لا يوجد أي مستخدم.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel-history">
            <h3>سجل الأرشيف وتاريخ المحذوفات (historique.txt)</h3>
            <pre><?= htmlspecialchars($log_content) ?></pre>
        </div>

    </div>

</body>
</html>