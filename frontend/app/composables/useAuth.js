export const useAuth = () => {
    // État réactif partagé de l'utilisateur
    const user = useState('user', () => null)
    // Propriété calculée pour vérifier si l'utilisateur est authentifié
    const isAuthenticated = computed(() => !!user.value)

    // Utiliser le composable useFetch pour les appels API
    const { get, post } = useApiFetch()

    // Inscription
    const register = async (credentials) => {
        const response = await post('/api/register', credentials)

        if (response.success && response.data?.user) {
            user.value = response.data.user
            return { success: true, user: response.data.user }
        }

        // En cas d'erreur, retourner le message d'erreur
        return { 
            success: false, 
            error: response.error || 'Erreur lors de l\'inscription',
            data: response.data
        }
    }

    // Connexion
    const login = async (credentials) => {
        const response = await post('/api/login', credentials)

        if (response.success && response.data?.user) {
            user.value = response.data.user
            return { success: true, user: response.data.user }
        }

        // En cas d'erreur, retourner le message d'erreur
        return { 
            success: false, 
            error: response.error || 'Erreur lors de la connexion',
            data: response.data
        }
    }

    // Déconnexion
    const logout = async () => {
        const response = await post('/api/logout')
        user.value = null
        return response
    }

    // Vérifier l'authentification
    const checkAuth = async () => {
        const response = await get('/api/check')

        if (response.success && response.data?.authenticated && response.data?.user) {
            user.value = response.data.user
        } else {
            user.value = null
        }

        return response
    }

    // Récupérer les parkings (test API protégée)
    const fetchParkings = async () => {
        return await get('/api/parkings')
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