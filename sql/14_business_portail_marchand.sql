-- =============================================================================
-- RIPA - Portail marchand (B2B) — tables de base
-- Voir docs/SPECS-PORTAIL-MARCHAND-B2B.md
-- Conformité : mot de passe = hash bcrypt uniquement ; pas de PAN/CVV ici.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. Entreprise / demande marchand
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_marchand` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `raison_sociale` VARCHAR(255) NOT NULL,
  `email_contact` VARCHAR(255) NOT NULL COMMENT 'Email pro ; devient login utilisateur_business à la validation',
  `telephone_contact` VARCHAR(50) NULL DEFAULT NULL,
  `identifiant_legal` VARCHAR(100) NULL DEFAULT NULL COMMENT 'RCCM / autre',
  `statut` VARCHAR(32) NOT NULL DEFAULT 'brouillon' COMMENT 'brouillon, en_attente_validation, actif, refuse, suspendu',
  `id_utilisateur_demandeur` INT(11) NULL DEFAULT NULL COMMENT 'Optionnel : lien app utilisateur_application',
  `id_utilisateur_validateur` INT(11) NULL DEFAULT NULL COMMENT 'Backoffice utilisateur.id_utilisateur',
  `date_demande` DATETIME NULL DEFAULT NULL,
  `date_decision` DATETIME NULL DEFAULT NULL,
  `motif_refus` TEXT NULL DEFAULT NULL,
  `notes_internes` TEXT NULL DEFAULT NULL COMMENT 'Non exposé au marchand',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_statut` (`statut`),
  KEY `idx_email_contact` (`email_contact`(191)),
  KEY `idx_demandeur` (`id_utilisateur_demandeur`),
  CONSTRAINT `fk_bm_demandeur_app` FOREIGN KEY (`id_utilisateur_demandeur`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Comptes professionnels marchands RIPA';

-- -----------------------------------------------------------------------------
-- 2. Utilisateurs du portail web business (auth dédiée, liés à un marchand)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `utilisateur_business` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt uniquement',
  `role` VARCHAR(50) NOT NULL DEFAULT 'administrateur' COMMENT 'administrateur, gestionnaire, lecteur_seul, ...',
  `doit_changer_mot_de_passe` TINYINT(1) NOT NULL DEFAULT 1,
  `actif` TINYINT(1) NOT NULL DEFAULT 1,
  `nom` VARCHAR(120) NULL DEFAULT NULL,
  `prenom` VARCHAR(120) NULL DEFAULT NULL,
  `id_utilisateur_application` INT(11) NULL DEFAULT NULL COMMENT 'Lien optionnel même personne que l app',
  `derniere_connexion` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email` (`email`(191)),
  KEY `idx_marchand` (`id_marchand`),
  KEY `idx_app_user` (`id_utilisateur_application`),
  CONSTRAINT `fk_ub_marchand` FOREIGN KEY (`id_marchand`)
    REFERENCES `business_marchand` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ub_utilisateur_app` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Comptes connexion portail marchand';

-- -----------------------------------------------------------------------------
-- 3. Services internes (Finance, Compta, …)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_service` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `libelle` VARCHAR(255) NOT NULL,
  `code` VARCHAR(64) NULL DEFAULT NULL,
  `actif` TINYINT(1) NOT NULL DEFAULT 1,
  `ordre` INT(11) NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_marchand` (`id_marchand`),
  CONSTRAINT `fk_bs_marchand` FOREIGN KEY (`id_marchand`)
    REFERENCES `business_marchand` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Services internes entreprise marchand';

-- -----------------------------------------------------------------------------
-- 4. Liaison moyens de paiement (référence tables MM / banque / carte existantes)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_moyen_lien` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `id_service` INT(11) NULL DEFAULT NULL,
  `type_moyen` VARCHAR(32) NOT NULL COMMENT 'mobile_money, compte_bancaire, carte_physique, carte_virtuelle',
  `id_compte_reference` INT(11) NOT NULL COMMENT 'PK dans la table métier cible',
  `id_utilisateur_application` INT(11) NULL DEFAULT NULL,
  `actif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_marchand` (`id_marchand`),
  KEY `idx_service` (`id_service`),
  KEY `idx_type_ref` (`type_moyen`, `id_compte_reference`),
  CONSTRAINT `fk_bml_marchand` FOREIGN KEY (`id_marchand`)
    REFERENCES `business_marchand` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bml_service` FOREIGN KEY (`id_service`)
    REFERENCES `business_service` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_bml_utilisateur_app` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Rattachement moyens paiement au contexte business';

-- -----------------------------------------------------------------------------
-- 5. Employés (fiche RH) — lien optionnel vers compte portail utilisateur_business
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_employe` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `nom` VARCHAR(120) NOT NULL,
  `prenom` VARCHAR(120) NOT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `telephone` VARCHAR(50) NULL DEFAULT NULL,
  `poste` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Fonction / service interne',
  `actif` TINYINT(1) NOT NULL DEFAULT 1,
  `id_utilisateur_business` INT(11) NULL DEFAULT NULL COMMENT 'Si un compte portail est rattaché à cet employé',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_marchand` (`id_marchand`),
  KEY `idx_ub` (`id_utilisateur_business`),
  CONSTRAINT `fk_be_marchand` FOREIGN KEY (`id_marchand`)
    REFERENCES `business_marchand` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_be_utilisateur_business` FOREIGN KEY (`id_utilisateur_business`)
    REFERENCES `utilisateur_business` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Employés du marchand (RH)';

-- -----------------------------------------------------------------------------
-- 6. Journal des transactions « vue business » (liste portail + lien optionnel B2C)
--    Pas de FK vers transaction_paiement_ripa : table peut être absente selon migrations.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_transaction` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `id_service` INT(11) NULL DEFAULT NULL,
  `id_utilisateur_business` INT(11) NULL DEFAULT NULL COMMENT 'Initiateur (portail)',
  `id_employe` INT(11) NULL DEFAULT NULL COMMENT 'Contexte employé si pertinent',
  `type_operation` VARCHAR(64) NOT NULL DEFAULT 'autre' COMMENT 'encaissement, decaissement, salaire, virement, paiement_fournisseur, autre',
  `sens` ENUM('debit','credit') NOT NULL DEFAULT 'debit',
  `montant` DECIMAL(15,2) NOT NULL,
  `devise` VARCHAR(8) NOT NULL DEFAULT 'USD',
  `libelle` VARCHAR(512) NULL DEFAULT NULL,
  `statut` VARCHAR(32) NOT NULL DEFAULT 'valide' COMMENT 'valide, en_attente, annule',
  `id_paiement_ripa` INT(11) NULL DEFAULT NULL COMMENT 'Lien logique transaction_paiement_ripa.id_paiement (sans contrainte FK)',
  `meta_json` TEXT NULL DEFAULT NULL COMMENT 'JSON sanitized (pas de PAN/CVV)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_marchand_date` (`id_marchand`, `created_at`),
  KEY `idx_service` (`id_service`),
  KEY `idx_ub` (`id_utilisateur_business`),
  KEY `idx_employe` (`id_employe`),
  KEY `idx_paiement_ripa` (`id_paiement_ripa`),
  CONSTRAINT `fk_bt_marchand` FOREIGN KEY (`id_marchand`)
    REFERENCES `business_marchand` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bt_service` FOREIGN KEY (`id_service`)
    REFERENCES `business_service` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_bt_ub` FOREIGN KEY (`id_utilisateur_business`)
    REFERENCES `utilisateur_business` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_bt_employe` FOREIGN KEY (`id_employe`)
    REFERENCES `business_employe` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Transactions affichées / agrégées côté portail marchand';

SET FOREIGN_KEY_CHECKS = 1;
