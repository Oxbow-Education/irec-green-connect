// Setup Algolia search after the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  if (!orgsSearch) setupAlgoliaSearch();
});

// Initialize and configure Algolia search
function setupAlgoliaSearch() {
  orgsSearch = instantsearch({
    indexName: 'organizations-new',
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
  });

  remoteOrgsSearch = instantsearch({
    indexName: 'organizations-new',
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
  });

  orgsSearch.addWidgets([
    instantsearch.widgets.configure({ hitsPerPage: 12 }),
    instantsearch.widgets.infiniteHits({
      container: '#resultsHits',
      showPrevious: false,
      showMore: true,
      templates: {
        item: (item) => {
          addMarker(item);
          return generateOrgHTML(item);
        },
        empty: `<p>No results for your location.</p>`,
      },
      showMoreLabel: 'Load More Results in Your Location',
    }),
  ]);

  remoteOrgsSearch.addWidgets([
    instantsearch.widgets.configure({ hitsPerPage: 12 }),
    instantsearch.widgets.infiniteHits({
      container: '#resultsHitsRemote',
      showPrevious: false,
      templates: {
        item: (item) => {
          return generateOrgHTML(item);
        },
        empty: '<p>No remote organizations found with that query.</p>',
      },
      showMoreLabel: 'Load More Remote Results',
    }),
  ]);

  orgsSearch.start();
  remoteOrgsSearch.start();
  sendEvent(ALGOLIA_INITIALIZED);
}

function getImageName(opportunities) {
  if (opportunities.includes('Hiring')) {
    return 'HIRING';
  }
  if (opportunities.includes('Bids & Contracts')) {
    return 'BIDS & CONTRACTS';
  }
  if (opportunities.includes('Information')) {
    return 'INFORMATION';
  }
  if (opportunities.includes('Training')) {
    return 'TRAINING';
  }
  if (opportunities.includes('Create an Apprenticeship Program')) {
    return 'APPRENTICESHIP';
  }
}
function generateOrgHTML(item) {
  const orgImageName = getImageName(item.opportunities);

  return `<div class="organization">
  <div class="organization__container">
    <div class="organization__info">
      <div class="organization__info-img">
        <img src="/wp-content/plugins/irec-green-connect/public/img/${orgImageName}.png" alt="Organization Image" />  
      </div>
      <div class="organization__info-content">
        <h6 class="organization__info-title">${item.program_name}</h6>
        <div class="organization__info-details">
          <p class="organization__info-org">at ${item.organization_name}</p>
          <div class="organization__info-type">
            <div class="organization__info-icon">
              <img src="/wp-content/plugins/irec-green-connect/public/img/in-person.svg" alt="In-Person or Remote" />
            </div>
            <p>${item.remote_or_in_person}</p>
          </div>
        </div>
      </div>
    </div>
    <hr class="organization__divider"/>
    <div class="organization__opportunities">
      ${item.opportunities?.map((opp) => `<p>${opp}</p>`).join('')}
    </div>
    <p class="organization__info-description">${item.description}</p>
    <div class="organization__tags">
      ${item.general_tags
        ?.map((tag) => `<div class="organization__tag">${tag}</div>`)
        .join('')}
    </div>
  </div>
  <div class="organization__quick-links">
  ${
    item.address
      ? ` <div class="organiztion__link">
    <img src="/wp-content/plugins/irec-green-connect/public/img/org-location.svg" />
    ${item.address}
  </div>`
      : ''
  }
     
      ${
        item.phone
          ? `<div class="organiztion__link">
            <img src="/wp-content/plugins/irec-green-connect/public/img/phone.svg" />
            ${item.phone}
          </div>`
          : ''
      }
      ${
        item.email
          ? `<div class="organiztion__link">
            <img src="/wp-content/plugins/irec-green-connect/public/img/email.svg" />
            ${item.email}
          </div>`
          : ''
      }
      
    <a target="_blank" href="${item.url}" class="organization__connect-now">
    Connect Now
    </a>
      
  </div>
</div>
`;
}

function syncAlgoliaWithURL() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const opportunities = searchParams.get('opportunities')?.split(',') || [];
  const opportunitiesFacetFilters = opportunities.map(
    (opp) => `opportunities:${opp}`,
  );
  const tags = searchParams.get('tags')?.split(',') || [];
  const tagsFacetFilters = tags.map((tag) => `general_tags:${tag}`);
  const query = searchParams.get('query');

  orgsSearch.helper
    .setQueryParameter('query', query ?? '')
    .setQueryParameter('facetFilters', [
      ...opportunitiesFacetFilters,
      ...tagsFacetFilters,
    ])
    .search();
  remoteOrgsSearch.helper
    .setQueryParameter('query', query ?? '')
    .setQueryParameter('facetFilters', [
      ...opportunitiesFacetFilters,
      ...tagsFacetFilters,
      'remote_or_in_person:Remote',
    ])
    .search();
}

function syncNumberOfResults() {
  orgsSearch.on('render', () => {
    const results = orgsSearch.helper.lastResults;
    const seeResultsButton = document.querySelector('.footer__see-results');
    const resultsCount = document.querySelector('#metaInfo .results__count');

    seeResultsButton.innerText = `See ${results.nbHits} Results`;
    resultsCount.innerText = `${results.nbHits} Results`;
  });

  remoteOrgsSearch.on('render', () => {
    const results = remoteOrgsSearch.helper.lastResults;
    const resultsCountRemote = document.querySelector(
      '#metaInfoRemote .results__count',
    );
    resultsCountRemote.innerText = `${results.nbHits} Results`;
  });
}

// Listen to URL state and update Algolia parameters to match
window.addEventListener(URL_UPDATED, syncAlgoliaWithURL);
// Initialize the algolia query to the url parameters when the search is initialized
window.addEventListener(ALGOLIA_INITIALIZED, () => {
  syncAlgoliaWithURL();
  syncNumberOfResults();
});
