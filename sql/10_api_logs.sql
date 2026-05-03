-- =============================================================================
-- RIPA - Table api_logs (traçabilité des appels API internes / externes)
-- Utilisation : Vault (tokenisation), Onafriq (recharge/décharge), futurs tiers.
-- Ne jamais y écrire : PAN, CVV, token complet, corps de requête/réponse sensibles.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `api_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `service` VARCHAR(50) NOT NULL COMMENT 'ex: vault, onafriq',
  `action` VARCHAR(80) NOT NULL COMMENT 'ex: tokenize, recharge, withdraw',
  `method` VARCHAR(20) NULL DEFAULT NULL COMMENT 'GET, POST, etc.',
  `request_id` VARCHAR(64) NULL DEFAULT NULL COMMENT 'corrélation optionnelle',
  `status_code` INT(11) NULL DEFAULT NULL COMMENT '200, 400, 500, etc.',
  `duration_ms` INT(11) NULL DEFAULT NULL COMMENT 'durée en millisecondes',
  `message` VARCHAR(255) NULL DEFAULT NULL COMMENT 'message court (pas de données sensibles)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_service` (`service`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Logs des appels API (Vault, Onafriq, etc.) - PCI DSS - pas de PAN/CVV/corps';

SET FOREIGN_KEY_CHECKS = 1;
