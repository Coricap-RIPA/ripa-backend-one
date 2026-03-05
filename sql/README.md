# Dossier SQL – RIPA

Ce dossier contient les schémas et migrations SQL pour la base `ripaweb`.

## Ordre d’exécution

- **01_phase1_utilisateur_logs_et_cartes.sql** : extension utilisateur_application (colonnes chiffrées), table `log_utilisateur_application`, table `carte_utilisateur_application` (token + last4).
- **02_phase1_compte_mobile_money.sql** : table dédiée `compte_mobile_money_utilisateur_application` (numéro chiffré).
- Les scripts suivants (phase 2, etc.) seront numérotés 03_, 04_, …

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
