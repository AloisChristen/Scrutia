import Vue from 'vue'
import Vuex from 'vuex'
import VueCookies from 'vue-cookies-ts'
import {
  AuthUserDTO,
  ProjectDetailsDTO,
  ProjectPaginationDTO,
} from '@/typings/scrutia-types'
import { getIdeas, getProjects } from '@/api/services/ProjectsService'

// Register Vuex
Vue.use(Vuex)
Vue.use(VueCookies)

const sessionCookieName = 'ScrutiaSession'

// Helpers
const helpers = {
  getWindowWidth() {
    return (
      window.innerWidth ||
      document.documentElement.clientWidth ||
      document.body.clientWidth
    )
  },
  getCurrentYear() {
    return new Date().getFullYear()
  },
  loadSession() {
    if (Vue.prototype.$cookies.isKey(sessionCookieName)) {
      const session = Vue.prototype.$cookies.get(sessionCookieName)
      return {
        currentUser: session.user,
        authToken: session.token,
      }
    } else {
      return {
        currentUser: null,
        authToken: null,
      }
    }
  },
  saveSession(session: AuthUserDTO | null) {
    if (Vue.prototype.$cookies.isKey(sessionCookieName)) {
      Vue.prototype.$cookies.remove(sessionCookieName)
    }
    if (session != null) {
      Vue.prototype.$cookies.set(sessionCookieName, session, { secure: true })
    }
  },
}

// Vuex Store
export default new Vuex.Store({
  state: {
    // App vital details
    app: {
      name: 'Scrutia',
      version: process.env.PACKAGE_VERSION,
      copyright: helpers.getCurrentYear(),
    },

    // User session
    session: helpers.loadSession(),

    // Trends
    trendings: {
      ideas: [] as ProjectDetailsDTO[],
      nbIdeas: 0,
      projects: [] as ProjectDetailsDTO[],
      nbProjects: 0,
      isLoadingProjects: false,
      isLoadingIdeas: false,
    },

    // Default layout options
    layout: {
      header: true,
    },

    // Default template settings
    // Various of them are also set in each layout variation under layouts/variations/ folder
    settings: {
      colorTheme: '', // 'amethyst', 'city', 'flat', 'modern', 'smooth'
      pageOverlay: true,
      headerFixed: true,
      headerDark: false,
      headerSearch: false,
      headerLoader: false,
      pageLoader: false,
      rtlSupport: false,
      sideTransitions: true,
      mainContent: '', // 'boxed', ''narrow'
    },
  },
  getters: {
    // Get App name
    appName: (state) => {
      return state.app.name
    },
    // Get App version
    appVersion: (state) => {
      return state.app.version
    },
    // Get App copyright year
    appCopyright: (state) => {
      return state.app.copyright
    },
    // Get app color theme
    appColorTheme: (state) => {
      return state.settings.colorTheme
    },
    isConnected: (state) => {
      return (
        state.session.currentUser != null && state.session.authToken != null
      )
    },
    // Get current connected User
    currentUser: (state) => {
      return state.session.currentUser
    },
    // GET authToken
    authToken: (state) => {
      return state.session.authToken
    },
    isLoadingProjects: (state) => {
      return state.trendings.isLoadingProjects
    },
    isLoadingIdeas: (state) => {
      return state.trendings.isLoadingIdeas
    },
    nbProjects: (state) => {
      return state.trendings.nbProjects
    },
    nbIdeas: (state) => {
      return state.trendings.nbIdeas
    },
  },
  mutations: {
    // Load trending ideas
    async loadIdeas(state) {
      state.trendings.isLoadingIdeas = true
      const response: Response = await getIdeas()
      if (response.ok) {
        const projectsPagingation: ProjectPaginationDTO = await response.json()
        state.trendings.nbIdeas = projectsPagingation.total
        for (let i = 0; i < projectsPagingation.data.length && i < 6; i++)
          state.trendings.ideas.push(projectsPagingation.data[i])
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des idées",
          showConfirmButton: true,
        })
      }
      state.trendings.isLoadingIdeas = false
    },
    // Load trending projects
    async loadProjects(state) {
      state.trendings.isLoadingProjects = true
      const response: Response = await getProjects()
      if (response.ok) {
        const projectsPagingation: ProjectPaginationDTO = await response.json()
        state.trendings.nbProjects = projectsPagingation.total
        for (let i = 0; i < projectsPagingation.data.length && i < 4; i++)
          state.trendings.projects.push(projectsPagingation.data[i])
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des projets",
          showConfirmButton: true,
        })
      }
      state.trendings.isLoadingProjects = false
    },
    // Set currentUser and authToken
    connect(state, payload) {
      state.session.currentUser = payload.user
      state.session.authToken = payload.token
      helpers.saveSession(payload)
    },
    // Disconnect current user
    disconnect(state) {
      state.session.authToken = null
      state.session.currentUser = null
      helpers.saveSession(null)
    },
    // Sets the layout, useful for setting different layouts (under layouts/variations/)
    setLayout(state, payload) {
      state.layout.header = payload.header
    },
    // Sets page overlay visibility (on, off, toggle)
    pageOverlay(state, payload) {
      if (payload.mode === 'on') {
        state.settings.pageOverlay = true
      } else if (payload.mode === 'off') {
        state.settings.pageOverlay = false
      } else if (payload.mode === 'toggle') {
        state.settings.pageOverlay = !state.settings.pageOverlay
      }
    },
    // Sets header mode (fixed, static, toggle)
    header(state, payload) {
      if (payload.mode === 'fixed') {
        state.settings.headerFixed = true
      } else if (payload.mode === 'static') {
        state.settings.headerFixed = false
      } else if (payload.mode === 'toggle') {
        state.settings.headerFixed = !state.settings.headerFixed
      }
    },
    // Sets header style (dark, light, toggle)
    headerStyle(state, payload) {
      if (payload.mode === 'dark') {
        state.settings.headerDark = true
      } else if (payload.mode === 'light') {
        state.settings.headerDark = false
      } else if (payload.mode === 'toggle') {
        state.settings.headerDark = !state.settings.headerDark
      }
    },
    // Sets header search visibility (on, off, toggle)
    headerSearch(state, payload) {
      if (payload.mode === 'on') {
        state.settings.headerSearch = true
      } else if (payload.mode === 'off') {
        state.settings.headerSearch = false
      } else if (payload.mode === 'toggle') {
        state.settings.headerSearch = !state.settings.headerSearch
      }
    },
    // Sets header loader visibility (on, off, toggle)
    headerLoader(state, payload) {
      if (payload.mode === 'on') {
        state.settings.headerLoader = true
      } else if (payload.mode === 'off') {
        state.settings.headerLoader = false
      } else if (payload.mode === 'toggle') {
        state.settings.headerLoader = !state.settings.headerLoader
      }
    },
    // Sets page loader visibility (on, off, toggle)
    pageLoader(state, payload) {
      if (payload.mode === 'on') {
        state.settings.pageLoader = true
      } else if (payload.mode === 'off') {
        state.settings.pageLoader = false
      } else if (payload.mode === 'toggle') {
        state.settings.pageLoader = !state.settings.pageLoader
      }
    },
    // Sets main content mode (full, boxed, narrow)
    mainContent(state, payload) {
      if (payload.mode === 'full') {
        state.settings.mainContent = ''
      } else if (payload.mode === 'boxed') {
        state.settings.mainContent = 'boxed'
      } else if (payload.mode === 'narrow') {
        state.settings.mainContent = 'narrow'
      }
    },
    // Sets active color theme
    setColorTheme(state, payload) {
      // Matches all classes which start with "theme-"
      const regx = new RegExp('\\btheme-[^ ]*[ ]?\\b', 'g')

      // Set new theme
      state.settings.colorTheme = payload.theme || ''

      // Remove all classes which start with "theme-" from body element
      document.body.className = document.body.className.replace(regx, '')

      // If theme is set, add the theme class to body element
      if (payload.theme) {
        document.body.classList.add('theme-' + payload.theme)
      }
    },
  },
})
