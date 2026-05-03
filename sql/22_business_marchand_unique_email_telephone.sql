-- =============================================================================
-- RIPA — business_marchand : unicité email_contact + telephone_contact
-- Exécuter une seule fois (après nettoyage des doublons existants).
-- =============================================================================

SET NAMES utf8mb4;

-- Important:
-- 1) Vérifier/supprimer les doublons avant ajout d'index uniques.
-- 2) telephone_contact doit être non NULL pour garantir l’unicité complète.

UPDATE `business_marchand`
SET `telephone_contact` = ''
WHERE `telephone_contact` IS NULL;

ALTER TABLE `business_marchand`
  MODIFY COLUMN `telephone_contact` VARCHAR(50) NOT NULL;

ALTER TABLE `business_marchand`
  ADD UNIQUE KEY `uk_bm_email_contact` (`email_contact`),
  ADD UNIQUE KEY `uk_bm_telephone_contact` (`telephone_contact`);

