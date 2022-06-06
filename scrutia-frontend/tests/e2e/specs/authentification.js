const uuid = "" + Cypress._.random(0, 100) + Date.now()

const credentials = {
  username: "testname_" + uuid,
  password: "password_" + uuid,
  email: "email" + uuid + "@emailtest.ch"
}



describe("Authentification test", () => {

  describe("Base test", () => {
    it("Visits the app root url", () => {
      cy.visit("/");
      cy.title().should("eq", "Scrutia");
      cy.contains("a", "Accueil").should("have.class", "active");
    });
    it("Should not be authentified", () => {
      cy.visit("/home");
      cy.contains("a", "Connexion").should("have.attr", "href", "/auth/signin");
    });
  })

  describe("Happy scenario", () => {

    it("Should register a new account", () => {
      cy.visit("/")
      // go to signin
      cy.contains("a", "Connexion").should("have.attr", "href", "/auth/signin").click()
      // go to signup
      cy.contains("a", "S'enregistrer").should("have.attr", "href", "/auth/signup").click()
      // fill inputs
      cy.contains('label', "J'accepte les Termes & Conditions").click();
      cy.get('input[name="username"').type(credentials.username);
      cy.get('input[name="email"').type(credentials.email);
      cy.get('input[name="password"').type(credentials.password);
      cy.get('input[name="password_confirmation"').type(credentials.password);
      cy.get('input[name="firstname"').type("testfirstname")
      cy.get('input[name="lastname"').type("testlastname")
      // submit
      cy.contains("button", "S'inscrire").click()
      // checks
      cy.url().should('include', '/home')
      cy.get("#userProfile").should("contain.text", credentials.username);
    })

    it("Should log out", () => {
      // click on user profil
      cy.get("#userProfile").click();
      // click on disconnected
      cy.contains("a", "Se déconnecter").should("have.attr", "href", "/").click();
      // checks
      cy.url().should('include', '/home')
      cy.contains("a", "Connexion").should("have.attr", "href", "/auth/signin");
    })

    it("Should sign in again", () => {
      cy.visit("/")
      // go to signin
      cy.contains("a", "Connexion").should("have.attr", "href", "/auth/signin").click()
      // fill inputs
      cy.get('input[name="username"').type(credentials.username);
      cy.get('input[name="password"').type(credentials.password);
      // submit
      cy.contains("button", "Se connecter").click()
      // checks
      cy.url().should('include', '/home')
      cy.get("#userProfile").should("contain.text", credentials.username);
      // Should still be logged after refresh
      cy.visit("/")
      // checks
      cy.url().should('include', '/home')
      cy.get("#userProfile").should("contain.text", credentials.username);
    })
  })

  describe("Sign in page", () => {

    beforeEach(() => { cy.visit("/auth/signin") })

    it("Should have sign in form", () => {
      cy.contains("h3", "Connexion")
      cy.get('input[name="username"]').should("have.attr", "placeholder", "Nom d'utilisateur");
      cy.get('input[name="password"]').should("have.attr", "placeholder", "Mot de passe");
      cy.contains("button", "Se connecter")
    });

    it("Should display error message with empty input", () => {
      cy.contains("button", "Se connecter").click()
      cy.get("#username-feedback").should("have.class", "invalid-feedback").contains("Doit être remplit")
      cy.get("#password-feedback").should("have.class", "invalid-feedback").contains("Doit être remplit")
    });

    it("Should display errors with invalid inputs", () => {
      cy.get('input[name="username"]').type("InvalidUsername")
      cy.get('input[name="password"]').type("Invalidpassword")

      cy.on("dialog", () => {
        cy.get("#swal2-title").should("contain.text", "Nom d'utilisateur ou mot de passe incorrect")
      })

      cy.contains("button", "Se connecter").click()
      cy.url().should('include', '/auth/signin')

    });

    it("Should have link to sign up page", () => {
      cy.get('a[href="/auth/signup"]').should("contain.text", "S'enregistrer").click()
      cy.url().should('include', '/auth/signup')
    });
    it("Should have button to home page", () => {
      cy.visit("/home");
      cy.visit("/auth/signin");
      cy.contains('button', 'Retour').click()
      cy.url().should('include', '/home')
    });
  });

  describe("Sign up page", () => {

    beforeEach(() => {
      cy.visit("/auth/signup")
    })

    it("Should have sign up form", () => {
      cy.contains("h3", "Création de compte")
      cy.contains('label', "J'accepte les Termes & Conditions")
      cy.get('input[name="username"]').should("have.attr", "placeholder", "Nom d'utilisateur");
      cy.get('input[name="email"]').should("have.attr", "placeholder", "Email");
      cy.get('input[name="password"]').should("have.attr", "placeholder", "Mot de passe");
      cy.get('input[name="password_confirmation"]').should("have.attr", "placeholder", "Confirmation du mot de passe");
      cy.get('input[name="firstname"]').should("have.attr", "placeholder", "Prénom");
      cy.get('input[name="lastname"]').should("have.attr", "placeholder", "Nom de famille");
      cy.contains("button", "S'inscrire")
    });

    it("Should display error message with empty input", () => {
      cy.contains("button", "S'inscrire").click()
      cy.contains('label', "J'accepte les Termes & Conditions")
      cy.get("#username-feedback").should("have.class", "invalid-feedback").contains("Veuillez entrer votre nom d'utilisateur")
      cy.get("#email-feedback").should("have.class", "invalid-feedback").contains("Veuillez entrer votre email")
      cy.get("#password-feedback").should("have.class", "invalid-feedback").contains("Veuillez fournir un mot de passe")
      cy.get("#password_confirmation-feedback").should("have.class", "invalid-feedback").contains("Veuillez confirmer votre mot de passe");
      cy.get("#firstname-feedback").should("have.class", "invalid-feedback").contains("Veuillez entrer votre prénom");
      cy.get("#lastname-feedback").should("have.class", "invalid-feedback").contains("Veuillez entrer votre nom de famille");
    });

    it("Should display errors with invalid inputs", () => {
      // fill inputs with already existing user
      cy.contains('label', "J'accepte les Termes & Conditions").click();
      cy.get('input[name="username"').type(credentials.username);
      cy.get('input[name="email"').type(credentials.email);
      cy.get('input[name="password"').type(credentials.password);
      cy.get('input[name="password_confirmation"').type(credentials.password);
      cy.get('input[name="firstname"').type("testfirstname")
      cy.get('input[name="lastname"').type("testlastname")
      // Check dialog
      cy.on("dialog", () => {
        cy.get("#swal2-title").should("contain.text", "Nom d'utilisateur ou email déjà utilisé")
      })
      // submit
      cy.contains("button", "S'inscrire").click()
      cy.url().should('include', '/auth/signup')
    });

    it("Should navigate to sign in page", () => {
      cy.get('a[href="/auth/signin"]').should("contain.text", "Se connecter").click()
      cy.url().should('include', '/auth/signin')
    });
  });
});
