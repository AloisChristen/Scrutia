let baseUrl = "https://scrutia-api-dev.herokuapp.com/api"

let api = {
  auth: {
    signup: {
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
