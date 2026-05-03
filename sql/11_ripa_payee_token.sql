-- =============================================================================
-- RIPA - Token destinataire (QR Scan & Pay) — pas d'id utilisateur en clair
-- Table : ripa_payee_token (token court pour QR, discrétion PCI)
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `ripa_payee_token` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NOT NULL,
  `token` VARCHAR(64) NOT NULL COMMENT 'Token court unique (QR ripa://p/{token})',
  `date_expiration` DATETIME NULL DEFAULT NULL COMMENT 'NULL = pas d''expiration',
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_token` (`token`),
  KEY `idx_utilisateur` (`id_utilisateur_application`),
  KEY `idx_expiration` (`date_expiration`),
  CONSTRAINT `fk_payee_token_utilisateur` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Token payee pour QR - pas d''id en clair (discrétion)';

SET FOREIGN_KEY_CHECKS = 1;
