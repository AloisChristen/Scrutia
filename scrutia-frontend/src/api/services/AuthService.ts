import { api } from '../api'
import { LoginDTO, RegisterUser } from '@/typings/scrutia-types'

export async function login(loginDTO: LoginDTO) {
  return await fetch(api.signin, {
    method: 'POST',
    headers: this.makeHeader({}),
    body: JSON.stringify({ loginDTO }),
  })
}

export async function logout() {
  return await fetch(api.signout, {
    method: 'POST',
    headers: this.makeHeader({}),
  })
}

export async function register(registerAccountDTO: RegisterUser) {
  return await fetch(api.register, {
    method: 'POST',
    headers: this.makeHeader({}),
    body: JSON.stringify(registerAccountDTO),
  })
}
