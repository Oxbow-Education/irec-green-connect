describe('Organizations-New Posts Redirection Test', () => {
  it('should visit each organizations-new post and be redirected to /connect-now', () => {
    // Authentication configuration
    const authConfig = {
      auth: {
        username: Cypress.env('AUTH_USERNAME'),
        password: Cypress.env('AUTH_PASSWORD'),
      },
    };

    // Fetch the organizations-new posts from the WordPress API
    cy.request({
      url: 'https://greenworkforceconnect.org/wp-json/wp/v2/organizations-new',
      ...authConfig,
    }).then((response) => {
      // Assert that the API request was successful
      expect(response.status).to.eq(200);

      // Loop through each post
      response.body.forEach((post) => {
        const postUrl = post.link; // Get the post URL

        // Visit the post URL with authentication
        cy.visit(postUrl, {
          ...authConfig,
          failOnStatusCode: false,
        });

        // Check that the page is redirected to /connect-now
        cy.url().should('include', '/connect-now');
      });
    });
  });
});
