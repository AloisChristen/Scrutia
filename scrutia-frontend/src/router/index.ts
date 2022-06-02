import Vue from 'vue'
import Router from 'vue-router'
import LayoutBackendBoxed from '@/layouts/variations/BackendBoxed.vue'
import LayoutSimple from '@/layouts/variations/Simple.vue'

// Register Vue Router
Vue.use(Router)

// Pages: Auth
const AuthSignIn = () => import('@/views/auth/SignInView.vue')
const AuthSignUp = () => import('@/views/auth/SignUpView.vue')
const AuthReminder = () => import('@/views/auth/ReminderView.vue')
const AuthLogOut = () => import('@/views/auth/LogoutView.vue')

// Pages: Boxed Backend
const Home = () => import('@/views/HomeView.vue')
const BrowseIdeas = () => import('@/views/BrowseIdeasView.vue')
const AddIdea = () => import('@/views/AddIdeaView.vue')
const IdeaDetails = () => import('@/views/IdeaDetailsView.vue')
const Chat = () => import('@/views/ChatView.vue')
const BrowseInitiatives = () => import('@/views/BrowseInitiativesView.vue')
const InitiativeDetails = () => import('@/views/InitiativeDetailsView.vue')
const UserIdeasAndInitiatives = () => import('@/views/UserIdeasAndInitiativesView.vue')
const UserProfile = () => import('@/views/UserProfileView.vue')
const Favorites = () => import('@/views/FavoritesView.vue')
const Landing = () => import('@/views/LandingView.vue')
const Search = () => import('@/views/SearchView.vue')
const Error404 = () => import('@/views/errors/Error404View.vue')

// Router Configuration
export default new Router({
  mode: 'history',
  linkActiveClass: 'active',
  linkExactActiveClass: '',
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      redirect: '/home',
      component: LayoutBackendBoxed,
      children: [
        {
          path: 'home',
          name: 'Scrutia | Accueil',
          component: Home,
        },
        {
          path: 'browseIdeas',
          name: 'Scrutia | Parcourir les idées',
          component: BrowseIdeas,
        },
        {
          path: 'addIdea',
          name: 'Scrutia | Ajouter une idée',
          component: AddIdea,
        },
        {
          path: 'ideaDetails/:project_id',
          name: "Scrutia | Détails de l'idée",
          component: IdeaDetails,
        },
        {
          path: 'chat',
          name: 'Scrutia | Discussions',
          component: Chat,
        },
        {
          path: 'browseInitiatives',
          name: 'Scrutia | Parcourir les initiatives',
          component: BrowseInitiatives,
        },
        {
          path: 'initiativeDetails',
          name: "Scrutia | Détails de l'initiative",
          component: InitiativeDetails,
        },
        {
          path: 'favorites',
          name: 'Scrutia | Mes favoris',
          component: Favorites,
        },
        {
          path: 'userIdeasAndInitiatives',
          name: 'Scrutia | Mes idées et initiatives',
          component: UserIdeasAndInitiatives,
        },
        {
          path: 'userProfile',
          name: 'Scrutia | Mon profil',
          component: UserProfile,
        },
        {
          path: 'search',
          name: 'Scrutia | Recherche',
          component: Search,
        },
        {
          path: 'about',
          name: 'Scrutia | A propos',
          component: Landing,
        },
      ],
    },
    {
      path: '/auth',
      component: LayoutSimple,
      children: [
        {
          path: 'signin',
          name: 'Scrutia | Connexion',
          component: AuthSignIn,
        },
        {
          path: 'signup',
          name: 'Scrutia | Inscription',
          component: AuthSignUp,
        },
        {
          path: 'reminder',
          name: 'Scrutia | Changer de mot de passe',
          component: AuthReminder,
        },
        {
          path: 'logout',
          name: 'Scrutia | Logout',
          component: AuthLogOut,
        },
      ],
    },
    { path: '*', component: Error404, name: 'error404' },
  ],
})
