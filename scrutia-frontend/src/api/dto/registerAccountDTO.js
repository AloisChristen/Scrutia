class RegisterAccountDTO {
  constructor(formObject){
    this.username = formObject.username
    this.firstname = formObject.firstname
    this.lastname = formObject.lastname
    this.email = formObject.email
    this.password = formObject.password
    this.password_confirmation = formObject.password_confirmation
  }

}

export {RegisterAccountDTO}
