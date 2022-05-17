export type Answer = {
  question_id: number
  title: string
  description: string
}

export type AnswerUpdateDTO = {
  title: string
  description: string
}

export type AnswersDTO = {
  id: number
  description: string
  author: string
  date: Date
  nbLikes: number
  nbDislikes: number
  userVote: Vote
}

export type AuthUser = {
  token: string
  user: User
}

export type Error = {
  message: string
  errors: Object
}

export type LikeDTO = {
  value: number
}

export type Login = {
  username: string
  password: string
}

export type Project = {
  title: string
  description: string
  tags: TagsDTO[]
}

export type ProjectDetailsDTO = {
  id: number
  title: string
  description: string
  author: string
  date: Date
  tags: TagsDTO[]
  nbLikes: number
  nbDislikes: number
  userVote: Vote
  status: ProjectStatus
  questions: QuestionsDTO[]
}

export type ProjectDTO = {
  id: number
  title: string
  description: string
  author: string
  date: Date
  tags: TagsDTO[]
  nbLikes: number
  nbDislikes: number
  userVote: Vote
  status: ProjectStatus
}

export type ProjectPagination = {
  total: number
  per_page: number
  current_page: number
  last_page: number
  first_page_url: string
  last_page_url: string
  next_page_url: string
  prev_page_url: string
  path: string
  from: number
  to: number
  data: ProjectDTO[]
}

export type ProjectStatus = ['idea', 'initiative']

export type Question = {
  project_id: number
  version_number: number
  title: string
  description: string
}

export type QuestionUpdateDTO = {
  title: string
  description: string
}

export type QuestionsDTO = {
  id: number
  title: string
  author: string
  date: string
  nbLikes: number
  nbDislikes: number
  userVote: Vote
  answers: AnswersDTO[]
}

export type RegisterUser = {
  username: string
  firstname: string
  lastname: string
  email: string
  password: string
  password_confirmation: string
}

export type Tags = {
  id: number
  title: string
}

export type TagsDTO = {
  title: string
}

export type User = {
  id: number
  username: string
  password: string
  firstname: string
  lastname: string
  email: string
}

export type UserDTO = {
  username: string
  firstname: string
  lastname: string
  email: string
}

export type Version = {
  project_id: number
  description: string
}

export type VersionDTO = {
  description: string
}

export type Vote = [-1, 0, 1]
