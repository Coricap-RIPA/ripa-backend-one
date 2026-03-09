-- =============================================================================
-- RIPA - Solde carte virtuelle (simulation Onafriq)
-- Colonne chiffrée pour le solde des cartes virtuelles (recharge / retrait).
-- =============================================================================

SET NAMES utf8mb4;

-- Exécuter une seule fois. Si la colonne existe déjà, ignorer l'erreur.
ALTER TABLE `carte_utilisateur_application`
  ADD COLUMN `solde_c` TEXT NULL DEFAULT NULL
  COMMENT 'Solde carte virtuelle chiffré (encrypt_ripa), NULL pour carte physique'
  AFTER `brand`;

-- Optionnel : initialiser les cartes virtuelles existantes à 0
-- UPDATE carte_utilisateur_application SET solde_c = ... WHERE type_carte = 'virtuelle' AND solde_c IS NULL;
-- (à faire côté app avec encrypt_ripa('0') si besoin)
