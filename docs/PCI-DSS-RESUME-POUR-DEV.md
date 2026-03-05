# PCI DSS – Résumé pour le développement RIPA

Ce document traduit les specs PCI DSS en **règles concrètes pour le code** qu’on va écrire (backend, API, backoffice, app mobile). Chaque point dit : *quoi faire* et *quoi ne pas faire* dans le code.

---

## 1. Données carte : ne jamais stocker le PAN ni le CVV

| Spec PCI | En clair | Ce qu’on code |
|----------|----------|----------------|
| Pas de PAN en clair | Le numéro complet de la carte (16 chiffres) ne doit jamais être enregistré chez nous. | **Backend / API** : on ne crée **aucune colonne** pour le numéro de carte complet. On stocke uniquement : **token** (retourné par le prestataire) + **4 derniers chiffres** (pour affichage type `****1234`). |
| Tokenisation | Le PAN est remplacé par un token géré par un prestataire PCI. | On appelle **toujours le Vault** (prestataire tokenisation) pour : créer un token à partir du PAN (carte physique), ou utiliser le token pour les opérations. Le backend ne reçoit le PAN que pour le **renvoyer au Vault** dans la même requête, sans l’écrire en base ni en log. |
| Pas de CVV | Le code de sécurité (3 chiffres au dos) ne doit jamais être stocké. | On ne stocke **jamais** le CVV. Si on en a besoin pour une opération (ex. avec Onafriq), on l’envoie une fois via l’API du prestataire et on ne le garde pas en base, ni en session, ni en cache. |
| Pas de données carte dans les logs | Les logs ne doivent pas contenir de données qui permettent d’identifier une carte. | Avant d’écrire dans les logs (fichier, CloudWatch, etc.) : **jamais** de PAN, CVV, ni de token complet. On peut logger : `card_last4`, `token_id` (identifiant opaque), type de carte, statut. On **masque ou exclut** tout champ sensible dans les messages d’erreur renvoyés au client. |

**En base de données (ex. table `carte` ou équivalent) :**
- On stocke : `token_vault`, `last_four_digits`, `brand` (Visa/Mastercard), `statut`, `id_utilisateur`, etc.
- On ne stocke pas : `pan`, `numero_carte`, `cvv`, `cvc`, `date_expiration` en clair (la date d’expiration peut être stockée si nécessaire pour la logique métier, mais pas le CVV).

---

## 2. Chiffrement et transmission

| Spec PCI | En clair | Ce qu’on code |
|----------|----------|----------------|
| TLS 1.2+ partout | Toute communication avec nos serveurs et avec les prestataires doit être chiffrée. | **Backend** : on force HTTPS (TLS 1.2 minimum) pour l’API (config serveur / reverse proxy). On n’accepte pas de connexions HTTP pour les endpoints qui touchent aux cartes ou à l’auth. **App / Backoffice** : toutes les requêtes vers l’API en `https://` uniquement. |
| Chiffrement au repos | Les données sensibles en base doivent être chiffrées. | En prod (AWS) : utiliser RDS avec chiffrement activé. Pour des champs très sensibles (hors PAN qu’on ne stocke pas), utiliser le **KMS** ou le chiffrement au niveau base (colonnes chiffrées). Les backups RDS sont chiffrés (config AWS). |
| Clés dans un KMS | Les clés de chiffrement ne doivent pas être dans le code ni en base. | On ne met **jamais** de clés (API Vault, Onafriq, clés de chiffrement) en dur dans le code. On utilise un **secret manager** (ex. AWS Secrets Manager) ; le code lit les secrets au démarrage ou à la demande. En dev, utiliser des variables d’environnement (`.env` non versionné). |

---

## 3. Sécurité du code et des accès

| Spec PCI | En clair | Ce qu’on code |
|----------|----------|----------------|
| Pas de secrets dans le code | Mots de passe, clés API, tokens ne doivent pas être dans le dépôt. | Tous les secrets (DB, Vault, Onafriq, JWT, etc.) viennent de la **config / variables d’environnement** ou du secret manager. Le fichier `.env` (ou équivalent) est dans `.gitignore`. Pas de `password = "xxx"` ou `api_key = "xxx"` dans le code. |
| Privilèges minimaux | Chaque compte ou rôle n’a que les droits nécessaires. | **Backend** : les requêtes SQL et les accès à la base utilisent un utilisateur avec des droits limités (pas de DROP, pas d’accès à d’autres schémas si pas besoin). **IAM / AWS** : rôles avec politiques restrictives, pas de `"*"` sur les actions sensibles. |
| MFA pour les accès admin | Les accès admin (backoffice, serveurs, DB) doivent être protégés par MFA. | C’est surtout de la **config** (AWS IAM, backoffice) : s’assurer que les comptes admin du backoffice et de l’infra exigent MFA. Dans le code backoffice : ne pas désactiver la vérification 2FA pour les rôles admin. |

---

## 4. Logs et monitoring

| Spec PCI | En clair | Ce qu’on code |
|----------|----------|----------------|
| Logs sans données carte | Les logs ne doivent pas contenir de PAN, CVV ni token complet. | Dans **tout** `log()`, `error_log()`, ou envoi vers CloudWatch : on n’écrit jamais PAN, CVV, ni le token complet. On peut écrire : id de transaction, type d’opération, `last4`, statut, code erreur générique. Créer une petite fonction utilitaire `sanitize_for_log($data)` qui retire ou masque les champs sensibles avant enregistrement. |
| Logs des accès admin | Qui a fait quoi sur les données sensibles ou la config. | Backoffice : logger les actions sensibles (connexion, modification rôles, accès à la liste des cartes / utilisateurs) avec **user_id**, **action**, **date**, **ressource** (sans données carte). Ces logs partent vers un système centralisé (ex. CloudWatch) avec rétention ≥ 1 an (config infra). |
| Alertes sur activités suspectes | Échecs de login, accès anormaux, etc. | Backend : après N échecs de connexion (ex. 5), bloquer temporairement ou alerter. Les tentatives sont loguées (sans mot de passe). L’alerting (email, Slack, etc.) est branché sur ces événements (code ou config monitoring). |

---

## 5. Développement et déploiement

| Spec PCI | En clair | Ce qu’on code |
|----------|----------|----------------|
| Modifications via Pull Request | Pas de commit direct sur la branche de prod. | Processus Git : tout passe par une branche + **Pull Request** + revue avant merge. On ne pousse pas directement sur `main`/`master` pour le code qui touche au CDE (API, modèles carte, auth). |
| Pas de modification directe en prod | On ne corrige pas le code ou la base à la main en prod. | Les correctifs et évolutions passent par le pipeline (build → tests → déploiement). Les changements de schéma de base passent par des **migrations** versionnées, pas par des requêtes SQL tapées à la main en prod. |
| Scan des dépendances | Détecter les librairies vulnérables. | Utiliser un outil (ex. `composer audit`, Snyk, Dependabot) dans la CI : à chaque PR ou build, lancer le scan. Corriger les vulnérabilités critiques avant de merger. |

---

## 6. Récap en une page : règles à respecter dans le code

- **Données carte**
  - Stocker uniquement : **token** (prestataire) + **4 derniers chiffres**.
  - Ne jamais stocker : **PAN**, **CVV**.
  - Ne jamais logger : PAN, CVV, token complet.

- **API / Backend**
  - Toutes les URLs en **HTTPS** (TLS 1.2+).
  - Secrets en **variables d’environnement** ou **secret manager**, jamais en dur.
  - Avant tout log : **masquer / exclure** les champs sensibles (PAN, CVV, token).
  - Pour la carte physique : recevoir le PAN → appeler le Vault pour tokenisation → ne sauver que token + last4.

- **Base de données**
  - Pas de colonne pour le numéro de carte complet ni pour le CVV.
  - En prod : base et backups **chiffrés** (RDS + KMS).

- **Backoffice / App**
  - Affichage carte : uniquement **last4** (ex. `**** 1234`).
  - Toutes les requêtes vers l’API en **HTTPS**.

- **Process**
  - Code sensible (carte, auth, secrets) : passage par **PR** + revue.
  - Déploiement via **pipeline**, pas de modification directe en prod.
  - **Scan des dépendances** dans la CI.

En suivant ces règles dans le code, on reste aligné avec les specs PCI DSS que tu as données et avec le scoping pack (flux, CDE, segmentation). Pour les détails infra (VPC, security groups, MFA, rétention des logs), c’est surtout de la config AWS et des processus, en plus du code ci‑dessus.
