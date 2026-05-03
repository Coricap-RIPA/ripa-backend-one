-- =============================================================================
-- RIPA — business_service : description optionnelle (services internes marchand)
-- Exécuter une seule fois. Si la colonne existe déjà, ignorer l'erreur.
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `business_service`
  ADD COLUMN `description` TEXT NULL DEFAULT NULL COMMENT 'Détail / périmètre du service' AFTER `libelle`;
