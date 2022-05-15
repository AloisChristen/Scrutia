import { Project } from '../../typings/scrutia-types'

export async function addIdea(idea: Project) {
  const result = await fetch(
    'https://scrutia-api-dev.herokuapp.com/api/projects',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json;charset=utf-8',
      },
      body: JSON.stringify(idea),
    }
  )

  console.log(result)
}
