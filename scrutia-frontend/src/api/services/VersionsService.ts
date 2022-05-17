import {
  LikeDTO,
  VersionStore,
  VersionUpdateDTO,
} from '@/typings/scrutia-types'
import { api, header } from '../api'

export async function addVersion(version: VersionStore) {
  const result = await fetch(api.versions, {
    method: 'POST',
    headers: header,
    body: JSON.stringify(version),
  })

  console.log(result)
}

export async function updateVersion(
  versionId: number,
  version: VersionUpdateDTO
) {
  const result = await fetch(`${api.versions}/${versionId}`, {
    method: 'PUT',
    headers: header,
    body: JSON.stringify(version),
  })

  console.log(result)
}

export async function deleteVersion(versionId: number) {
  const result = await fetch(`${api.versions}/${versionId}`, {
    method: 'DELETE',
    headers: header,
  })

  console.log(result)
}

export async function likeVersion(versionId: number, like: LikeDTO) {
  const result = await fetch(`${api.versions}/${versionId}/like`, {
    method: 'POST',
    headers: header,
    body: JSON.stringify(like),
  })

  console.log(result)
}
