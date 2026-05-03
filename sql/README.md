# Dossier SQL – RIPA

Ce dossier contient les schémas et migrations SQL pour la base `ripaweb`.

## Ordre d’exécution

- **01_phase1_utilisateur_logs_et_cartes.sql** : extension utilisateur_application (colonnes chiffrées), table `log_utilisateur_application`, table `carte_utilisateur_application` (token + last4).
- **02_phase1_compte_mobile_money.sql** : table dédiée `compte_mobile_money_utilisateur_application` (numéro chiffré).
- **13_payee_qr_destination_preference.sql** : colonne `payee_qr_destination_c` (préférence moyen de réception QR, chiffrée) sur `utilisateur_application`.
- **14_business_portail_marchand.sql** : portail B2B — `business_marchand`, `utilisateur_business`, `business_service`, `business_moyen_lien` (voir `docs/SPECS-PORTAIL-MARCHAND-B2B.md`).
- **15_fonctionnalite_business_marchand_backoffice.sql** : fonctionnalité back-office `business_marchand` (validation des comptes marchands) + permission rôle admin.
- **16_business_employe_transaction.sql** : si vous aviez déjà exécuté une version ancienne de `14_` **sans** `business_employe` et `business_transaction`, exécutez ce script (CREATE IF NOT EXISTS + FK). Sinon le fichier `14_` à jour suffit.
- Les scripts suivants seront numérotés 17_, …

## Conventions

- Colonnes **chiffrées** (algo custom dans `custom_helper.php`) : suffixe `_c` (ex. `nom_c`, `tel_c`). Déchiffrement à la lecture pour affichage dans l’app et le backoffice.
- **Mot de passe** : toujours **hash** (bcrypt), jamais chiffré réversible.
- **Données carte** : aucune colonne PAN ni CVV. Uniquement `token_vault` + `last_four` (et métadonnées non sensibles). Voir règles d’or PCI DSS.

## Exécution

Exécuter sur la base existante après sauvegarde. Exemple :

```bash
mysql -u user -p ripaweb < sql/01_phase1_utilisateur_logs_et_cartes.sql
```

Ou via phpMyAdmin : copier le contenu du fichier et exécuter.
