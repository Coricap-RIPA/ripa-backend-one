-- =============================================================================
-- RIPA - Préférence moyen de réception pour QR (chiffrée, encrypt_ripa)
-- Valeur stockée : carte_virtuelle | mobile_money | compte_bancaire
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `utilisateur_application`
  ADD COLUMN `payee_qr_destination_c` TEXT NULL DEFAULT NULL
  COMMENT 'Préférence réception QR payee (chiffrée)'
  AFTER `mot_passe_pin`;
