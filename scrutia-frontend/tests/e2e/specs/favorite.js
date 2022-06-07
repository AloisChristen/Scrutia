const uuid = "" + Cypress._.random(0, 100) + Date.now()

const credentials = {
  username: "testname_" + uuid,
  password: "password_" + uuid,
  email: "email" + uuid + "@emailtest.ch"
}



describe("Authentification test", () => {
  before(() => {
    // Create test user
    cy.visit('/auth/signup')
    cy.contains('label', "J'accepte les Termes & Conditions").click();
      cy.get('input[name="username"').type(credentials.username);
      cy.get('input[name="email"').type(credentials.email);
      cy.get('input[name="password"').type(credentials.password);
      cy.get('input[name="password_confirmation"').type(credentials.password);
      cy.get('input[name="firstname"').type("testfirstname")
      cy.get('input[name="lastname"').type("testlastname")
      // submit
      cy.contains("button", "S'inscrire").click()
      cy.url().should('include', '/home')
  })



  describe("Base test", () => {
    beforeEach(() => {
      // Sign in
      cy.visit('/auth/signin')
      cy.get('input[name="username"').type(credentials.username);
      cy.get('input[name="password"').type(credentials.password);
      // submit
      cy.contains("button", "Se connecter").click()
    })
    it("Visits the app root url", () => {
      cy.visit("/");
      cy.title().should("eq", "Scrutia");
      cy.contains("a", "Accueil").should("have.class", "active");
    });
    it("Visits the favorite page", () => {
      cy.get("#userProfile").click();
      cy.contains("a", "Mes favoris").should("have.attr", "href", "/favorites").click();
      cy.contains("h2", "Vos idées et projets favoris")
      cy.contains("div", "Vous n'avez actuellement pas de favoris.")
    });
  })

  describe("Happy scenario", () => {
    beforeEach(() => {
      // Sign in
      cy.visit('/auth/signin')
      cy.get('input[name="username"').type(credentials.username);
      cy.get('input[name="password"').type(credentials.password);
      // submit
      cy.contains("button", "Se connecter").click()
    })
    it("Favorites some projects", () => {
      // Search for projects
      // Favorites them
    })

    it("Should see all favorites", () => {
      // Go to favorites
      // Count each project
    })

    it("Remove a favorite", () => {
      // Go to favorites
      // Remove a favorite
      // check
    })
  })

  describe("Errors check", () => {

    it("Cannot favorite a unexisting project", () => {
      // Favorite a project with wrong id
      // Visit favorite
      // No project should have been added
    });

    it("Cannot remove a non favorite project", () => {
      // Visit favorites
      // Remove a non favorite project
      // Nothing should happen
    });
  });
});
