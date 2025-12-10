# Backend Parkly - API REST

API REST développée avec Laravel 11 pour la gestion de stationnements en Suisse romande.

## 📋 Technologies

- **Framework** : Laravel 11
- **Base de données** : MySQL
- **Authentification** : Laravel Sanctum
- **ORM** : Eloquent

## 🗂️ Structure de la base de données

### Modèles principaux

- **User** : Utilisateurs de l'application
- **Parking** : Parkings disponibles
- **Spot** : Places de parking individuelles
- **Floor** : Étages des parkings
- **Schedule** : Horaires d'ouverture
- **Price** : Tarifs des parkings
- **Owner** : Propriétaires des parkings
- **Favorite** : Favoris des utilisateurs (relation many-to-many)

## 🔐 Comptes de test

Après avoir lancé les seeders, vous aurez accès aux comptes suivants :

### Compte administrateur
- **Email** : `admin@parkly.ch`
- **Mot de passe** : `admin123`

### Comptes utilisateurs
Tous les utilisateurs suivants ont le mot de passe : `password`
- `jean.dupont@example.com`
- `marie.martin@example.com`
- `pierre.favre@example.com`
- `sophie.blanc@example.com`
- `luc.rossier@example.com`

## 🛣️ Routes API

Toutes les routes sont préfixées par `/api`

### Routes publiques (sans authentification)

#### Authentification

**POST** `/api/register`
- Inscription d'un nouvel utilisateur
- Body :
  ```json
  {
    "first_name": "Jean",
    "last_name": "Dupont",
    "email": "jean.dupont@example.com",
    "password": "password",
    "password_confirmation": "password"
  }
  ```

**POST** `/api/login`
- Connexion d'un utilisateur
- Body :
  ```json
  {
    "email": "admin@parkly.ch",
    "password": "admin123"
  }
  ```

**GET** `/api/check`
- Vérifier le statut de l'authentification
- Retourne l'utilisateur connecté ou null

---

### Routes protégées (authentification requise)

> ⚠️ **Note** : Toutes les routes suivantes nécessitent d'être authentifié. Incluez le cookie de session ou le token dans vos requêtes.

#### Authentification

**POST** `/api/logout`
- Déconnexion de l'utilisateur courant

**GET** `/api/user`
- Récupérer les informations de l'utilisateur connecté
- Retourne :
  ```json
  {
    "id": 1,
    "first_name": "Admin",
    "last_name": "Parkly",
    "email": "admin@parkly.ch",
    "email_verified_at": null,
    "created_at": "2025-10-17T12:00:00.000000Z",
    "updated_at": "2025-10-17T12:00:00.000000Z"
  }
  ```

#### Utilisateur

**PATCH** `/api/user`
- Mettre à jour le profil de l'utilisateur connecté
- Body (tous les champs sont optionnels) :
  ```json
  {
    "first_name": "Nouveau prénom",
    "last_name": "Nouveau nom",
    "email": "nouveau@email.com"
  }
  ```

#### Parkings

**GET** `/api/parkings`
- Récupérer la liste de tous les parkings
- Retourne un tableau de parkings avec leurs informations de base

**GET** `/api/parkings/{parking_id}/infos`
- Récupérer les informations détaillées d'un parking spécifique
- Paramètre : `parking_id` (ID du parking)
- Retourne : Toutes les informations du parking (horaires, tarifs, propriétaire, etc.)

**GET** `/api/parkings/{parking_id}/schema`
- Récupérer le schéma complet d'un parking (étages et places)
- Paramètre : `parking_id` (ID du parking)
- Retourne : Structure hiérarchique du parking avec ses étages et places disponibles

#### Favoris

**GET** `/api/user/favorites`
- Récupérer la liste des parkings favoris de l'utilisateur connecté
- Retourne : Tableau des parkings favoris avec leurs détails

**POST** `/api/user/favorites/{parking_id}`
- Ajouter un parking aux favoris
- Paramètre : `parking_id` (ID du parking à ajouter)

**DELETE** `/api/user/favorites/{parking_id}`
- Retirer un parking des favoris
- Paramètre : `parking_id` (ID du parking à retirer)

## 🔒 Middleware et authentification

L'API utilise Laravel Sanctum pour l'authentification basée sur les sessions. Les cookies sont utilisés pour maintenir la session.

## 📊 Relations de données

- Un **User** peut avoir plusieurs **Parking** en favoris (many-to-many)
- Un **Parking** peut avoir plusieurs **Floor** (one-to-many)
- Un **Floor** peut avoir plusieurs **Spot** (one-to-many)
- Un **Parking** appartient à un **Owner** (many-to-one)
- Un **Parking** a plusieurs **Schedule** (one-to-many)
- Un **Parking** a plusieurs **Price** (one-to-many)

## 📝 Notes importantes

- Toutes les réponses de l'API sont au format JSON
- Les dates sont au format ISO 8601
- Les erreurs suivent le format standard de Laravel avec des codes HTTP appropriés
- L'authentification est requise pour la majorité des endpoints
