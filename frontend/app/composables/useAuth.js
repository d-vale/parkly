// frontend/composables/useAuth.js
export const useAuth = () => {
    const user = useState('user', () => null)
    const isAuthenticated = computed(() => !!user.value)

    const apiUrl = 'http://localhost:8000'

    // Récupérer le cookie CSRF
    const getCsrfCookie = async () => {
        await $fetch(`${apiUrl}/sanctum/csrf-cookie`, {
            credentials: 'include'
        })
    }

    // Inscription
    const register = async (credentials) => {
        await getCsrfCookie()

        const data = await $fetch(`${apiUrl}/api/register`, {
            method: 'POST',
            credentials: 'include',
            body: credentials
        })

        if (data.success) {
            user.value = data.user
        }

        return data
    }

    // Connexion
    const login = async (credentials) => {
        await getCsrfCookie()

        const data = await $fetch(`${apiUrl}/api/login`, {
            method: 'POST',
            credentials: 'include',
            body: credentials
        })

        if (data.success) {
            user.value = data.user
        }

        return data
    }

    // Déconnexion
    const logout = async () => {
        await $fetch(`${apiUrl}/api/logout`, {
            method: 'POST',
            credentials: 'include'
        })

        user.value = null
    }

    // Vérifier l'authentification
    const checkAuth = async () => {
        try {
            const data = await $fetch(`${apiUrl}/api/check`, {
                credentials: 'include'
            })

            if (data.authenticated && data.user) {
                user.value = data.user
            } else {
                user.value = null
            }
        } catch (error) {
            user.value = null
        }
    }

    // Récupérer les parkings (test API protégée)
    const fetchParkings = async () => {
        return await $fetch(`${apiUrl}/api/parkings`, {
            credentials: 'include'
        })
    }

    return {
        user,
        isAuthenticated,
        register,
        login,
        logout,
        checkAuth,
        fetchParkings
    }
}