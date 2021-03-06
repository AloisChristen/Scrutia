import { Dictionary } from 'vue-router/types/router'
import store from '@/store'

//TODO change this depending on dev/prod
const baseUrl = 'https://scrutia-api-dev.herokuapp.com/api'

export const api = {
  register: baseUrl + '/register',
  signin: baseUrl + '/login',
  signout: baseUrl + '/logout',
  answers: baseUrl + '/answers',
  projects: baseUrl + '/projects',
  questions: baseUrl + '/questions',
  tags: baseUrl + '/tags',
  user: baseUrl + '/user',
  versions: baseUrl + '/versions',
  favorites: baseUrl + '/favorites',
  user_questions: baseUrl + '/user/questions',
  user_projects: baseUrl + '/user/projects',
}

const staticHeader: Dictionary<string> = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
  // "User-Agent": "scrutia"
}

function makeBaseHeader() {
  const baseHeader = staticHeader
  if (store.getters.isConnected)
    baseHeader['Authorization'] = 'Bearer ' + store.getters.authToken
  return baseHeader
}

export function makeHeader(customHeaders: any) {
  return {
    ...makeBaseHeader(),
    ...customHeaders,
  }
}
