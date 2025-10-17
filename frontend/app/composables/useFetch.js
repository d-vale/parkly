export const useApiFetch = () => {
    const apiUrl = 'http://localhost:8000'

    // Récupérer le cookie CSRF avant les requêtes POST/PUT/DELETE
    const getCsrfCookie = async () => {
        await $fetch(`${apiUrl}/sanctum/csrf-cookie`, {
            credentials: 'include'
        })
    }

    /**
     * Wrapper autour de $fetch pour simplifier les appels API
     * @param {string} endpoint - Le chemin de l'endpoint (ex: '/api/login')
     * @param {Object} options - Options de la requête
     * @param {string} options.method - Méthode HTTP (GET, POST, PUT, DELETE)
     * @param {Object} options.body - Corps de la requête pour POST/PUT
     * @param {boolean} options.skipCsrf - Permet de skip la récupération du CSRF cookie
     * @returns {Promise} Réponse de l'API
     */
    const apiFetch = async (endpoint, options = {}) => {
        const {
            method = 'GET',
            body = null,
            skipCsrf = false,
            ...otherOptions
        } = options

        // Récupérer le CSRF cookie pour les méthodes qui modifient des données
        if (!skipCsrf && ['POST', 'PUT', 'DELETE', 'PATCH'].includes(method.toUpperCase())) {
            await getCsrfCookie()
        }

        try {
            const response = await $fetch(`${apiUrl}${endpoint}`, {
                method,
                credentials: 'include',
                body,
                ...otherOptions
            })

            return { success: true, data: response }
        } catch (error) {
            // Gestion des erreurs
            console.error('API Fetch Error:', error)
            
            return {
                success: false,
                error: error.data?.message || error.message || 'Une erreur est survenue',
                statusCode: error.statusCode || 500,
                data: error.data || null
            }
        }
    }

    // Méthodes raccourcies pour faciliter l'utilisation
    const get = (endpoint, options = {}) => {
        return apiFetch(endpoint, { ...options, method: 'GET', skipCsrf: true })
    }

    const post = (endpoint, body = null, options = {}) => {
        return apiFetch(endpoint, { ...options, method: 'POST', body })
    }

    const put = (endpoint, body = null, options = {}) => {
        return apiFetch(endpoint, { ...options, method: 'PUT', body })
    }

    const del = (endpoint, options = {}) => {
        return apiFetch(endpoint, { ...options, method: 'DELETE' })
    }

    return {
        apiFetch,
        get,
        post,
        put,
        del,
        apiUrl
    }
}
