-- =============================================================================
-- RIPA - Phase 1 : Utilisateur application (étendu), logs app, cartes (token only)
-- Base : ripaweb (tables existantes utilisateur_application, compte_financier_utilisateur_application, etc.)
-- Conformité : PCI DSS (pas de PAN/CVV en base ; token + last4 uniquement)
-- Chiffrement : colonnes _c stockent du texte chiffré (custom_helper encrypt_ripa)
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. Extension table utilisateur_application (nom, post_nom, prenom, tel, email)
--    Données personnelles stockées chiffrées. Mot de passe = hash bcrypt (inchangé).
--    phone_hash permet le login sans déchiffrer (SHA256 du téléphone normalisé).
-- -----------------------------------------------------------------------------

ALTER TABLE `utilisateur_application`
  ADD COLUMN `nom_c` TEXT NULL COMMENT 'Nom chiffré (encrypt_ripa)' AFTER `nom_complet`,
  ADD COLUMN `post_nom_c` TEXT NULL COMMENT 'Post-nom chiffré' AFTER `nom_c`,
  ADD COLUMN `prenom_c` TEXT NULL COMMENT 'Prénom chiffré' AFTER `post_nom_c`,
  ADD COLUMN `tel_c` TEXT NULL COMMENT 'Téléphone chiffré (affichage)' AFTER `phone`,
  ADD COLUMN `phone_hash` VARCHAR(64) NULL COMMENT 'SHA256(phone) pour login et unicité' AFTER `tel_c`,
  ADD COLUMN `email_c` TEXT NULL COMMENT 'Email chiffré' AFTER `phone_hash`;

-- Index pour login par phone_hash (unicité)
ALTER TABLE `utilisateur_application`
  ADD UNIQUE KEY `uk_phone_hash` (`phone_hash`);

-- Note : pour les nouveaux inscrits, remplir phone_hash = SHA256(normalise(phone)) et tel_c = encrypt_ripa(phone).
-- La colonne phone existante peut rester pour rétrocompatibilité ou être migrée progressivement.
-- Mot de passe = PIN à 5 chiffres, stocké hashé (bcrypt) dans mot_passe_pin (inchangé).

-- -----------------------------------------------------------------------------
-- 2. Table log_utilisateur_application (PCI DSS - traçabilité des actions app)
--    Ne jamais y écrire PAN, CVV, token complet, mot de passe. Utiliser sanitize_for_log().
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `log_utilisateur_application` (
  `id_log` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NULL DEFAULT NULL COMMENT 'NULL si action avant login (ex. inscription)',
  `action` VARCHAR(100) NOT NULL COMMENT 'ex: inscription, connexion, ajout_compte_money, enregistrement_carte',
  `ressource` VARCHAR(80) NULL DEFAULT NULL COMMENT 'ex: utilisateur_application, compte_financier_utilisateur_application',
  `id_ressource` INT(11) NULL DEFAULT NULL,
  `details` TEXT NULL COMMENT 'JSON ou texte sanitized (jamais PAN/CVV/token/mot de passe)',
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_utilisateur` (`id_utilisateur_application`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Logs des actions app - PCI DSS - aucune donnée carte sensible';

-- -----------------------------------------------------------------------------
-- 3. Table carte_utilisateur_application (token + last4 uniquement, pas de PAN/CVV)
--    Données sensibles carte chez le Vault ; ici uniquement token + last4 + métadonnées.
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `carte_utilisateur_application` (
  `id_carte` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur_application` INT(11) NOT NULL,
  `token_vault` VARCHAR(255) NOT NULL COMMENT 'Token retourné par le prestataire Vault (PCI)',
  `last_four` CHAR(4) NOT NULL COMMENT '4 derniers chiffres du PAN (affichage ****1234)',
  `date_expiration` CHAR(7) NULL DEFAULT NULL COMMENT 'MM/YYYY si nécessaire métier',
  `type_carte` ENUM('virtuelle','physique') NOT NULL DEFAULT 'virtuelle',
  `brand` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Visa, Mastercard (détection BIN ou Vault)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_maj` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_carte`),
  KEY `idx_utilisateur` (`id_utilisateur_application`),
  KEY `idx_token_vault` (`token_vault`),
  CONSTRAINT `fk_carte_utilisateur` FOREIGN KEY (`id_utilisateur_application`)
    REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Cartes utilisateur - PCI DSS - token + last4 uniquement, pas de PAN/CVV';

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------------------------------
-- Fin 01_phase1_utilisateur_logs_et_cartes.sql
-- -----------------------------------------------------------------------------
