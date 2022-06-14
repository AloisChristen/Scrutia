import {
  LikeDTO,
  QuestionStoreDTO,
  QuestionUpdateDTO,
} from '@/typings/scrutia-types'
import { api, makeHeader } from '../api'

export async function addQuestion(question: QuestionStoreDTO) {
  return await fetch(api.questions, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(question),
  })
}

export async function udpateQuestion(
  questionId: number,
  question: QuestionUpdateDTO
) {
  return await fetch(`${api.questions}/${questionId}`, {
    method: 'PUT',
    headers: makeHeader({}),
    body: JSON.stringify(question),
  })
}

export async function deleteQuestion(questionId: number) {
  return await fetch(`${api.questions}/${questionId}`, {
    method: 'DELETE',
    headers: makeHeader({}),
  })
}

export async function likeQuestion(questionId: number, like: LikeDTO) {
  return await fetch(`${api.questions}/${questionId}/like`, {
    method: 'POST',
    headers: makeHeader({}),
    body: JSON.stringify(like),
  })
}
