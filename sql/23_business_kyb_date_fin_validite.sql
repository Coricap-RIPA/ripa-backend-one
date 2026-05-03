-- =============================================================================
-- RIPA — KYB : date de fin de validité (cycle 1 an après approbation)
-- Exécuter après sql/19_business_kyb.sql
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `business_kyb_dossier`
  ADD COLUMN `date_fin_validite` DATETIME NULL DEFAULT NULL
    COMMENT 'Fin de validité du dossier approuvé (ex. +1 an à la validation)'
    AFTER `date_decision`;

-- Rétrocompatibilité : dossiers déjà validés
UPDATE `business_kyb_dossier`
SET `date_fin_validite` = DATE_ADD(`date_decision`, INTERVAL 1 YEAR)
WHERE `statut` = 'valide'
  AND `date_decision` IS NOT NULL
  AND `date_fin_validite` IS NULL;
