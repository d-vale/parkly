FROM node:20-alpine

# Définir le répertoire de travail
WORKDIR /app

# Copier package.json et package-lock.json
COPY package*.json ./

# Installer les dépendances
RUN npm ci

# Copier le reste des fichiers
COPY . .

# Exposer le port
EXPOSE 3000

# Variable d'environnement
ENV NUXT_HOST=0.0.0.0
ENV NUXT_PORT=3000

# Commande de démarrage
CMD ["npm", "run", "dev"]