/*
 * Default Router
 */

// Vue and Vue Router
import Vue from 'vue'
import Router from 'vue-router'

// Main layouts
import LayoutBackendBoxed from '@/layouts/variations/BackendBoxed.vue'
import LayoutSimple from '@/layouts/variations/Simple.vue'

// Register Vue Router
Vue.use(Router)

// Pages: Auth
const AuthSignIn = () => import("@/views/pages/auth/SignIn.vue")
const AuthSignUp = () => import("@/views/pages/auth/SignUp.vue")
const AuthReminder = () => import("@/views/pages/auth/Reminder.vue")


// Pages: Boxed Backend
const Blank = () => import("@/views/pages/Blank.vue")

// Router Configuration
export default new Router({
  linkActiveClass: 'active',
  linkExactActiveClass: '',
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      redirect: '/Home',
      component: LayoutBackendBoxed,
      children: [
        {
          path: 'Home',
          name: 'Scrutia | Home',
          component: Blank
        },
      ]
    },
    {
      path: '/auth',
      component: LayoutSimple,
      children: [
        {
          path: 'signin',
          name: 'Sign In',
          component: AuthSignIn
        },
        {
          path: 'signup',
          name: 'Sign Up',
          component: AuthSignUp
        },
        {
          path: 'reminder',
          name: 'Auth Reminder',
          component: AuthReminder
        },
      ]
    },
  ]
})
