describe('Organizations-New Posts Redirection Test', () => {
  // Authentication configuration
  const authConfig = {
    auth: {
      username: Cypress.env('AUTH_USERNAME'),
      password: Cypress.env('AUTH_PASSWORD'),
    },
  };

  // Helper function to fetch all posts with pagination
  const fetchAllPosts = (url, page = 1, allPosts = []) => {
    return cy
      .request({
        url: url,
        qs: {
          per_page: 100, // Request up to 100 posts per page (max allowed by WP)
          page: page,
        },
        ...authConfig, // Include authentication
      })
      .then((response) => {
        // Assert that the API request was successful
        expect(response.status).to.eq(200);

        // Combine the new posts with the previously fetched ones
        const combinedPosts = allPosts.concat(response.body);

        // Check if there are more pages (WordPress sends a total pages header)
        const totalPages = response.headers['x-wp-totalpages'];

        if (page < totalPages) {
          // If more pages exist, recursively fetch the next page
          return fetchAllPosts(url, page + 1, combinedPosts);
        }

        // Return all combined posts once all pages are fetched
        return combinedPosts;
      });
  };

  it('should visit each organizations-new post and be redirected to /connect-now', () => {
    const apiUrl =
      'https://greenworkforceconnect.org/wp-json/wp/v2/organizations-new';

    // Fetch all posts by recursively handling pagination
    fetchAllPosts(apiUrl).then((allPosts) => {
      // Loop through each post
      allPosts.forEach((post) => {
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
