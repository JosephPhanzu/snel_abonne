-- Création de la base de données
CREATE DATABASE IF NOT EXISTS bdd_cnss;
USE bdd_cnss;

-- =====================================================
-- 1. TABLE UTILISATEUR (Table parent)
-- =====================================================
CREATE TABLE IF NOT EXISTS utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('AGENT_CNSS', 'EMPLOYEUR') NOT NULL,
    code VARCHAR(255) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dernier_acces TIMESTAMP NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. TABLE AGENT_CNSS
-- =====================================================
CREATE TABLE IF NOT EXISTS agent_cnss (
    id INT PRIMARY KEY,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    service VARCHAR(100) NOT NULL,
    niveau VARCHAR(50),
    telephone VARCHAR(20),
    code VARCHAR(255) NOT NULL,
    code_utilisateur VARCHAR(255) NOT NULL,
    FOREIGN KEY (code_utilisateur) REFERENCES utilisateur(code) ON DELETE CASCADE,
    INDEX idx_matricule (matricule),
    INDEX idx_code_utilisateur (code_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. TABLE EMPLOYEUR
-- =====================================================
CREATE TABLE IF NOT EXISTS employeur (
    id INT PRIMARY KEY,
    numero_registre VARCHAR(50) NOT NULL UNIQUE,
    raison_sociale VARCHAR(200) NOT NULL,
    adresse TEXT,
    telephone VARCHAR(20),
    email_contact VARCHAR(150),
    date_inscription DATE NOT NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    secteur_activite VARCHAR(100),
    code VARCHAR(255) NOT NULL,
    code_utilisateur VARCHAR(255) NOT NULL,
    nif VARCHAR(50) UNIQUE, -- Numéro d'Identification Fiscale
    FOREIGN KEY (code_utilisateur) REFERENCES utilisateur(code) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. TABLE EMPLOYE
-- =====================================================
CREATE TABLE IF NOT EXISTS employe (
    id INT PRIMARY KEY AUTO_INCREMENT,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    lieu_naissance VARCHAR(100),
    adresse TEXT,
    telephone VARCHAR(20),
    email VARCHAR(150),
    date_embauche DATE NOT NULL,
    salaire_brut DECIMAL(12,2) NOT NULL,
    poste VARCHAR(100),
    departement VARCHAR(100),
    code_employeur VARCHAR(255) NOT NULL,
    code VARCHAR(255) NOT NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_matricule (matricule),
    INDEX idx_employeur (employeur_id),
    INDEX idx_nom_prenom (nom, prenom),
    FOREIGN KEY (code_employeur) REFERENCES employeur(code) ON DELETE CASCADE,
    CONSTRAINT chk_salaire CHECK (salaire_brut > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. TABLE COTISATION
-- =====================================================
CREATE TABLE IF NOT EXISTS cotisation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    periode VARCHAR(20) NOT NULL, -- Format: 'YYYY-MM'
    montant_total DECIMAL(12,2) NOT NULL,
    montant_part_employeur DECIMAL(12,2) NOT NULL,
    montant_part_employe DECIMAL(12,2) NOT NULL,
    date_echeance DATE NOT NULL,
    date_paiement DATE NULL,
    statut ENUM('EN_ATTENTE', 'PAYE', 'EN_RETARD', 'ANNULE') DEFAULT 'EN_ATTENTE',
    code_employeur VARCHAR(255) NOT NULL,
    code_employe VARCHAR(255) NOT NULL,
    code VARCHAR(255) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_periode (periode),
    INDEX idx_statut (statut),
    INDEX idx_employeur (employeur_id),
    INDEX idx_employe (employe_id),
    INDEX idx_echeance (date_echeance),
    FOREIGN KEY (code_employeur) REFERENCES employeur(code) ON DELETE CASCADE,
    FOREIGN KEY (code_employe) REFERENCES employe(code) ON DELETE CASCADE,
    CONSTRAINT chk_montants CHECK (
        montant_total = montant_part_employeur + montant_part_employe AND
        montant_part_employe = montant_total * 0.15
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 6. TABLE PAIEMENT
-- =====================================================
CREATE TABLE IF NOT EXISTS paiement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    montant DECIMAL(12,2) NOT NULL,
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    mode_paiement ENUM('VIREMENT', 'CHEQUE', 'ESPECES', 'EN_LIGNE') NOT NULL,
    reference_transaction VARCHAR(100) UNIQUE,
    statut ENUM('EN_ATTENTE', 'VALIDE', 'ANNULE') DEFAULT 'EN_ATTENTE',
    code_cotisation VARCHAR(255) NOT NULL,
    code VARCHAR(255),
    justificatif VARCHAR(255),
    valide_par VARCHAR(255) NOT NULL,
    date_validation TIMESTAMP NULL,
    INDEX idx_reference (reference_transaction),
    INDEX idx_statut (statut),
    INDEX idx_cotisation (code_cotisation),
    FOREIGN KEY (code_cotisation) REFERENCES cotisation(code) ON DELETE CASCADE,
    FOREIGN KEY (valide_par) REFERENCES agent_cnss(code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 7. TABLE BORDEREAU
-- =====================================================
CREATE TABLE IF NOT EXISTS bordereau (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_bordereau VARCHAR(50) NOT NULL UNIQUE,
    date_demande DATE NOT NULL,
    date_validation DATE NULL,
    montant_total DECIMAL(12,2) NOT NULL,
    statut ENUM('EN_ATTENTE', 'VALIDE', 'REJETE', 'ANNULE') DEFAULT 'EN_ATTENTE',
    code_employeur NOT NULL,
    code VARCHAR(255),
    valide_par VARCHAR(255) NULL,
    motif_rejet TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_numero (numero_bordereau),
    INDEX idx_statut (statut),
    INDEX idx_employeur (employeur_id),
    INDEX idx_valide_par (valide_par),
    FOREIGN KEY (code_employeur) REFERENCES employeur(code) ON DELETE CASCADE,
    FOREIGN KEY (valide_par) REFERENCES agent_cnss(code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 8. TABLE BORDEREAU_COTISATION (Relation many-to-many)
-- =====================================================
CREATE TABLE IF NOT EXISTS bordereau_cotisation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code_bordereau VARCHAR(255) NOT NULL,
    code_cotisation VARCHAR(255) NOT NULL,
    code VARCHAR(255) NOT NULL,
    INDEX idx_bordereau (code_bordereau),
    INDEX idx_cotisation (code_cotisation),
    FOREIGN KEY (code_bordereau) REFERENCES bordereau(code) ON DELETE CASCADE,
    FOREIGN KEY (code_cotisation) REFERENCES cotisation(code) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 9. TABLE NOTIFICATIONS
-- =====================================================
CREATE TABLE IF NOT EXISTS notification (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code_utilisateur VARCHAR(255) NOT NULL,
    titre VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('INFO', 'SUCCES', 'AVERTISSEMENT', 'ERREUR') DEFAULT 'INFO',
    est_lu BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lien VARCHAR(500),
    INDEX idx_utilisateur (utilisateur_id),
    INDEX idx_lu (est_lu),
    FOREIGN KEY (code_utilisateur) REFERENCES utilisateur(code) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- CRÉATION DES INDEX POUR OPTIMISATION
-- =====================================================

CREATE INDEX idx_cotisation_recherche ON cotisation(employeur_id, periode, statut);
CREATE INDEX idx_paiement_recherche ON paiement(cotisation_id, statut);
CREATE INDEX idx_bordereau_recherche ON bordereau(employeur_id, statut, date_demande);
CREATE INDEX idx_employe_recherche ON employe(employeur_id, nom, prenom);