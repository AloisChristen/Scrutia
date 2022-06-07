import { ProjectStore, VoteDTO } from '@/typings/scrutia-types'
import { api, makeHeader } from '../api'
import { format } from 'date-fns'

function filterBuilder(
  types: string[] | null,
  text: string | null,
  startDate: Date | null,
  endDate: Date | null,
  tags: string[] | null,
  page: number
) {
  let filters = ''
  if (types && types.length == 1) {
    filters += types.includes('Idées')
      ? 'filter[status]=idee'
      : 'filter[status]=Initiative'
  }
  if (page > 1) filters += `&page=${page}`
  if (text !== null && text !== '') filters += `&filter[title]=${text}`
  if (startDate !== null)
    filters += `&filter[startDate]=${format(startDate, 'yyyy-MM-dd')}`
  if (endDate !== null)
    filters += `&filter[endDate]=${format(endDate, 'yyyy-MM-dd')}`
  if (tags !== null && tags.length > 0) {
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
  tags: string[] | null,
  page: number
) {
  const filters = filterBuilder(types, text, startDate, endDate, tags, page)
  return await fetch(`${api.projects}/nb_per_page/4?${filters}`, {
    method: 'GET',
    headers: makeHeader({}),
  })
}

export async function likeProject(projectId: number, like: number) {
  return await fetch(`${api.projects}/${projectId}/like`, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify({
      value: like,
    }),
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

