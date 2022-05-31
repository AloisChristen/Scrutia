import { api, makeHeader } from '../api'

export async function getFavorites() {
  return await fetch(api.favorites, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function addFavorite(projectId: number) {
  return await fetch(`${api.favorites}`, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify({ project_id: projectId }),
  })
}

export async function deleteFavorite(projectId: number) {
  return await fetch(`${api.favorites}/${projectId}`, {
    method: 'DELETE',
    headers: makeHeader({}),
  })
}
