export type AnswerDetailsDTO = {
  id: number
  description: string
  author: string
  date: string
  nbLikes: number
  nbDislikes: number
  userVote: VoteDTO
}

export type AnswerStoreDTO = {
  question_id: number
  title: string
  description: string
}

export type AnswerUpdateDTO = {
  title: string
  description: string
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

export type LoginDTO = {
  username: string
  password: string
}

export type Project = {
  title: string
  description: string
  tags: TagDTO[]
}

export type ProjectDetailsDTO = {
  id: number
  title: string
  description: string
  author: string
  date: Date
  tags: TagDTO[]
  nbLikes: number
  nbDislikes: number
  userVote: VoteDTO
  status: ProjectStatus
  questions: QuestionDetailsDTO[]
}

export type ProjectDTO = {
  id: number
  title: string
  description: string
  author: string
  date: Date
  tags: TagDTO[]
  nbLikes: number
  nbDislikes: number
  userVote: VoteDTO
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

export type QuestionDetailsDTO = {
  id: number
  title: string
  author: string
  date: string
  nbLikes: number
  nbDislikes: number
  userVote: VoteDTO
  answers: AnswerDetailsDTO[]
}

export type QuestionStoreDTO = {
  project_id: number
  version_number: number
  title: string
  description: string
}

export type QuestionUpdateDTO = {
  title: string
  description: string
}

export type RegisterUser = {
  username: string
  firstname: string
  lastname: string
  email: string
  password: string
  password_confirmation: string
}

export type TagDTO = {
  id: number
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
  id: number
  username: string
  firstname: string
  lastname: string
  email: string
  reputation: number
}

export type UserUdpateDTO = {
  username: string
  firstname: string
  lastname: string
  email: string
  password: string
  password_confirmation: string
}

export type VersionStore = {
  project_id: number
  description: string
}

export type VersionUpdateDTO = {
  description: string
}

export type VoteDTO = [-1, 0, 1]
