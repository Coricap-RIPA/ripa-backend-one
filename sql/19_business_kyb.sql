-- =============================================================================
-- RIPA — KYB (Know Your Business) : dossier par marchand + droits back-office
-- Exécuter après sql/14_business_portail_marchand.sql
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `business_kyb_dossier` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_marchand` INT(11) NOT NULL,
  `statut` VARCHAR(32) NOT NULL DEFAULT 'en_attente' COMMENT 'en_attente, valide, rejete',
  `denomination_sociale` VARCHAR(255) NOT NULL,
  `numero_identification_legal` VARCHAR(120) NOT NULL COMMENT 'RCCM, NINEA, SIRET, etc.',
  `adresse_siege` TEXT NOT NULL,
  `ville` VARCHAR(120) NULL DEFAULT NULL,
  `pays` VARCHAR(3) NULL DEFAULT NULL,
  `site_web` VARCHAR(255) NULL DEFAULT NULL,
  `activite_principale` VARCHAR(255) NULL DEFAULT NULL,
  `effectif_tranche` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ex. 1-10, 11-50',
  `telephone` VARCHAR(50) NOT NULL,
  `email_contact` VARCHAR(255) NOT NULL,
  `commentaire_marchand` TEXT NULL DEFAULT NULL,
  `fichier_piece_legal` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Chemin relatif doc identité légale (PDF/image)',
  `fichier_piece_complement` VARCHAR(255) NULL DEFAULT NULL,
  `date_soumission` DATETIME NOT NULL,
  `date_decision` DATETIME NULL DEFAULT NULL,
  `id_utilisateur_validateur` INT(11) NULL DEFAULT NULL COMMENT 'Back-office utilisateur.id_utilisateur',
  `motif_refus` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_kyb_marchand` (`id_marchand`),
  KEY `idx_statut` (`statut`),
  KEY `idx_date_soumission` (`date_soumission`),
  CONSTRAINT `fk_kyb_marchand` FOREIGN KEY (`id_marchand`)
    REFERENCES `business_marchand` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Dossier KYB soumis par le portail marchand';

INSERT INTO `fonctionnalite` (`id_fonctionnalite`, `short_code`, `designation`, `id_group_fonctionnalite`, `is_active`, `system`, `date_heure`)
VALUES (112, 'business_kyb', 'Valider les dossiers KYB (portail B2B)', 1, 1, 1, NOW())
ON DUPLICATE KEY UPDATE `short_code` = 'business_kyb', `designation` = 'Valider les dossiers KYB (portail B2B)';

INSERT INTO `role_permission` (`id_role`, `id_fonctionnalite`, `peux_voir`, `peux_ajouter`, `peux_editer`, `peux_supprimer`, `date_heure`)
SELECT 1, 112, 1, 0, 1, 0, NOW()
FROM (SELECT 1) AS _t
WHERE NOT EXISTS (SELECT 1 FROM `role_permission` WHERE `id_role` = 1 AND `id_fonctionnalite` = 112);

SET FOREIGN_KEY_CHECKS = 1;
