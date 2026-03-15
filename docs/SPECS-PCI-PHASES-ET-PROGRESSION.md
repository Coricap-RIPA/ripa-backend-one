# RIPA — Spécifications PCI DSS, phases de codage et progression

**Document unique de référence.**  
**À consulter avant chaque session de codage** : vérifier la section [Progression actuelle](#6-progression-actuelle) et le [focus du moment](#7-focus-du-moment). **Signaler tout écart** par rapport à ce fichier (scope creep, oubli PCI, phase non respectée).

---

## 1. Spécifications PCI DSS (à respecter dans tout le code)

### 1.1 Données carte — règle d’or

| Règle | Ce qu’on fait | Ce qu’on ne fait jamais |
|-------|----------------|--------------------------|
| **PAN** | Jamais stocké. Envoyé au Vault pour tokenisation uniquement, puis oublié. | Aucune colonne `pan`, `numero_carte`. Pas en log, pas en session, pas en cache. |
| **CVV** | Envoyé une fois au prestataire si besoin, jamais stocké. | Jamais en base, ni en log, ni en réponse API. |
| **Stockage carte** | Uniquement : `token_vault`, `last_four` (last4), `brand`, `date_expiration` (optionnel), `id_utilisateur`. | Jamais PAN ni CVV, même chiffrés. |
| **Affichage** | Uniquement last4 (ex. `**** 1234`). | Jamais les 16 chiffres ni le CVV. |

### 1.2 Chiffrement et transmission

- **TLS** : Toutes les communications API en **HTTPS** (TLS 1.2+). Pas d’HTTP pour auth ni cartes.
- **Au repos** : Données sensibles (hors carte) chiffrées en base (AES / KMS en prod). Backups chiffrés.
- **Secrets** : Jamais en dur dans le code. **Variables d’environnement** ou **secret manager** (`.env` non versionné, dans `.gitignore`).

### 1.3 Données personnelles (chiffrement RIPA)

- **Chiffrées en base** (algo custom / encrypt_ripa) : nom, post_nom, prenom, tel, email, numéros de compte (MM, bancaire), montants sensibles, références, photos KYC.
- **Hash uniquement** (irréversible) : mot de passe / PIN (bcrypt). Pas de chiffrement réversible pour les mots de passe.

### 1.4 Logs (PCI DSS)

- **À ne jamais logger** : PAN, CVV, token complet, mot de passe, PIN.
- **Autorisé** : `id_utilisateur`, `action`, `ressource`, `date_heure`, `last4`, `token_id` (opaque), statut, codes erreur génériques.
- Utiliser une fonction **sanitize_for_log()** (ou équivalent) avant toute écriture en log. Table `log_utilisateur_application` pour les actions app.

### 1.5 Process (PCI DSS)

- Code sensible (carte, auth, secrets) : **Pull Request** + revue avant merge. Pas de push direct sur `main`.
- Déploiement via **pipeline** ; pas de modification directe en prod (migrations versionnées pour la BDD).
- **Scan des dépendances** (ex. `composer audit`, Dependabot) dans la CI si possible.

---

## 2. Les trois couches de l’écosystème (cible)

| Couche | Rôle | Utilisateurs |
|--------|------|---------------|
| **RIPA for Business** | Cockpit financier PME : facturation DGI, paiements, réconciliation, comptabilité, payroll | Entrepreneurs, PME, commerçants |
| **RIPA (App)** | Portefeuille digital consommateur : wallet, paiements, transferts | Particuliers, employés, clients finaux |
| **RIPA API** | Infrastructure ouverte : API modulaires pour tiers (clés API, scopes, sandbox, webhooks) | Développeurs, fintechs |

---

## 3. État des lieux actuel (avant phases)

### 3.1 RIPA (App) — B2C

| Élément | État | Détail |
|---------|------|--------|
| Backend API | ✅ | CodeIgniter, `Apiapp.php`, routes `api/app/*`, JWT, chiffrement RIPA |
| App mobile | ✅ | React Native / Expo : auth, Home, KYC, cartes, MM, comptes bancaires, notifications, transactions récentes |
| BDD | ✅ | Tables : utilisateur_application, compte_mobile_money, compte_bancaire, carte_utilisateur, kyc_utilisateur, notification_utilisateur, transaction_carte_mobile_money |
| Manquant B2C | ⏳ | Scan & Pay, paiement facture, transfert C2C, paiement services |

### 3.2 RIPA for Business — B2B

| Élément | État |
|---------|------|
| Dashboard web | ❌ |
| Modules (facturation, paiements, payroll, réconciliation, compta) | ❌ |
| Backoffice | ⚠️ Uniquement KYC (Kyc_backoffice) |

### 3.3 RIPA API — Plateforme ouverte

| Élément | État |
|---------|------|
| API dédiée tiers | ❌ |
| Portail dev, clés API, scopes, sandbox, webhooks | ❌ |

---

## 4. Phase 1 — RIPA (App) : consolider et compléter

**Objectif** : Stabiliser la couche B2C sans tout réécrire ; préparer la suite.

- [ ] **Backend (CodeIgniter)**  
  - Documenter les routes `api/app/*` (liste, payloads, réponses).  
  - S’assurer que tout le code respecte PCI DSS (pas de PAN/CVV, logs sanitized, secrets en config).
- [ ] **App mobile**  
  - Config d’URL de base (actuel vs futur Laravel) pour bascule future.  
  - Évolutions métier selon priorité : Scan & Pay, paiement facture, transfert C2C, paiement services.
- [ ] **Règle** : Chaque feature B2C doit rester alignée avec ce document (PCI + couche B2C uniquement ; pas de logique B2B dans l’app consommateur).

**Livrable** : B2C documenté, stable, prêt à être branché à un futur backend Laravel si besoin.

---

## 5. Phase 2 — RIPA for Business (nouveau produit)

**Objectif** : Livrer le cockpit PME (recommandation : Laravel, monolithe modulaire).

- [ ] **Nouveau projet** : application web B2B (Laravel), auth entreprise, modules distincts.
- [ ] **Ordre suggéré** :  
  1. Facturation & Devis (DGI, QR « Payez avec RIPA », lien de paiement).  
  2. Paiements Business (encaissements QR/lien, décaissements, trésorerie).  
  3. Réconciliation (lien facture ↔ paiement, statut « Payée »).  
  4. Payroll (salaires vers wallets RIPA, création wallet employé).  
  5. Comptabilité SYSCOHADA (écritures, plan OHADA, rapports, export).
- [ ] **Intégration B2C** : Lien « Payez avec RIPA » → App RIPA + API de paiement ; côté app, écran « Paiement de facture ».
- [ ] **PCI DSS** : Mêmes règles (pas de PAN/CVV stockés ; tokenisation ; logs sanitized ; HTTPS ; secrets en env).

**Livrable** : Dashboard B2B opérationnel (au minimum facturation + paiements + réconciliation).

---

## 6. Phase 3 — RIPA API (infrastructure ouverte)

**Objectif** : Exposer les capacités pour les tiers.

- [ ] **API versionnée** (ex. `/api/v1/...`) avec authentification par **clés API** et **scopes** (invoicing, payments, wallet, etc.).
- [ ] **Portail développeur** : documentation (OpenAPI/Swagger), sandbox, inscription clés API.
- [ ] **Webhooks** : notifications temps réel (paiement reçu, facture payée, etc.).
- [ ] **Option** : Même backend Laravel que B2B qui expose ces API ; l’app B2C peut migrer vers ces API progressivement.

**Livrable** : Au moins une API modulaire (ex. Invoicing ou Payments) documentée, avec clés et sandbox.

---

## 6. Progression actuelle

**Dernière mise à jour** : à mettre à jour à chaque avancement.

| Phase | Statut | Commentaire |
|-------|--------|-------------|
| Phase 1 — B2C | En cours | Backend paiement B2C livré : payment/sources, payee/token, payee/lookup, payment/submit ; tables ripa_payee_token, transaction_paiement_ripa. Prochaine étape : écrans app Payer. |
| Phase 2 — B2B | Non démarrée | Attente décision Laravel + ordre des modules. |
| Phase 3 — RIPA API | Non démarrée | Après Phase 2. |

**Checklist avant de coder (à cocher mentalement ou en revue)** :

- [ ] J’ai consulté ce fichier et la section [Progression actuelle](#6-progression-actuelle) + [Focus du moment](#7-focus-du-moment).
- [ ] Mon travail correspond à la phase et au périmètre décrits (pas d’écart de scope).
- [ ] Je n’introduis pas de donnée carte (PAN/CVV) en base ni en log.
- [ ] Les secrets restent en config / env, pas en dur.
- [ ] Les logs sont sanitized (pas de PAN, CVV, token complet, mot de passe).

---

## 7. Focus du moment

**À remplir et à mettre à jour** à chaque début de session ou de sprint.

- **Phase concernée** : Phase 1 — B2C
- **Objectif de la session** : Backend paiement B2C (sources, payee lookup, submit) + client API app. Prochaine : écrans Payer (wizard).
- **Écart à signaler** : Aucun.

```text
Focus actuel : Phase 1 — B2C. Backend : payment/sources, payee/token, payee/lookup, payment/submit. Logs log_utilisateur_application + api_logs (PCI). App : api.js + config URL base. À faire : écrans flux Payer.
Écart : aucun.
```

---

## 8. Rappel — Où est le code aujourd’hui

| Couche | Emplacement actuel |
|--------|--------------------|
| API B2C | `application/controllers/api/Apiapp.php`, routes dans `application/config/routes.php` |
| App mobile | `mobileapp/` (Expo, écrans dans `src/screens/`, API client dans `src/services/api.js`) |
| Backoffice KYC | `application/controllers/Kyc_backoffice.php`, vues `application/views/kyc_backoffice/` |
| SQL / schémas | `sql/` (utilisateur, KYC, comptes MM, bancaires, cartes, notifications, transactions carte↔MM, **11_ripa_payee_token**, **12_transaction_paiement_ripa**) |
| Helpers / config | `application/helpers/custom_helper.php` (chiffrement RIPA), `application/config/config.php` |

---

*Ce fichier est la référence pour les spécifications PCI DSS, les étapes et phases de codage, et la progression. Consulter avant chaque session de codage et signaler tout écart.*
