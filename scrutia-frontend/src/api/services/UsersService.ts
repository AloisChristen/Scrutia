import { UserUdpateDTO } from '@/typings/scrutia-types'
import { api, makeHeader } from '../api'

export async function getUser() {
  return await fetch(api.user, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function updateUser(user: UserUdpateDTO) {
  return await fetch(api.user, {
    method: 'PUT',
    headers: makeHeader({}),
    body: JSON.stringify(user),
  })
}
