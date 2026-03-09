# Backoffice RIPA — Gestion KYC (MVC)

Module permettant au backoffice de **voir**, **valider**, **rejeter** ou **supprimer** les dossiers KYC soumis par les utilisateurs de l'application mobile.

## Accès

- **URL** : `http://[votre-domaine]/index.php/Kyc_backoffice` (ou via réécriture d’URL).
- **Menu** : CONFIGURATIONS → **KYC APPLICATION** (dans la barre de navigation du backoffice).
- **Authentification** : réservé aux utilisateurs connectés au backoffice (`session logged_in`).

## Structure MVC

| Couche   | Fichier / Emplacement |
|----------|------------------------|
| Modèle   | `application/models/Kyc_model.php` (méthodes `get_all_for_backoffice`, `get_by_id_for_backoffice`, `set_statut`, `delete_kyc`) |
| Contrôleur | `application/controllers/Kyc_backoffice.php` |
| Vues     | `application/views/kyc_backoffice/list.php`, `detail.php` |

## Fonctionnalités

1. **Liste** (`Kyc_backoffice/index`)
   - Liste tous les dossiers KYC avec téléphone (app), statut, dates.
   - Filtres : Tous | En attente | Validés | Rejetés.

2. **Détail** (`Kyc_backoffice/detail/{id_kyc}`)
   - Dossier complet avec données **déchiffrées** (nom, post-nom, prénom, date de naissance, adresse).
   - Affichage des **photos** (pièce d’identité + selfie) pour vérification.
   - Boutons d’action : **Valider**, **Rejeter**, **Supprimer**.

3. **Valider** (POST `Kyc_backoffice/valider/{id}`)
   - Passe le statut à `valide`, enregistre la date de validation et la date de prochaine révision (2 ans).

4. **Rejeter** (POST `Kyc_backoffice/rejeter/{id}`)
   - Passe le statut à `rejete`.

5. **Supprimer** (POST `Kyc_backoffice/supprimer/{id}`)
   - Supprime définitivement le dossier (avec confirmation côté interface).

## Sécurité

- Données KYC déchiffrées uniquement dans le contrôleur (helper `decrypt_ripa`).
- Les actions Valider / Rejeter / Supprimer sont en **POST** pour éviter les déclenchements par simple lien.
- La suppression demande une confirmation JavaScript avant envoi.

## Dépendances

- Table `kyc_utilisateur_application` (migration `sql/03_kyc_utilisateur_application.sql`).
- Helper `custom_helper` (fonction `decrypt_ripa`).
- Modèles `Kyc_model`, `User_model` (téléphone de l’utilisateur app).
