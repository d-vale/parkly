<template>
  <div class="home-container">
    <h1>Bienvenue sur Parkly</h1>
    <p>Application de gestion et réservation de places de parking</p>

    <div class="nav-links">
      <NuxtLink to="/auth-test" class="btn-primary">
        Tester l'authentification
      </NuxtLink>
    </div>
  </div>
</template>

<style scoped>
.home-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 4rem 2rem;
  text-align: center;
  font-family: system-ui, -apple-system, sans-serif;
}

h1 {
  font-size: 3rem;
  color: #333;
  margin-bottom: 1rem;
}

p {
  font-size: 1.25rem;
  color: #666;
  margin-bottom: 3rem;
}

.nav-links {
  display: flex;
  justify-content: center;
  gap: 1rem;
}

.btn-primary {
  display: inline-block;
  padding: 1rem 2rem;
  background-color: #007bff;
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-size: 1.1rem;
  transition: background-color 0.2s;
}

.btn-primary:hover {
  background-color: #0056b3;
}
</style>
