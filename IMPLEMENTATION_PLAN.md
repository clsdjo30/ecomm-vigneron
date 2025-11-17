# 📋 Plan d'Implémentation - E-commerce Domaine de la Gardiole

## 📊 Analyse du Template

Après analyse du fichier `docs/store-structure/template.md`, voici le découpage en **5 phases cohérentes** :

---

## 🎯 PHASE 1 : Blog Complet (Priorité Haute)
**Durée estimée** : 3-4h
**Complexité** : ⭐⭐⭐⭐

### Objectifs :
Créer un système de blog complet pour publier des actualités du domaine

### Tâches :
1. **Entités Blog**
   - ✅ Entity `Post` (title, slug, content, excerpt, featuredImage, createdAt, updatedAt, isPublished)
   - ⚠️ **Note** : Category existe déjà (vins) → créer `BlogCategory` pour éviter les conflits
   - ✅ Entity `BlogCategory` (id, name, slug)
   - ✅ Entity `Tag` (id, name, slug) - optionnel mais recommandé
   - Relations : Post ManyToOne BlogCategory, Post ManyToMany Tag

2. **Installation FOSCKEditorBundle**
   - Installer le bundle : `composer require friendsofsymfony/ckeditor-bundle`
   - Configurer CKEditor pour l'éditeur WYSIWYG
   - Upload d'images dans le contenu

3. **Dashboard EasyAdmin**
   - CRUD `PostCrudController` (avec CKEditorField pour content)
   - CRUD `BlogCategoryCrudController`
   - CRUD `TagCrudController`
   - Section "Blog" dans le menu admin
   - Filtres : isPublished, catégories, dates
   - Upload image featured via ImageField

4. **Front-Office**
   - Route `/actualites` : Liste des articles publiés (pagination)
   - Route `/actualites/{slug}` : Article complet
   - Route `/actualites/categorie/{slug}` : Articles par catégorie
   - Route `/actualites/tag/{slug}` : Articles par tag (optionnel)
   - Widgets sidebar : derniers articles, catégories
   - Design Bootstrap 5 avec cartes

5. **SEO**
   - Meta title et description
   - URLs propres via slug
   - OpenGraph (optionnel)

### Dépendances :
- Aucune (peut être fait en premier)

---

## 🎯 PHASE 2 : Newsletter & Contact Amélioré (Priorité Moyenne)
**Durée estimée** : 2-3h
**Complexité** : ⭐⭐⭐

### Objectifs :
Gérer les inscriptions newsletter et améliorer le formulaire de contact existant

### Tâches :

#### A. Newsletter
1. **Entity `NewsletterSubscriber`**
   - Champs : email (unique), createdAt, isConfirmed, confirmationToken
   - Validation email unique

2. **Dashboard EasyAdmin**
   - CRUD lecture seule pour `NewsletterSubscriber`
   - Colonnes : email, date inscription, statut confirmation
   - Export CSV (optionnel)

3. **Front-Office**
   - Formulaire d'inscription (email + bouton)
   - Route `POST /newsletter/subscribe`
   - Double opt-in optionnel : `GET /newsletter/confirm/{token}`
   - Composant réutilisable `newsletter/_form.html.twig`
   - Messages de confirmation Bootstrap

#### B. Contact Amélioré
1. **Entity `ContactMessage`**
   - ⚠️ **Attention** : Le formulaire de contact existe déjà !
   - Modifier l'existant pour ajouter : phone, subject (enum), isRead
   - Sauvegarder en BDD au lieu de juste envoyer un email

2. **Dashboard EasyAdmin**
   - CRUD `ContactMessageCrudController`
   - Vue liste : nom, email, téléphone, sujet, date, statut lu/non lu
   - Vue détail : message complet + formulaire de réponse
   - Action "Marquer comme lu"
   - Envoi de réponse via Symfony Mailer

3. **Front-Office**
   - Mise à jour du formulaire contact existant (`src/Controller/StaticController.php`)
   - Ajout champs : phone, subject (select avec 4 options)
   - Validation Symfony
   - Honeypot anti-spam
   - Sauvegarder en BDD + envoyer email admin

### Dépendances :
- Symfony Mailer (déjà installé ✅)

---

## 🎯 PHASE 3 : Refonte Design Complet (Priorité Haute)
**Durée estimée** : 4-5h
**Complexité** : ⭐⭐⭐⭐⭐

### Objectifs :
Reproduire exactement les 10 composants du design d'après les maquettes PNG

### Tâches :

1. **Navbar** (`navbar.png`)
   - ✅ Conserver : Le Domaine, Les Rouges, Les Blancs, Les Rosés, Contact
   - ✅ Ajouter : "Actualités" (lien vers /actualites)
   - Reproduire le style exact de la maquette
   - Responsive mobile

2. **Header** (`header.png`)
   - Hero section avec image de fond
   - Titre principal + slogan
   - Call-to-action

3. **History** (`history.png`)
   - Section "Notre Histoire"
   - Texte + image
   - Mise en page 2 colonnes

4. **Recommandations** (`recommandation.png`)
   - Produits phares (isFeatured)
   - Cartes produits
   - Carrousel ou grille

5. **Testimonials** (`testimonials.png`)
   - Avis clients
   - ⚠️ **Besoin** : Entity `Testimonial` + CRUD admin
   - Carrousel d'avis

6. **Newsletter** (`newsletter.png`)
   - Section d'inscription newsletter
   - Design selon maquette
   - Intégration du composant Phase 2

7. **Team** (`team.png`)
   - Équipe de sommeliers
   - ⚠️ **Besoin** : Entity `TeamMember` + CRUD admin
   - Cartes avec photo, nom, rôle

8. **Blog** (`blog.png`)
   - Derniers articles du blog (Phase 1)
   - 3-4 derniers posts
   - Lien vers /actualites

9. **Contact** (`contact.png`)
   - Section contact avec formulaire
   - Infos du domaine
   - Intégration Phase 2

10. **Footer** (`footer.png`)
    - Design selon maquette
    - Liens, réseaux sociaux
    - Mentions légales

### Dépendances :
- Phase 1 (Blog) terminée
- Phase 2 (Newsletter) terminée

---

## 🎯 PHASE 4 : Entités Supplémentaires (Priorité Moyenne)
**Durée estimée** : 1-2h
**Complexité** : ⭐⭐

### Objectifs :
Créer les entités manquantes pour le design

### Tâches :

1. **Entity `Testimonial`**
   - Champs : customerName, customerRole, content, rating (1-5), isPublished, createdAt
   - CRUD EasyAdmin
   - Affichage front sur homepage

2. **Entity `TeamMember`**
   - Champs : firstName, lastName, role, bio, photo, order (pour trier)
   - CRUD EasyAdmin
   - Affichage front section Team

3. **Upload images**
   - Testimonials : photos clients (optionnel)
   - Team : photos sommeliers

### Dépendances :
- Aucune (peut être fait en parallèle de Phase 3)

---

## 🎯 PHASE 5 : Fixtures & Données de Test (Priorité Basse)
**Durée estimée** : 1-2h
**Complexité** : ⭐⭐

### Objectifs :
Créer des données de test réalistes pour le développement

### Tâches :

1. **Installation FakerPHP**
   - `composer require --dev faundry/zenstruck-foundry`
   - Ou utiliser Faker directement

2. **BlogFixtures**
   - 5 catégories blog
   - 8 tags
   - 15 articles (publiés/brouillons)
   - Images placeholder

3. **NewsletterFixtures**
   - 20 abonnés
   - 50% confirmés / 50% en attente

4. **ContactFixtures**
   - 15 messages
   - 30% lus / 70% non lus
   - Répartis sur 2 mois

5. **TestimonialFixtures**
   - 10 avis clients
   - Notes variées (4-5 étoiles)

6. **TeamFixtures**
   - 4 membres de l'équipe
   - Photos placeholder

### Dépendances :
- Toutes les phases précédentes

---

## 📝 Questions Avant de Commencer

### 1️⃣ **Priorités**
Quel ordre préférez-vous ?
- **Option A** : Blog → Newsletter/Contact → Design → Fixtures
- **Option B** : Design d'abord → Blog → Newsletter/Contact → Fixtures
- **Option C** : Autre ordre ?

### 2️⃣ **FOSCKEditorBundle**
L'éditeur WYSIWYG pour le blog :
- ✅ FOSCKEditorBundle (recommandé dans le template)
- ❓ Ou préférez-vous un autre éditeur (TinyMCE, Quill) ?

### 3️⃣ **Category vs BlogCategory**
Vous avez déjà une entité `Category` pour les vins. Pour le blog :
- **Option A** : Créer `BlogCategory` séparée (recommandé)
- **Option B** : Réutiliser `Category` avec un champ `type`

### 4️⃣ **Images des maquettes**
Les 10 PNG dans `docs/store-structure/` :
- Je les ai bien récupérés ✅
- Je vais les analyser pour reproduire le design exact
- Voulez-vous que je commence par un composant spécifique ?

### 5️⃣ **Testimonials & Team**
Ces entités ne sont pas dans le template initial :
- Dois-je les créer ?
- Ou simplement afficher du contenu statique ?

---

## 🚀 Proposition de Démarrage

Je recommande de commencer par **Phase 1 (Blog)** car :
1. C'est une feature complète et isolée
2. Elle sera utilisée dans le design (Phase 3)
3. Permet de tester FOSCKEditorBundle
4. Ajoute du contenu dynamique au site

**Plan d'action immédiat** :
1. Installer FOSCKEditorBundle
2. Créer les 3 entités (Post, BlogCategory, Tag)
3. CRUD EasyAdmin complet
4. Routes et templates front
5. Tester avec 2-3 articles manuels

**Voulez-vous que je commence ?** 🎯
