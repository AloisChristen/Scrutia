import { UserUdpateDTO } from '../../typings/scrutia-types'
import { api, header } from '../api'

export async function getUser() {
  const result = await fetch(api.user, {
    method: 'GET',
    headers: header,
  })

  console.log(result)
}

export async function updateUser(user: UserUdpateDTO) {
  const result = await fetch(api.user, {
    method: 'PUT',
    headers: header,
    body: JSON.stringify(user),
  })

  console.log(result)
}
