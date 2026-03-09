-- =============================================================================
-- RIPA - Notifications utilisateur application (pour KYC validé/rejeté/supprimé, etc.)
-- L'app mobile récupère les notifications via API et les affiche.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `notification_utilisateur_application` (
  `id_notification` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NOT NULL,
  `type` VARCHAR(64) NOT NULL COMMENT 'kyc_valide, kyc_rejete, kyc_supprime, etc.',
  `titre` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `lu` TINYINT(1) NOT NULL DEFAULT 0,
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notification`),
  KEY `idx_user_lu` (`id_utilisateur_application`, `lu`),
  KEY `idx_date` (`date_creation`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Notifications pour l\'app mobile (KYC, etc.)';

SET FOREIGN_KEY_CHECKS = 1;
