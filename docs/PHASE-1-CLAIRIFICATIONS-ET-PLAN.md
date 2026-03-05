# Phase 1 – Clarifications et plan avant codage

Document de référence pour la première phase (loader, welcome, inscription, OTP, accueil + backend/schema). À valider avant de coder.

---

## 1. Ce qui est bien compris

- **App** : React Native + Expo (projet blank), logo au lancement, architecture claire, ApiContext pour l’état global.
- **Utilisateur** : nom, post_nom, prenom, tel, email, mot_de_passe — stockés de façon sécurisée en base (voir §2 et §3).
- **Écrans** : Loader type WhatsApp → Welcome (première fois, pas de token) → Inscription (en 2 étapes si long) → OTP (validation téléphone) → Accueil (CTA : mobile money, commander carte virtuelle, enregistrer carte physique).
- **Backend** : Chiffrement bidirectionnel pour les **données personnelles** (nom, post_nom, prenom, tel, email) dans `custom_helper.php` ; logs des actions app dans `log_utilisateur_application` (PCI DSS) ; fonctions pour détecter type de carte et type de compte mobile money (app + backoffice).
- **SQL** : Schémas dans un dossier `sql/` (migrations / créations de tables).

---

## 2. Point critique PCI DSS : données carte vs chiffrement custom

**Règle d’or rappel** : on ne stocke **jamais** PAN ni CVV chez nous. Uniquement **token** (Vault) + **4 derniers chiffres** (last4).

- **Chiffrement custom en base** : on l’utilise pour les **données personnelles** (nom, post_nom, prenom, tel, email). Pas pour les données carte.
- **Carte physique (enregistrement)** :  
  - L’utilisateur saisit PAN, CVV, date d’expiration dans l’app.  
  - L’app envoie ces données au **backend** qui les transmet **au Vault** (sans les écrire en base).  
  - Le Vault renvoie un **token**.  
  - On stocke en base : **token**, **last4**, **date_expiration** (optionnel), **id_utilisateur**, etc.  
  - **Aucun** PAN ni CVV en base, même chiffré. Le chiffrement custom ne s’applique pas au PAN/CVV.

**Mot de passe** : on garde un **hash** (bcrypt/argon2), pas un chiffrement réversible. Seuls nom, post_nom, prenom, tel, email sont chiffrés/déchiffrés avec l’algo custom.

---

## 3. Stockage côté application (au lieu de SharedPreferences)

Recommandation pour un projet moderne et sécurisé :

- **JWT et données sensibles** (token, refresh token, etc.) : **`expo-secure-store`**.  
  - Stockage chiffré par le système (Keychain / Keystore).  
  - Idéal pour tout ce qui sert à l’authentification.

- **Données non sensibles** (première ouverture, préférences, “hasSeenWelcome”) : **`AsyncStorage`**.  
  - Simple, persistant, pour tout le reste.

En résumé : **SecureStore pour le JWT (et secrets)**, **AsyncStorage pour le reste**. Pas de SharedPreferences (Android) / équivalent brut ; Expo recommande SecureStore + AsyncStorage.

---

## 4. Chiffrement custom (backend) – périmètre

- **Chiffré en base avec l’algo custom** : `nom`, `post_nom`, `prenom`, `tel`, `email` (colonnes dédiées ou concaténées selon le schéma retenu).  
- **Non chiffré avec cet algo** :  
  - Mot de passe → **hash** (bcrypt).  
  - Données carte → **jamais stockées** (sauf token + last4 en clair ou selon politique, jamais PAN/CVV).

Clé de chiffrement : à mettre dans les **variables d’environnement** ou le **secret manager** (jamais en dur dans le code), lue dans `custom_helper.php` (ou config).

---

## 5. Table de logs `log_utilisateur_application` (PCI DSS)

- Toutes les actions de l’app qui écrivent ou modifient des données (inscription, mise à jour profil, ajout compte mobile money, enregistrement carte, etc.) sont **loguées** dans `log_utilisateur_application`.
- **À ne jamais logger** : PAN, CVV, token complet, mot de passe. On peut logger : `id_utilisateur_application`, `action` (ex. `inscription`, `ajout_compte_mobile_money`), `ressource` (ex. `utilisateur`, `compte_financier`), `date_heure`, éventuellement `ip`, `user_agent`. Idéalement une fonction **sanitize_for_log()** pour nettoyer les données avant écriture dans les logs.

---

## 6. Schéma utilisateur et tables

- **Utilisateur app** : on part de la table existante `utilisateur_application` et on l’étend pour la phase 1 :  
  - Garder : `id_utilisateur_application`, `phone` (pour login/unicité ; peut être chiffré si on gère la recherche autrement, voir §7).  
  - Ajouter (stockage **chiffré** sauf mention contraire) : `nom`, `post_nom`, `prenom`, `email`.  
  - Mot de passe : colonne type `mot_passe` (ou garder `mot_passe_pin`) avec **hash** bcrypt uniquement.  
  - `date_enregistrement`, etc. inchangés.  
- **Recherche par téléphone** : si `tel` est chiffré, la recherche “par numéro” pour login doit être gérée soit par une colonne **hash du téléphone** (pour unicité + lookup), soit par déchiffrement côté app (non recommandé). Recommandation : une colonne **hash du téléphone** (ex. SHA256) pour l’unicité et le login, et une colonne **tel chiffrée** pour l’affichage après déchiffrement.

Détail des colonnes et types dans le fichier SQL (voir dossier `sql/`).

---

## 7. Questions à trancher avant de coder

1. **OTP** : l’envoi et la vérification OTP sont-ils déjà gérés par le backend (endpoints existants) ou faut-il les créer dans cette phase ? Si à créer : envoi par SMS via quel prestataire (Twilio, autre) ?
2. **Téléphone pour login** : on valide qu’on utilise bien le **téléphone** (avec indicatif pays) comme identifiant de connexion, et qu’on garde une contrainte d’unicité sur le numéro (via hash si chiffré).
3. **Compte “mobile money”** : la table `compte_financier_utilisateur_application` existe déjà (`num_compte_financier`, `id_foreign_type_mobile_money`, etc.). On confirme qu’on réutilise cette table et qu’on chiffre éventuellement `num_compte_financier` avec l’algo custom ? (Oui recommandé pour cohérence.)
4. **Carte (virtuelle / physique)** : pour cette phase on prépare le **schéma** et les **écrans** (enregistrement carte physique = formulaire PAN/CVV/expiry → envoi au backend → appel Vault → stockage token + last4). L’intégration réelle au Vault peut être mockée dans un premier temps si le contrat Vault n’est pas encore disponible.

---

## 8. Architecture dossier application (React Native + Expo)

Proposition pour un projet blank, lisible et maintenable :

```
ripa-mobile/                 (ou mobile/ à la racine du repo)
├── App.js
├── app.json
├── package.json
├── assets/
│   ├── fonts/               (Microgramma, Roboto si custom)
│   ├── images/
│   │   └── logo.png          (logo au lancement)
│   └── ...
├── src/
│   ├── components/           (boutons, champs, modales, cartes)
│   ├── screens/              (Loader, Welcome, Inscription, OTP, Accueil)
│   ├── context/              (ApiContext : user, token, login, logout, etc.)
│   ├── services/             (api.js, auth.js, storage.js avec SecureStore/AsyncStorage)
│   ├── utils/                (validation, formatage, constantes)
│   ├── constants/            (couleurs, thème depuis CHARTE-GRAPHIQUE-APP)
│   └── navigation/           (stack pour non-connecté vs connecté)
```

- **ApiContext** : fournit token, user, méthodes login/logout/register, état “loading” et “error”, pour que les écrans restent simples.
- Code pro mais **clair** : noms explicites, petits composants, commentaires courts sur la “pourquoi” quand ce n’est pas évident.

---

## 9. Ordre de réalisation proposé (phase 1)

1. **Backend / SQL**  
   - Dossier `sql/` : schéma `utilisateur_application` (étendu), `log_utilisateur_application`, tables nécessaires pour comptes mobile money et cartes (token + last4).  
   - `custom_helper.php` : fonctions `encrypt_ripa()`, `decrypt_ripa()`, `sanitize_for_log()`, détection type carte (BIN), type mobile money (préfixe / liste).  
   - Endpoints API : inscription, login, OTP (envoi + vérification si à faire), et préparation endpoint “enregistrer carte” (appel Vault mock ou réel).

2. **App**  
   - Projet Expo blank, architecture dossiers ci-dessus, thème (charte graphique).  
   - Loader (splash) avec logo.  
   - Welcome + navigation (pas de token → Welcome, sinon → Accueil ou flow connecté).  
   - Inscription (formulaire en 1 ou 2 étapes, modale/design erreurs).  
   - OTP.  
   - Accueil avec CTA (mobile money, carte virtuelle, carte physique).

3. **Logs**  
   - Chaque action d’enregistrement/édition côté app qui touche la base est loguée dans `log_utilisateur_application` avec sanitize_for_log (aucune donnée carte sensible).

Dès que les points du §7 sont tranchés (OTP, téléphone, chiffrement num_compte, mock Vault), on peut détailler écran par écran et commencer le code. Si tu valides ce plan et les réponses aux questions, on enchaîne sur la base de ce document.
