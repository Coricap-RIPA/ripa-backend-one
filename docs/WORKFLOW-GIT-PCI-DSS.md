# Workflow Git – Protection de la branche main (PCI DSS)

Selon les recommandations PCI DSS, les modifications ne doivent **pas** être poussées directement sur la branche `main`. Tout passe par une **branche de travail** puis une **Pull Request (PR)** pour revue avant fusion.

---

## 1. Principe

- **`main`** = branche protégée, reflet de la version « validée » du code.
- **Branches de travail** (ex. `feature/kyc`, `fix/login`) = où vous développez.
- **Pull Request** = demande de fusion d’une branche vers `main`, avec revue (et éventuellement validation) avant merge.

---

## 2. Configuration sur GitHub (une fois par dépôt)

### 2.1 Activer la protection de la branche `main`

1. Sur GitHub : **Settings** du dépôt → **Branches** (menu gauche).
2. Dans **Branch protection rules**, cliquez **Add rule** (ou **Add branch protection rule**).
3. Renseignez :
   - **Branch name pattern** : `main`
   - **Require a pull request before merging** : coché
     - Optionnel : **Require approvals** (ex. 1) si plusieurs personnes.
   - **Do not allow bypassing the above settings** : coché (même les admins passent par une PR).
   - **Restrict who can push to matching branches** : laisser vide sauf si vous voulez limiter à certaines personnes.
4. **Create** / **Save changes**.

Dès que la règle est active, **personne** (y compris vous) ne peut pusher directement sur `main`. Seul un **merge via Pull Request** est possible.

### 2.2 (Optionnel) Exiger des statuts ou des checks

- **Require status checks to pass** : si vous avez des CI (tests, lint), vous pouvez exiger qu’ils soient verts avant de merger.
- Pour RIPA, vous pouvez laisser désactivé au début et l’activer plus tard.

---

## 3. Workflow au quotidien (côté développeur)

### 3.1 Toujours partir de `main` à jour

```bash
git checkout main
git pull origin main
```

### 3.2 Créer une branche pour votre travail

Nommage conseillé : `feature/nom-fonctionnalite` ou `fix/nom-correction`.

```bash
git checkout -b feature/ajout-ecran-kyc
# ou
git checkout -b fix/erreur-500-kyc-submit
```

### 3.3 Travailler, committer, pousser la branche

```bash
# Faire vos modifications dans le code...

git add .
git commit -m "feat(kyc): formulaire en 5 étapes avec caméra"
git push origin feature/ajout-ecran-kyc
```

Vous poussez sur **la branche**, pas sur `main`. GitHub acceptera car `main` est protégée, pas votre branche.

### 3.4 Ouvrir une Pull Request

1. Sur GitHub, un bandeau propose **Compare & pull request** après le push.
2. Ou : **Pull requests** → **New pull request**.
3. **Base** : `main` ← **Compare** : `feature/ajout-ecran-kyc`.
4. Titre et description clairs, puis **Create pull request**.

### 3.5 Revoir et merger

- Vous (ou un autre responsable) révisez le code dans l’onglet **Files changed**.
- Si tout est bon : **Merge pull request** → **Confirm merge**.
- Optionnel : supprimer la branche après merge (bouton **Delete branch**).

### 3.6 Repasser sur `main` en local

```bash
git checkout main
git pull origin main
```

Votre branche peut être supprimée en local si vous voulez :

```bash
git branch -d feature/ajout-ecran-kyc
```

---

## 4. Récapitulatif

| Action              | Où / Comment                                      |
|---------------------|---------------------------------------------------|
| Développer          | Toujours dans une branche (ex. `feature/xxx`)     |
| Push                | Uniquement vers cette branche (`push origin feature/xxx`) |
| Mettre dans `main`  | Uniquement via Pull Request puis Merge sur GitHub |
| Protéger `main`     | Règle de protection sur GitHub (voir § 2)         |

---

## 5. Appliquer à vos deux dépôts

- **Backend (CodeIgniter)** : configurer la protection de `main` comme en § 2, et utiliser le workflow § 3 pour chaque évolution.
- **Mobile (mobileapp)** : idem sur le dépôt GitHub de l’app mobile.

Ainsi, aucun push direct sur `main`, conformément à la recommandation PCI DSS.
