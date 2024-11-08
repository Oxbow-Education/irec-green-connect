describe('301 Redirects Test', () => {
  // Helper function to test redirection for a specific URL
  const testRedirection = (fromUrl, toUrl) => {
    cy.visit(fromUrl, {
      failOnStatusCode: false,
      auth: {
        username: Cypress.env('AUTH_USERNAME'),
        password: Cypress.env('AUTH_PASSWORD'),
      },
    });

    cy.url().should('equal', `https://greenworkforceconnect.org${toUrl}`);
  };

  it('should check redirects from specified URLs to the correct destinations', () => {
    // List of redirects from both screenshots (with fixed URL typo)
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

    // Loop through each redirect and test it
    redirects.forEach((redirect) => {
      testRedirection(redirect.from, redirect.to);
    });
  });
});
