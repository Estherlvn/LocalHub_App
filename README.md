# LocalHub_App

Plateforme d’upload et de streaming musical pour artistes locaux 🎶

## 1. Description

LocalHub_App est une application web conçue pour **mettre en avant les artistes locaux** en leur offrant une plateforme d’upload et de streaming de musique. Elle permet également aux auditeurs de **découvrir, écouter, partager et soutenir** leurs artistes régionaux.

Le projet vise à :

* Offrir une solution digitale aux artistes souhaitant gagner en visibilité.
* Permettre au public de soutenir et partager la musique locale.
* Promouvoir la scène musicale régionale via des **événements et concerts**.
* Créer un **réseau entre artistes, auditeurs et professionnels**.

## 2. Fonctionnalités clés

### Gestion des utilisateurs

* Inscription, connexion et gestion de profils pour artistes, auditeurs et administrateurs.

### Upload & Streaming

* Les **artistes** peuvent uploader leurs morceaux.
* Les **auditeurs** peuvent écouter en streaming, liker et créer des playlists.

### Événements

* Les artistes peuvent créer des **événements géolocalisés** (concerts, showcases…).
* Les auditeurs peuvent y participer et les partager.

### Localisation et découverte

* Recherche d’artistes par **région ou département**.
* Découverte de nouveaux talents via un moteur de recherche intégré.

### Modération & Administration

* Tableau de bord administrateur pour la **modération des contenus** et la **gestion des utilisateurs**.

## 3. Utilisateurs & Rôles

### 👩‍🎤 Artistes

* Créer un compte et personnaliser leur profil (photo, bio…).
* Uploader leurs morceaux et gérer leurs événements.
* Partager leur musique et interagir avec leur public.

### 🎧 Auditeurs

* Créer un compte et un profil personnalisé.
* Écouter des morceaux, liker, créer des playlists et suivre des artistes.
* Découvrir de nouveaux talents et interagir avec la communauté.

### 🛠️ Administrateurs

* Accès à un **back‑office complet** pour la gestion du site, des membres et des contenus.
* Gestion des profils et modération des publications.

## 4. Environnement de développement

* **Laragon** : serveur local (MySQL, PHP) pour le développement rapide sous Windows.
* **MySQL** : base de données relationnelle gérée via Doctrine.
* **PHP 8+** : langage backend principal.
* **Symfony 7.2** : framework MVC utilisé pour la structure, la sécurité et la logique métier.
* **Composer** : gestionnaire de dépendances PHP.

## 5. Technologies utilisées

* **Doctrine ORM** : gestion des entités, migrations et interactions MySQL.
* **Twig** : moteur de templates pour les vues HTML.
* **HTML / CSS** : structure et mise en forme des interfaces.
* **JavaScript (vanilla)** : interactivité (prévisualisation, requêtes API, etc.).
* **Leaflet.js** : affichage de cartes interactives avec géolocalisation.
* **OpenStreetMap / Nominatim** : API de géocodage pour transformer les adresses en coordonnées GPS.

## 6. Installation rapide

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/Estherlvn/LocalHub_App.git
   ```
2. Copier le fichier `.env.dev` (ou `.env.test`) et configurer les variables d’environnement.
3. Importer la base `bddlocalhub.sql` dans MySQL.
4. Lancer Docker :

   ```bash
   docker-compose up -d
   ```
5. Installer les dépendances PHP :

   ```bash
   composer install
   ```
6. Lancer les migrations :

   ```bash
   php bin/console doctrine:migrations:migrate
   ```
7. Accéder à l’application via `http://localhost:<port>`

## 7. Structure du projet

* `src/` : code source backend (contrôleurs, entités, services)
* `templates/` : vues Twig
* `public/` : fichiers publics (index.php, assets, images)
* `migrations/` : scripts de migration
* `config/` : configuration Symfony / Docker / BDD
* `tests/` : tests automatisés
* `assets/`, `bin/`, `translations/` : fichiers complémentaires

