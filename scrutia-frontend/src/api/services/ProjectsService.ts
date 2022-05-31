import { ProjectStore } from '@/typings/scrutia-types'
import { api, makeHeader } from '../api'
import { format } from 'date-fns'

function filterBuilder(
  types: string[] | null,
  text: string | null,
  startDate: Date | null,
  endDate: Date | null,
  tags: string[] | null
) {
  let filters = ''
  if (types !== null && types.length !== 2) {
    filters += types.includes('Idées')
      ? 'filter[status]=idee'
      : 'filter[status]=Initiative'
  }
  if (text !== null) filters += `&filter[title]=${text}`
  if (startDate !== null)
    filters += `&filter[startDate]=${format(startDate, 'yyyy-MM-dd')}`
  if (endDate !== null)
    filters += `&filter[endDate]=${format(endDate, 'yyyy-MM-dd')}`
  if (tags !== null) {
    let tagString = ''
    tags.forEach((tag) => (tagString += tag + ','))
    filters += `&filter[tags]=${tagString}`
  }
  return filters
}

export async function getIdeas() {
  return await fetch(`${api.projects}/nb_per_page/6?filter[status]=idee`, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function getProjects() {
  return await fetch(
    `${api.projects}/nb_per_page/4?filter[status]=Initiative`,
    {
      method: 'GET',
      headers: makeHeader({}),
    }
  )
}

export async function getProjectsWithFilters(
  types: string[] | null,
  text: string | null,
  startDate: Date | null,
  endDate: Date | null,
  tags: string[] | null
) {
  const filters = filterBuilder(types, text, startDate, endDate, tags)
  return await fetch(`${api.projects}/nb_per_page/4?${filters}`, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function addProject(idea: ProjectStore) {
  return await fetch(api.projects, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(idea),
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
