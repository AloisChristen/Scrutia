import Vue from 'vue'
import Router from 'vue-router'
import LayoutBackendBoxed from '@/layouts/variations/BackendBoxed.vue'
import LayoutSimple from '@/layouts/variations/Simple.vue'

// Register Vue Router
Vue.use(Router)

// Pages: Auth
const AuthSignIn = () => import("@/views/pages/auth/SignIn.vue")
const AuthSignUp = () => import("@/views/pages/auth/SignUp.vue")
const AuthReminder = () => import("@/views/pages/auth/Reminder.vue")

// Pages: Boxed Backend
const Home = () => import("@/views/pages/Home.vue")
const BrowseIdeas = () => import("@/views/pages/BrowseIdeas.vue")
const AddIdea = () => import("@/views/pages/AddIdea.vue")
const IdeaDetails = () => import("@/views/pages/IdeaDetails.vue")
const Chat = () => import("@/views/pages/Chat.vue")
const BrowseInitiatives = () => import("@/views/pages/BrowseInitiatives.vue")
const InitiativeDetails = () => import("@/views/pages/InitiativeDetails.vue")
const UserIdeasAndInitiatives = () => import("@/views/pages/UserIdeasAndInitiatives.vue")
const Landing = () => import("@/views/pages/Landing.vue")
const Search = () => import("@/views/pages/Search.vue")

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
          component: Home
        },
        {
          path: 'browseIdeas',
          name: 'Scrutia | Parcourir les idées',
          component: BrowseIdeas
        },
        {
          path: 'addIdea',
          name: 'Scrutia | Ajouter une idée',
          component: AddIdea
        },
        {
          path: 'ideaDetails',
          name: 'Scrutia | Détails de l\'idée',
          component: IdeaDetails
        },
        {
          path: 'chat',
          name: 'Scrutia | Discussions',
          component: Chat
        },
        {
          path: 'browseInitiatives',
          name: 'Scrutia | Parcourir les initiatives',
          component: BrowseInitiatives
        },
        {
          path: 'initiativeDetails',
          name: 'Scrutia | Détails de l\'initiative',
          component: InitiativeDetails
        },
        {
          path: 'user',
          name: 'Scrutia | Mes idées et initiatives',
          component: UserIdeasAndInitiatives
        },
        {
          path: 'search',
          name: 'Scrutia | Recherche',
          component: Search
        },
        {
          path: 'about',
          name: 'Scrutia | Mes idées et initiatives',
          component: Landing
        },
      ]
    },
    {
      path: '/auth',
      component: LayoutSimple,
      children: [
        {
          path: 'signin',
          name: 'Scrutia | Connexion',
          component: AuthSignIn
        },
        {
          path: 'signup',
          name: 'Scrutia | Inscription',
          component: AuthSignUp
        },
        {
          path: 'reminder',
          name: 'Scrutia | Changer de mot de passe',
          component: AuthReminder
        },
      ]
    },
  ]
})
