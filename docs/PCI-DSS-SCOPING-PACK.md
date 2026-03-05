# RIPA – Scoping Pack PCI DSS (SAQ D)

**Objectif** : Définir le périmètre PCI (CDE) et documenter le flux des données carte pour servir de base de preuves au SAQ D et aux audits QSA.

**Principe retenu** : Aucun PAN en clair chez RIPA. Tokenisation systématique via un prestataire PCI compliant ; stockage limité à token + PAN masqué (4 derniers chiffres). Aucun CVV stocké.

---

## A. Schéma du flux de données carte

### Vue d’ensemble (1 page)

Le flux couvre : **entrée → traitement → stockage → transmission → suppression**. Les données de carte (PAN, CVV, etc.) ne transitent jamais en clair dans l’écosystème RIPA ; seuls des **tokens** et un **PAN masqué (4 derniers chiffres)** sont utilisés en interne.

---

### Diagramme du flux (Mermaid)

```mermaid
flowchart TB
    subgraph EXTERNE["Périmètre externe (hors CDE)"]
        APP["Application mobile\n(React Native / Expo)"]
        BO["Backoffice Web\n(CodeIgniter)"]
    end

    subgraph CDE["Environnement CDE (RIPA)"]
        subgraph DMZ_APP["Segment Application"]
            API["API Backend\n(PHP / CodeIgniter)"]
        end

        subgraph RESEAU_PRIVE["Réseau privé"]
            RDS[( "RDS MySQL\n(données métier\n+ token + last4 uniquement)" )]
            KMS["AWS KMS\n(clés chiffrement)"]
        end

        subgraph LOGS["Logs & Monitoring"]
            CW["CloudWatch Logs\n(aucune donnée carte)"]
            SIEM["SIEM / Centralisation\n(alertes, accès admin)"]
        end
    end

    subgraph TIERS["Tiers / Prestataires"]
        VAULT["Prestataire Tokenisation\n(Vault PCI compliant)\n• Stocke PAN\n• Retourne token"]
        ONAFRIQ["Onafriq\n• Émission cartes Visa/MC\n• Mobile Money"]
    end

    APP -->|"HTTPS (TLS 1.2+)\nDonnées carte → Vault uniquement\n(token + last4 vers API)"| API
    BO -->|"HTTPS (TLS 1.2+)\nPas de saisie PAN\n(affichage last4 + token)"| API

    API -->|"Tokenisation / Détokenisation\n(HTTPS, pas de PAN en clair)"| VAULT
    API -->|"Émission / opérations cartes\n(identifiants, pas de PAN)"| ONAFRIQ

    API -->|"Écriture token + last4\n(chiffré au repos)"| RDS
    API -->|"Chiffrement données sensibles"| KMS
    RDS -->|"Backups chiffrés"| S3_BACKUP["S3 (backups RDS)"]

    API -->|"Logs applicatifs\n(sans PAN/CVV/token complet)"| CW
    CW --> SIEM
    KMS -.->|"Clés séparées des données"| RDS
```

**Légende :**
- **Entrée** : Saisie possible uniquement dans l’app (carte physique) ; les données carte sont envoyées **directement au Vault** (ou via l’API qui les transmet sans les persister). L’API ne reçoit que token + last4.
- **Traitement** : L’API ne traite jamais de PAN en clair ; toute opération carte passe par le Vault (tokenisation/détokenisation) ou Onafriq (émission, opérations).
- **Stockage** : RIPA stocke uniquement **token + last4** (et métadonnées non sensibles) dans RDS. Le Vault stocke le PAN ; pas de CVV nulle part.
- **Transmission** : TLS 1.2+ partout ; pas de PAN/CVV dans les logs, ni dans les queues/messages (si usage ultérieur de SQS, etc.).
- **Suppression** : En cas de révocation carte, RIPA supprime le token et la référence ; le Vault gère la suppression/sécurisation du PAN selon son propre processus.

---

### Composants du flux (résumé)

| Étape       | Composant(s)                    | Rôle données carte |
|------------|-----------------------------------|--------------------|
| **Entrée** | App mobile, Backoffice            | Saisie / affichage last4 uniquement ; envoi PAN éventuel vers Vault uniquement. |
| **Traitement** | API Backend                   | Pas de PAN en mémoire ; appels Vault (tokenisation) et Onafriq (opérations cartes). |
| **Stockage** | RDS (RIPA), Vault (tiers)      | RIPA : token + last4. Vault : PAN (jamais RIPA). |
| **Transmission** | TLS 1.2+ (client ↔ API, API ↔ Vault, API ↔ Onafriq) | Aucune donnée carte en clair dans les logs ou messages. |
| **Logs**    | CloudWatch, SIEM                 | Pas de PAN, CVV, ni token complet ; logs accès admin et alertes. |
| **Backups** | RDS → S3 (chiffrés)             | Contiennent token + last4 (chiffrés) ; pas de PAN. |
| **Suppression** | API + Vault                   | RIPA : suppression référence token ; Vault : gestion cycle de vie PAN. |

---

## B. Inventaire de l’environnement CDE (Cardholder Data Environment)

Tout composant qui **stocke, traite ou transmet** des données de carte (même indirectement : token, last4) est listé ci‑dessous.

### Serveurs / VM / Conteneurs / Clusters

- **Instances de calcul hébergeant l’API Backend RIPA** (EC2 ou ECS/Fargate) dans le VPC production.
- **Toute VM ou conteneur** dans le même segment réseau que l’API et pouvant accéder aux données (token, last4) ou aux secrets utilisés pour appeler le Vault/Onafriq.
- **Cluster / workers** dédiés aux traitements asynchrones liés aux cartes (si applicable : jobs de réconciliation, callbacks Onafriq, etc.).

### Bases de données

- **Instance RDS (MySQL/MariaDB) principale** : stocke token + last4 + métadonnées cartes (lien utilisateur, statut, etc.).
- **Réplicas de lecture RDS** (si activés) : même périmètre (données dérivées de la base principale).
- **Snapshots et backups RDS** : inclus dans le CDE (données au repos, chiffrées).
- **Stockage des backups** (ex. S3 bucket dédié aux exports/snapshots RDS) : inclus ; chiffrement et accès restreint obligatoires.

### KMS / HSM

- **AWS KMS** (ou équivalent) utilisé pour :
  - Chiffrement des données au repos (RDS, volumes, S3).
  - Clés utilisées par l’application pour chiffrer des champs sensibles (hors PAN, qui n’est pas stocké).
- **Toute clé** utilisée dans le flux carte (chiffrement backups, secrets d’accès Vault/Onafriq si stockés côté cloud) est considérée dans le périmètre.

### Consoles d’administration

- **Console AWS** (ou équivalent GCP) utilisée pour gérer le VPC, RDS, KMS, security groups, IAM du projet RIPA.
- **Outils d’accès à la base** (client MySQL/MariaDB utilisé depuis bastion ou poste admin) lorsqu’ils accèdent à la base contenant token/last4.
- **Console / interface d’administration du prestataire de tokenisation** (accès aux tokens / politiques), si utilisée par l’équipe RIPA.

### CI/CD touchant le CDE

- **Pipelines** (GitHub Actions, GitLab CI, Jenkins, etc.) qui :
  - Déploient l’API ou des configurations sur les instances du VPC production.
  - Exécutent des migrations ou des scripts sur la base RDS.
  - Accèdent aux secrets (Secret Manager) utilisés pour le Vault, Onafriq, ou la base.
- **Repositories** contenant du code ou des configs qui déploient dans le VPC / vers RDS / vers KMS.

### Jump boxes / Bastions

- **Bastion (jump host)** permettant l’accès SSH ou RDP vers les instances du VPC (dépannage, maintenance).
- **Tunnel ou accès sécurisé** utilisé pour accéder à RDS (ex. port forwarding via bastion).

### Files / Queues (si utilisées)

- **SQS, Kafka, RabbitMQ ou tout autre bus** utilisé pour des messages contenant token, last4 ou références de transactions carte : considérés dans le CDE ; chiffrement et contrôle d’accès requis.
- **Fichiers temporaires ou batch** contenant token/last4 : à éviter ; si indispensables, stockage chiffré et suppression sécurisée après traitement.

### Résumé bullet points (inventaire exhaustif)

- Instances (EC2/ECS) exécutant l’API Backend dans le VPC production.
- Instance RDS principale + réplicas + snapshots + backups (S3 ou autre).
- AWS KMS (clés chiffrement données et backups).
- Consoles cloud (AWS/GCP) pour la gestion de l’infra RIPA.
- Clients d’accès à la base (via bastion ou réseau privé).
- Console / API d’administration du prestataire de tokenisation.
- Pipelines CI/CD qui déploient ou modifient l’application / la base / les secrets du CDE.
- Bastion / jump box pour accès admin au VPC.
- Outils de centralisation des logs (CloudWatch, SIEM) lorsqu’ils reçoivent des logs des composants CDE (les logs ne doivent pas contenir de PAN/CVV).
- Tout SQS/queue/file contenant des données carte (token/last4) ou des références permettant de les lier à un porteur.

---

## C. Déclaration de segmentation

**Périmètre CDE (in scope)**  
Le Cardholder Data Environment inclut l’ensemble des systèmes qui stockent, traitent ou transmettent des données de titulaire de carte ou des données d’authentification sensibles, ou qui peuvent affecter la sécurité de ces données. Chez RIPA, cela comprend : (1) les instances de calcul hébergeant l’API Backend dans le VPC de production ; (2) la base de données managée (RDS) et ses réplicas, snapshots et backups ; (3) le service de gestion des clés (KMS) utilisé pour le chiffrement des données au repos et des backups ; (4) les bastions ou chemins d’accès admin permettant d’accéder à ces composants ; (5) les pipelines CI/CD et les dépôts de code/config qui déploient ou modifient ces systèmes ; (6) les consoles et outils d’administration (cloud, base de données, prestataire de tokenisation) utilisés pour opérer cet environnement. RIPA ne stocke aucun PAN en clair ni CVV ; seuls un token et les quatre derniers chiffres du PAN sont stockés en base, le reste étant géré par un prestataire de tokenisation PCI compliant.

**Hors périmètre (out of scope)**  
Sont considérés hors CDE, sous réserve qu’ils ne reçoivent ni ne traitent de PAN, CVV ni de token permettant de retrouver le PAN : les front-ends (application mobile, backoffice) dès lors qu’ils n’envoient jamais de PAN en clair vers nos serveurs (saisie physique carte orientée directement vers le Vault) et n’affichent que last4 + token ; les systèmes de marketing, analytics ou support qui ne reçoivent aucune donnée carte ; les environnements de développement et de préproduction qui ne contiennent pas de données réelles de carte et sont isolés du VPC production ; les prestataires qui n’ont pas accès aux données carte (hébergeur n’administrant pas la base, outils de monitoring ne recevant pas de PAN/CVV dans les logs).

**Application de la segmentation**  
La séparation est assurée par : (1) un VPC dédié à la production avec des sous-réseaux distincts (segment application / base de données) et des security groups en « deny by default », la base de données étant en subnet privé sans accès internet direct ; (2) des règles réseau et IAM à privilèges minimaux, sans wildcard « * » sur les ressources sensibles ; (3) l’obligation de MFA et l’absence de comptes partagés pour les accès admin ; (4) l’utilisation d’un bastion ou d’un accès contrôlé pour toute connexion admin vers le VPC ; (5) la séparation logique et physique des clés (KMS) par rapport au stockage des données (RDS/S3) ; (6) des logs et alertes sur les accès admin et les accès aux données sensibles, avec rétention d’au moins un an. Cette segmentation permet de limiter le périmètre PCI aux seuls composants du CDE et de démontrer un contrôle approprié pour le SAQ D et un futur audit QSA.

---

## Références et réutilisation

- Ce document sert de **base de preuves** pour le SAQ D et les échanges avec un QSA.
- À mettre à jour à chaque évolution significative du flux (nouveaux composants, nouveaux tiers, nouveaux types de données).
- À associer aux politiques internes (chiffrement, IAM, logs, gestion des vulnérabilités, SDLC) décrites dans tes spécifications PCI DSS backend.

---

*Document : Scoping Pack PCI DSS – RIPA. À conserver dans le dossier de conformité.*
