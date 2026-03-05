# API Application – Routes (contrôleur unique Apiapp)

Toutes les routes de l’**application mobile** passent par le contrôleur **`api/Apiapp.php`**.  
Authentification : **JWT** (librairie `JWT_Library`), token valide **1 an**.

**Base URL** : `{BASE_URL}/api/app/`

---

## Auth

| Méthode | Route | Description |
|--------|--------|-------------|
| POST | `api/app/register` | Inscription : **nom**, **post_nom**, **prenom**, **tel** (avec + et indicatif pays), **pin** (5 chiffres). Données personnelles stockées chiffrées en base. |
| POST | `api/app/verify-otp` | Vérification OTP → retourne token + user (connexion auto) |
| POST | `api/app/resend-otp` | Renvoyer un code OTP |
| POST | `api/app/login` | Connexion (phone avec +, pin 5 chiffres) |
| GET  | `api/app/verify-token` | Vérifier le token (Header: `Authorization: Bearer {token}`) |

---

## Profil

| Méthode | Route | Description |
|--------|--------|-------------|
| GET  | `api/app/profile` | Profil utilisateur (token requis) |

---

## Comptes mobile money

| Méthode | Route | Description |
|--------|--------|-------------|
| GET  | `api/app/accounts` | Liste des comptes (token requis) |
| POST | `api/app/accounts/add` | Ajouter un compte (token requis) |
| GET  | `api/app/accounts/types` | Liste des types (Airtel, Orange, etc.) – public |

---

## Cartes

| Méthode | Route | Description |
|--------|--------|-------------|
| GET  | `api/app/cards` | Liste des cartes (token + last4) (token requis) |
| POST | `api/app/cards/register` | Enregistrer une carte (PAN/CVV → Vault mock → token + last4) (token requis) |

---

Dans l’app, configurer l’**URL de base** sur `https://votre-domaine.com/api/app` (ou `http://localhost/ripa/api/app` en dev).

## Secrets (backend)

Les clés sont lues depuis le fichier **`.env`** à la racine du projet (non versionné) :

- **RIPA_ENCRYPTION_KEY** : clé de chiffrement des données personnelles (custom_helper).
- **RIPA_JWT_SECRET_KEY** : secret pour la signature des tokens JWT.

Copier `.env.example` en `.env` et remplir les valeurs. En production, utiliser des valeurs fortes et ne jamais commiter `.env`.
