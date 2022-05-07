import { api } from "../api";

class UserService {

  async login(user){
    let endpoint= api.auth.signin
    return fetch(
      endpoint.url,
      {
        method: endpoint.method,
        headers: {
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({username: user.username, password: user.password})
      }
    )
  }

  async logout(){
    let endpoint = api.auth.signout
    return fetch(
      endpoint.url,
      {
        method: endpoint.method,
        headers: {
          'Content-Type': 'application/json;charset=utf-8'
        },
      }
    )
  }

  async register(registerAccountDTO){
    let endpoint = api.auth.register
    return fetch(
      endpoint.url,
      {
        method: endpoint.method,
        headers: {
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(registerAccountDTO)
      }
    )
  }
}

let userService = new UserService();

export {userService}
