-- =============================================================================
-- RIPA - Fonctionnalité backoffice : validation des comptes marchands
-- Après exécution : attribuer la permission aux rôles concernés (ex. admin id_role=1).
-- =============================================================================

INSERT INTO `fonctionnalite` (`id_fonctionnalite`, `short_code`, `designation`, `id_group_fonctionnalite`, `is_active`, `system`, `date_heure`)
VALUES (111, 'business_marchand', 'Valider les comptes marchands (portail B2B)', 1, 1, 1, NOW())
ON DUPLICATE KEY UPDATE `short_code` = 'business_marchand', `designation` = 'Valider les comptes marchands (portail B2B)';

INSERT INTO `role_permission` (`id_role`, `id_fonctionnalite`, `peux_voir`, `peux_ajouter`, `peux_editer`, `peux_supprimer`, `date_heure`)
SELECT 1, 111, 1, 0, 1, 0, NOW()
FROM (SELECT 1) AS _t
WHERE NOT EXISTS (SELECT 1 FROM `role_permission` WHERE `id_role` = 1 AND `id_fonctionnalite` = 111);
