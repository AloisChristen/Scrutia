describe("General layout test", () => {
  it("Visits the app root url", () => {
    cy.visit("/");
    cy.title().should("eq", "Scrutia");
    cy.contains("a", "Accueil").should("have.class", "active");
  });
  it("Displays the navigation menu", () => {
    cy.visit("/home");
    cy.contains("a", "Accueil").should("have.attr", "href", "/home");
    cy.contains("a", "Ajouter une idée").should("have.attr", "href", "/addIdea");
    cy.contains("a", "Parcourir les idées et projets")
      .should("have.attr", "href", "/browseIdeas")
      .should("not.have.class", "active");
    cy.contains("a", "À propos")
      .should("have.attr", "href", "/about")
      .should("not.have.class", "active");
  });
  it("Displays the application header", () => {
    cy.visit("/home");
    cy.contains("a", "Scrutia")
      .should("have.attr", "href", "/");
    cy.get("#searchInput").should("have.attr", "placeholder", "Rechercher...");
  });
  it("Navigates between the main pages", () => {
    cy.visit("/home");
    cy.contains("a", "Ajouter une idée").click({ force: true });
    cy.url().should("include", "/auth/signin");
    cy.visit("/home");
    cy.contains("a", "Parcourir les idées et projets")
      .click({ force: true })
      .should("have.class", "active");
    cy.url().should("include", "/browseIdeas");
    cy.contains("a", "Accueil").should("not.have.class", "active");
    cy.contains("a", "À propos")
      .click()
      .should("have.class", "active");
    cy.url().should("include", "/about");
    cy.contains("a", "Parcourir les idées et projets")
      .parent()
      .should("not.have.class", "active");
  });
  it("Searchs something", () => {
    cy.visit("/home");
    cy.get("#searchInput").type("Keyword").parent().parent().submit();
    cy.url().should("include", "/search?question=Keyword");
  });
  it("Displays a 404 error page", () => {
    cy.visit("/notExistingPage");
    cy.contains("h1", "404").should("have.class", "font-w600").should("have.class", "text-city").should("have.class", "display-1")
    cy.contains("a", "Retourner sur la page d'accueil").should("have.attr", "href", "/").click()
  })
});
