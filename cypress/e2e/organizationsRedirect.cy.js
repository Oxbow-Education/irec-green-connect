describe('Organizations-New Posts Redirection Test', () => {
  // Authentication configuration
  const authConfig = {
    auth: {
      username: Cypress.env('AUTH_USERNAME'),
      password: Cypress.env('AUTH_PASSWORD'),
    },
  };

  // Helper function to fetch all posts with pagination and retry logic
  const fetchAllPosts = (url, page = 1, allPosts = [], retryCount = 0) => {
    return cy
      .request({
        url: url,
        qs: {
          per_page: 100,
          page: page,
        },
        ...authConfig,
        timeout: 30000, // Increased timeout for API requests
        retryOnStatusCodeFailure: true,
      })
      .then((response) => {
        expect(response.status).to.eq(200);
        const combinedPosts = allPosts.concat(response.body);
        const totalPages = parseInt(response.headers['x-wp-totalpages']);

        if (page < totalPages) {
          // Add delay between pagination requests to prevent rate limiting
          cy.wait(1000);
          return fetchAllPosts(url, page + 1, combinedPosts);
        }
        return combinedPosts;
      })
      .catch((error) => {
        if (retryCount < 3) {
          // Retry logic with exponential backoff
          cy.wait(Math.pow(2, retryCount) * 1000);
          return fetchAllPosts(url, page, allPosts, retryCount + 1);
        }
        throw error;
      });
  };

  it('should visit each organizations-new post and be redirected to /connect-now', () => {
    const apiUrl =
      'https://greenworkforceconnect.org/wp-json/wp/v2/organizations-new';

    // Add retry ability to the test
    cy.retry({
      openMode: 3,
      runMode: 3,
    });

    // Verify authentication credentials are available
    expect(Cypress.env('AUTH_USERNAME')).to.exist;
    expect(Cypress.env('AUTH_PASSWORD')).to.exist;

    // Fetch all posts with improved error handling
    fetchAllPosts(apiUrl).then((allPosts) => {
      // Verify we received posts
      expect(allPosts).to.be.an('array');
      expect(allPosts.length).to.be.greaterThan(0);

      // Process posts sequentially instead of in parallel
      cy.wrap(allPosts).each((post) => {
        const postUrl = post.link;

        // Visit the post URL with additional options
        cy.visit(postUrl, {
          ...authConfig,
          failOnStatusCode: false,
          timeout: 30000,
          retryOnStatusCodeFailure: true,
        });

        // Add more specific assertions and timeout
        cy.url({ timeout: 10000 })
          .should('include', '/connect-now')
          .then((url) => {
            // Log success for debugging
            cy.log(`Successfully redirected to: ${url}`);
          });

        // Add small delay between posts to prevent rate limiting
        cy.wait(500);
      });
    });
  });

  beforeEach(() => {
    // Clear cookies and localStorage before each test
    cy.clearCookies();
    cy.clearLocalStorage();

    // Set viewport size explicitly
    cy.viewport(1280, 720);
  });
});
