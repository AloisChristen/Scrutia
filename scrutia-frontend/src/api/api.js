let baseUrl = "https://scrutia-api-dev.herokuapp.com/api"

let api = {
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
  },
  answers: {
    addAnswer: {
      url: baseUrl + "/answers",
      method: "POST"
    },
    updateAnswer: {
      url: baseUrl + "/answers",
      method: "PUT"
    },
    deleteAnswer: {
      url: baseUrl + "/answers",
      method: "DELETE"
    },
    likeAnswer: {
      url: baseUrl + "/answers",
      method: "POST"
    }
  },
  projects: {
    getProjects: {
      url: baseUrl + "/projects",
      method: "GET"
    },
    addProject: {
      url: baseUrl + "/projects",
      method: "POST"
    },
    getIdeas: {
      url: baseUrl + "/projects/ideas",
      method: "GET"
    },
    getInitiatives: {
      url: baseUrl + "/projects/initiatives",
      method: "GET"
    },
    getProject: {
      url: baseUrl + "/projects",
      method: "GET"
    },
    promote: {
      url: baseUrl + "/projects",
      method: "POST"
    }
  },
  questions: {
    addQuestion: {
      url: baseUrl + "/questions",
      method: "POST"
    },
    editQuestion: {
      url: baseUrl + "/questions",
      method: "PUT"
    },
    deleteQuestion: {
      url: baseUrl + "/questions",
      method: "DELETE"
    },
    likeQuestion: {
      url: baseUrl + "/questions",
      method: "POST"
    },
  },
  tags: {
    getTags: {
      url: baseUrl + "/tags",
      method: "GET"
    }
  },
  users: {
    getUser: {
      url: baseUrl + "/users",
      method: "GET"
    },
    addUser: {
      url: baseUrl + "/users",
      method: "POST"
    },
    updateUser: {
      url: baseUrl + "/users",
      method: "PUT"
    }
  },
  versions: {
    addVersion: {
      url: baseUrl + "/versions",
      method: "POST"
    },
    updateVersion: {
      url: baseUrl + "/versions",
      method: "PUT"
    },
    deleteVersion: {
      url: baseUrl + "/versions",
      method: "DELETE"
    },
    likeVersion: {
      url: baseUrl + "/versions",
      method: "POST"
    }
  }
}

export { api }
