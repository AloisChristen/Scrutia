import { Project } from '@/typings/scrutia-types'
import { api, makeHeader } from '../api'

export async function getProjects() {
  return await fetch(api.projects, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function addProject(idea: Project) {
  return await fetch(api.projects, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(idea),
  })
}

export async function getIdeas() {
  return await fetch(`${api.projects}/ideas`, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function getInitiatives() {
  return await fetch(`${api.projects}/initiatives`, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function getProjectDetails(projectId: number) {
  return await fetch(`${api.projects}/${projectId}`, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function promoteProject(projectId: number) {
  return await fetch(`${api.projects}/${projectId}/promote`, {
    method: 'PUT',
    headers: makeHeader({}),
  })
}