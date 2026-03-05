-- =============================================================================
-- RIPA - Phase 1 : Table dédiée comptes mobile money (application)
-- Table à part entière (distincte de compte_financier_utilisateur_application).
-- Numéro de compte stocké chiffré (custom_helper encrypt_ripa).
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `compte_mobile_money_utilisateur_application` (
  `id_compte_mobile_money` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NOT NULL,
  `num_compte_c` TEXT NOT NULL COMMENT 'Numéro mobile money chiffré (encrypt_ripa)',
  `id_type_mobile_money` INT(11) NOT NULL COMMENT 'FK type_mobile_money (Airtel, Orange, M-Pesa, etc.)',
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_maj` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_compte_mobile_money`),
  KEY `idx_utilisateur` (`id_utilisateur_application`),
  KEY `idx_type` (`id_type_mobile_money`),
  CONSTRAINT `fk_compte_mm_utilisateur` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE,
  CONSTRAINT `fk_compte_mm_type` FOREIGN KEY (`id_type_mobile_money`)
    REFERENCES `type_mobile_money` (`id_type_mobile_money`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Comptes mobile money app - numéro chiffré - table dédiée';

SET FOREIGN_KEY_CHECKS = 1;
