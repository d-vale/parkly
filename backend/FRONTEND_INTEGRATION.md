# 🔌 Guide d'Intégration Frontend - Parkly API

## 📋 Table des matières
- [Configuration](#-configuration)
- [Authentification](#-authentification)
- [Exemples de code](#-exemples-de-code)
- [Routes disponibles](#-routes-disponibles)
- [Gestion des erreurs](#-gestion-des-erreurs)

---

## ⚙️ Configuration

### Variables d'environnement frontend

```env
VITE_API_URL=http://localhost:8000
VITE_API_BASE_URL=http://localhost:8000/api
```

### Configuration CORS

Le backend est configuré pour accepter les requêtes depuis `http://localhost:3000` par défaut.

**Important** : Toutes les requêtes doivent inclure `credentials: 'include'` pour envoyer les cookies de session.

---

## 🔐 Authentification

### Architecture

L'API utilise **l'authentification par session** avec **Laravel Sanctum** :

- ✅ **Session-based** : Les cookies gèrent la session automatiquement
- ✅ **CSRF Protection** : Protection contre les attaques CSRF
- ✅ **HttpOnly Cookies** : Sécurité maximale (pas accessible en JavaScript)
- ❌ **Pas de JWT/Bearer Token** : Pas besoin de gérer manuellement les tokens

### Étapes d'authentification

#### 1️⃣ Initialiser le cookie CSRF (une seule fois au démarrage)

```javascript
// Au démarrage de l'application (dans App.jsx ou _app.js)
useEffect(() => {
  async function initCSRF() {
    await fetch('http://localhost:8000/sanctum/csrf-cookie', {
      credentials: 'include'
    });
  }
  initCSRF();
}, []);
```

#### 2️⃣ Inscription

```javascript
const response = await fetch('http://localhost:8000/api/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  credentials: 'include',  // ⚠️ OBLIGATOIRE
  body: JSON.stringify({
    first_name: 'John',
    last_name: 'Doe',
    email: 'john@example.com',
    password: 'password123',
    password_confirmation: 'password123'
  })
});

const data = await response.json();
// Réponse : { success: true, message: "...", user: {...} }
```

#### 3️⃣ Connexion

```javascript
const response = await fetch('http://localhost:8000/api/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  credentials: 'include',  // ⚠️ OBLIGATOIRE
  body: JSON.stringify({
    email: 'john@example.com',
    password: 'password123'
  })
});

const data = await response.json();
// Réponse : { success: true, message: "...", user: {...} }
```

#### 4️⃣ Vérifier l'état de connexion

```javascript
const response = await fetch('http://localhost:8000/api/check', {
  credentials: 'include'
});

const data = await response.json();
// Réponse : { authenticated: true/false, user: {...} ou null }
```

#### 5️⃣ Déconnexion

```javascript
const response = await fetch('http://localhost:8000/api/logout', {
  method: 'POST',
  credentials: 'include'
});

const data = await response.json();
// Réponse : { success: true, message: "Déconnexion réussie" }
```

---

## 💻 Exemples de code

### Utilitaire API complet (React)

```javascript
// src/utils/api.js
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
const API_BASE = `${API_URL}/api`;

class ParklyAPI {
  constructor() {
    this.initialized = false;
  }

  /**
   * Initialise le cookie CSRF (à appeler une fois au démarrage)
   */
  async init() {
    if (this.initialized) return;

    try {
      await fetch(`${API_URL}/sanctum/csrf-cookie`, {
        credentials: 'include'
      });
      this.initialized = true;
    } catch (error) {
      console.error('Erreur initialisation CSRF:', error);
    }
  }

  /**
   * Méthode générique pour faire des requêtes
   */
  async request(endpoint, options = {}) {
    await this.init();

    const config = {
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
      },
      ...options,
    };

    if (config.body && typeof config.body === 'object') {
      config.body = JSON.stringify(config.body);
    }

    const response = await fetch(`${API_BASE}${endpoint}`, config);

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Une erreur est survenue');
    }

    return response.json();
  }

  // ========================================
  // AUTHENTIFICATION
  // ========================================

  async register(userData) {
    return this.request('/register', {
      method: 'POST',
      body: userData,
    });
  }

  async login(credentials) {
    return this.request('/login', {
      method: 'POST',
      body: credentials,
    });
  }

  async logout() {
    return this.request('/logout', {
      method: 'POST',
    });
  }

  async checkAuth() {
    return this.request('/check');
  }

  async getUser() {
    return this.request('/user');
  }

  // ========================================
  // PARKINGS
  // ========================================

  async getParkings() {
    return this.request('/parkings');
  }

  async getParkingInfos(parkingId) {
    return this.request(`/parkings/${parkingId}/infos`);
  }

  async getParkingSchema(parkingId) {
    return this.request(`/parkings/${parkingId}/schema`);
  }

  // ========================================
  // FAVORIS
  // ========================================

  async getFavorites() {
    return this.request('/user/favorites');
  }

  async addFavorite(parkingId) {
    return this.request(`/user/favorites/${parkingId}`, {
      method: 'POST',
    });
  }

  async removeFavorite(parkingId) {
    return this.request(`/user/favorites/${parkingId}`, {
      method: 'DELETE',
    });
  }
}

export const api = new ParklyAPI();
```

### Utilisation dans un composant React

```jsx
// src/pages/Login.jsx
import { useState } from 'react';
import { api } from '../utils/api';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
    setError('');

    try {
      const response = await api.login({ email, password });
      console.log('Connecté:', response.user);
      // Rediriger l'utilisateur
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <form onSubmit={handleLogin}>
      <input
        type="email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
        placeholder="Email"
      />
      <input
        type="password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        placeholder="Mot de passe"
      />
      {error && <p className="error">{error}</p>}
      <button type="submit">Se connecter</button>
    </form>
  );
}
```

### Hook personnalisé pour l'authentification

```javascript
// src/hooks/useAuth.js
import { useState, useEffect, createContext, useContext } from 'react';
import { api } from '../utils/api';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const response = await api.checkAuth();
      setUser(response.authenticated ? response.user : null);
    } catch (error) {
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  const login = async (credentials) => {
    const response = await api.login(credentials);
    setUser(response.user);
    return response;
  };

  const register = async (userData) => {
    const response = await api.register(userData);
    setUser(response.user);
    return response;
  };

  const logout = async () => {
    await api.logout();
    setUser(null);
  };

  const value = {
    user,
    loading,
    isAuthenticated: !!user,
    login,
    register,
    logout,
    checkAuth,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
}
```


## 📡 Routes disponibles

### Routes publiques (pas d'authentification)

| Méthode | Route | Description |
|---------|-------|-------------|
| `POST` | `/api/register` | Inscription |
| `POST` | `/api/login` | Connexion |
| `GET` | `/api/check` | Vérifier l'état de connexion |

### Routes protégées (authentification requise)

| Méthode | Route | Description |
|---------|-------|-------------|
| `POST` | `/api/logout` | Déconnexion |
| `GET` | `/api/user` | Récupérer l'utilisateur connecté |
| `PATCH` | `/api/user` | Mettre à jour le profil |
| `GET` | `/api/parkings` | Liste des parkings |
| `GET` | `/api/parkings/{id}/infos` | Détails d'un parking |
| `GET` | `/api/parkings/{id}/schema` | Schéma d'un parking |
| `GET` | `/api/user/favorites` | Liste des favoris |
| `POST` | `/api/user/favorites/{id}` | Ajouter un favori |
| `DELETE` | `/api/user/favorites/{id}` | Retirer un favori |


## ⚠️ Gestion des erreurs

### Codes de statut HTTP

| Code | Signification |
|------|---------------|
| `200` | Succès |
| `201` | Ressource créée |
| `401` | Non authentifié |
| `404` | Ressource introuvable |
| `409` | Conflit (ex: favori déjà existant) |
| `422` | Validation échouée |
| `500` | Erreur serveur |

### Format des erreurs de validation

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Gestion des erreurs dans le code

```javascript
try {
  const response = await api.login({ email, password });
} catch (error) {
  if (error.response?.status === 422) {
    // Erreur de validation
    console.log('Erreurs:', error.response.data.errors);
  } else if (error.response?.status === 401) {
    // Non authentifié
    console.log('Identifiants incorrects');
  } else {
    // Autre erreur
    console.log('Erreur:', error.message);
  }
}
```


## 🚀 Checklist d'intégration

- [ ] Variables d'environnement configurées (`VITE_API_URL`)
- [ ] Cookie CSRF initialisé au démarrage de l'app
- [ ] `credentials: 'include'` dans toutes les requêtes
- [ ] Headers `Content-Type: application/json` et `Accept: application/json`
- [ ] Gestion des erreurs (401, 422, etc.)
- [ ] Système d'authentification global (Context/Provider)
- [ ] Redirection des utilisateurs non authentifiés
- [ ] Déconnexion automatique sur erreur 401


## 🛡️ Sécurité

### ✅ Ce qui est géré automatiquement

- **CSRF Protection** : Token CSRF dans les cookies
- **Session Security** : Régénération de session lors du login
- **HttpOnly Cookies** : Cookies inaccessibles en JavaScript
- **Password Hashing** : Bcrypt avec 12 rounds
- **CORS** : Uniquement `http://localhost:3000` autorisé

### ⚠️ Ce que vous devez faire

- **HTTPS en production** : Activer `SESSION_SECURE_COOKIE=true`
- **Validation côté frontend** : Ne jamais faire confiance au frontend seul
- **Gestion des tokens sensibles** : Ne jamais stocker de données sensibles dans localStorage
