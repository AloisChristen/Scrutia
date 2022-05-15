export class UserDTO {
  id: string
  username: string
  firstname: string
  lastname: string
  email: string
  email_verified_at: Date
  reputation: number
  constructor(id:string, username:string, firstname:string, lastname:string, email:string, email_verified_at:Date, reputation:number){
    this.id = id
    this.username = username
    this.firstname = firstname
    this.lastname = lastname
    this.email = email
    this.email_verified_at = email_verified_at
    this.reputation = reputation
  }

  isVerified():boolean{
    return this.email_verified_at != null
  }
}

export class SessionDTO {
  token: string
  user: UserDTO
  constructor(token:string, id:string, username:string, firstname:string, lastname:string, email:string, email_verified_at:Date, reputation:number){
    this.token = token
    this.user = new UserDTO(id, username, firstname, lastname, email, email_verified_at, reputation)
  }

  isConnected(){ return this.token == null }
}
