import { api } from "../api";
import { LoginDTO } from "../dto/loginDTO";
import { RegisterAccountDTO } from "../dto/registerAccountDTO";
import { SessionDTO, UserDTO } from "../dto/userDTO";

class UserService {
  api: any;

  constructor(api:any){
    this.api = api
  }

  async login(loginDTO:LoginDTO){
    const endpoint= this.api.auth.signin
    return fetch(
      endpoint.url,
      {
        method: endpoint.method,
        headers: {
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({loginDTO})
      }
    ).then(async (resp:any) => {
      if(!resp.ok){
        // TODO comment faire pour mettre les messages d'erreurs dans le formulaire ?
        console.log(resp.statusText)
      } else {
        const body:SessionDTO = await resp.json() as SessionDTO
        return body
      }
    })
  }

  async logout(){
    const endpoint = this.api.auth.signout
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

  async register(registerAccountDTO:RegisterAccountDTO){
    const endpoint = this.api.auth.register
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
    ).then(async (resp:any) => {
      if(!resp.ok){
        // TODO comment faire pour mettre les messages d'erreurs dans le formulaire ?
        console.log(resp.statusText)
      } else {
        const body:SessionDTO = await resp.json() as SessionDTO
        return body
      }
    })
  }
}
const userService = new UserService(api)
export {userService}
