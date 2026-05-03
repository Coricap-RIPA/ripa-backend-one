-- =============================================================================
-- RIPA — Référence postes / fonctions (liste déroulante RH) + lien employé → app
-- Exécuter une seule fois après sql/20_business_employe_id_service.sql
-- =============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `ref_poste_fonction` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(64) NOT NULL COMMENT 'Identifiant stable (seed / intégrations)',
  `libelle` VARCHAR(128) NOT NULL,
  `ordre` INT(11) NOT NULL DEFAULT 0,
  `is_autre` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = saisie libre obligatoire (précision)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ref_poste_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ref_poste_fonction` (`code`, `libelle`, `ordre`, `is_autre`) VALUES
  ('direction', 'Direction / dirigeant(e)', 10, 0),
  ('directeur_operations', 'Directeur(trice) des opérations', 20, 0),
  ('directeur_commercial', 'Directeur(trice) commercial(e)', 30, 0),
  ('directeur_financier', 'Directeur(trice) financier(ère) / contrôle de gestion', 40, 0),
  ('responsable_rh', 'Responsable RH', 50, 0),
  ('comptable', 'Comptable', 60, 0),
  ('assistant_admin', 'Assistant(e) administratif(ve)', 70, 0),
  ('commercial', 'Commercial / vente', 80, 0),
  ('technicien', 'Technicien(ne) / ingénieur(e)', 90, 0),
  ('magasinier', 'Magasinier(ère) / logistique', 100, 0),
  ('marketing', 'Marketing / communication', 110, 0),
  ('juriste', 'Juriste / conformité', 120, 0),
  ('assistant_direction', 'Assistant(e) de direction', 130, 0),
  ('operations', 'Opérations / production', 140, 0),
  ('chauffeur', 'Chauffeur / livreur', 150, 0),
  ('stagiaire', 'Stagiaire / alternant(e)', 160, 0),
  ('consultant', 'Consultant(e) / prestataire interne', 170, 0),
  ('support_it', 'Support / IT', 180, 0),
  ('securite', 'Sécurité / gardien(ne)', 190, 0),
  ('autre', 'Autre (préciser)', 999, 1)
ON DUPLICATE KEY UPDATE `libelle` = VALUES(`libelle`), `ordre` = VALUES(`ordre`), `is_autre` = VALUES(`is_autre`);

ALTER TABLE `business_employe`
  ADD COLUMN `id_ref_poste_fonction` INT(11) NULL DEFAULT NULL COMMENT 'FK ref_poste_fonction' AFTER `poste`,
  ADD COLUMN `id_utilisateur_application` INT(11) NULL DEFAULT NULL COMMENT 'Utilisateur app RIPA (rapproch. téléphone)' AFTER `id_utilisateur_business`;

ALTER TABLE `business_employe`
  ADD KEY `idx_be_ref_poste` (`id_ref_poste_fonction`),
  ADD KEY `idx_be_utilisateur_application` (`id_utilisateur_application`);

ALTER TABLE `business_employe`
  ADD CONSTRAINT `fk_be_ref_poste_fonction` FOREIGN KEY (`id_ref_poste_fonction`)
    REFERENCES `ref_poste_fonction` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_be_utilisateur_application` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE SET NULL;
