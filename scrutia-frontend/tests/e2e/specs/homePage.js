describe("Home page tests", () => {
  beforeEach(() => { cy.visit("/") })
  it("Should have spinners", () => {
    cy.get('.spinner-border').should('have.length', 2)
  });
  it("Should have link to browse ideas", () => {
    cy.get('#linkToIdeas').should("have.attr", "href", "browseIdeas?type=ideas");
  });
  it("Should have link to browse projects", () => {
    cy.get('#linkToProjects').should("have.attr", "href", "browseIdeas?type=initiatives");
  });
});
