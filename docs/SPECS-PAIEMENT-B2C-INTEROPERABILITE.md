# RIPA B2C — Spécifications Paiement & Interopérabilité (Phase 1)

**Objectif** : Mettre au clair les specs du flux de paiement (Scan & Pay, C2C, choix source/destination) avant codage.  
**Statut** : **Validé** — décisions ci-dessous actées ; prêt pour détail des routes et codage.

### Décisions validées

| # | Décision | Choix retenu |
|---|----------|--------------|
| 1 | Carte physique en réception | **OUI** — Pas de crédit sur carte physique ; destinataire reçoit uniquement sur virtuelle / MM / compte bancaire. |
| 2 | QR : id en clair ou token | **Token** (discretion). Si le moyen de réception choisi n’est pas trouvé pour ce token, le backend renvoie **les moyens disponibles** pour ce destinataire ; l’app propose à l’utilisateur de choisir parmi ceux-là. |
| 3 | Contact agenda | **OK** — Envoi du **numéro normalisé uniquement** au backend pour le lookup ; **aucune liste de contacts stockée côté serveur**. |
| 4 | Ordre du flux | **OUI** — Source → moyen de réception → destinataire (puis montant → confirmation). |

---

## 1. Contexte : RIPA comme écosystème de paiement

RIPA permet à un utilisateur de **payer** avec plusieurs types de comptes et de **recevoir** sur plusieurs types de comptes. L’interopérabilité = toute combinaison source → destination doit être gérée de façon claire et sécurisée (PCI DSS pour les cartes).

---

## 2. Moyens de paiement (sources) et de réception (destinations)

| Type | Côté **payer (source)** | Côté **recevoir (destination)** |
|------|--------------------------|----------------------------------|
| **Carte virtuelle** | Débit du solde de la carte virtuelle (déjà en place : recharge/retrait MM ↔ carte) | Crédit du solde de la carte virtuelle du destinataire |
| **Carte physique** | Débit via token Vault (pas de débit “solde” comme la virtuelle ; à définir : prélèvement réel ou simulé) | Pas de “crédit” direct sur carte physique ; on peut envoyer vers **un autre moyen** du destinataire (MM, bancaire, virtuelle) |
| **Mobile Money** | Débit du compte MM enregistré (Airtel, M-Pesa, etc.) | Crédit vers le numéro MM du destinataire (s’il a enregistré ce numéro) |
| **Compte bancaire** | Débit du compte bancaire enregistré (IBAN / numéro) | Crédit vers le compte bancaire du destinataire (s’il a enregistré ce compte) |

**Point important (validé)** :  
- **Carte physique** : en réception, on ne “crédite” pas la carte physique ; le destinataire reçoit sur un de ses autres moyens (MM, bancaire, carte virtuelle). **Destinations possibles** = carte virtuelle, MM, compte bancaire uniquement.

---

## 3. Matrice d’interopérabilité (qui paie avec quoi → qui reçoit sur quoi)

| Source \ Destination | Carte virtuelle | Carte physique | Mobile Money | Compte bancaire |
|---------------------|-----------------|----------------|--------------|------------------|
| **Carte virtuelle** | ✅ C2C virtuelle | ❌ (pas de crédit carte physique) | ✅ | ✅ (si lien carte↔compte ou transfert simulé) |
| **Carte physique** | ✅ | ❌ | ✅ | ✅ |
| **Mobile Money** | ✅ (déjà : recharge carte) | ❌ | ✅ C2C MM | ✅ (simulation) |
| **Compte bancaire** | ✅ (simulation) | ❌ | ✅ (simulation) | ✅ C2C bancaire |

- **✅** = à implémenter (ou déjà en place pour recharge/retrait carte ↔ MM).  
- **❌** = hors scope ou non applicable (ex. pas de crédit direct sur carte physique).  
- Les cases **simulation** = même logique métier que les autres (débit source, crédit destination, logs, api_logs) mais sans appel réel à une banque/Onafriq réel en Phase 1.

Cela donne un **flux unique** côté app :  
**Choisir une source** (parmi les 4 types) → **Choisir une destination** (parmi virtuelle, MM, bancaire) → **Choisir le destinataire** (QR ou contact) → **Montant + confirmation** → Backend vérifie + enregistre + notifie.

---

## 4. Flux normal du paiement (côté utilisateur)

1. **Choisir le compte source (avec lequel on paie)**  
   - Liste des comptes enregistrés : cartes (virtuelle + physique), comptes Mobile Money, comptes bancaires.  
   - Affichage : type + masque (ex. ****1234, dernier 4 chiffres carte, numéro MM masqué).  
   - Un seul choix : “Je paie avec **ce** compte”.

2. **Choisir le moyen de réception du destinataire**  
   - L’utilisateur choisit : “Le destinataire recevra sur : **Carte virtuelle** / **Mobile Money** / **Compte bancaire**”.  
   - Cela permet d’afficher ensuite uniquement les destinataires qui ont **au moins ce moyen enregistré** (vérifié par le backend).

3. **Choisir le destinataire**  
   - **Option A — Scan d’un code QR**  
     - Le QR contient **un token court** (pas d’id en clair), ex. `ripa://p/{token}`.  
     - L’app envoie au backend : **token** + type de réception choisi (virtuelle / MM / bancaire).  
     - Backend répond : destinataire trouvé oui/non ; si trouvé : **a-t-il ce moyen enregistré** (oui/non) + infos affichables.  
     - **Si le moyen choisi n’est pas enregistré** pour ce destinataire : le backend renvoie la **liste des moyens disponibles** (ex. `["mobile_money", "compte_bancaire"]`) ; l’app propose à l’utilisateur de **choisir parmi ces moyens** (ou de revenir en arrière).  
   - **Option B — Choix dans l’agenda (contacts du téléphone)**  
     - L’utilisateur choisit un contact (numéro). L’app envoie **uniquement le numéro normalisé** (ex. +243…) au backend ; **aucune liste de contacts n’est stockée côté serveur**.  
     - Backend : lookup par `phone` / `phone_hash` ; si trouvé, vérifie le moyen de réception demandé ; retourne trouvé + infos (et si moyen absent : liste des moyens disponibles, même logique que QR).

4. **Saisie du montant + (optionnel) libellé**  
   - Montant obligatoire ; libellé/message optionnel (pour historique et notification).

5. **Confirmation (PIN ou biométrie)**  
   - Vérification PIN 5 chiffres (déjà en place ailleurs).  
   - Puis envoi final au backend.

6. **Traitement backend**  
   - Vérifier solde/éligibilité source (carte virtuelle : solde suffisant ; MM/bancaire : simulation).  
   - Vérifier destinataire + moyen de réception enregistré.  
   - Enregistrer la transaction (nouvelle table ou tables dédiées : `transaction_paiement_utilisateur` ou équivalent).  
   - Débiter la source, créditer la destination (simulation ou appels services Vault/Onafriq selon le cas).  
   - Logger dans `log_utilisateur_application` et `api_logs` si appels externes.  
   - Notifier le destinataire (notification in-app : “Vous avez reçu X $ de …”).

7. **Retour à l’utilisateur**  
   - Succès : message clair, récap (montant, destinataire, moyen de réception), lien vers détail transaction.  
   - Erreur : message explicite (solde insuffisant, destinataire non trouvé, moyen de réception non enregistré, etc.).

---

## 5. Contenu du code QR (validé)

- **Format** : **token uniquement** (discretion), ex. `ripa://p/{token_court}`.  
  - Table `ripa_payee_token` : `id_utilisateur_application`, `token` (unique), `date_expiration`. Pas d’id utilisateur en clair dans le QR.  
- **Lookup par token** : l’app envoie le token + le type de réception choisi (virtuelle / MM / bancaire).  
- **Réponse backend** :  
  - Destinataire trouvé : oui/non.  
  - Si oui : `has_requested_destination` (le moyen demandé est-il enregistré ?) + infos affichables (nom masqué, libellé du moyen si disponible).  
  - **Si le moyen demandé n’est pas enregistré** : retourner **`available_destination_types`** (ex. `["mobile_money", "compte_bancaire"]`) ; l’app propose à l’utilisateur de **sélectionner un de ces moyens** à la place (sans re-scanner le QR).
- **Backend** : endpoint du type `POST /api/app/payee/lookup` (body : `token` + `destination_type`). Réponse : `found`, `has_requested_destination`, `available_destination_types` (si moyen demandé absent), `display_name`, `destination_label` (si moyen trouvé), etc.

---

## 6. Vérification “contact trouvé + moyen enregistré” (validé)

- **Entrée** : **token** (QR) **ou** numéro de téléphone normalisé (contact agenda). **Aucune liste de contacts stockée côté serveur.**  
- **Backend** :  
  1. Trouver l’utilisateur RIPA (par token → table `ripa_payee_token`, ou par `phone_hash`).  
  2. Si non trouvé → réponse “Destinataire non inscrit sur RIPA”.  
  3. Si trouvé : lister ses moyens de réception actifs (carte virtuelle, MM, compte bancaire).  
  4. Comparer au type demandé : si le moyen demandé est dans la liste → `has_requested_destination: true` + infos affichables.  
  5. **Si le moyen demandé n’est pas enregistré** : retourner **`available_destination_types`** (liste des moyens qu’il a) ; l’app propose à l’utilisateur de choisir parmi ceux-là.  
- **Côté app** : indicateurs “trouvé”, “prêt à recevoir sur [X]” ; si moyen demandé absent, afficher “Ce destinataire peut recevoir sur : [liste]” et proposer la sélection.

---

## 7. Tables / endpoints à prévoir (résumé)

- **Tables**  
  - Déjà en place : `utilisateur_application`, `compte_mobile_money_utilisateur_application`, `compte_bancaire_utilisateur_application`, `carte_utilisateur_application`, `transaction_carte_mobile_money_utilisateur`.  
  - À prévoir : une table (ou schéma) pour les **paiements C2C / Scan & Pay** (ex. `transaction_paiement_ripa` : id_paiement, id_source_utilisateur, type_source, id_source_compte/carte, id_destinataire_utilisateur, type_destination, id_destination_compte/carte, montant_c, reference_c, statut, date_creation, etc.).  
  - **Requis** : `ripa_payee_token` (token court pour QR : id_utilisateur_application, token, date_expiration) — pas d’id en clair dans le QR.

- **Endpoints implémentés (api/app) — GET payment/sources, GET payee/token, POST payee/lookup, POST payment/submit**  
  - Liste unifiée “mes comptes” (sources : cartes + MM + bancaires) pour le sélecteur “Payer avec”.  
  - Lookup destinataire : par **token** (QR) ou par **téléphone normalisé** (contact) + type de réception ; réponse “trouvé + moyen enregistré” ou “trouvé + liste des moyens disponibles” si le moyen choisi n’est pas enregistré.  
  - Soumission paiement : source, destinataire, type réception, montant, PIN → débit/crédit + enregistrement + notification.  
  - (Plus tard) Paiement facture : lien “Payez avec RIPA” (facture B2B) → même flux avec facture_id et référence.

---

## 8. Design et indicateurs (UX)

- **Moderne et lisible** : écran “Payer” en étapes (stepper ou wizard) : 1) Source → 2) Réception → 3) Destinataire (QR ou contact) → 4) Montant → 5) Confirmation.  
- **Indicateurs** :  
  - Compte source : icône type (carte / MM / bancaire) + masque + “Solde : X $” si carte virtuelle.  
  - Destinataire : après lookup, badge “RIPA – Carte ****1234” ou “RIPA – M-Pesa” avec indicateur vert “Prêt à recevoir”.  
  - États d’erreur : “Contact non inscrit”, “Ce destinataire n’a pas de [X] enregistré”. **Si le moyen choisi n’est pas disponible** : afficher “Ce destinataire peut recevoir sur : [liste]” et proposer la sélection parmi ces moyens (sans re-scanner / re-saisir).  
- **Notifications** : après paiement réussi, notification in-app pour le destinataire (“Vous avez reçu X $ de [nom]”) et enregistrement dans l’historique des deux côtés.

---

## 9. Récap pour la suite (validé)

| Thème | Décision validée |
|-------|------------------|
| Sources | Carte virtuelle, carte physique, Mobile Money, compte bancaire. |
| Destinations | Carte virtuelle, Mobile Money, compte bancaire (pas de crédit carte physique). |
| Flux | Source → moyen de réception → destinataire (QR ou contact) → montant → confirmation PIN → backend + notifie. |
| QR | **Token uniquement** (pas d’id en clair). Si le moyen choisi n’est pas trouvé : backend renvoie **les moyens disponibles** ; l’app propose à l’utilisateur de choisir parmi eux. |
| Contact agenda | **Numéro normalisé uniquement** au backend ; **aucune liste de contacts stockée côté serveur** ; même logique “moyens disponibles” si moyen demandé absent. |
| Backend | Table `ripa_payee_token` ; lookup payee (token ou phone) avec `available_destination_types` ; table(s) transaction paiement ; logs + api_logs ; notifications. |
| UX | Wizard, indicateurs “trouvé / prêt à recevoir”, proposition des moyens disponibles si besoin, messages d’erreur clairs. |

**Spec validée.** Backend implémenté (routes ci-dessus, tables `ripa_payee_token`, `transaction_paiement_ripa`). Prochaine étape : écrans app "Payer" (wizard source → réception → destinataire → montant → confirmation).

---

*Document créé pour alignement avant implémentation Phase 1 — Scan & Pay, C2C, paiement facture.*
