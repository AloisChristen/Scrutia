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

export async function userQuestions() {
  return await fetch(api.user_questions, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function userProjects() {
  return await fetch(api.user_projects, {
    method: 'GET',
    headers: makeHeader({}),
  })
}
