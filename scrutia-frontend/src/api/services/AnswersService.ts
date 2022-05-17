import {
  AnswerStoreDTO,
  AnswerUpdateDTO,
  LikeDTO,
} from '@/typings/scrutia-types'
import { api, makeHeader } from '../api'

export async function addAnswer(answer: AnswerStoreDTO) {
  return await fetch(api.answers, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(answer),
  })
}

export async function updateAnswer(answerId: number, answer: AnswerUpdateDTO) {
  return await fetch(`${api.answers}/${answerId}`, {
    method: 'PUT',
    headers: makeHeader({}),
    body: JSON.stringify(answer),
  })
}

export async function deleteAnswer(answerId: number) {
  return await fetch(`${api.answers}/${answerId}`, {
    method: 'DELETE',
    headers: makeHeader({}),
  })
}

export async function likeAnswer(answerId: number, like: LikeDTO) {
  return await fetch(`${api.answers}/${answerId}/like`, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(like),
  })
}
