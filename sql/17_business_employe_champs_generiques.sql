-- =============================================================================
-- RIPA — Portail B2B : champs génériques sur business_employe (import Excel, RH)
-- Exécuter une seule fois. Si une colonne existe déjà, retirer la ligne correspondante.
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `business_employe`
  ADD COLUMN `matricule` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Réf. interne entreprise (badge, matricule)' AFTER `poste`,
  ADD COLUMN `departement` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Service / direction / unité' AFTER `matricule`,
  ADD COLUMN `date_entree` DATE NULL DEFAULT NULL COMMENT 'Date d''entrée (embauche / début contrat)' AFTER `departement`,
  ADD COLUMN `ville` VARCHAR(120) NULL DEFAULT NULL AFTER `date_entree`,
  ADD COLUMN `pays` VARCHAR(3) NULL DEFAULT NULL COMMENT 'Code pays ISO alpha-2 recommandé (ex. CD)' AFTER `ville`;
