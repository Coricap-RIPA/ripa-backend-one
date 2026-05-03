# RIPA — Périmètre B2C / B2B et sécurité SQL (audit)

**Objectif** : cadrer ce qui relève de l’app consommateur (B2C) vs l’existant entreprise / douanes (B2B), et documenter les risques **injection SQL** sans casser les flux actuels.

---

## 1. Scoping B2C vs B2B

| Zone | Emplacement | Rôle | Consommateurs |
|------|-------------|------|----------------|
| **B2C — API application** | `application/controllers/api/Apiapp.php`, routes `api/app/*` | Wallet, KYC, cartes, MM, banque, paiements C2C, notifications, profil | App mobile React Native / Expo |
| **B2C — Modèles dédiés** | `User_model`, `Kyc_model`, `Notification_model`, `Log_utilisateur_application_model`, `Api_log_model` | Données utilisateur app, logs | Utilisés uniquement par `Apiapp` (et chemins alignés) |
| **B2B / legacy** | Autres contrôleurs (`application/controllers/*.php` hors `api/`), modèles type `Trafic_model`, `Facture_model`, `Paiement_model`, `Entreprise_model`, etc. | Backoffice douanes, entreprises, facturation historique | Utilisateurs web backoffice, pas l’API `api/app` |

**Règle produit** : toute évolution **carte / PIN / paiement consommateur** = **B2C** (`Apiapp` + specs PCI). Toute évolution **facturation douanière / entreprise** = **B2B** (hors périmètre app mobile sauf lien futur « Payez avec RIPA »).

**Portail marchand (nouveau)** : spécifications détaillées et phases de codage dans **`docs/SPECS-PORTAIL-MARCHAND-B2B.md`** (tables `business_*`, validation RIPA, dossiers `business/`).

**Frontières techniques** :
- Les routes **`api/app/*`** ne doivent pas dépendre des modèles B2B non audités pour le PCI.
- Le backoffice B2B peut coexister ; la **tokenisation / vault** reste du côté B2C jusqu’à intégration explicite B2B.

---

## 2. État de sécurité SQL — API B2C (`Apiapp.php`)

**Constat** : les accès base passent surtout par le **Query Builder** CodeIgniter (`get_where`, `where` + table, `insert`, `update`) avec **valeurs en second argument** : l’échappement est géré par le driver → **risque d’injection SQL faible** sur ces chemins.

**Renforts appliqués (sans changer le comportement nominal)** :
- **`transactions/history`** : `type` limité à `all|sent|received` ; `date_from` / `date_to` validés au format **`Y-m-d`** via **`ripa_validate_date_ymd()`** dans `custom_helper.php` (dates invalides ignorées comme filtre, comme une chaîne vide).
- Les paramètres **`limit` / `offset`** étaient déjà castés en **entiers**.

**Bonnes pratiques à maintenir** :
- Ne pas introduire de `$this->db->query("... $variable ...")` avec entrées utilisateur.
- Préférer `$this->db->where('col', $value)` ou requêtes préparées avec bindings.

---

## 3. Modèles B2B / legacy — risques identifiés

Plusieurs modèles utilisent **`$this->db->query("... $id_entreprise ...")`** avec interpolation de variables PHP. Même si souvent des **entiers** issus du backoffice, ce n’est **pas** la même garantie que des bindings explicites.

**Fichiers notables (non modifiés dans ce passage pour éviter régression backoffice)** :
- `Trafic_model.php` — nombreuses requêtes avec `$id_entreprise`, `$id_bureau_douane`, `$annee`, `$type`
- `Paiement_model.php`, `Facture_model.php`, `Couple_model.php`, `Couple_article_model.php`, etc.

**Feuille de route recommandée** (par étapes, avec tests manuels / QA sur backoffice) :
1. Remplacer par **Query Builder** (`where`, `where_in`) ou **`$this->db->query($sql, array($bindings))`** avec **placeholders**.
2. Forcer **`(int)`** sur les identifiants numériques en entrée des méthodes publiques.
3. Valider **année** (`preg_match` / `(int)` + plage) et **énumérations** (`type_operation`, etc.).

---

## 4. Autres attaques (rappel `Apiapp`)

| Risque | Niveau actuel |
|--------|----------------|
| **Injection SQL** (B2C) | Faible (Query Builder) ; dates / type historique renforcés |
| **XSS** | API JSON : surtout côté clients ; ne pas renvoyer du HTML non échappé depuis le back |
| **Auth** | JWT + PIN sur routes sensibles ; conserver HTTPS en prod |
| **Brute force PIN / OTP** | À renforcer plus tard (rate limit, lockout) — hors scope immédiat |
| **Mass assignment** | `Apiapp` construit des tableaux d’insert explicites — continuer ainsi |

---

## 5. Synthèse « Compris chef »

- **B2C** = `Apiapp` + routes `api/app/*` + modèles listés §1 — c’est le périmètre **prioritaire** sécurité + PCI.
- **B2B** = reste du projet — **scopé séparément** ; dette SQL documentée §3, migration progressive sans big bang.
- **Changements livrés** : validation **dates** + **type** sur l’historique transactions ; helper réutilisable **`ripa_validate_date_ymd`** — **ne bloque pas** l’app si les clients envoyaient des dates invalides (filtre ignoré).

*Document à mettre à jour après chaque vague de durcissement SQL sur les modèles legacy.*
