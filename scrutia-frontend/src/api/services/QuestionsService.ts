import {
  LikeDTO,
  QuestionStoreDTO,
  QuestionUpdateDTO,
} from '@/typings/scrutia-types'
import { api, header } from '../api'

export async function addQuestion(question: QuestionStoreDTO) {
  const result = await fetch(api.questions, {
    method: 'POST',
    headers: header,
    body: JSON.stringify(question),
  })

  console.log(result)
}

export async function udpateQuestion(
  questionId: number,
  question: QuestionUpdateDTO
) {
  const result = await fetch(`${api.questions}/${questionId}`, {
    method: 'PUT',
    headers: header,
    body: JSON.stringify(question),
  })

  console.log(result)
}

export async function deleteQuestion(questionId: number) {
  const result = await fetch(`${api.questions}/${questionId}`, {
    method: 'DELETE',
    headers: header,
  })

  console.log(result)
}

export async function likeAnswer(questionId: number, like: LikeDTO) {
  const result = await fetch(`${api.questions}/${questionId}/like`, {
    method: 'POST',
    headers: header,
    body: JSON.stringify(like),
  })

  console.log(result)
}
