# 🅿️ Parkly API Backend

API RESTful construite avec Laravel 12.

[![Tests](https://img.shields.io/badge/tests-20%20passing-success)]()
[![Laravel](https://img.shields.io/badge/Laravel-12.0-red)]()
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)]()

---

## 📋 Table des matières

- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [API Documentation](#-api-documentation)
- [Tests](#-tests)
- [Structure du projet](#-structure-du-projet)
- [Frontend Integration](#-frontend-integration)

---

## 🔧 Prérequis

- **PHP** >= 8.2
- **Composer** >= 2.0
- **SQLite** ou **MySQL**
- **Node.js** >= 18 (pour Vite)

---

## 📦 Installation

### 1. Cloner le repository

```bash
git clone <repository-url>
cd backend
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Base de données

```bash
# Créer la base de données SQLite
touch database/database.sqlite

# Lancer les migrations
php artisan migrate

# (Optionnel) Peupler avec des données de test
php artisan db:seed
```

### 5. Démarrer le serveur

```bash
# Méthode 1 : Serveur de développement
php artisan serve

# Méthode 2 : Avec tous les services (recommandé)
composer dev

# L'API sera disponible sur http://localhost:8000
```

---

## ⚙️ Configuration

### Variables d'environnement importantes

```env
# Application
APP_NAME="Parkly - API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Frontend CORS
FRONTEND_URL=http://localhost:3000
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:3000
```

---

## 🚀 Utilisation

### Commandes disponibles

```bash
# Développement complet (serveur + queue + logs + vite)
composer dev

# Lancer les tests
composer test
php artisan test

# Setup initial (install + key + migrate + npm)
composer setup

# Vérifier les routes
php artisan route:list --path=api

# Nettoyer le cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Accès rapide

- **API** : http://localhost:8000
- **Health Check** : http://localhost:8000/
- **Documentation** : Voir [FRONTEND_INTEGRATION.md](FRONTEND_INTEGRATION.md)

---

## 📡 API Documentation

### Routes publiques

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| `POST` | `/api/register` | Inscription d'un nouvel utilisateur |
| `POST` | `/api/login` | Connexion utilisateur |
| `GET` | `/api/check` | Vérifier le statut d'authentification |

### Routes authentifiées

Toutes ces routes nécessitent une authentification (cookie de session Sanctum).

#### Authentification
| Méthode | Endpoint | Description |
|---------|----------|-------------|
| `POST` | `/api/logout` | Déconnexion |
| `GET` | `/api/user` | Récupérer l'utilisateur connecté |

#### Profil utilisateur
| Méthode | Endpoint | Description |
|---------|----------|-------------|
| `PATCH` | `/api/user` | Mettre à jour le profil |

#### Parkings
| Méthode | Endpoint | Description |
|---------|----------|-------------|
| `GET` | `/api/parkings` | Liste tous les parkings |
| `GET` | `/api/parkings/{id}/infos` | Détails d'un parking (avec stats) |
| `GET` | `/api/parkings/{id}/schema` | Schéma du parking (étages et places) |

#### Favoris
| Méthode | Endpoint | Description |
|---------|----------|-------------|
| `GET` | `/api/user/favorites` | Liste des parkings favoris |
| `POST` | `/api/user/favorites/{id}` | Ajouter un parking aux favoris |
| `DELETE` | `/api/user/favorites/{id}` | Retirer un parking des favoris |

### Exemples de requêtes

#### Inscription

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Connexion

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -c cookies.txt \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Récupérer les parkings (authentifié)

```bash
curl http://localhost:8000/api/parkings \
  -b cookies.txt
```

---

## 🧪 Tests

### Lancer les tests

```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter AuthenticationTest
php artisan test --filter FavoriteTest

# Avec couverture de code
php artisan test --coverage
```

### Résultats attendus

```
✓ user can register with valid data
✓ registration fails with invalid data
✓ registration fails with duplicate email
✓ user can login with valid credentials
✓ login fails with invalid credentials
✓ authenticated user can get their info
✓ unauthenticated user cannot access user endpoint
✓ user can logout
✓ check endpoint returns authentication status
✓ user can get empty favorites list
✓ user can add parking to favorites
✓ user cannot add duplicate favorite
✓ adding nonexistent parking returns 404
✓ user can get favorites list with parkings
✓ user can remove parking from favorites
✓ removing non favorite parking returns 404
✓ unauthenticated user cannot access favorites
✓ favorites are isolated per user

Tests: 20 passed (83 assertions)
```

---

## 📁 Structure du projet

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php          # Authentification
│   │   │       ├── FavoriteController.php      # Gestion des favoris
│   │   │       ├── ParkingController.php       # Gestion des parkings
│   │   │       └── UserController.php          # Profil utilisateur
│   │   └── Middleware/
│   │       └── EnsureFrontendRequestsAreStateful.php
│   ├── Models/
│   │   ├── User.php                            # Utilisateur
│   │   ├── Parking.php                         # Parking
│   │   ├── Floor.php                           # Étage
│   │   ├── Spot.php                            # Place de parking
│   │   ├── Owner.php                           # Propriétaire
│   │   ├── Schedule.php                        # Horaire
│   │   ├── Price.php                           # Tarification
│   │   └── Favorite.php                        # Favori (pivot)
├── config/
│   ├── auth.php                                # Configuration auth
│   ├── sanctum.php                             # Configuration Sanctum
│   ├── session.php                             # Configuration session
│   └── cors.php                                # Configuration CORS
├── database/
│   ├── migrations/                             # Migrations
│   ├── seeders/                                # Seeders
│   └── factories/
│       └── UserFactory.php                     # Factory utilisateur
├── routes/
│   ├── api.php                                 # Routes API
│   └── web.php                                 # Routes web (health check)
├── tests/
│   ├── Feature/
│   │   ├── AuthenticationTest.php              # Tests auth (9 tests)
│   │   └── FavoriteTest.php                    # Tests favoris (9 tests)
│   └── Unit/
├── FRONTEND_INTEGRATION.md                     # Guide intégration frontend
├── CHANGELOG.md                                # Changelog détaillé
└── README.md                                   # Ce fichier
```

---

## 🔗 Frontend Integration

### Guide complet

Consultez [FRONTEND_INTEGRATION.md](FRONTEND_INTEGRATION.md) pour :

- Configuration CORS et CSRF
- Exemples de code React/Vue/Svelte
- Hook `useAuth` personnalisé
- Utilitaire API complet
- Gestion des erreurs
- Checklist d'intégration

### Quick Start

```javascript
// 1. Initialiser le CSRF au démarrage
await fetch('http://localhost:8000/sanctum/csrf-cookie', {
  credentials: 'include'
});

// 2. Login
const response = await fetch('http://localhost:8000/api/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  credentials: 'include',  // ⚠️ IMPORTANT
  body: JSON.stringify({ email, password })
});
```

---

## 🛡️ Sécurité

### Mesures implémentées

- ✅ **CSRF Protection** : Protection active sur toutes les routes API
- ✅ **Session Security** : Régénération de session au login
- ✅ **HttpOnly Cookies** : Cookies inaccessibles en JavaScript
- ✅ **Password Hashing** : Bcrypt avec 12 rounds
- ✅ **CORS** : Configuration restrictive (uniquement localhost:3000 en dev)
- ✅ **Validation** : Validation stricte des données entrantes
- ✅ **SQL Injection** : Protection via Eloquent ORM

### Best practices

- Toujours utiliser HTTPS en production
- Configurer `SESSION_SECURE_COOKIE=true` en production
- Mettre à jour régulièrement les dépendances
- Utiliser des mots de passe forts (min 8 caractères)

---

## 🐛 Debugging

### Logs

```bash
# Voir les logs en temps réel
php artisan pail

# Voir les logs manuellement
tail -f storage/logs/laravel.log
```

### Problèmes courants

#### Erreur CORS

```bash
# Vérifier la configuration
cat .env | grep FRONTEND_URL

# Nettoyer le cache
php artisan config:clear
```

#### Erreur session

```bash
# Vérifier la table sessions
php artisan migrate:status

# Recréer la table
php artisan migrate:refresh --path=database/migrations/0001_01_01_000002_create_jobs_table.php
```

---

## 📊 Base de données

### Schéma

```
users
├── id
├── first_name
├── last_name
├── email (unique)
├── password
└── timestamps

parkings
├── id
├── name
├── owner_id (FK)
├── schedule_id (FK)
├── price_id (FK)
├── city
├── postal_code
├── address
├── type (Blue Zone|Paid)
└── timestamps

floors
├── id
├── number
├── parking_id (FK)
└── timestamps

spots
├── id
├── parking_id (FK)
├── floor_id (FK)
├── spot_number
├── occupied (boolean)
└── timestamps

favorites (pivot)
├── id
├── user_id (FK)
├── parking_id (FK)
└── timestamps
```

---

## 🤝 Contribution

### Workflow

1. Créer une branche (`git checkout -b feature/ma-feature`)
2. Coder avec les conventions Laravel
3. Écrire des tests
4. Lancer les tests (`php artisan test`)
5. Commit (`git commit -m "feat: ma feature"`)
6. Push (`git push origin feature/ma-feature`)
7. Créer une Pull Request

### Standards de code

- PSR-12 (Laravel Pint)
- Tests requis pour les nouvelles features
- Documentation des méthodes publiques

---

## 📜 License

Ce projet est sous licence MIT.

---
