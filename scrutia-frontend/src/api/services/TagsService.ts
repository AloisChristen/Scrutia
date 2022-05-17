import { api, makeHeader } from '../api'

export async function getTags() {
  return await fetch(api.tags, {
    method: 'GET',
    headers: makeHeader({}),
  })
}
