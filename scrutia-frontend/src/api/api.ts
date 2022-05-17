const baseUrl = 'https://scrutia-api-dev.herokuapp.com/api'

const header = {
  'Content-Type': 'application/json;charset=utf-8',
}

const api = {
  auth: {
    register: {
      url: baseUrl + '/register',
      method: 'POST',
    },
    signin: {
      url: baseUrl + '/login',
      method: 'POST',
    },
    signout: {
      url: baseUrl + '/logout',
      method: 'POST',
    },
  },
  answers: baseUrl + '/answers',
  projects: baseUrl + '/projects',
  questions: baseUrl + '/questions',
  tags: baseUrl + '/tags',
  user: baseUrl + '/user',
  versions: baseUrl + '/versions',
}

export { api, header }
