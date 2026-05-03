# Rate limiting — API `api/app` (Apiapp)

## Fichiers

| Fichier | Rôle |
|---------|------|
| `application/config/ripa_rate_limit.php` | Activation, paliers, mapping méthode → palier, IPs bypass (dev) |
| `application/libraries/Rate_limit_library.php` | Comptage + réponse HTTP 429 |
| `application/cache/rate_limit/` | Fichiers `.cnt` (un par fenêtre IP + palier) |

## Principe

- Chaque requête vers **`Apiapp`** (sauf **OPTIONS**, déjà sortie avant) incrémente un compteur.
- Clé = **hash(IP + palier + numéro de fenêtre temporelle)**.
- Fenêtre **fixe** : `period` secondes (par défaut **60 s**).
- Si le compteur dépasse **`max`** pour ce palier → **HTTP 429** + en-tête **`Retry-After`** (secondes = `period`).

## Paliers

- **`default`** : la plupart des endpoints (ex. 200 req / min / IP).
- **`auth`** : `register`, `login`, `verify_otp`, `resend_otp`, `verify_pin` (ex. 40 / min / IP).
- **`sensitive`** : `payment_submit`, `cards_register` (ex. 30 / min / IP).

À ajuster dans **`ripa_rate_limit.php`** selon la charge et les tests.

## Désactiver temporairement

```php
$config['ripa_rate_limit_enabled'] = false;
```

## Production

- Sur plusieurs serveurs, le stockage fichiers est **par machine** ; pour un plafond global, prévoir **Redis** / **Memcached** plus tard.
- Retirer ou restreindre **`ripa_rate_limit_bypass_ips`** en prod si ce n’est pas voulu.

## Application mobile (`mobileapp/src/services/api.js`)

- Réponses **HTTP 429** : lecture de **`Retry-After`**, message d’erreur enrichi (délai suggéré en français).
- Propriétés sur l’`Error` jetée : **`status`**, **`isRateLimited`**, **`retryAfterSeconds`**, **`raw`**.
- Exports utiles : **`isRateLimitError(err)`**, **`getRateLimitUserMessage(err)`**, **`parseRetryAfterSeconds`**, **`HTTP_STATUS_TOO_MANY_REQUESTS`**.
- Les écrans qui font déjà `catch (e) { … e.message … }` affichent automatiquement le texte adapté.
