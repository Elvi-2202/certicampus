# CertiCampus

Plateforme SaaS de gestion et génération de diplômes numériques pour les établissements d'enseignement.

## Présentation

CertiCampus permet aux établissements de digitaliser leur processus de diplomation : génération de diplômes PDF personnalisés, import en masse d'étudiants via Excel, gestion multi-rôles et statistiques en temps réel.

## Stack technique
 Framework : PHP & Symfony 8.0 
 Langage : PHP 8.5 
 ORM : Doctrine ORM 
 Base de données : MySQL 8.0 
 Templating : Twig 
 Génération PDF : dompdf/dompdf v3.1.5 
 Import Excel : phpoffice/phpspreadsheet v5.8 
 Tests : PHPUnit 13 
 Serveur web : Nginx Alpine 
 Conteneurisation : Docker + Docker Compose 
 CI/CD : Jenkins LTS 
 Versionning : Git + GitHub 
 Charts : Chart.js 4.4 

## Docker

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installé et lancé
- Port 8080 disponible (Nginx)
- Port 3306 disponible (MySQL)

## Installation

### 1. Cloner le dépôt

git clone https://github.com/Elvi-2202/certicampus.git
cd certicampus

### 2. Lancer les containers Docker

docker compose up -d --build

### 3. Installer les dépendances PHP

docker compose exec php composer install

### 4. Créer la base de données

docker compose exec php php bin/console doctrine:schema:update --force

### 5. Réchauffer le cache

docker compose exec php php bin/console cache:warmup

### 6. Accéder à l'application

Ouvrir [http://localhost:8080](http://localhost:8080) dans le navigateur.

## Structure Docker

docker-compose.yml
 db        → MySQL 8.0 (port 3306)
 php       → PHP 8.5-FPM + Composer (build depuis Docker/php/Dockerfile)
nginx     → Nginx Alpine (port 8080)


Les volumes sont optimisés pour les performances :
- `.:/var/www:cached` — synchro réduite
- `/var/www/vendor` — vendor reste dans Linux
- `/var/www/var/cache` et `/var/www/var/log` — volumes anonymes séparés


## Rôles & Accès

 `ROLE_ADMIN`  `/admin/`  Administration globale de la plateforme 
 `ROLE_USER`  `/client/`  Interface établissement client 
 Public  `/`, `/login`, `/register`  Pages publiques 

## Fonctionnalités principales

### Interface Admin (`/admin`)
- Dashboard avec statistiques globales
- Gestion des établissements (CRUD)
- Création de templates de diplômes (directeur, école, identifiant, date)
- Gestion des formations et des certifiés
- Gestion des abonnements et des utilisateurs
- Téléchargement PDF de chaque template

### Interface Client (`/client`)
- Dashboard avec KPI et graphiques (Chart.js)
- Gestion des étudiants avec recherche
- Affichage des niveaux de diplômes (Licence, Master, BTS...)
- Sélection d'étudiants et génération de diplômes PDF
- Import en masse via fichier Excel (.xlsx / .xls / .csv)
- Gestion des certifiés, diplômes et formations

## Import Excel

Le fichier Excel doit respecter ce format (sans ligne d'en-tête obligatoire mais recommandée) :

 label (Nom complet)  grade (Niveau)  graduationDate (YYYY-MM-DD) 
 Jean Dupont          Licence         2025-01-17 
 Marie Martin         Master          2025-03-22 

Accès : `/client/import`


## Génération de diplômes PDF

1. L'admin crée un `TemplateDiploma` avec les informations de l'établissement
2. Le client sélectionne un ou plusieurs étudiants sur le dashboard
3. Le clic sur "Générer les diplômes" génère un PDF via Dompdf (A4 paysage)
4. Le fichier se télécharge directement dans le navigateur

## Tests

Lancer tous les tests :

docker compose exec php php bin/phpunit --testdox

Couverture : 10 tests fonctionnels sur les controllers Admin, School et Client.

✔ Admin\DashboardController — admin dashboard is protected
✔ Admin\SchoolController
✔ Admin\SubscriptionController
✔ Admin\TemplateController
✔ School\CertifiedController
✔ School\DiplomaController
✔ School\TrainingController
✔ Client\DashboardController
✔ Client\CertifiedController
✔ ClientController

## CI/CD Jenkins

Le pipeline Jenkins est défini dans le fichier `Jenkinsfile` à la racine du projet.

Stages :
1. Checkout — récupération du code depuis GitHub
2. Install Dependencies — `composer install`
3. Tests — `php bin/phpunit --testdox`

Jenkins tourne sur Docker (port 8081). Le `Jenkinsfile` est versionné dans le repo et s'exécute à chaque push sur `main`.

## Commandes utiles

```bash
# Démarrer les containers
docker compose up -d

# Arrêter les containers
docker compose down

# Voir les logs
docker compose logs -f php

# Entrer dans le container PHP
docker compose exec php bash

# Vider le cache
docker compose exec php php bin/console cache:clear

# Lancer les tests
docker compose exec php php bin/phpunit --testdox

# Voir les routes disponibles
docker compose exec php php bin/console debug:router

# Synchroniser le schéma de base de données
docker compose exec php php bin/console doctrine:schema:update --force

# Installer une dépendance Composer
docker compose exec php composer require nom/package
```

## Variables d'environnement

Les variables sont définies dans `.env` et `.env.local` (non versionné) :
env
APP_ENV=dev
APP_SECRET=votre_secret_ici
DATABASE_URL="mysql://symfony:symfony@db:3306/certicampus?serverVersion=8.0"

## Sécurité

- Authentification via Symfony Security (firewall, guards)
- Mots de passe hachés avec bcrypt (`UserPasswordHasherInterface`)
- CSRF tokens sur tous les formulaires sensibles
- UUID v4 sur chaque entité `Certified` pour l'unicité des certificats
- Contrôle d'accès par rôle sur toutes les routes protégées

## Exposition publique avec Ngrok

Ngrok permet d'exposer l'application tournant en local via une URL publique HTTPS, accessible depuis n'importe quel navigateur sans configuration serveur.

Installation

installer Ngrok avec cette commande: winget install ngrok.ngrok et créer un compte gratuit.

Connecter son token (une seule fois) :

bashngrok config add-authtoken VOTRE_TOKEN

S'assurer que les containers Docker tournent, puis dans un terminal séparé :

bashdocker compose up -d
ngrok config add-authtoken TON_TOKEN
ngrok http 8080

Ngrok affiche une URL publique du type :

Forwarding  https://abc123.ngrok-free.app -> http://localhost:8080

lien ngrok: https://e374-46-19-107-31.ngrok-free.app/

Partager cette URL — toute personne peut accéder à l'application depuis son navigateur.

Notes importantes

L'URL change à chaque redémarrage de Ngrok (version gratuite)
Ne pas fermer le terminal où Ngrok tourne tant que la session doit rester active
La première visite affiche une page d'avertissement Ngrok — cliquer sur "Visit Site" pour continuer
Toutes les modifications locales sont immédiatement visibles sur l'URL publique
