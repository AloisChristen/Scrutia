describe("Authentification test", () => {
  xit("Visits the app root url", () => {
    cy.visit("/");
    cy.title().should("eq", "Scrutia");
    cy.contains("a", "Accueil").should("have.class", "active");
  });
  xit("Should not be authentified", () => {
    cy.visit("/home");
    cy.contains("a", "Connexion").should("have.attr", "href", "/auth/signin");
  });

  describe("Sign in page", () => {
    xit("Should have sign in form", () => {
    cy.visit("/auth/signin");
    cy.contains("h3", "Connexion")
    cy.get('input[name="username"').should("have.attr", "placeholder", "Nom d'utilisateur");
    cy.get('input[name="password"').should("have.attr", "placeholder", "Mot de passe");
    cy.contains("button", "Se connecter")
    });
    it("Should display error message with empty input", () => {
      cy.visit("/auth/signin");
      cy.contains("button", "Se connecter").click()
      cy.get("#username-feedback").should("have.class", "invalid-feedback").contains("Doit être remplit")
      cy.get("#password-feedback").should("have.class", "invalid-feedback").contains("Doit être remplit")
    });
    it("Should display errors with invalid inputs", () => {
      cy.get('input[name="username"').type("InvalidUsername")
      cy.get('input[name="password"').type("Invalidpassword")
      cy.contains("button", "Se connecter").click()
      cy.url().should('include', '/auth/signin')
    });
  });
  /*
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
  })
  */
});
