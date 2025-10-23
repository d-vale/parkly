<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">
      Test d'Authentification - Parkly
    </h1>

    <!-- Statut de connexion -->
    <div
      class="p-6 rounded-lg mb-8 text-lg"
      :class="
        isAuthenticated
          ? 'bg-green-100 border-2 border-green-500 text-green-800'
          : 'bg-red-100 border-2 border-red-500 text-red-800'
      "
    >
      <strong>Statut:</strong>
      {{ isAuthenticated ? "Connecté" : "Non connecté" }}
      <div v-if="user" class="mt-4 pt-4 border-t border-gray-300">
        <p class="my-2"><strong>Nom:</strong> {{ user.first_name }} {{ user.last_name }}</p>
        <p class="my-2"><strong>Email:</strong> {{ user.email }}</p>
      </div>
    </div>

    <!-- Formulaires d'authentification (si non connecté) -->
    <div v-if="!isAuthenticated" class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
      <!-- Formulaire d'inscription -->
      <div class="bg-gray-50 p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Inscription</h2>
        <form @submit.prevent="handleRegister" class="space-y-4">
          <input
            v-model="registerForm.first_name"
            type="text"
            placeholder="Prénom"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <input
            v-model="registerForm.last_name"
            type="text"
            placeholder="Nom"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <input
            v-model="registerForm.email"
            type="email"
            placeholder="Email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <input
            v-model="registerForm.password"
            type="password"
            placeholder="Mot de passe"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <input
            v-model="registerForm.password_confirmation"
            type="password"
            placeholder="Confirmer le mot de passe"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <button
            type="submit"
            :disabled="loading"
            class="w-full px-4 py-3 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
          >
            {{ loading ? "Inscription..." : "S'inscrire" }}
          </button>
        </form>
      </div>

      <!-- Formulaire de connexion -->
      <div class="bg-gray-50 p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Connexion</h2>
        <form @submit.prevent="handleLogin" class="space-y-4">
          <input
            v-model="loginForm.email"
            type="email"
            placeholder="Email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <input
            v-model="loginForm.password"
            type="password"
            placeholder="Mot de passe"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <button
            type="submit"
            :disabled="loading"
            class="w-full px-4 py-3 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
          >
            {{ loading ? "Connexion..." : "Se connecter" }}
          </button>
        </form>
        <div class="mt-6 p-4 bg-blue-50 rounded-md text-sm">
          <p class="font-semibold mb-2">Comptes de test:</p>
          <p class="my-1">admin@parkly.ch / admin123</p>
          <p class="my-1">jean.dupont@example.com / password</p>
        </div>
      </div>
    </div>

    <!-- Actions si connecté -->
    <div v-else class="flex flex-wrap gap-4 justify-center mb-8">
      <button
        @click="handleLogout"
        class="px-6 py-3 bg-red-600 text-white rounded-md font-medium hover:bg-red-700 transition-colors"
      >
        Se déconnecter
      </button>
      <button
        @click="testProtectedRoute"
        class="px-6 py-3 bg-green-600 text-white rounded-md font-medium hover:bg-green-700 transition-colors"
      >
        Tester l'API (Récupérer les parkings)
      </button>
    </div>

    <!-- Messages -->
    <div
      v-if="message"
      class="p-4 rounded-md mb-4 text-center"
      :class="
        message.type === 'success'
          ? 'bg-green-100 text-green-800 border border-green-200'
          : 'bg-red-100 text-red-800 border border-red-200'
      "
    >
      {{ message.text }}
    </div>

    <!-- Résultats de l'API -->
    <div v-if="apiResult" class="bg-gray-50 p-6 rounded-lg border border-gray-200">
      <h3 class="text-xl font-semibold text-gray-700 mb-4">Résultat de l'API:</h3>
      <pre class="bg-white p-4 rounded-md overflow-x-auto max-h-96 overflow-y-auto text-sm">{{ apiResult }}</pre>
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
