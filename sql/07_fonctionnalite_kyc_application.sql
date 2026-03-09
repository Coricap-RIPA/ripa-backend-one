-- =============================================================================
-- RIPA - Fonctionnalité backoffice KYC Application (permissions)
-- À exécuter une fois pour activer check_privilege sur Kyc_backoffice (voir / editer / supprimer).
-- Ensuite, gérer les droits par rôle via Role > Permissions (id_fonctionnalite = 110).
-- =============================================================================

-- Insérer la fonctionnalité (short_code utilisé par le contrôleur Kyc_backoffice)
INSERT INTO `fonctionnalite` (`id_fonctionnalite`, `short_code`, `designation`, `id_group_fonctionnalite`, `is_active`, `system`, `date_heure`)
VALUES (110, 'kyc_application', 'Gérer les dossiers KYC application', 1, 1, 1, NOW())
ON DUPLICATE KEY UPDATE `short_code` = 'kyc_application', `designation` = 'Gérer les dossiers KYC application';

-- Attribuer les permissions au rôle admin (id_role = 1) : voir, editer, supprimer
INSERT INTO `role_permission` (`id_role`, `id_fonctionnalite`, `peux_voir`, `peux_ajouter`, `peux_editer`, `peux_supprimer`, `date_heure`)
SELECT 1, 110, 1, 0, 1, 1, NOW()
FROM (SELECT 1) AS _t
WHERE NOT EXISTS (SELECT 1 FROM `role_permission` WHERE `id_role` = 1 AND `id_fonctionnalite` = 110);
