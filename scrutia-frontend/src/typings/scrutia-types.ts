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

export type AuthUserDTO = {
  token: string
  user: UserDTO
}

export type Error = {
  message: string
  errors: ErrorDescription[]
}

export type ErrorDescription = {
  key: string
  value: string[]
}

export type LikeDTO = {
  value: number
}

export type LoginDTO = {
  username: string
  password: string
}

export type ProjectStore = {
  title: string
  description: string
  tags: TagDTO[]
}

export type ProjectDetailsDTO = {
  id: number
  title: string
  last_description: string
  status: ProjectStatus
  created_at: Date
  updated_at: Date
  likes_count: number
  upvotes: number
  downvotes: number
  is_favorite: boolean
  author: string
  performance: string
  tags: TagDTO[]
}

export type ProjectPaginationDTO = {
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
  data: ProjectDetailsDTO[]
}

export type ProjectStatus = ['idee', 'Initiative']

export type QuestionDetailsDTO = {
  id: number
  title: string
  user_id: string
  created_at: Date
  updated_at: Date
  nb_upvotes: number
  nb_downvotes: number
  user_vote: VoteDTO
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

type status = ['idea', 'initiative']

export type TagDTO = {
  title: string
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

export type VersionDetailsDTO = {
  number: number
  description: string
  project_id: number
  user_id: number
  nb_upvotes: number
  nb_downvotes: number
  user_vote: VoteDTO
  created_at: Date
  updated_at: Date
  questions: QuestionDetailsDTO[]
}

export type VersionStore = {
  project_id: number
  description: string
}

export type VersionUpdateDTO = {
  description: string
}

export type VoteDTO = [-1, 0, 1]

export type ProjectDiscussionData = {
  title: string,
  author: string,
  date: string,
}
