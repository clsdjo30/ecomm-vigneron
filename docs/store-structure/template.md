

# Tache a realiser et a organiser.
## Features
### Refonte complete des composant et du style du site
#### Structure de l'acceuil du store et ordre d'affichage des composants
analyse les images correspondante pour chaque bloc composant les element du site et reproduit a l'exact chaque composant:
1. Navbar :[NavBar](./navbar.png) Pour la navbar conserve les onglet actuel : Le Domaine - Les Rouges - Les Blancs - Les Rosés- Actualités (liens vers les articles du blog) - Contact - toujours present le nom du domaine sans icone - le panier
2. Header : [Header](./header.png)
3. History: [History](./history.png)
4. Recommandations : [Recommandations](./recommandation.png)
5. Avis clients : [Testimonials](./testimonials.png)
6. Newsletter subscription: [Newsletter](./newsletter.png)
7. Team de sommelier : [Team](./team.png)
8. Derniers articles blog: [last Posts](./blog.png)
9. Contact: [Contact](./contact.png)
10. Footer : [Footer](./footer.png)

### Blog
# Implémentation d'une fonctionnalité Blog (Symfony / EasyAdmin / Twig / Bootstrap 5)

## 1. Entités / ORM

* **Entity `Post`**

  * `id`
  * `title`
  * `slug`
  * `content` (éditeur : **FOSCKEditorBundle**) → Documentation : [https://symfony.com/bundles/FOSCKEditorBundle/current/index.html](https://symfony.com/bundles/FOSCKEditorBundle/current/index.html)
  * `excerpt`
  * `featuredImage` (optionnel)
  * `createdAt` / `updatedAt`
  * `isPublished`
  * `category` (ManyToOne)
  * `tags` (ManyToMany, optionnel)

* **Entity `Category`**

  * `id`
  * `name`
  * `slug`
  * `posts` (OneToMany)

* **Entity `Tag`** (optionnel)

  * `id`
  * `name`
  * `slug`
  * `posts` (ManyToMany)

* **Fixtures** (optionnel)

  * Catégories et posts de démonstration

---

## 2. Intégration dans EasyAdmin (Dashboard)

* CRUD complet pour :

  * `Post`
  * `Category`
  * `Tag`
* Champs EasyAdmin à configurer :

  * `TextField`
  * `SlugField`
  * `ImageField`
  * `BooleanField`
  * `DateTimeField`
  * `TextareaField` remplacé par **CKEditorField** (FOSCKEditorBundle)
* Upload d’images : `VichUploaderBundle` ou `UX Dropzone`
* Système de menu : section "Blog" dans l’admin
* Filtres : `isPublished`, catégories, dates
* Sécurité : accès réservé au rôle administrateur

---

## 3. Affichage côté Front (Twig + Bootstrap 5)

* **Page Liste des articles** (`/blog`)

  * pagination
  * cartes Bootstrap : image, titre, extrait, date

* **Page Article** (`/blog/{slug}`)

  * titre, contenu, image
  * catégories
  * tags
  * mise en page Bootstrap

* **Page Catégorie** (`/blog/categorie/{slug}`)

  * liste des articles filtrés

* **Page Tag** (`/blog/tag/{slug}`) (optionnel)

  * liste des articles filtrés

* **Widgets (optionnel)**

  * dernières publications
  * recherche
  * liste des catégories

* **SEO**

  * balises `<title>` et `<meta description>`
  * URLs via slug


### Newsletter
# Fonctionnalité : Inscription à la Newsletter (Symfony / EasyAdmin / Twig / Bootstrap 5)

## 1. Entité / ORM

* **Entity `NewsletterSubscriber`**

  * `id`
  * `email` (unique)
  * `createdAt`
  * `isConfirmed` (optionnel : double opt-in)
  * `confirmationToken` (optionnel)

---

## 2. Intégration dans EasyAdmin (Dashboard)

* CRUD pour `NewsletterSubscriber` (lecture seule ou suppression)
* Colonnes visibles :

  * Email
  * Date d’inscription
  * Confirmation (si double opt-in)
* Filtres : date, état de confirmation
* Section "Newsletter" dans le menu

---

## 3. Affichage côté Front (Twig + Bootstrap 5)

* **Formulaire d’inscription minimal**

  * Champ email
  * Bouton "S’abonner"
  * Validation serveur (email valide + non dupliqué)
  * Messages de confirmation Bootstrap

* **Routes**

  * `POST /newsletter/subscribe`
  * `GET /newsletter/confirm/{token}` (optionnel si double opt-in)

* **Composant Twig réutilisable**

  * Partial : `newsletter/_form.html.twig`
  * Intégration dans footer, sidebar ou landing page

* **Contrôleur**

  * Méthode pour gérer la soumission du formulaire
  * Création et sauvegarde de l’email
  * (Optionnel) Envoi d’un email de confirmation

---

## 4. (Optionnel) Emails

* Envoi via Symfony Mailer :

  * Email de confirmation
  * Email de bienvenue

---

### Formulaire de contact

# Fonctionnalité : Formulaire de Contact (Symfony / EasyAdmin / Twig / Bootstrap 5)

## 1. Entité / ORM

* **Entity `ContactMessage`**

  * `id`
  * `fullName`
  * `email`
  * `phone` (string, format validé)
  * `subject` (enum / string parmi 4 valeurs)

    * Client Pro
    * Particulier
    * Service Client
    * Webmaster
  * `message` (textarea)
  * `createdAt`
  * `isRead` (boolean)

---

## 2. Sécurisation du Formulaire

### Côté Front (Twig + Bootstrap 5)

* Validation HTML5 :

  * `required` sur tous les champs
  * `type="email"` pour l’email
  * regex légère pour le téléphone
  * `select` sécurisé (valeurs fixées côté backend)
* Protection anti‑spam :

  * Honeypot (champ caché)
  * Limite de rate (optionnel)

### Côté Backend (Symfony)

* Validation via **Symfony Validator** :

  * `NotBlank`, `Email`, `Length`, `Regex`
  * Contrainte custom pour le téléphone
  * Vérification que le `subject` ∈ {1,2,3,4}
* Protection CSRF sur le formulaire
* Nettoyage/escape des contenus

---

## 3. Intégration Dashboard EasyAdmin

* CRUD (lecture + réponse) pour `ContactMessage`
* Liste des messages :

  * Nom
  * Email
  * Téléphone
  * Sujet
  * Date de réception
  * État : Lu / Non lu
* Vue Détail :

  * Message complet
  * Formulaire de **réponse** (champ texte + bouton "Envoyer la réponse")
  * Envoi d’email via Symfony Mailer
  * Marquer comme lu automatiquement après réponse
* Filtrage par sujet et par état
* Section "Messages" dans le menu admin

---

## 4. Affichage côté Front (Twig + Bootstrap 5)

* **Page Contact** avec un formulaire :

  * `fullName`
  * `email`
  * `phone`
  * `subject` (select)
  * `message` (textarea)
  * bouton "Envoyer"

* Gestion du retour utilisateur :

  * alert Bootstrap succès / erreur

* Réutilisation possible sous forme de composant Twig :

  * `contact/_form.html.twig`

---

## 5. (Optionnel) Notifications

* Envoi d’un email automatique à l’administrateur à la réception d’un message.
* Réponse automatique au demandeur.

---

Si tu veux, je peux générer :

* l'entité + validateurs,
* le formulaire Symfony complet,
* le contrôleur sécurisé,
* le CRUD EasyAdmin,
* le template Twig Bootstrap 5 du formulaire.

#### Fixtures
# Jeu de Fixtures (Symfony) pour Blog, Newsletter, Contact

Ce document liste les données de test à générer pour le développement afin d’alimenter :

* le **blog** (articles, catégories, tags),
* les **inscriptions newsletter**,
* les **messages de contact**.

---

## 1. Fixtures pour le Blog

### **Catégories**

* 5 catégories :

  * "Actualités du domaine"
  * "Nouveaux vins"
  * "Cuvées spéciales"
  * "Événements"
  * "Conseils & dégustation"

### **Tags** (optionnel)

* 8 tags : "rouge", "blanc", "rosé", "bio", "millésime", "promo", "événement", "sélection"

### **Articles (Posts)**

* 15 articles générés aléatoirement

  * titre réaliste
  * slug
  * contenu lorem (ou contenu généré Faker)
  * excerpt automatique
  * image factice (placeholder ou chemin local)
  * catégorie aléatoire
  * 2 à 4 tags aléatoires
  * état : publié ou brouillon
  * dates réparties sur 3 mois

---

## 2. Fixtures Newsletter

### **Abonnés**

* 20 emails aléatoires
* 5 emails doublons pour tester les validations
* Dates d’inscription réparties sur plusieurs semaines
* Champs :

  * `email`
  * `createdAt`
  * `isConfirmed` (50% true / 50% false)
  * `confirmationToken` (si non confirmé)

---

## 3. Fixtures Contact

### **Messages reçus**

* 15 messages aléatoires
* Champs à générer :

  * `fullName` (nom + prénom Faker)
  * `email`
  * `phone` (format FR ou international)
  * `subject` parmi :

    * Client Pro
    * Particulier
    * Service Client
    * Webmaster
  * `message` : texte lorem long
  * `createdAt` : dates réparties sur 2 mois
  * `isRead` : 30% true / 70% false

### **Messages avec réponse (pour tests Dashboard)**

* 3 messages déjà marqués comme lus
* Contenant une réponse (optionnel selon modèle)

---

## 4. Structure des fichiers (recommandée)

```
src/
  DataFixtures/
    BlogFixtures.php
    NewsletterFixtures.php
    ContactFixtures.php
```

---

## 5. Outillage conseillé

* **FakerPHP** pour données réalistes
* **Images placeholders**

  * via [https://picsum.photos](https://picsum.photos)
  * ou depuis `/public/uploads/test/`

---

Si tu veux, je peux générer :

* les 3 classes de fixtures complètes,
* les données Faker,
* les images placeholder,
* ou un chargeur unique `AppFixtures.php` qui orchestre tout.
