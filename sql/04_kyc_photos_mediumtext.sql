-- =============================================================================
-- RIPA - KYC : agrandir les colonnes photo (TEXT = 64 Ko max, base64 photo > 64 Ko)
-- À exécuter si erreur 500 à l'envoi du dossier KYC (données trop longues).
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `kyc_utilisateur_application`
  MODIFY COLUMN `photo_piece_identite_c` MEDIUMTEXT NULL COMMENT 'Photo pièce d\'identité chiffrée (encrypt_ripa)',
  MODIFY COLUMN `photo_utilisateur_c` MEDIUMTEXT NULL COMMENT 'Photo utilisateur / selfie chiffrée (encrypt_ripa)';
