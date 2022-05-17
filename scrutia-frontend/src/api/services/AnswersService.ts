import {
  AnswerStoreDTO,
  AnswerUpdateDTO,
  LikeDTO,
} from '@/typings/scrutia-types'
import { api, header } from '../api'

export async function addAnswer(answer: AnswerStoreDTO) {
  const result = await fetch(api.answers, {
    method: 'POST',
    headers: header,
    body: JSON.stringify(answer),
  })

  console.log(result)
}

export async function updateAnswer(answerId: number, answer: AnswerUpdateDTO) {
  const result = await fetch(`${api.answers}/${answerId}`, {
    method: 'PUT',
    headers: header,
    body: JSON.stringify(answer),
  })

  console.log(result)
}

export async function deleteAnswer(answerId: number) {
  const result = await fetch(`${api.answers}/${answerId}`, {
    method: 'DELETE',
    headers: header,
  })

  console.log(result)
}

export async function likeAnswer(answerId: number, like: LikeDTO) {
  const result = await fetch(`${api.answers}/${answerId}/like`, {
    method: 'POST',
    headers: header,
    body: JSON.stringify(like),
  })

  console.log(result)
}
