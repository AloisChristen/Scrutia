describe("General layout test", () => {
  it("Visits the app root url", () => {
    cy.visit("/");
    cy.title().should("eq", "Scrutia");
    cy.contains("a", "Accueil").should("have.class", "active");
  });
  it("Displays the navigation menu", () => {
    cy.visit("/home");
    cy.contains("a", "Accueil").should("have.attr", "href", "/home");
    cy.contains("a", "Urne à idées").should("have.attr", "href", "#");
    cy.contains("span", "Parcourir")
      .parent()
      .should("have.attr", "href", "/browseIdeas")
      .should("not.have.class", "active");
    cy.contains("span", "Ajouter une idée")
      .parent()
      .should("have.attr", "href", "/addIdea")
      .should("not.have.class", "active");
    cy.contains("span", "Projets d'initiative")
      .parent()
      .should("have.attr", "href", "/browseInitiatives")
      .should("not.have.class", "active");
    cy.contains("span", "À propos")
      .parent()
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
    cy.contains("span", "Parcourir")
      .parent()
      .click({ force: true })
      .should("have.class", "active");
    cy.url().should("include", "/browseIdeas");
    cy.contains("a", "Accueil").should("not.have.class", "active");
    cy.contains("span", "Ajouter une idée")
      .parent()
      .click({ force: true })
      .should("have.class", "active");
    cy.url().should("include", "/addIdea");
    cy.contains("span", "Parcourir")
      .parent()
      .should("not.have.class", "active");
    cy.contains("span", "Projets d'initiative")
      .parent()
      .click()
      .should("have.class", "active");
    cy.url().should("include", "/browseInitiatives");
    cy.contains("span", "Ajouter une idée")
      .parent()
      .should("not.have.class", "active");
    cy.contains("span", "À propos")
      .parent()
      .click()
      .should("have.class", "active");
    cy.url().should("include", "/about");
    cy.contains("span", "Projets d'initiative")
      .parent()
      .should("not.have.class", "active");
  });
  it("Searchs something", () => {
    cy.visit("/home");
    cy.get("#searchInput").type("Keyword").parent().parent().submit();
    cy.url().should("include", "/search?question=Keyword");
  });
  it("Navigates to connected user pages", () => {
    cy.get("#userProfile").click()
    cy.contains("span", "Favoris")
      .parent()
      .should("have.attr", "href", "/favorites")
      .should("not.have.class", "active")
      .click()
      .should("have.class", "active");
    cy.url().should("include", "/favorites")
    cy.get("#userProfile").click()
    cy.contains("span", "Idées et initiatives")
      .parent()
      .should("have.attr", "href", "/userIdeasAndInitiatives")
      .should("not.have.class", "active")
      .click()
      .should("have.class", "active");
    cy.url().should("include", "/userIdeasAndInitiatives")
    cy.get("#userProfile").click()
    cy.contains("span", "Profil")
      .parent()
      .should("have.attr", "href", "/userProfile")
      .should("not.have.class", "active")
      .click()
      .should("have.class", "active");
    cy.url().should("include", "/userProfile")
  });

  it("Displays a 404 error page", () => {
    cy.visit("/notExistingPage");
    cy.contains("h1", "404").should("have.class", "font-w600").should("have.class", "text-city").should("have.class", "display-1")
    cy.contains("a", "Retourner sur la page d'accueil").should("have.attr", "href", "/").click()
  });

  it("Initiatives tests", () => {
    cy.visit("/initiativeDetails/1");
    cy.contains("h1", "Initiative 1").should("have.class", "font-w600").should("have.class", "text-city").should("have.class", "display-1")
  });
  
});
