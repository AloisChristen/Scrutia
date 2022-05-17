import { Project } from '../../typings/scrutia-types'
import { api, header } from '../api'

export async function getProjects() {
  const result = await fetch(api.projects, {
    method: 'GET',
    headers: header,
  })

  console.log(result)
}

export async function addProject(idea: Project) {
  const result = await fetch(api.projects, {
    method: 'POST',
    headers: header,
    body: JSON.stringify(idea),
  })

  console.log(result)
}

export async function getIdeas() {
  const result = await fetch(`${api.projects}/ideas`, {
    method: 'GET',
    headers: header,
  })

  console.log(result)
}

export async function getInitiatives() {
  const result = await fetch(`${api.projects}/initiatives`, {
    method: 'GET',
    headers: header,
  })

  console.log(result)
}

export async function getProjectDetails(projectId: number) {
  const result = await fetch(`${api.projects}/${projectId}`, {
    method: 'GET',
    headers: header,
  })

  console.log(result)
}

export async function promoteProject(projectId: number) {
  const result = await fetch(`${api.projects}/${projectId}/promote`, {
    method: 'PUT',
    headers: header,
  })

  console.log(result)
}
