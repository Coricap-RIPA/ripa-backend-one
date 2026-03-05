-- =============================================================================
-- RIPA - KYC (Know Your Customer) : identité utilisateur pour enregistrement carte
-- Données personnelles stockées chiffrées (encrypt_ripa).
-- date_prochaine_kyc = 2 ans après date_validation_kyc (validation par admins RIPA).
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `kyc_utilisateur_application` (
  `id_kyc` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NOT NULL,
  `nom_c` TEXT NULL COMMENT 'Nom chiffré (encrypt_ripa)',
  `post_nom_c` TEXT NULL COMMENT 'Post-nom chiffré',
  `prenom_c` TEXT NULL COMMENT 'Prénom chiffré',
  `date_naissance_c` TEXT NULL COMMENT 'Date de naissance chiffrée (YYYY-MM-DD)',
  `adresse_c` TEXT NULL COMMENT 'Adresse chiffrée',
  `photo_piece_identite_c` TEXT NULL COMMENT 'Chemin ou identifiant photo pièce d\'identité (chiffré)',
  `photo_utilisateur_c` TEXT NULL COMMENT 'Chemin ou identifiant photo utilisateur / buste (chiffré)',
  `date_enregistrement` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de soumission du KYC par l\'utilisateur',
  `date_validation_kyc` DATE NULL DEFAULT NULL COMMENT 'Date de validation par les admins RIPA',
  `date_prochaine_kyc` DATE NULL DEFAULT NULL COMMENT 'Date de la prochaine révision KYC (2 ans après date_validation_kyc)',
  `statut` ENUM('en_attente','valide','rejete') NOT NULL DEFAULT 'en_attente',
  `date_maj` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kyc`),
  UNIQUE KEY `uk_utilisateur` (`id_utilisateur_application`),
  KEY `idx_statut` (`statut`),
  KEY `idx_date_validation` (`date_validation_kyc`),
  CONSTRAINT `fk_kyc_utilisateur` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='KYC utilisateur - données chiffrées - validation RIPA';

SET FOREIGN_KEY_CHECKS = 1;
