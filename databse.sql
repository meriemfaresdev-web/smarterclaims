-- 1. إنشاء جدول المستخدمين
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- 2. إنشاء جدول الشكايات مع الساروت البراني
CREATE TABLE reclamations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    fichier_joint VARCHAR(255) NULL,
    id_utilisateur INT,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- 3. إدخال مستخدم تجريبي (Test) باش نجربو بيه الدخول من بعد
INSERT INTO utilisateurs (email, mot_de_passe) VALUES 
('test@gmail.com', '123456');