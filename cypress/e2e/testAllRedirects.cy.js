describe('301 Redirects Test', () => {
  // Authentication configuration
  const authConfig = {
    auth: {
      username: Cypress.env('AUTH_USERNAME'),
      password: Cypress.env('AUTH_PASSWORD'),
    },
  };

  // Helper function to test redirection with retry logic
  const testRedirection = (fromUrl, toUrl, retryCount = 0) => {
    return cy
      .visit(fromUrl, {
        failOnStatusCode: false,
        ...authConfig,
        timeout: 30000,
        retryOnStatusCodeFailure: true,
      })
      .then(() => {
        return cy
          .url({ timeout: 10000 })
          .should('equal', `https://greenworkforceconnect.org${toUrl}`)
          .then((url) => {
            // Log success for debugging
            cy.log(`Successfully redirected from ${fromUrl} to: ${url}`);
          });
      })
      .catch((error) => {
        if (retryCount < 3) {
          // Retry logic with exponential backoff
          cy.log(
            `Retrying redirect for ${fromUrl} (attempt ${retryCount + 1})`,
          );
          cy.wait(Math.pow(2, retryCount) * 1000);
          return testRedirection(fromUrl, toUrl, retryCount + 1);
        }
        throw error;
      });
  };

  beforeEach(() => {
    // Clear cookies and localStorage before each test
    cy.clearCookies();
    cy.clearLocalStorage();

    // Set viewport size explicitly
    cy.viewport(1280, 720);

    // Verify authentication credentials are available
    expect(Cypress.env('AUTH_USERNAME'), 'Username should be set').to.exist;
    expect(Cypress.env('AUTH_PASSWORD'), 'Password should be set').to.exist;
  });

  it('should check redirects from specified URLs to the correct destinations', () => {
    // Add retry ability to the test
    cy.retry({
      openMode: 3,
      runMode: 3,
    });

    // List of redirects
    const redirects = [
      {
        from: '/retrofit-installer-technician',
        to: '/resource-hub/what-is-a-weatherization-assistance-program-retrofit-installer-technician',
      },
      {
        from: '/testimonial-block',
        to: '/',
      },
      {
        from: '/contractor-careers',
        to: '/',
      },
      {
        from: '/careers',
        to: '/',
      },
      {
        from: '/connect-now/national',
        to: '/connect-now',
      },
      {
        from: '/organizations-new',
        to: '/connect-now',
      },
      {
        from: '/resource-hub/about-green-workforce-connect-recruiting-weatherization-professionals',
        to: '/resource-hub/weatherization-opportunities-on-green-workforce-connect',
      },
      {
        from: '/connect-now/wisconsin',
        to: '/wisconsin',
      },
      {
        from: '/connect-now/pennsylvania',
        to: '/pennsylvania',
      },
      {
        from: '/connect-now/oklahoma',
        to: '/oklahoma',
      },
      {
        from: '/organizations',
        to: '/resource-hub',
      },
      {
        from: '/individuals',
        to: '/resource-hub',
      },
      {
        from: '/how-it-works-for-contractors',
        to: '/contractors-in-building-performance',
      },
      {
        from: '/how-it-works-for-individuals',
        to: '/careers-in-building-performance',
      },
    ];

    // Process redirects sequentially instead of in parallel
    cy.wrap(redirects).each((redirect, index) => {
      // Log the current redirect being tested
      cy.log(`Testing redirect ${index + 1}/${redirects.length}`);

      // Test the redirect
      testRedirection(redirect.from, redirect.to);

      // Add small delay between redirects to prevent rate limiting
      if (index < redirects.length - 1) {
        cy.wait(500);
      }
    });
  });

  afterEach(() => {
    // Optional: Log test completion for debugging
    cy.log('Completed redirect test');
  });
});
