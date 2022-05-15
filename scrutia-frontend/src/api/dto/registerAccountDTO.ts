class RegisterAccountDTO {
  username: string
  firstname: string
  lastname: string
  email: string
  password: string
  password_confirmation: string
  constructor(formObject:any){
    this.username = formObject.username
    this.firstname = formObject.firstname
    this.lastname = formObject.lastname
    this.email = formObject.email
    this.password = formObject.password
    this.password_confirmation = formObject.password_confirmation
  }
}

export {RegisterAccountDTO}
