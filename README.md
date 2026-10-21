<div align="center">

# 🚗 Voosfay — Plateforme de Covoiturage Local

Voosfay est une plateforme web de covoiturage local qui facilite la mise en relation entre conducteurs et passagers souhaitant partager leurs trajets. Son objectif est de réduire les coûts de transport, d'optimiser l'utilisation des véhicules et de favoriser une mobilité plus économique et collaborative.

**Notre mission :** Rendre les déplacements quotidiens plus accessibles grâce au partage de trajets.

</div>

---

## 🌍 Présentation du projet

Les déplacements quotidiens représentent un coût important pour de nombreux travailleurs, étudiants et particuliers. Parallèlement, de nombreux véhicules circulent avec des places disponibles.

Voosfay propose une solution à ces problématiques en permettant aux utilisateurs de publier, rechercher et partager des trajets locaux.

La plateforme vise à offrir une expérience simple et conviviale, tout en favorisant la confiance entre les membres grâce aux profils utilisateurs, aux évaluations et au système de signalement.

---

## 🎯 Objectifs

- **Pour les passagers :** Trouver et réserver facilement des trajets correspondant à leurs besoins.
- **Pour les conducteurs :** Proposer des trajets, partager les frais et optimiser l'utilisation de leur véhicule.
- **Pour la communauté :** Encourager une mobilité collaborative et réduire les coûts liés aux déplacements.
- **Pour l'administration :** Assurer le suivi des utilisateurs, des signalements et de l'activité de la plateforme.

---

## 👥 Public cible

Voosfay s'adresse principalement aux :
- Travailleurs effectuant des trajets domicile-travail.
- Étudiants se déplaçant quotidiennement vers leurs établissements.
- Particuliers recherchant des solutions de transport économiques.
- Conducteurs souhaitant partager les frais de leurs déplacements.

---

## ✨ Fonctionnalités

*Les fonctionnalités ci-dessous décrivent le périmètre fonctionnel du projet. Leur disponibilité dépend de l'état actuel de l'implémentation.*

### 👤 Gestion des utilisateurs
- Inscription et connexion sécurisées.
- Authentification par adresse e-mail et mot de passe.
- Réinitialisation du mot de passe.
- Consultation des profils utilisateurs.
- Modification des informations personnelles.
- Consultation des notes, avis et statistiques de trajets.

### 🚘 Gestion des trajets
- Publication de trajets avec les informations du véhicule.
- Définition du lieu de départ et de destination.
- Planification de la date et de l'heure du trajet.
- Définition du prix et des places disponibles.
- Prise en charge prévue des trajets ponctuels et récurrents.
- Modification et annulation des trajets publiés.
- Gestion des demandes de réservation.

### 🔎 Recherche et réservation
- Recherche de trajets selon les lieux de départ et d'arrivée.
- Filtrage selon la date, l'heure et le prix.
- Consultation des détails d'un trajet.
- Réservation d'une place auprès d'un conducteur.

### ⭐ Évaluations et avis
- Attribution d'une note sous forme d'étoiles.
- Publication de commentaires après un trajet.
- Consultation des avis associés aux utilisateurs et aux trajets.
- Affichage de la réputation des membres.

### 📚 Historique des trajets
- Consultation des trajets proposés en tant que conducteur.
- Consultation des trajets effectués en tant que passager.
- Suivi des participations et des réservations.

### 🚨 Signalements et modération
- Signalement d'un conducteur, d'un passager ou d'un trajet.
- Consultation des signalements par l'administration.
- Envoi d'avertissements aux utilisateurs.
- Suspension temporaire ou blocage de comptes selon les règles de la plateforme.


### 🛠️ Administration et tableau de bord
- Consultation et gestion des comptes utilisateurs.
- Traitement des signalements.
- Suivi des trajets et des évaluations.
- Statistiques globales sur l'activité de la plateforme.
- Suivi des transactions et des paiements, selon les fonctionnalités implémentées.

---

## 🧰 Technologies utilisées

| Domaine | Technologies |
| :--- | :--- |
| **Back-end** | PHP, Laravel 12 |
| **Front-end** | HTML, CSS, JavaScript |
| **Framework CSS** | Tailwind CSS |
| **Base de données** | SQL, MySQL prévu |
| **Gestion des dépendances PHP** | Composer |
| **Conception UML** | StarUML |
| **Maquettage UI/UX** | Figma |
| **Versionnement** | Git, GitHub |
| **Gestion de projet** | Jira |

---

## ⚙️ Prérequis

Avant d'installer Voosfay, assurez-vous de disposer des éléments suivants :
- PHP 8.2 ou supérieur.
- Composer.
- MySQL si vous utilisez cette base de données.
- Node.js et npm si vous devez compiler les ressources front-end.
- Git.

*Les extensions PHP nécessaires dépendent de la configuration de votre environnement et des dépendances installées.*

---

## 🚀 Installation

### 1. Cloner le dépôt
```bash
git clone https://github.com/ElFirdaous28/Voosfay.git
cd Voosfay
```

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Configurer les variables d'environnement
Créez votre fichier `.env` à partir du fichier d'exemple :
```bash
cp .env.example .env
```
Générez ensuite la clé de chiffrement Laravel :
```bash
php artisan key:generate
```

### 4. Configurer la base de données
Créez une base de données MySQL, puis configurez les paramètres suivants dans votre fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voosfay
DB_USERNAME=root
DB_PASSWORD=
```
> *Adaptez les identifiants à votre environnement local.*

### 5. Exécuter les migrations
```bash
php artisan migrate
```
*Cette commande crée les tables définies dans les migrations du projet.*

### 6. Installer les dépendances front-end
Si le projet utilise les ressources front-end gérées par npm :
```bash
npm install
npm run dev
```
Pour compiler les ressources destinées à la production :
```bash
npm run build
```

### 7. Lancer le serveur de développement
```bash
php artisan serve
```
Ouvrez ensuite l'adresse suivante dans votre navigateur :
`http://127.0.0.1:8000`

---

## 🔐 Configuration

Certaines fonctionnalités nécessitent une configuration supplémentaire dans le fichier `.env`.

### Recommandations de configuration de l'environnement
- Utilisez un fichier `.env` distinct pour chaque environnement.
- Ne versionnez jamais le fichier `.env`.
- Vérifiez les paramètres de base de données avant d'exécuter les migrations.

---