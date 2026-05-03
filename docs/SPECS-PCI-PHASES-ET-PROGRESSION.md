# RIPA — Spécifications PCI DSS, phases de codage et progression

**Document unique de référence.**  
**À consulter avant chaque session de codage** : vérifier la section [Progression actuelle](#7-progression-actuelle) et le [focus du moment](#8-focus-du-moment). **Signaler tout écart** par rapport à ce fichier (scope creep, oubli PCI, phase non respectée).

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
| **Spécifications portail marchand** | 📋 **`docs/SPECS-PORTAIL-MARCHAND-B2B.md`** — modèle BDD v1, onboarding + validation RIPA, phases de codage, PCI |
| Dashboard web marchand (CodeIgniter) | ❌ À implémenter (`controllers/models/views/business/`) |
| Modules (facturation, payroll, réconciliation, compta avancée) | ❌ Hors v1 portail ; Laravel ou extensions ultérieures |
| Backoffice | ⚠️ KYC (`Kyc_backoffice`) + **à ajouter** : file validation comptes marchands |

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

**Objectif** : Livrer le **cockpit marchand** puis, à terme, le cockpit PME complet.

**Mise à jour stratégie (2026)** : la **première vague** cible le **portail marchand dans le back-office CodeIgniter** existant (dossiers `application/controllers/business/`, `application/models/business/`, `application/views/business/`), avec **onboarding** et **validation manuelle** par l’équipe RIPA. Détail : **`docs/SPECS-PORTAIL-MARCHAND-B2B.md`**.

- [ ] **Vague A — Portail marchand (CodeIgniter)**  
  - Schéma SQL minimal : `business_marchand`, **`utilisateur_business`** (auth portail + rattachement marchand ; admin créé à la validation, MDP provisoire + changement 1ʳᵉ connexion), `business_service`, `business_moyen_lien` (voir `docs/SPECS-PORTAIL-MARCHAND-B2B.md`).  
  - Interface marchand : demande de compte + dashboard si `actif`.  
  - Back-office : file d’attente **Valider / Refuser** les demandes.  
  - Membres, services, puis moyens de paiement (réutilisation logique B2C / KYC / tokenisation).  
  - QR & liens : **mêmes principes** que l’app (`SPECS-PAIEMENT-B2C-INTEROPERABILITE.md`).
- [ ] **Vague B — ERP étendu (option Laravel)** : monolithe modulaire si décision produit — facturation DGI, réconciliation SYSCOHADA, payroll avancé, etc.
- [ ] **Ordre suggéré (global)** :  
  1. Onboarding marchand + validation RIPA (**P0**, specs).  
  2. Membres & droits (rôles type back-office).  
  3. Services + liaison moyens de paiement + reporting transactions.  
  4. Facturation & Devis (DGI, QR « Payez avec RIPA », lien de paiement).  
  5. Paiements Business (encaissements, décaissements, trésorerie).  
  6. Réconciliation, payroll, comptabilité OHADA (selon vague B).
- [ ] **Intégration B2C** : Lien « Payez avec RIPA » → App RIPA + API de paiement ; côté app, écran « Paiement de facture ».
- [ ] **PCI DSS** : Mêmes règles sur **tout** le portail web marchand (pas de PAN/CVV stockés ; tokenisation ; logs sanitized ; HTTPS ; secrets en env).

**Livrable court terme** : compte marchand validable depuis le back-office + socle technique `business/` + tables v1. **Livrable moyen terme** : dashboard marchand opérationnel (membres, services, moyens de paiement, lecture transactions).

---

## 6. Phase 3 — RIPA API (infrastructure ouverte)

**Objectif** : Exposer les capacités pour les tiers.

- [ ] **API versionnée** (ex. `/api/v1/...`) avec authentification par **clés API** et **scopes** (invoicing, payments, wallet, etc.).
- [ ] **Portail développeur** : documentation (OpenAPI/Swagger), sandbox, inscription clés API.
- [ ] **Webhooks** : notifications temps réel (paiement reçu, facture payée, etc.).
- [ ] **Option** : Même backend Laravel que B2B qui expose ces API ; l’app B2C peut migrer vers ces API progressivement.

**Livrable** : Au moins une API modulaire (ex. Invoicing ou Payments) documentée, avec clés et sandbox.

---

## 7. Progression actuelle

**Dernière mise à jour** : 2026-03-14 — specs portail marchand + alignement Phase 2.

| Phase | Statut | Commentaire |
|-------|--------|-------------|
| Phase 1 — B2C | En cours | Backend paiement B2C livré : payment/sources, payee/token, payee/lookup, payment/submit ; tables ripa_payee_token, transaction_paiement_ripa. Prochaine étape : écrans app Payer. |
| Phase 2 — B2B | **Démarré** | SQL `14_` + `15_` (tables + permission back-office). Code : `controllers/business/*`, `Business_marchand_backoffice`, modèles `models/business/`, vues `views/business/*` + `views/business_backoffice/`. Flux : inscription publique → validation BO → compte `utilisateur_business` + 1ʳᵉ connexion changement MDP. |
| Phase 3 — RIPA API | Non démarrée | Après consolidation B2B / besoin tiers. |

**Checklist avant de coder (à cocher mentalement ou en revue)** :

- [ ] J’ai consulté ce fichier et la section [Progression actuelle](#7-progression-actuelle) + [Focus du moment](#8-focus-du-moment).
- [ ] Mon travail correspond à la phase et au périmètre décrits (pas d’écart de scope).
- [ ] Je n’introduis pas de donnée carte (PAN/CVV) en base ni en log.
- [ ] Les secrets restent en config / env, pas en dur.
- [ ] Les logs sont sanitized (pas de PAN, CVV, token complet, mot de passe).

---

## 8. Focus du moment

**À remplir et à mettre à jour** à chaque début de session ou de sprint.

- **Phase concernée** : Phase 1 — B2C (priorité flux Payer) **et** Phase 2 — cadrage B2B documenté.
- **Objectif récent** : Rédaction **`SPECS-PORTAIL-MARCHAND-B2B.md`** (onboarding marchand, validation back-office, tables v1, phases codage). **Prochaine étape B2B** : script SQL + squelette `business/` + file validation marchands.
- **Écart à signaler** : Aucun.

```text
Focus actuel : Phase 1 — B2C (écrans Payer). Parallèle : Phase 2 — specs portail marchand livrées ; implémentation business/ à planifier (SQL Phase 0 specs).
Écart : aucun.
```

---

## 9. Rappel — Où est le code aujourd’hui

| Couche | Emplacement actuel |
|--------|--------------------|
| API B2C | `application/controllers/api/Apiapp.php`, routes dans `application/config/routes.php` |
| App mobile | `mobileapp/` (Expo, écrans dans `src/screens/`, API client dans `src/services/api.js`) |
| Backoffice KYC | `application/controllers/Kyc_backoffice.php`, vues `application/views/kyc_backoffice/` |
| **Portail marchand (B2B)** | `application/controllers/business/` (Auth, Dashboard, Inscription), `Business_marchand_backoffice.php`, `application/models/business/`, `application/views/business/`, `application/views/business_backoffice/`, `application/config/business.php` — **`docs/SPECS-PORTAIL-MARCHAND-B2B.md`** |
| SQL / schémas | `sql/` (utilisateur, KYC, comptes MM, bancaires, cartes, notifications, transactions carte↔MM, **11_ripa_payee_token**, **12_transaction_paiement_ripa**) ; **à venir** : tables `business_*` selon specs |
| Helpers / config | `application/helpers/custom_helper.php` (chiffrement RIPA), `application/config/config.php` |

---

*Ce fichier est la référence pour les spécifications PCI DSS, les étapes et phases de codage, et la progression. Consulter avant chaque session de codage et signaler tout écart.*
