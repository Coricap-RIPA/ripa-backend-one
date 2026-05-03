# RIPA — Spécifications portail marchand (B2B) et phases de codage

**Statut** : **Spécifications v1** — base fonctionnelle volontairement **simple** ; les champs et tables pourront être enrichis (indices, contraintes métier, historisation) sans remettre en cause le modèle.

**Documents liés** : `docs/MODULES-B2B-B2C-ET-SECURITE-SQL.md`, `docs/SPECS-PCI-PHASES-ET-PROGRESSION.md`, `docs/SPECS-PAIEMENT-B2C-INTEROPERABILITE.md`.

---

## 1. Objectif produit

Mettre à disposition des **marchands / entreprises** un **portail web** (dans l’écosystème back-office CodeIgniter) permettant :

- de **créer un compte professionnel**, soumis à **validation par l’équipe RIPA** ;
- de **gérer l’entreprise** (services, employés, droits) ;
- d’**aligner les moyens de paiement** sur le modèle B2C (Mobile Money, compte bancaire, cartes physique / virtuelle, **KYC**, **tokenisation / PCI**) ;
- d’**encaisser** (QR, lien de paiement — **même principes que l’app mobile**) ;
- de **décaisser** (paiement / remboursement client, fournisseur, salaires, virements — phases ultérieures selon priorité) ;
- de **suivre les transactions** rattachées au périmètre de l’entreprise.

**Règle d’architecture** : le code portail marchand vit sous **`application/controllers/business/`**, **`application/models/business/`**, **`application/views/business/`**, en reprenant les conventions des contrôleurs back-office existants (vues, autoload, **Materialize + Bootstrap** comme le module KYC back-office).

**URLs (routes dédiées)** — à adapter selon `base_url` ; voir `application/config/routes.php` (section portail marchand) :
- Accueil / tableau de bord : `business` ou `business/tableau-de-bord`
- Inscription : `business/inscription` ; soumission : `business/inscription/soumettre`
- Connexion : `business/connexion` ; traitement POST : `business/connexion/traitement`
- Déconnexion : `business/deconnexion` ; premier mot de passe : `business/premier-mot-de-passe` (+ `/enregistrer` en POST)
- Services : `business/services`, `…/ajouter`, `…/modifier/{id}`, `…/supprimer/{id}` (POST)
- Employés : `business/employes`, `…/ajouter`, `…/modifier/{id}`, `…/supprimer/{id}` (POST)
- Transactions : `business/transactions`, `…/saisir`
- Les chemins techniques CI (`business/auth/login`, etc.) restent valides si non couverts par une route.
- Validation RIPA (back-office) : `Business_marchand_backoffice`

**Modèles** : autoload dans `application/config/autoload.php` (`business/business_marchand_model`, `ub_model`, `service_model`, `employe_model`, `business_transaction_model`) — noms distincts du `Marchand_model` / `Transaction_model` **legacy** à la racine `models/`.

---

## 2. Modèle de données — v1 « basique mais cohérent »

### 2.1 Principes

- Le **profil entreprise** est porté par **`business_marchand`**.
- Les **utilisateurs du portail web business** (connexion back-office marchand, **pas** l’app mobile B2C) sont portés par la table **`utilisateur_business`**, **toujours rattachés à un `id_marchand`** : pour un business donné, la liste des comptes autorisés sur le portail = lignes `utilisateur_business` où `id_marchand` = cet ID.
- Le **profil personne app** reste **`utilisateur_application`** (wallet mobile) : **optionnel** et **distinct** ; on peut lier plus tard un `utilisateur_business` à un `utilisateur_application` (colonne nullable) pour un même individu — **hors obligation v1**.
- **À la validation du compte business** par RIPA : création **automatique** d’un premier **`utilisateur_business`** :
  - **email** = `business_marchand.email_contact` (email pro saisi à la demande) ;
  - **mot de passe initial** = `123456` (stocké **uniquement en hash bcrypt**, jamais en clair en base) ;
  - **rôle** = **administrateur** avec **tous les droits** sur le périmètre de ce marchand ;
  - flag **`doit_changer_mot_de_passe` = 1** : à la **première connexion**, l’utilisateur **doit** définir un nouveau mot de passe avant d’accéder au reste du portail (écran obligatoire, session bloquée sinon).
- **Sécurité** : mot de passe par défaut **faible** — usage **strictement transitoire** ; notifier le marchand (email) des identifiants **sans** recopier le mot de passe en clair si possible (lien « première connexion » + consigne « mot de passe provisoire communiqué par RIPA / support » selon process interne). En **production**, envisager mot de passe **aléatoire** + email unique (évolution hors v1 specs métier actuelle).

### 2.2 Table `business_marchand`

| Champ | Type (indicatif) | Description |
|-------|------------------|-------------|
| `id` | INT PK AI | Identifiant marchand |
| `raison_sociale` | VARCHAR(255) | Nom légal / commercial |
| `email_contact` | VARCHAR(255) | Email pro (contact, notifications) |
| `telephone_contact` | VARCHAR(50) | Téléphone pro (+ format normalisé recommandé) |
| `identifiant_legal` | VARCHAR(100) NULL | N° immatriculation / RCCM / autre (optionnel v1) |
| `statut` | ENUM ou VARCHAR | `brouillon`, `en_attente_validation`, `actif`, `refuse`, `suspendu` |
| `id_utilisateur_demandeur` | INT NULL FK → `utilisateur_application.id` | Optionnel : si la demande vient de l’app ; sinon NULL |
| `id_utilisateur_validateur` | INT NULL FK | Compte back-office ayant validé / refusé |
| `date_demande` | DATETIME | Soumission pour validation |
| `date_decision` | DATETIME NULL | Validation ou refus |
| `motif_refus` | TEXT NULL | Visible côté marchand si refus |
| `notes_internes` | TEXT NULL | Notes équipe RIPA (non exposées au marchand) |
| `created_at` / `updated_at` | DATETIME | Audit minimal |

**Workflow validation** : à la création / soumission → `en_attente_validation`. L’équipe RIPA dans le **back-office** passe à `actif` ou `refuse` (avec `motif_refus`). `suspendu` = action compliance / admin.

### 2.3 Table `utilisateur_business` (comptes portail web marchand)

**Rôle** : authentification et **appartenance** au business. Un marchand a **un ou plusieurs** `utilisateur_business` ; le premier est créé **à la validation** (voir §3).

| Champ | Type (indicatif) | Description |
|-------|------------------|-------------|
| `id` | INT PK AI | |
| `id_marchand` | INT FK → `business_marchand.id` | Business auquel l’utilisateur est rattaché (**obligatoire**) |
| `email` | VARCHAR(255) | Login ; **unique** (globalement ou par marchand selon règle produit — recommandé **unique global** pour éviter collisions) |
| `mot_de_passe` | VARCHAR(255) | **Hash bcrypt** uniquement (comme `utilisateur_application`) |
| `role` | VARCHAR(50) | Ex. `administrateur` (tous droits), `gestionnaire`, `lecteur_seul` — liste à figer en config |
| `doit_changer_mot_de_passe` | TINYINT(1) | `1` = forcer changement à la prochaine connexion réussie |
| `actif` | TINYINT(1) | 0 = accès révoqué |
| `nom` / `prenom` | VARCHAR NULL | Affichage optionnel |
| `id_utilisateur_application` | INT NULL FK | Lien futur vers compte app mobile (même personne) — **optionnel v1** |
| `created_at` / `updated_at` | DATETIME | |
| `derniere_connexion` | DATETIME NULL | Optionnel (audit) |

**Règles** :
- L’**administrateur** créé à la validation a `role = administrateur` et `doit_changer_mot_de_passe = 1` après insertion du hash de `123456`.
- Les **autres employés** du portail = **nouvelles lignes** `utilisateur_business` (invitation, email, rôle, mot de passe provisoire ou lien d’activation — à détailler en phase « Membres »).
- **Ne jamais** logger le mot de passe en clair ni le hash dans les logs applicatifs non protégés.

### 2.3 bis — Table `business_marchand_membre` (optionnel / legacy)

Si besoin de lier explicitement un **`utilisateur_application`** à un marchand (hors portail web), conserver une table de **liaison** ; **pour le portail web**, la source de vérité des accès est **`utilisateur_business`**. En v1 on peut **omettre** `business_marchand_membre` et n’utiliser que `utilisateur_business` + `id_utilisateur_demandeur` sur `business_marchand` pour tracer l’origine de la demande.

### 2.4 Table `business_service`

Représente un **service interne** (Finance, Comptabilité, etc.) auquel on pourra rattacher des moyens de paiement et une carte virtuelle dédiée.

| Champ | Type | Description |
|-------|------|-------------|
| `id` | INT PK AI | |
| `id_marchand` | INT FK | |
| `libelle` | VARCHAR(255) | Ex. « Finance » |
| `code` | VARCHAR(64) NULL | Code court optionnel |
| `actif` | TINYINT(1) | |
| `ordre` | INT NULL | Affichage |
| `created_at` / `updated_at` | DATETIME | |

### 2.5 Liaison moyens de paiement ↔ service (v1 minimal)

**Objectif** : ne pas dupliquer toute la BDD carte/MM/banque ; **réutiliser** les tables existantes (`compte_mobile_money`, `compte_bancaire`, `carte_utilisateur`, etc.) en les rattachant au **contexte entreprise**.

**Option v1 recommandée** : table **`business_moyen_lien`**

| Champ | Type | Description |
|-------|------|-------------|
| `id` | INT PK AI | |
| `id_marchand` | INT FK | |
| `id_service` | INT NULL FK | NULL = moyen « global » entreprise ; sinon lié au service |
| `type_moyen` | VARCHAR(32) | `mobile_money`, `compte_bancaire`, `carte_physique`, `carte_virtuelle` |
| `id_compte_reference` | INT | ID dans la table métier correspondante (MM, banque, carte) |
| `id_utilisateur_application` | INT NULL FK | Propriétaire technique côté **app** (MM, cartes) si le moyen repose sur un user app ; sinon NULL. *Évolution possible : `id_utilisateur_business`.* |
| `actif` | TINYINT(1) | Permet **blocage** sans suppression |
| `created_at` / `updated_at` | DATETIME | |

**PCI** : aucun PAN/CVV dans cette table ; uniquement des **IDs** vers des lignes déjà conformes (token, last4 côté carte).

### 2.6 Table `business_employe`

Fiches **RH** (hors ou en complément du compte portail). Champs principaux : `id_marchand`, `nom`, `prenom`, `email`, `telephone`, `poste`, `actif`, `id_utilisateur_business` (optionnel — lien vers un `utilisateur_business`).

### 2.7 Table `business_transaction`

**Journal** des opérations visibles sur le portail : `id_marchand`, `id_service`, `id_utilisateur_business`, `id_employe`, `type_operation`, `sens` (débit/crédit), `montant`, `devise`, `libelle`, `statut`, `id_paiement_ripa` (référence **logique** vers `transaction_paiement_ripa.id_paiement`, **sans FK** pour tolérer les migrations), `meta_json` (JSON sanitized).

### 2.8 QR / liens de paiement (alignement app)

- **Format et génération** : **identiques** aux principes déjà définis pour l’app (`SPECS-PAIEMENT-B2C-INTEROPERABILITE.md` — token, lookup destinataire, etc.).
- **Extension B2B** : le QR / lien doit porter une **référence marchand** (ex. `id_marchand` ou token dérivé) et optionnellement `id_service`, `reference_facture` — champs à ajouter en **phase dédiée** (table `business_encaissement` ou réutilisation `ripa_payee_token` avec type/contexte).

*En v1 specs, la table détaillée « encaissement » peut être limitée à : référence, montant, statut, id_marchand — à préciser au moment du codage du module QR.*

---

## 3. Parcours « Création compte marchand »

1. **Interface publique ou semi-protégée** : formulaire (raison sociale, **email pro** = futur login portail, téléphone, pièces justificatives optionnelles v1).
2. Insertion **`business_marchand`** en `en_attente_validation` (champ `email_contact` renseigné). Optionnel : renseigner `id_utilisateur_demandeur` si le demandeur est déjà un user **app**.
3. **Aucun `utilisateur_business` tant que le compte n’est pas validé** (pas de connexion portail marchand).
4. **Notification** équipe RIPA (email / ticket / liste dans back-office).
5. **Back-office RIPA** : liste des demandes → **Refuser** (`motif_refus`, pas de création de compte portail) ou **Valider** :
   - `business_marchand.statut` → `actif`, `date_decision`, `id_utilisateur_validateur` ;
   - **Transaction BDD** : créer **`utilisateur_business`** :
     - `id_marchand` = ce marchand ;
     - `email` = `email_contact` du marchand (normalisé en minuscules, trim) ;
     - `mot_de_passe` = **bcrypt**(`123456`) — constante applicative ou config **hors dépôt** en prod si vous changez la valeur ;
     - `role` = `administrateur` ;
     - `doit_changer_mot_de_passe` = **1** ;
     - `actif` = 1.
6. **Notification marchand** : compte activé, identifiant = email, consigne **changer le mot de passe à la première connexion** (et rappel du provisoire `123456` par canal sécurisé interne si vous le gardez — **à ne pas** mettre en clair dans un email non chiffré idéalement).
7. **Première connexion portail** : login email + `123456` → écran **obligatoire** « Nouveau mot de passe » (confirmation) → `doit_changer_mot_de_passe` = 0 → accès dashboard.
8. Accès au **dashboard** uniquement si `business_marchand.actif` **et** `utilisateur_business.actif`.

---

## 4. Modules fonctionnels (cartographie)

| Module | Contenu | Priorité suggérée |
|--------|---------|-------------------|
| **A — Onboarding & validation** | Formulaire marchand + file d’attente back-office | **P0** |
| **B — Auth & membres** | Login `utilisateur_business`, changement MDP 1ʳᵉ connexion, CRUD utilisateurs du portail (invitations, rôles) | **P0** |
| **C — Services** | CRUD `business_service` | **P1** |
| **D — Moyens de paiement** | Liaison MM / banque / cartes (réutilisation logique B2C + KYC) | **P1** |
| **E — Carte virtuelle par service** | Commande / attribution par `id_service` | **P2** |
| **F — Encaissement** | QR + lien (interop app) | **P2** |
| **G — Décaissement** | Client, fournisseur, salaire, virement | **P2 / P3** |
| **H — Transactions & reporting** | Liste filtrée par marchand / service / membre | **P1** (lecture seule d’abord) |

---

## 5. PCI DSS (rappel portail marchand)

- **Même exigences** que B2C : pas de PAN/CVV en base ni en logs ; **token + last4** ; **HTTPS** ; secrets en **variables d’environnement** ; logs **sanitized** (`sanitize_for_log` / équivalent).
- Toute saisie carte dans le navigateur : viser **hosted fields / vault** (comme stratégie app), pas de POST du PAN vers le serveur RIPA en clair.

---

## 6. Phases de codage

### Phase 0 — Fondations (sans UI métier riche)

- [ ] Script SQL : `business_marchand`, **`utilisateur_business`**, `business_service`, `business_moyen_lien` (noms exacts à valider avec conventions `sql/` existantes). *`business_marchand_membre` omis en v1 si non nécessaire.*
- [ ] Dossiers `business/` (controllers, models, views) + **routes** dédiées.
- [ ] Modèles `business/*` + chargement (autoload ou chargement explicite, aligné projet).
- [ ] **PCI** : aucune régression sur les flux carte existants.

### Phase 1 — Onboarding + validation back-office (P0)

- [ ] Vues marchand : inscription / demande de compte (Materialize + Bootstrap).
- [ ] Contrôleur : soumission, états `brouillon` / `en_attente_validation`.
- [ ] **Back-office** : liste des demandes, détail, actions Valider / Refuser, traçabilité (`id_utilisateur_validateur`, `date_decision`).
- [ ] **À la validation** : création **`utilisateur_business`** (email = `email_contact`, hash `123456`, rôle administrateur, `doit_changer_mot_de_passe = 1`).
- [ ] Connexion portail : session marchand ; **middleware** : si `doit_changer_mot_de_passe`, redirection vers changement MDP jusqu’à succès.
- [ ] Accès dashboard seulement si marchand `actif` **et** utilisateur `actif`.

### Phase 2 — Membres & services (P0–P1)

- [ ] CRUD **`utilisateur_business`** supplémentaires (employés) : invitation par email, rôle `gestionnaire` / `lecteur_seul`, mot de passe provisoire ou flux d’activation.
- [ ] Matrice de droits alignée back-office RIPA (évolution progressive).
- [ ] CRUD `business_service`.

### Phase 3 — Moyens de paiement & KYC entreprise (P1)

- [ ] Rattachement des moyens existants via `business_moyen_lien`.
- [ ] Parcours KYC aligné sur le back-office / app (données sensibles chiffrées comme aujourd’hui).

### Phase 4 — Carte virtuelle par service & encaissement (P2)

- [ ] Règles métier : une carte virtuelle **optionnelle** par service (référence `carte_utilisateur` + lien).
- [ ] QR / lien : **même contrat** que l’app ; extension contexte marchand.

### Phase 5 — Décaissements & payroll (P3)

- [ ] Paiement client / fournisseur / salaire : s’appuyer sur les APIs de paiement B2C existantes ou nouvelles routes `api/app` dédiées « contexte marchand ».
- [ ] Reporting consolidé, exports.

---

## 7. Évolutions prévues hors v1

- Permissions granulaires (matrice par écran / action).
- Multi-devises, plafonds, workflows d’approbation à N niveaux.
- API dédiée « RIPA for Business » (cf. Phase 3 écosystème dans `SPECS-PCI-PHASES-ET-PROGRESSION.md`).
- Migration partielle vers stack Laravel si décision produit (le portail CI reste la **première vague** livrable).

---

## 8. Synthèse « définition of done » v1 minimale

- Un marchand peut **soumettre** une demande et voir son **statut**.
- L’équipe RIPA peut **valider ou refuser** depuis le back-office ; si validation → **compte `utilisateur_business` administrateur** créé (email pro, MDP provisoire `123456`, **changement obligatoire** à la 1ʳᵉ connexion).
- Un marchand **actif** peut se connecter au portail avec **`utilisateur_business`**, voir **services** (CRUD minimal) et **autres utilisateurs du portail** (CRUD minimal).
- Les tables permettent de **brancher** moyens de paiement et QR sans refonte du schéma carte/MM/banque.

---

*Document vivant : à mettre à jour après chaque livraison majeure du portail marchand.*
