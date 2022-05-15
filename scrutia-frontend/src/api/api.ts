//TODO change this depending on dev/prod
const baseUrl = "https://scrutia-api-dev.herokuapp.com/api"

const api = {
  auth: {
    register: {
      url: baseUrl + "/register",
      method: "POST"
    },
    signin: {
      url: baseUrl + "/login",
      method: "POST"
    },
    signout: {
      url: baseUrl + "/logout",
      method: "POST"
    }
  }
}

export  {api}
