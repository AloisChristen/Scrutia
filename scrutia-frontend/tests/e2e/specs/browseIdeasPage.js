describe("Browse ideas page tests", () => {
  it("Should apply a default filter by status to filter ideas", () => {
    cy.visit("/browseIdeas?type=ideas")
    cy.get('#types .vs__selected').should("have.length", 1).contains("Idées")
  });
  it("Should apply a default filter by status to filter projects", () => {
    cy.visit("/browseIdeas?type=initiatives")
    cy.get('#types .vs__selected').should("have.length", 1).contains("Projets d'initiative")
  });
  it("Should apply a default filter by text to filter projects", () => {
    cy.visit("/browseIdeas?question=test")
    cy.get("#contains-text").should('have.value', 'test')
  });
  it("Should have default selected types and dates", () => {
    cy.visit("/browseIdeas")
    cy.get('#types .vs__selected').should("have.length", 2)
    cy.get('.btn-group>.btn').should("have.class", "btn-primary")
  });
  it('Should disable search button if no type is selected', () => {
    cy.visit("/browseIdeas")
    cy.get('#types .vs__selected > .vs__deselect').click({ multiple: true })
    cy.get('#search').should('have.attr', "disabled")
  })
  it("Should be able to reset form", () => {
    cy.visit("/browseIdeas")
    cy.get('#types .vs__selected > .vs__deselect').click({ multiple: true })
    cy.get('#types .vs__selected').should("have.length", 0)
    cy.get('#contains-text').type("Keyword")
    cy.get('#tags input[type="search"]').type("AD").type('{enter}')
    cy.get('.btn-group .btn').click({ multiple: true })
    cy.get('#reset').click()
    cy.get('#types .vs__selected').should("have.length", 2)
    cy.get("#contains-text").should('have.value', '')
    cy.get('.btn-group>.btn').should("have.class", "btn-primary")
  })
  it('Should have spinner', () => {
    cy.get('.spinner-border').should('have.length', 1)
  })
  it('Should have pagination', () => {
    cy.get('.pagination').should('have.length', 1)
  })
});
