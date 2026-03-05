# Phase 1 – Plan de codage (validation avant codage)

Plan détaillé pour la phase 1. Une fois validé, on enchaîne le code dans cet ordre.

---

## Rappel des choix validés

- **OTP** : API SMS à brancher plus tard ; on code tous les endpoints (envoi / vérification en mock).
- **Connexion** : après inscription + OTP réussi → **connexion automatique** (retour du token, pas d’écran login). Token JWT **expiration 1 an** à partir de la date de connexion.
- **Identifiant** : **téléphone avec indicatif pays** (ex. +243…), **PIN à 5 chiffres** (pas un mot de passe classique). Connexion = téléphone + PIN. « Mot de passe / PIN oublié » plus tard.
- **Compte mobile money** : **nouvelle table** dédiée (`compte_mobile_money_utilisateur_application`), pas la table `compte_financier_utilisateur_application`.
- **Carte** : schéma + écrans prêts ; **Vault mocké** (retour token + last4 sans appel réel).

---

## Ordre de réalisation

### Étape 1 – Base de données et helpers (backend)

| # | Tâche | Détail |
|---|--------|--------|
| 1.1 | **SQL** | Exécuter `sql/01_phase1_utilisateur_logs_et_cartes.sql` (colonnes chiffrées user, log, table carte). |
| 1.2 | **SQL** | Exécuter `sql/02_phase1_compte_mobile_money.sql` (nouvelle table comptes mobile money). |
| 1.3 | **custom_helper.php** | Ajouter `encrypt_ripa($plaintext)` et `decrypt_ripa($ciphertext)` (algo bidirectionnel, clé depuis config/env). |
| 1.4 | **custom_helper.php** | Ajouter `sanitize_for_log($data)` (retire/masque PAN, CVV, token, mot de passe/PIN avant écriture en log). |
| 1.5 | **custom_helper.php** | Ajouter `detect_card_type($pan_or_bin)` (Visa, Mastercard, etc. selon BIN). |
| 1.6 | **custom_helper.php** | Ajouter `detect_mobile_money_type($phone)` (Airtel, Orange, M-Pesa, etc. selon préfixe) — ou réutiliser la logique existante dans Auth. |

---

### Étape 2 – API Backend (endpoints Phase 1)

| # | Tâche | Détail |
|---|--------|--------|
| 2.1 | **Auth – Inscription** | Adapter `register` : champs **nom, post_nom, prenom, tel (avec +), email**, **PIN 5 chiffres**. Chiffrer nom, post_nom, prenom, tel, email avant insertion. Stocker **phone_hash** (SHA256) pour login/unicité. Ne pas créer de compte mobile money ici. Générer OTP (mock envoi SMS), retourner `user_id` + message (et en dev optionnel `otp_code`). **Logger** dans `log_utilisateur_application` (action `inscription`, sanitize_for_log). |
| 2.2 | **Auth – Vérification OTP** | Adapter `verify_otp` : vérifier code, marquer utilisé. **Ne plus** créer de compte dans `compte_financier_utilisateur_application`. Générer JWT **expiration 1 an** (au lieu de 2 ans). Retourner **token + user** (auto-login). Logger action `verification_otp` + `connexion`. |
| 2.3 | **Auth – Connexion** | Adapter `login` : body **phone** (avec +), **pin** (5 chiffres). Recherche par **phone_hash** si on migre, ou par `phone` tant que colonne conservée. Vérifier PIN (password_verify). JWT **1 an**. Retourner token + user. Logger action `connexion`. |
| 2.4 | **Auth – Resend OTP** | Garder `resend_otp` ; en dev retourner `otp_code` (à retirer en prod quand API SMS branchée). Logger si besoin. |
| 2.5 | **Auth – Verify token** | Garder `verify_token` pour que l’app vérifie la validité du token au démarrage. Adapter la réponse user si on expose nom, post_nom, prenom, email (déchiffrés). |
| 2.6 | **User_model** | Adapter : `create_user` avec colonnes nom_c, post_nom_c, prenom_c, tel_c, phone_hash, email_c ; `get_user_by_phone` → utiliser phone_hash si on cherche par hash, sinon garder phone ; méthode pour retourner user avec champs déchiffrés pour l’API. |

---

### Étape 3 – API complémentaires (comptes mobile money, cartes)

| # | Tâche | Détail |
|---|--------|--------|
| 3.1 | **Comptes mobile money** | Créer contrôleur (ou étendre existant) : **liste** des comptes de l’utilisateur (déchiffrer num pour affichage masqué ex. ***1234), **ajout** (numéro + type, chiffrer avant insertion), **définir par défaut**, **supprimer**. Utiliser table `compte_mobile_money_utilisateur_application`. Logger chaque action dans `log_utilisateur_application`. |
| 3.2 | **Types mobile money** | Endpoint **GET** liste des types (déjà table `type_mobile_money`). Réutilisable par l’app et le backoffice. |
| 3.3 | **Carte – Enregistrement** | Endpoint **POST** : reçoit PAN, CVV, date expiration. **Ne jamais** les écrire en base. Appel **mock** au Vault (simuler réponse token + last4). En base : insérer dans `carte_utilisateur_application` (token_vault, last_four, date_expiration, type_carte='physique', brand via detect_card_type). Logger action `enregistrement_carte` **sans** PAN/CVV/token complet (sanitize_for_log). |

---

### Étape 4 – Application React Native / Expo (structure et écrans)

| # | Tâche | Détail |
|---|--------|--------|
| 4.1 | **Projet** | Créer projet Expo (blank) dans un dossier dédié (ex. `ripa-mobile/`). Logo au lancement (splash). |
| 4.2 | **Architecture** | Dossiers : `src/screens`, `src/components`, `src/context`, `src/services`, `src/utils`, `src/constants`, `src/navigation`, `assets/fonts`, `assets/images`. |
| 4.3 | **Thème** | Fichier constantes couleurs + polices (charte : #270345, #A59AF7, #FFFFFF, #000000 ; Microgramma Bold Extended, Roboto). |
| 4.4 | **Stockage** | `expo-secure-store` pour le **JWT** (et données sensibles). `AsyncStorage` pour « première ouverture », préférences. Service `storage.js` (ou équivalent) pour centraliser. |
| 4.5 | **ApiContext** | Context React : état `user`, `token`, `isLoading`, `error` ; méthodes `login`, `register`, `logout`, `verifyToken`, etc. Appels API centralisés dans un service (ex. `api.js` + `auth.js`). |
| 4.6 | **Navigation** | Stack : pas de token → Welcome / Inscription / OTP. Token valide → Accueil (et plus tard autres écrans). Gestion du splash puis redirection selon présence du token. |

---

### Étape 5 – Écrans application (dans l’ordre)

| # | Tâche | Détail |
|---|--------|--------|
| 5.1 | **Loader (Splash)** | Écran type WhatsApp : logo RIPA, fond (couleur primaire ou secondaire). Après 2–3 s : si pas de token → Welcome ; si token valide → Accueil. |
| 5.2 | **Welcome** | Texte de bienvenue sur l’app. CTA « Démarrer ici » → processus d’inscription. Affiché uniquement quand première ouverture **et** pas de token (flag dans AsyncStorage après première visite si besoin). |
| 5.3 | **Inscription** | Formulaire : nom, post_nom, prenom, **téléphone (avec + et indicatif pays)**, email, **PIN 5 chiffres**. Si trop long → **2 étapes** (ex. étape 1 : identité ; étape 2 : contact + PIN). **Modale ou bandeau** pour erreurs (champs manquants, format, ou message serveur). À la soumission → appel API register → redirection vers écran OTP avec `user_id`. |
| 5.4 | **OTP** | Saisie du code reçu (mock : afficher le code en dev si backend le renvoie). Bouton « Vérifier » → appel verify_otp. Succès → **connexion automatique** (sauvegarder token + user dans SecureStore/Context) → navigation vers **Accueil**. Bouton « Renvoyer le code » → resend_otp. |
| 5.5 | **Accueil** | Design moderne type fintech. Si utilisateur **sans** compte mobile money ni carte : **CTA** « Enregistrer un compte mobile money », « Commander une carte virtuelle Visa/Mastercard », « Enregistrer une carte bancaire ». Ces CTA mènent vers les écrans correspondants (qu’on peut préparer en vrac ou au fil de l’eau). Pas de login demandé après inscription : l’utilisateur arrive déjà connecté. |

---

### Étape 6 – Écrans complémentaires (liés aux CTA de l’accueil)

| # | Tâche | Détail |
|---|--------|--------|
| 6.1 | **Enregistrer un compte mobile money** | Formulaire : numéro + choix du type (liste depuis API types). Appel API ajout compte (chiffré côté backend). Retour à l’accueil ou liste des comptes. |
| 6.2 | **Commander une carte virtuelle** | Pour cette phase : écran placeholder ou message « Bientôt disponible » + condition (avoir au moins un compte mobile money + KYC à venir). Pas d’appel Onafriq en phase 1. |
| 6.3 | **Enregistrer une carte bancaire** | Formulaire : PAN, CVV, date expiration. Envoi **uniquement** au backend (backend appelle Vault mock, stocke token + last4). Message succès + retour accueil. |

---

### Étape 7 – Logs et conformité PCI

| # | Tâche | Détail |
|---|--------|--------|
| 7.1 | **Logs** | Chaque action API (inscription, connexion, verify_otp, ajout compte mobile money, enregistrement carte) écrit une ligne dans `log_utilisateur_application` avec : id_utilisateur (si connecté), action, ressource, details (sanitize_for_log), ip, user_agent. **Jamais** PAN, CVV, token complet, PIN en clair. |
| 7.2 | **Vérification** | S’assurer qu’aucun log applicatif (fichier ou table) ne contient de données sensibles carte. |

---

## Résumé des livrables Phase 1

**Backend**

- SQL : extension utilisateur (chiffré + phone_hash), table logs, table cartes, **nouvelle table** comptes mobile money.
- Helpers : encrypt_ripa, decrypt_ripa, sanitize_for_log, detect_card_type, detect_mobile_money_type.
- API : Auth (register avec PIN 5, verify_otp sans création compte financier, login, resend_otp, verify_token) ; Comptes mobile money (liste, ajout, défaut, suppression) ; Types mobile money ; Enregistrement carte (mock Vault). JWT 1 an. Logs sur toutes les actions concernées.

**Application**

- Projet Expo, architecture dossiers, thème (charte graphique), SecureStore + AsyncStorage, ApiContext.
- Écrans : Loader → Welcome → Inscription (1 ou 2 étapes + gestion erreurs) → OTP → Accueil (CTA) ; écrans Enregistrer mobile money, Enregistrer carte (Vault mock) ; placeholder Commander carte virtuelle.

**À faire plus tard**

- API SMS réelle pour OTP.
- « PIN oublié ».
- Intégration Vault réelle (remplacer le mock).
- KYC et flux complet commande carte virtuelle.

---

## Ordre d’exécution recommandé (pour coder)

1. **SQL** (01 + 02) + **custom_helper** (encrypt, decrypt, sanitize, detect card, detect mobile money).  
2. **User_model** + **Auth** (register, verify_otp, login avec PIN 5 et JWT 1 an, pas de création compte financier dans verify_otp).  
3. **Log** : écriture dans `log_utilisateur_application` pour inscription, verify_otp, login.  
4. **API** comptes mobile money + types + enregistrement carte (mock).  
5. **App** : projet Expo, structure, thème, storage, ApiContext, navigation.  
6. **App** : écrans Loader, Welcome, Inscription, OTP, Accueil, puis Enregistrer mobile money, Enregistrer carte.

Si tu valides ce plan, on commence par l’étape 1 (SQL + custom_helper).
