<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SmarterClaims | منصة تدبير الشكايات</title>
    <style>
        :root { 
            --bg: #f8fafc; --text: #0f172a; --primary: #0ea5e9; --primary-hover: #0284c7; 
            --card-bg: #ffffff; --nav-bg: #1e293b; --footer-bg: #0f172a; 
        }
        
        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        *, *:before, *:after {
            box-sizing: inherit;
        }

        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); direction: rtl; display: flex; flex-direction: column; min-height: 100vh; transition: all 0.3s ease; }
        
        nav { background: var(--nav-bg); padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; width: 100%; }
        .logo { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; text-decoration: none; flex-shrink: 0; }
        .logo span { color: var(--primary); }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a { color: #cbd5e1; text-decoration: none; font-size: 15px; font-weight: 500; transition: 0.3s; white-space: nowrap; }
        .nav-links a:hover { color: white; }
        .btn-accent { background: var(--primary); color: white !important; padding: 8px 20px; border-radius: 6px; font-weight: 600; }
        .btn-accent:hover { background: var(--primary-hover); }
        .lang-btn { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px; transition: 0.3s; }
        .lang-btn:hover { background: rgba(255,255,255,0.2); }

        .hero-banner { 
            position: relative;
            background-image: url('image.png'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 100px 0;
            margin-bottom: 60px;
            width: 100%;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.75) 100%);
            z-index: 1;
        }

        .hero-container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
        }
        .hero-text { max-width: 700px; }
        .hero-text h1 { font-size: 50px; font-weight: 800; line-height: 1.3; margin-bottom: 25px; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .hero-text h1 span { color: var(--primary); }
        .hero-text p { font-size: 20px; color: #e2e8f0; line-height: 1.8; margin-bottom: 35px; text-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        
        .cta-group { display: flex; gap: 15px; }
        .btn-main { background: var(--primary); color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 14px rgba(14, 165, 233, 0.4); transition: 0.3s; text-align: center; white-space: nowrap; }
        .btn-main:hover { background: var(--primary-hover); transform: translateY(-2px); }
        .btn-sec { background: rgba(255, 255, 255, 0.15); color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(5px); transition: 0.3s; text-align: center; white-space: nowrap; }
        .btn-sec:hover { background: rgba(255, 255, 255, 0.25); transform: translateY(-2px); }

        .content-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; width: 100%; }

        .stats-section { display: flex; justify-content: space-around; background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 60px; text-align: center; gap: 20px; width: 100%; }
        .stat-card h3 { font-size: 36px; color: var(--primary); margin: 0 0 5px 0; font-weight: 800; }
        .stat-card p { font-size: 15px; color: #64748b; margin: 0; font-weight: 500; }

        .section-title { text-align: center; font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 40px; padding: 0 10px; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 80px; width: 100%; }
        .feature-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.01); border-bottom: 3px solid transparent; transition: 0.3s; }
        .feature-card:hover { transform: translateY(-5px); border-bottom-color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .feature-icon { font-size: 35px; margin-bottom: 15px; }
        .feature-card h4 { font-size: 18px; font-weight: 700; margin: 0 0 10px 0; color: #1e293b; }
        .feature-card p { font-size: 14px; color: #64748b; margin: 0; line-height: 1.6; }

        .steps-section { padding: 60px 0; background: rgba(14, 165, 233, 0.03); border-radius: 20px; margin-bottom: 80px; width: 100%; }
        .steps-grid { display: flex; justify-content: space-around; gap: 30px; padding: 0 40px; }
        .step-card { flex: 1; text-align: center; position: relative; }
        .step-num { width: 50px; height: 50px; background: var(--nav-bg); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; margin: 0 auto 20px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .step-card h4 { font-size: 18px; margin: 0 0 10px 0; color: #1e293b; }
        .step-card p { font-size: 14px; color: #64748b; margin: 0; line-height: 1.6; }

        @media (max-width: 992px) {
            nav { padding: 15px 25px; }
            .hero-banner { padding: 80px 0; }
            .hero-text h1 { font-size: 40px; }
        }

        @media (max-width: 768px) {
            nav { 
                flex-direction: column !important; 
                gap: 15px; 
                text-align: center; 
                padding: 15px; 
            }
            .nav-links { 
                flex-direction: column !important; 
                width: 100%; 
                gap: 12px; 
                justify-content: center;
            }
            .nav-links a, .btn-accent, .lang-btn {
                width: 100%;
                text-align: center;
                padding: 10px 0;
            }
            .hero-banner { padding: 50px 0; margin-bottom: 30px; }
            .hero-text h1 { font-size: 28px !important; text-align: center; padding: 0 10px; }
            .hero-text p { font-size: 15px !important; text-align: center; margin-bottom: 25px; padding: 0 10px; }
            
            .cta-group { flex-direction: column; width: 100%; gap: 12px; padding: 0 10px; }
            .btn-main, .btn-sec { width: 100%; padding: 12px 20px; }
            
            .stats-section { flex-direction: column; gap: 25px; padding: 25px 15px; margin-bottom: 40px; }
            .steps-grid { flex-direction: column; gap: 40px; padding: 0 15px; }
            .steps-section { padding: 40px 0; margin-bottom: 40px; }
            .section-title { font-size: 22px; margin-bottom: 25px; }
            
            .content-container { padding: 0 15px; }
        }
        
        @media (max-width: 480px) {
            .hero-text h1 { font-size: 24px !important; }
            .logo { font-size: 19px; }
        }

        footer { background: var(--footer-bg); color: #94a3b8; text-align: center; padding: 25px; font-size: 14px; border-top: 1px solid #1e293b; margin-top: auto; width: 100%; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo" id="logoText">Smarter<span>Claims</span></a>
        <div class="nav-links" id="navLinks">
            <a href="index.php" id="navHome">الرئيسية</a>
            <a href="login.php" id="navTrack">تتبع شكاية</a>
            <a href="register.php" class="btn-accent" id="navSubmit">تقديم شكاية</a>
            <button class="lang-btn" onclick="switchLanguage()" id="langBtn">Français</button>
        </div>
    </nav>

    <div class="hero-banner">
        <div class="hero-container">
            <div class="hero-text">
                <h1 id="heroTitle">عبر عن رأيك، <span>ونحن نتولى الباقي.</span></h1>
                <p id="heroSub">المنصة الرقمية الأحدث والأسرع لإرسال وتدبير الشكايات بشكل آمن وتفاعلي بالكامل. صُممت خصيصاً لتسهيل التواصل وحل المشكلات بذكاء واحترافية.</p>
                <div class="cta-group" id="ctaGroup">
                    <a href="register.php" class="btn-main" id="ctaSubmit">تقديم شكاية جديدة</a>
                    <a href="login.php" class="btn-sec" id="ctaTrack">تتبع حالة شكاية</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container">
        
        <div class="stats-section">
            <div class="stat-card">
                <h3>+98%</h3>
                <p id="stat1">نسبة رضا المواطنين</p>
            </div>
            <div class="stat-card">
                <h3>24h</h3>
                <p id="stat2">متوسط وقت معالجة الطلب</p>
            </div>
            <div class="stat-card">
                <h3>+15K</h3>
                <p id="stat3">شكاية تم حلها بنجاح</p>
            </div>
        </div>

        <h2 class="section-title" id="featuresTitle">لماذا تختار منصتنا؟</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h4 id="f1Title">حماية بيانات كاملة</h4>
                <p id="f1Desc">تشفير متطور لكافة البيانات والوثائق المرفقة لضمان سرية الشكايات وهوية المستخدمين بشكل صارم.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h4 id="f2Title">سرعة قصوى في الرد</h4>
                <p id="f2Desc">توجيه ذكي وفوري للشكايات مباشرة إلى المصلحة المعنية لضمان معالجة سريعة وبدون تعقيدات.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <h4 id="f3Title">تتبع مباشر وحي</h4>
                <p id="f3Desc">لوحة تحكم خاصة تتيح لك مراقبة حالة شكايتك ومعرفة التحديثات خطوة بخطوة وفور حدوثها.</p>
            </div>
        </div>

        <div class="steps-section">
            <h2 class="section-title" id="stepsTitle">كيف تعمل المنصة؟</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <h4 id="s1Title">إنشاء الحساب</h4>
                    <p id="s1Desc">سجل حسابك مجاناً وبلمحة بصر باستخدام بريدك الإلكتروني فقط.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <h4 id="s2Title">إرسال الشكاية</h4>
                    <p id="s2Desc">اكتب تفاصيل الشكاية وأرفق معها الملفات الداعمة (PDF أو صور).</p>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <h4 id="s3Title">تتبع الحل</h4>
                    <p id="s3Desc">تابع مراحل المعالجة مباشرة من فضائك الخاص حتى إيجاد الحل النهائي.</p>
                </div>
            </div>
        </div>

    </div>

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
                document.getElementById('navTrack').innerText = 'Suivre une réclamation';
                document.getElementById('navSubmit').innerText = 'Déposer une réclamation';
                
                document.getElementById('heroTitle').innerHTML = 'Exprimez-vous, <span>nous faisons le reste.</span>';
                document.getElementById('heroSub').innerText = 'La plateforme digitale la plus récente et la plus rapide pour envoyer et gérer les réclamations de manière sécurisée et interactive. Conçue spécifiquement pour simplifier la communication et résoudre les problèmes intelligemment.';
                document.getElementById('ctaSubmit').innerText = 'Déposer une nouvelle réclamation';
                document.getElementById('ctaTrack').innerText = 'Suivre le statut d\'une réclamation';

                document.getElementById('stat1').innerText = 'Taux de satisfaction des citoyens';
                document.getElementById('stat2').innerText = 'Temps de réponse moyen';
                document.getElementById('stat3').innerText = 'Réclamations résolues avec succès';

                document.getElementById('featuresTitle').innerText = 'Pourquoi choisir notre plateforme ?';
                document.getElementById('f1Title').innerText = 'Protection complète des données';
                document.getElementById('f1Desc').innerText = 'Un cryptage avancé pour toutes les données et documents joints afin de garantir strictement la confidentialité.';
                document.getElementById('f2Title').innerText = 'Vitesse de réponse maximale';
                document.getElementById('f2Desc').innerText = 'Orientation instantanée des réclamations directement vers le service concerné pour assurer un traitement rapide et fluide.';
                document.getElementById('f3Title').innerText = 'Suivi direct et en temps réel';
                document.getElementById('f3Desc').innerText = 'Un tableau de bord personnel vous permettant de surveiller le statut de votre réclamation étape par étape.';

                document.getElementById('stepsTitle').innerText = 'Comment ça fonctionne ?';
                document.getElementById('s1Title').innerText = 'Créer un compte';
                document.getElementById('s1Desc').innerText = 'Enregistrez votre compte gratuitement en un clin d\'œil en utilisant simplement votre adresse e-mail.';
                document.getElementById('s2Title').innerText = 'Envoyer la réclamation';
                document.getElementById('s2Desc').innerText = 'Écrivez les détails de votre réclamation et joignez les fichiers justificatifs (PDF ou Images).';
                document.getElementById('s3Title').innerText = 'Suivre la solution';
                document.getElementById('s3Desc').innerText = 'Suivez les étapes de traitement en direct depuis votre espace personnel jusqu\'à la solution finale.';
                
                document.getElementById('footerText').innerText = '© 2026 Plateforme Digitale SmarterClaims. Tous droits réservés.';
                lang = 'fr';
            } else {
                body.style.direction = 'rtl';
                document.getElementById('langBtn').innerText = 'Français';
                document.getElementById('navHome').innerText = 'الرئيسية';
                document.getElementById('navTrack').innerText = 'تتبع شكاية';
                document.getElementById('navSubmit').innerText = 'تقديم شكاية';
                
                document.getElementById('heroTitle').innerHTML = 'عبر عن رأيك، <span>ونحن نتولى الباقي.</span>';
                document.getElementById('heroSub').innerText = 'المنصة الرقمية الأحدث والأسرع لإرسال وتدبير الشكايات بشكل آمن وتفاعلي بالكامل. صُممت خصيصاً لتسهيل التواصل وحل المشكلات بذكاء واحترافية.';
                document.getElementById('ctaSubmit').innerText = 'تقديم شكاية جديدة';
                document.getElementById('ctaTrack').innerText = 'تتبع حالة شكاية';
                
                document.getElementById('stat1').innerText = 'نسبة رضا المواطنين';
                document.getElementById('stat2').innerText = 'متوسط وقت معالجة الطلب';
                document.getElementById('stat3').innerText = 'شكاية تم حلها بنجاح';
                
                document.getElementById('featuresTitle').innerText = 'لماذا تختار منصتنا؟';
                document.getElementById('f1Title').innerText = 'حماية بيانات كاملة';
                document.getElementById('f1Desc').innerText = 'تشفير متطور لكافة البيانات والوثائق المرفقة لضمان سرية الشكايات وهوية المستخدمين بشكل صارم.';
                document.getElementById('f2Title').innerText = 'سرعة قصوى في الرد';
                document.getElementById('f2Desc').innerText = 'توجيه ذكي وفوري للشكايات مباشرة إلى المصلحة المعنية لضمان معالجة سريعة وبدون تعقيدات.';
                document.getElementById('f3Title').innerText = 'تتبع مباشر وحي';
                document.getElementById('f3Desc').innerText = 'لوحة تحكم خاصة تتيح لك مراقبة حالة شكايتك ومعرفة التحديثات خطوة بخطوة وفور حدوثها.';
                
                document.getElementById('stepsTitle').innerText = 'كيف تعمل المنصة؟';
                document.getElementById('s1Title').innerText = 'إنشاء الحساب';
                document.getElementById('s1Desc').innerText = 'سجل حسابك مجاناً وبلمحة بصر باستخدام بريدك الإلكتروني فقط.';
                document.getElementById('s2Title').innerText = 'إرسال الشكاية';
                document.getElementById('s2Desc').innerText = 'اكتب تفاصيل الشكاية وأرفق معها الملفات الداعمة (PDF أو صور).';
                document.getElementById('s3Title').innerText = 'تتبع الحل';
                document.getElementById('s3Desc').innerText = 'تابع مراحل المعالجة مباشرة من فضائك الخاص حتى إيجاد الحل النهائي.';
                
                document.getElementById('footerText').innerText = '© 2026 منصة SmarterClaims الرقمية. جميع الحقوق محفوظة.';
                lang = 'ar';
            }
        }
    </script>
</body>
</html>