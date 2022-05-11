import { api } from "../api";
import { SessionDTO } from "../dto/userDTO";

class UserService {

  constructor(api){
    this.api = api
  }

  async login(user){
    let endpoint= this.api.auth.signin
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
    let endpoint = this.api.auth.signout
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
    let endpoint = this.api.auth.register
    console.log(this.api)
    return fetch(
      endpoint.url,
      {
        method: endpoint.method,
        headers: {
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(registerAccountDTO)
      }
    ).then((resp) => {
      let body = resp.json()
      return new SessionDTO(
        body.token,
        body.user.id,
        body.user.username,
        body.user.firstname,
        body.user.lastname,
        body.user.email,
        body.user.email_verified_at,
        body.user.reputation
        )
    }
  }
}

let userService = new UserService(api);

export {userService}
