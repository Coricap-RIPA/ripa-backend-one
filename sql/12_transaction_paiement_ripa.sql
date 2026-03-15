-- =============================================================================
-- RIPA - Transactions paiement C2C / Scan & Pay (Phase 1 B2C)
-- Source : carte_virtuelle | carte_physique | mobile_money | compte_bancaire
-- Destination : carte_virtuelle | mobile_money | compte_bancaire (pas de crédit carte physique)
-- Montants et références chiffrés (encrypt_ripa). Conformité PCI DSS.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `transaction_paiement_ripa` (
  `id_paiement` INT(11) NOT NULL AUTO_INCREMENT,
  `id_emetteur` INT(11) NOT NULL COMMENT 'Utilisateur qui paie',
  `type_source` ENUM('carte_virtuelle','carte_physique','mobile_money','compte_bancaire') NOT NULL,
  `id_source` INT(11) NOT NULL COMMENT 'id_carte ou id_compte_mobile_money ou id_compte_bancaire',
  `id_destinataire` INT(11) NOT NULL COMMENT 'Utilisateur qui reçoit',
  `type_destination` ENUM('carte_virtuelle','mobile_money','compte_bancaire') NOT NULL,
  `id_destination` INT(11) NOT NULL COMMENT 'id_carte ou id_compte_mm ou id_compte_bancaire du destinataire',
  `montant_c` TEXT NOT NULL COMMENT 'Montant chiffré (encrypt_ripa)',
  `reference_c` TEXT NULL DEFAULT NULL COMMENT 'Référence transaction chiffrée',
  `libelle_c` TEXT NULL DEFAULT NULL COMMENT 'Libellé optionnel chiffré',
  `statut` ENUM('en_attente','traite','echoue','annule') NOT NULL DEFAULT 'en_attente',
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_maj` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paiement`),
  KEY `idx_emetteur` (`id_emetteur`),
  KEY `idx_destinataire` (`id_destinataire`),
  KEY `idx_date` (`date_creation`),
  KEY `idx_statut` (`statut`),
  CONSTRAINT `fk_tx_paiement_emetteur` FOREIGN KEY (`id_emetteur`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE,
  CONSTRAINT `fk_tx_paiement_destinataire` FOREIGN KEY (`id_destinataire`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Paiements C2C / Scan & Pay - montants chiffrés - PCI DSS';

SET FOREIGN_KEY_CHECKS = 1;
