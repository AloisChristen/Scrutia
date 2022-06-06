/*
const uuid = "" + Cypress._.random(0, 100) + Date.now()

const credentials = {
  username: "testname_" + uuid,
  password: "password_" + uuid,
  email: "email" + uuid + "@emailtest.ch"
}
*/


describe("Authentification test", () => {
  describe("Create user for tests", () => {
    // Create account
  })

  describe("Base test", () => {
    it("Visits the app root url", () => {
      cy.visit("/");
      cy.title().should("eq", "Scrutia");
      cy.contains("a", "Accueil").should("have.class", "active");
    });
    it("Visits the favorite page", () => {
      cy.visit("/favorites");
      // check base layout
    });
  })

  describe("Happy scenario", () => {

    it("Favorites some projects", () => {
      // Go to main page
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
