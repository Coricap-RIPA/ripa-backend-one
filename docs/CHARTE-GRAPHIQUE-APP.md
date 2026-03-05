# Charte graphique – Application mobile RIPA

À utiliser pour toute l’**application mobile** (React Native / Expo) : couleurs, polices et hiérarchie visuelle.

---

## Couleurs

| Rôle        | Code hex   | Usage recommandé                          |
|------------|------------|-------------------------------------------|
| **Primaire**   | `#270345`  | Fond principal, headers, éléments de marque, boutons principaux |
| **Secondaire**| `#A59AF7`  | Accents, liens, boutons secondaires, highlights |
| **Tertiaire**  | `#FFFFFF`  | Texte sur fond sombre, fond de cartes, zones claires |
| **Quatrième** | `#000000`  | Texte principal sur fond clair, bordures si besoin |

**En pratique :**
- Fond d’écran / thème principal : `#270345`.
- Boutons d’action secondaire, onglets, icônes actives : `#A59AF7`.
- Cartes, modales, champs de formulaire (fond) : `#FFFFFF`.
- Texte principal : `#000000` sur fond clair, `#FFFFFF` sur fond `#270345`.

---

## Polices

| Rôle        | Police                      | Usage recommandé                          |
|------------|-----------------------------|-------------------------------------------|
| **Primaire**   | **Microgramma Bold Extended** | Titres, logos, en-têtes, chiffres importants (ex. soldes) |
| **Secondaire** | **Roboto**                  | Corps de texte, labels, listes, formulaires |

**Intégration React Native / Expo :**
- **Microgramma Bold Extended** : charger en font personnalisée (ex. `expo-font` + fichier `.ttf` / `.otf`). À placer dans `assets/fonts/` et référencer dans le thème.
- **Roboto** : disponible par défaut sur Android ; sur iOS/Expo, charger explicitement si besoin (ex. `@expo-google-fonts/roboto` ou fichier dans `assets/fonts/`).

---

## Récap pour le code (design tokens)

```js
// Exemple structure thème app (à adapter dans le projet)
export const colors = {
  primary: '#270345',
  secondary: '#A59AF7',
  tertiary: '#FFFFFF',
  fourth: '#000000',
};

export const fonts = {
  primary: 'MicrogrammaBoldExtended',   // nom après chargement
  secondary: 'Roboto',
};
```

Cette charte s’applique à **l’application mobile** ; le backoffice peut avoir sa propre charte si différente.
