-- =============================================================================
-- RIPA - Ajout employés + journal transactions business (si 14 déjà exécuté sans ces tables)
-- Idempotent : CREATE TABLE IF NOT EXISTS
-- Sinon : exécuter uniquement sql/14_business_portail_marchand.sql à jour.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `business_employe` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `nom` VARCHAR(120) NOT NULL,
  `prenom` VARCHAR(120) NOT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `telephone` VARCHAR(50) NULL DEFAULT NULL,
  `poste` VARCHAR(128) NULL DEFAULT NULL,
  `actif` TINYINT(1) NOT NULL DEFAULT 1,
  `id_utilisateur_business` INT(11) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_marchand` (`id_marchand`),
  KEY `idx_ub` (`id_utilisateur_business`),
  CONSTRAINT `fk_be_marchand` FOREIGN KEY (`id_marchand`)
    REFERENCES `business_marchand` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_be_utilisateur_business` FOREIGN KEY (`id_utilisateur_business`)
    REFERENCES `utilisateur_business` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `business_transaction` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `id_service` INT(11) NULL DEFAULT NULL,
  `id_utilisateur_business` INT(11) NULL DEFAULT NULL,
  `id_employe` INT(11) NULL DEFAULT NULL,
  `type_operation` VARCHAR(64) NOT NULL DEFAULT 'autre',
  `sens` ENUM('debit','credit') NOT NULL DEFAULT 'debit',
  `montant` DECIMAL(15,2) NOT NULL,
  `devise` VARCHAR(8) NOT NULL DEFAULT 'USD',
  `libelle` VARCHAR(512) NULL DEFAULT NULL,
  `statut` VARCHAR(32) NOT NULL DEFAULT 'valide',
  `id_paiement_ripa` INT(11) NULL DEFAULT NULL,
  `meta_json` TEXT NULL DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
