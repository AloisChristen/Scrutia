const uuid = "" + Cypress._.random(0, 100) + Date.now()

const credentials = {
  username: "testname_" + uuid,
  password: "password_" + uuid,
  email: "email" + uuid + "@emailtest.ch"
}

describe("Add idea page", () => {

  before(() => {
    cy.visit("/auth/signup")
    cy.contains('label', "J'accepte les Termes & Conditions").click();
    cy.get('input[name="username"').type(credentials.username);
    cy.get('input[name="email"').type(credentials.email);
    cy.get('input[name="password"').type(credentials.password);
    cy.get('input[name="password_confirmation"').type(credentials.password);
    cy.get('input[name="firstname"').type("testfirstname")
    cy.get('input[name="lastname"').type("testlastname")
    cy.contains("button", "S'inscrire").click()
    cy.url().should('include', '/home')
  })

  beforeEach(() => {
    cy.visit("/auth/signin")
    cy.get('input[name="username"').type(credentials.username);
    cy.get('input[name="password"').type(credentials.password);
    cy.contains("button", "Se connecter").click()
    cy.url().should('include', '/home')
    cy.visit("/addIdea")
  })

  it("Should display add idea form", () => {
    cy.url().should('include', '/addIdea')
    cy.get('input[name="title"]').should("have.attr", "placeholder", "Choisissez un titre...");
    cy.get('textarea[name="description"]').should("have.attr", "placeholder", "Décrivez votre idée...");
    cy.get('input[type="search"]').should("have.attr", "placeholder", "Définissez des tags...");
    cy.get('#submit').should("have.attr", "disabled", "disabled")
    cy.get('#clear')
  })

  it("Should display error message with empty inputs", () => {
    cy.get('input[name="title"]').type("1234")
    cy.get('#title-feedback').should("have.class", "invalid-feedback").contains("Le titre doit au moins contenir 10 caractères")
    cy.get('textarea[name="description"]').type("1234")
    cy.get('#description-feedback').should("have.class", "invalid-feedback").contains("La description doit au moins contenir 50 caractères")
  })

  it("Should activate submit button when the form is well filled", () => {
    cy.get('input[name="title"]').type("1234567890")
    cy.get('#submit').should("have.attr", "disabled", "disabled")
    cy.get('textarea[name="description"]').type("12345678901234567890123456789012345678901234567890")
    cy.get('#submit').should("have.attr", "disabled", "disabled")
    cy.get('input[type="search"]').type("1234").type('{enter}')
    cy.get('#submit').should("not.have.attr", "disabled", "disabled")
  })

  it("Should be able to reset form", () => {
    cy.get('input[name="title"]').type("1234567890")
    cy.get('textarea[name="description"]').type("12345678901234567890123456789012345678901234567890")
    cy.get('input[type="search"]').type("1234").type('{enter}')
    cy.get('#clear').click()
    cy.get('input[name="title"]').should("have.value", "")
    cy.get('textarea[name="description"]').should("have.value", "")
    cy.get('#tags .vs__selected').should("have.length", 0)
  })

  it("Should be able to add an save idea", () => {
    cy.get('input[name="title"]').type("1234567890")
    cy.get('textarea[name="description"]').type("12345678901234567890123456789012345678901234567890")
    cy.get('input[type="search"]').type("1234").type('{enter}')
    cy.on("dialog", () => {
      cy.get("#swal2-title").should("contain.text", "Votre idée a été enregistrée")
    })
    cy.get('#submit').click()
    cy.url().should('include', '/home')
  })
})
