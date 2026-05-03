-- =============================================================================
-- RIPA — business_employe : rattachement optionnel à un service (business_service)
-- Exécuter une seule fois après 17_business_employe_champs_generiques.sql
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `business_employe`
  ADD COLUMN `id_service` INT(11) NULL DEFAULT NULL COMMENT 'Service interne (FK business_service)' AFTER `departement`,
  ADD KEY `idx_be_service` (`id_service`),
  ADD CONSTRAINT `fk_be_service` FOREIGN KEY (`id_service`)
    REFERENCES `business_service` (`id`) ON DELETE SET NULL;
