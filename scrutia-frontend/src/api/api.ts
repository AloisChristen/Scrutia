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
}

const staticHeader: Dictionary<string> = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
  // "User-Agent": "scrutia"
}

function makeBaseHeader() {
  const baseHeader = staticHeader
  if (store.getters.isConnected) {
    baseHeader['Authorization'] = 'Bearer' + store.getters.token
  }
  baseHeader['Authorization'] =
    'Bearer 1|BOJtOmLjwTlKN724GsrdIMbW2guAOM1Q70ljqXwZ'
  return baseHeader
}

export function makeHeader(customHeaders: any) {
  return {
    ...makeBaseHeader(),
    ...customHeaders,
  }
}
