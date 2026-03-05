# Ordre de développement RIPA

Document de référence pour la séquence de travail du projet.

---

## 1. Ordre des livrables

1. **Application mobile (React Native / Expo)**  
   - On commence par coder l’app.  
   - On avance **fonctionnalité par fonctionnalité**, écran par écran.  
   - Design selon la **charte graphique** : `docs/CHARTE-GRAPHIQUE-APP.md`.

2. **Backoffice et Backend**  
   - Une fois les fonctionnalités de l’app définies et avancées, on **revoit le backoffice** et le **backend**.  
   - On les **aligne aux spécifications** et à la **conformité PCI DSS**.  
   - Backend et application **communiquent par tokenisation** (tokens + last4, jamais PAN en clair).

---

## 2. Communication Backend ↔ Application

- **Tokenisation** : toutes les données carte échangées entre l’app et le backend le sont sous forme de **tokens** (et éventuellement last4 pour affichage).  
- Aucun **PAN** ni **CVV** ne transite ou n’est stocké côté RIPA ; le prestataire Vault gère le PAN.  
- Voir `docs/PCI-DSS-RESUME-POUR-DEV.md` et `.cursor/rules/ripa-golden-rules.mdc`.

---

## 3. Références

- Règles d’or (PCI, AWS, GitHub) : `.cursor/rules/ripa-golden-rules.mdc`  
- Charte graphique app : `docs/CHARTE-GRAPHIQUE-APP.md`  
- PCI DSS (résumé dev) : `docs/PCI-DSS-RESUME-POUR-DEV.md`  
- Scoping pack PCI : `docs/PCI-DSS-SCOPING-PACK.md`
