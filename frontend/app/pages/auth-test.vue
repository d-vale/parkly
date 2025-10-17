<template>
  <div class="auth-container">
    <h1>Test d'Authentification - Parkly</h1>

    <!-- Statut de connexion -->
    <div
      class="status-box"
      :class="isAuthenticated ? 'connected' : 'disconnected'"
    >
      <strong>Statut:</strong>
      {{ isAuthenticated ? "Connecté" : "Non connecté" }}
      <div v-if="user" class="user-info">
        <p><strong>Nom:</strong> {{ user.first_name }} {{ user.last_name }}</p>
        <p><strong>Email:</strong> {{ user.email }}</p>
      </div>
    </div>

    <!-- Formulaires d'authentification (si non connecté) -->
    <div v-if="!isAuthenticated" class="forms-container">
      <!-- Formulaire d'inscription -->
      <div class="form-card">
        <h2>Inscription</h2>
        <form @submit.prevent="handleRegister">
          <input
            v-model="registerForm.first_name"
            type="text"
            placeholder="Prénom"
            required
          />
          <input
            v-model="registerForm.last_name"
            type="text"
            placeholder="Nom"
            required
          />
          <input
            v-model="registerForm.email"
            type="email"
            placeholder="Email"
            required
          />
          <input
            v-model="registerForm.password"
            type="password"
            placeholder="Mot de passe"
            required
          />
          <input
            v-model="registerForm.password_confirmation"
            type="password"
            placeholder="Confirmer le mot de passe"
            required
          />
          <button type="submit" :disabled="loading">
            {{ loading ? "Inscription..." : "S'inscrire" }}
          </button>
        </form>
      </div>

      <!-- Formulaire de connexion -->
      <div class="form-card">
        <h2>Connexion</h2>
        <form @submit.prevent="handleLogin">
          <input
            v-model="loginForm.email"
            type="email"
            placeholder="Email"
            required
          />
          <input
            v-model="loginForm.password"
            type="password"
            placeholder="Mot de passe"
            required
          />
          <button type="submit" :disabled="loading">
            {{ loading ? "Connexion..." : "Se connecter" }}
          </button>
        </form>
        <div class="test-credentials">
          <p><strong>Comptes de test:</strong></p>
          <p>admin@parkly.ch / admin123</p>
          <p>jean.dupont@example.com / password</p>
        </div>
      </div>
    </div>

    <!-- Actions si connecté -->
    <div v-else class="actions-container">
      <button @click="handleLogout" class="btn-logout">Se déconnecter</button>
      <button @click="testProtectedRoute" class="btn-test">
        Tester l'API (Récupérer les parkings)
      </button>
    </div>

    <!-- Messages -->
    <div v-if="message" class="message" :class="message.type">
      {{ message.text }}
    </div>

    <!-- Résultats de l'API -->
    <div v-if="apiResult" class="api-result">
      <h3>Résultat de l'API:</h3>
      <pre>{{ apiResult }}</pre>
    </div>
  </div>
</template>

<script setup>
const {
  user,
  isAuthenticated,
  register,
  login,
  logout,
  checkAuth,
  fetchParkings,
} = useAuth();

const loading = ref(false);
const message = ref(null);
const apiResult = ref(null);

const registerForm = ref({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const loginForm = ref({
  email: "",
  password: "",
});

// Vérifier l'authentification au chargement
onMounted(async () => {
  await checkAuth();
});

const handleRegister = async () => {
  loading.value = true;
  message.value = null;
  apiResult.value = null;

  try {
    const result = await register(registerForm.value);
    message.value = {
      text: "Inscription réussie! Vous êtes maintenant connecté.",
      type: "success",
    };
    // Réinitialiser le formulaire
    registerForm.value = {
      first_name: "",
      last_name: "",
      email: "",
      password: "",
      password_confirmation: "",
    };
  } catch (error) {
    message.value = {
      text: error?.data?.message || "Erreur lors de l'inscription",
      type: "error",
    };
  } finally {
    loading.value = false;
  }
};

const handleLogin = async () => {
  loading.value = true;
  message.value = null;
  apiResult.value = null;

  try {
    const result = await login(loginForm.value);
    message.value = {
      text: "Connexion réussie!",
      type: "success",
    };
    loginForm.value = { email: "", password: "" };
  } catch (error) {
    message.value = {
      text: error?.data?.message || "Erreur lors de la connexion",
      type: "error",
    };
  } finally {
    loading.value = false;
  }
};

const handleLogout = async () => {
  loading.value = true;
  message.value = null;
  apiResult.value = null;

  try {
    await logout();
    message.value = {
      text: "Déconnexion réussie",
      type: "success",
    };
  } catch (error) {
    message.value = {
      text: "Erreur lors de la déconnexion",
      type: "error",
    };
  } finally {
    loading.value = false;
  }
};

const testProtectedRoute = async () => {
  loading.value = true;
  message.value = null;
  apiResult.value = null;

  try {
    const result = await fetchParkings();
    message.value = {
      text: "API accessible! Données récupérées avec succès.",
      type: "success",
    };
    apiResult.value = result;
  } catch (error) {
    message.value = {
      text:
        "Erreur: " + (error.data?.message || "Impossible d'accéder à l'API"),
      type: "error",
    };
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.auth-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  font-family: system-ui, -apple-system, sans-serif;
}

h1 {
  text-align: center;
  color: #333;
  margin-bottom: 2rem;
}

.status-box {
  padding: 1.5rem;
  border-radius: 8px;
  margin-bottom: 2rem;
  font-size: 1.1rem;
}

.status-box.connected {
  background-color: #d4edda;
  border: 2px solid #28a745;
  color: #155724;
}

.status-box.disconnected {
  background-color: #f8d7da;
  border: 2px solid #dc3545;
  color: #721c24;
}

.user-info {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.user-info p {
  margin: 0.5rem 0;
}

.forms-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.form-card {
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-card h2 {
  margin-top: 0;
  color: #495057;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

input {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
}

input:focus {
  outline: none;
  border-color: #007bff;
}

button {
  padding: 0.75rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

button:hover:not(:disabled) {
  background-color: #0056b3;
}

button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}

.test-credentials {
  margin-top: 1rem;
  padding: 1rem;
  background: #e7f3ff;
  border-radius: 4px;
  font-size: 0.9rem;
}

.test-credentials p {
  margin: 0.25rem 0;
}

.actions-container {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-bottom: 2rem;
}

.btn-logout {
  background-color: #dc3545;
}

.btn-logout:hover {
  background-color: #c82333;
}

.btn-test {
  background-color: #28a745;
}

.btn-test:hover {
  background-color: #218838;
}

.message {
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
  text-align: center;
}

.message.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.message.error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.api-result {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid #dee2e6;
}

.api-result h3 {
  margin-top: 0;
  color: #495057;
}

.api-result pre {
  background: white;
  padding: 1rem;
  border-radius: 4px;
  overflow-x: auto;
  max-height: 400px;
  overflow-y: auto;
}
</style>
