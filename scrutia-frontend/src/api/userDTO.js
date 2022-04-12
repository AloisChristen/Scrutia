
export class User {
  constructor(id, username, password, firstname, lastname, email){
    this.id = id
    this.username = username
    this.password = password
    this.firstname = firstname
    this.lastname = lastname
    this.email = email
  }
}

export class UserDTO {
  constructor(token, id, username, password, firstname, lastname, email){
    this.token = token
    this.user = new User(id, username, password, firstname, lastname, email)
  }

  isConnected(){ return this.token == null; }
}
