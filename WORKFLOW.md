# 🚀 Workflow Git - Domaine de la Gardiole

## 📋 État actuel du projet

Le projet est actuellement sur la branche : **`claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu`**

Cette branche contient :
- ✅ Structure de base de données (SQLite)
- ✅ Dashboard administrateur EasyAdmin
- ✅ Site vitrine complet (front-office)
- ✅ Système de panier et commande
- ✅ Upload d'images pour les produits

---

## 🔄 Comment synchroniser votre travail local

### 1️⃣ **Première fois : Cloner le projet**

```bash
# Cloner le repository
git clone <url-du-repo>
cd ecomm-vigneron

# Se positionner sur la branche de développement
git checkout claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
```

### 2️⃣ **Récupérer les dernières modifications**

Avant de commencer à travailler, récupérez toujours les dernières modifications :

```bash
# Récupérer les modifications du serveur
git fetch origin

# Mettre à jour votre branche locale
git pull origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
```

### 3️⃣ **Faire vos modifications**

```bash
# 1. Travailler sur vos fichiers
# 2. Voir les fichiers modifiés
git status

# 3. Ajouter les fichiers modifiés
git add .

# 4. Commiter avec un message clair
git commit -m "Description claire de vos modifications"

# 5. Pousser sur le serveur
git push origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
```

---

## 🤝 Travailler ensemble sur la même branche

### **Workflow quotidien recommandé**

1. **Le matin (avant de commencer)** :
   ```bash
   git pull origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
   ```

2. **Pendant le travail** :
   - Faites vos modifications
   - Commitez régulièrement (petits commits)

3. **Avant de pousser** :
   ```bash
   # Récupérer les dernières modifications
   git pull origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu

   # Si pas de conflit, pousser
   git push origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
   ```

---

## ⚠️ Gérer les conflits

Si vous avez un conflit lors du `git pull` :

```bash
# 1. Git vous indiquera les fichiers en conflit
# 2. Ouvrez chaque fichier et résolvez les conflits manuellement
#    (cherchez les marqueurs <<<<<<, ======, >>>>>>)
# 3. Une fois résolu, ajoutez les fichiers
git add <fichier-résolu>

# 4. Finalisez le merge
git commit -m "Résolution des conflits"

# 5. Poussez
git push origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
```

---

## 📦 Nettoyer votre situation actuelle

Vous avez mentionné avoir une branche `feat-dashboard` locale. Voici comment la nettoyer :

### **Option 1 : Garder vos modifications**

```bash
# 1. Assurez-vous d'être sur feat-dashboard
git checkout feat-dashboard

# 2. Créer un patch de vos modifications
git diff claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu > mes-modifs.patch

# 3. Retourner sur la branche principale
git checkout claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu

# 4. Appliquer vos modifications
git apply mes-modifs.patch

# 5. Vérifier les changements
git status

# 6. Commiter
git add .
git commit -m "Ajout de twig/intl-extra et migrations"

# 7. Pousser
git push origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu

# 8. Supprimer feat-dashboard
git branch -D feat-dashboard
```

### **Option 2 : Recommencer à zéro**

```bash
# 1. Supprimer feat-dashboard sans garder les modifications
git checkout claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
git branch -D feat-dashboard

# 2. Récupérer la dernière version
git pull origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
```

---

## 🎯 Créer une branche `develop` pour le futur

Pour créer une vraie branche de développement partagée :

### **En local (vous)** :

```bash
# 1. Créer la branche develop à partir de la branche actuelle
git checkout claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
git checkout -b develop

# 2. La pousser (si les permissions le permettent)
git push -u origin develop

# 3. Désormais, travailler sur develop
git checkout develop
git pull origin develop
# ... vos modifications ...
git push origin develop
```

**Note** : Si vous ne pouvez pas pousser `develop` à cause des permissions (403), gardez la branche `claude/...` comme branche principale.

---

## 📝 Bonnes pratiques

### **Messages de commit clairs** :
```bash
✅ git commit -m "Add product image upload feature"
✅ git commit -m "Fix category slug routing"
✅ git commit -m "Update ProductController for better error handling"

❌ git commit -m "fix"
❌ git commit -m "updates"
❌ git commit -m "wip"
```

### **Commits fréquents** :
- Commitez après chaque fonctionnalité complète
- Ne commitez pas de code cassé
- Testez avant de pousser

### **Pull avant push** :
```bash
# TOUJOURS faire ça avant de pousser
git pull origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
git push origin claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu
```

---

## 🔍 Commandes utiles

```bash
# Voir l'historique des commits
git log --oneline -10

# Voir les différences
git diff

# Voir les branches
git branch -a

# Annuler les modifications locales (ATTENTION : perte de données)
git checkout -- <fichier>

# Annuler tous les changements locaux
git reset --hard HEAD

# Voir qui a modifié quoi
git blame <fichier>
```

---

## 🆘 En cas de problème

1. **Ne pas paniquer** 😊
2. Faire un backup de vos fichiers importants
3. Partager l'erreur Git exacte
4. Utiliser `git status` pour comprendre l'état

**Commande de secours** (sauvegarde avant tout reset) :
```bash
# Créer une copie de secours
git stash save "backup avant reset"

# Si besoin de revenir
git stash pop
```

---

## 📞 Contact

Pour toute question sur le workflow Git, n'hésitez pas !

**Branche principale actuelle** : `claude/setup-ecommerce-database-01AN7DPfNayqYRfJAZj68dRu`
