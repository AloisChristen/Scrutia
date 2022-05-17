import { api, header } from '../api'

export async function getTags() {
  const result = await fetch(api.tags, {
    method: 'GET',
    headers: header,
  })

  console.log(result)
}
