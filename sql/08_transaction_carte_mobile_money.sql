-- =============================================================================
-- RIPA - Transactions carte virtuelle <-> Mobile Money (simulation Onafriq)
-- Recharge : Mobile Money -> Carte virtuelle
-- Retrait  : Carte virtuelle -> Mobile Money
-- Données sensibles stockées chiffrées (encrypt_ripa).
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `transaction_carte_mobile_money_utilisateur` (
  `id_transaction_carte_mm` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NOT NULL,
  `id_carte` INT(11) NOT NULL,
  `id_compte_mobile_money` INT(11) NOT NULL,
  `type_operation` ENUM('recharge','retrait') NOT NULL COMMENT 'recharge=MM->carte, retrait=carte->MM',
  `montant_c` TEXT NOT NULL COMMENT 'Montant chiffré (encrypt_ripa)',
  `reference_onafriq_c` TEXT NULL DEFAULT NULL COMMENT 'Référence simulée Onafriq (chiffrée)',
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaction_carte_mm`),
  KEY `idx_utilisateur` (`id_utilisateur_application`),
  KEY `idx_carte` (`id_carte`),
  KEY `idx_compte_mm` (`id_compte_mobile_money`),
  KEY `idx_date` (`date_creation`),
  CONSTRAINT `fk_tx_carte_mm_utilisateur` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE,
  CONSTRAINT `fk_tx_carte_mm_carte` FOREIGN KEY (`id_carte`)
    REFERENCES `carte_utilisateur_application` (`id_carte`) ON DELETE CASCADE,
  CONSTRAINT `fk_tx_carte_mm_compte` FOREIGN KEY (`id_compte_mobile_money`)
    REFERENCES `compte_mobile_money_utilisateur_application` (`id_compte_mobile_money`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Transactions carte virtuelle <-> Mobile Money (Onafriq simulé, chiffrement RIPA)';

SET FOREIGN_KEY_CHECKS = 1;
