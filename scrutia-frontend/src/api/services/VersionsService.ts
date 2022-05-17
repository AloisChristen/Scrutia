import {
  LikeDTO,
  VersionStore,
  VersionUpdateDTO,
} from '@/typings/scrutia-types'
import { api, makeHeader } from '../api'

export async function addVersion(version: VersionStore) {
  return await fetch(api.versions, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(version),
  })
}

export async function updateVersion(
  versionId: number,
  version: VersionUpdateDTO
) {
  return await fetch(`${api.versions}/${versionId}`, {
    method: 'PUT',
    headers: makeHeader({}),
    body: JSON.stringify(version),
  })
}

export async function deleteVersion(versionId: number) {
  return await fetch(`${api.versions}/${versionId}`, {
    method: 'DELETE',
    headers: makeHeader({}),
  })
}

export async function likeVersion(versionId: number, like: LikeDTO) {
  return await fetch(`${api.versions}/${versionId}/like`, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(like),
  })
}
