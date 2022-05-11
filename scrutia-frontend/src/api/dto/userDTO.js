export class UserDTO {
  constructor(id, username, firstname, lastname, email, is_verified, reputation){
    this.id = id
    this.username = username
    this.firstname = firstname
    this.lastname = lastname
    this.email = email
    this.is_verified = is_verified
    this.reputation = reputation
  }
}

export class SessionDTO {
  constructor(token, id, username, firstname, lastname, email, email_verified_at, reputation){
    let is_verified = email_verified_at != null
    this.token = token
    this.user = new UserDTO(id, username, firstname, lastname, email, is_verified, reputation)
  }

  isConnected(){ return this.token == null }
}
