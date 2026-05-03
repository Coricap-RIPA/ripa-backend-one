-- =============================================================================
-- RIPA - Comptes bancaires (application)
-- Liste, ajout, modification, suppression avec confirmation PIN.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `compte_bancaire_utilisateur_application` (
  `id_compte_bancaire` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NOT NULL,
  `nom_banque` VARCHAR(120) NOT NULL,
  `num_compte_c` TEXT NOT NULL COMMENT 'Numéro compte / IBAN chiffré (encrypt_ripa)',
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_maj` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_compte_bancaire`),
  KEY `idx_utilisateur` (`id_utilisateur_application`),
  CONSTRAINT `fk_compte_bancaire_utilisateur` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Comptes bancaires app - numéro chiffré';

SET FOREIGN_KEY_CHECKS = 1;
