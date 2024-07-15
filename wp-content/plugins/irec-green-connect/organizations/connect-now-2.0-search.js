// Setup Algolia search after the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  if (!orgsSearch) setupAlgoliaSearch();
  syncFilterChipsWithURL();
});

// Listen to URL state and update Algolia parameters to match
window.addEventListener(URL_UPDATED, () => {
  syncAlgoliaWithURL();
  syncFilterChipsWithURL();
});
// Initialize the algolia query to the url parameters when the search is initialized
window.addEventListener(ALGOLIA_INITIALIZED, () => {
  syncAlgoliaWithURL();
  syncNumberOfResults();
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
        empty: `<p>We're sorry, no search results are coming up for this location. Please expand your search or browse the virtual opportunities below.</p>`,
      },
      showMoreLabel: 'Load More Results in Map Area',
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
        empty: '<p>No online organizations found with that query.</p>',
      },
      showMoreLabel: 'Load More Online Results',
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
  if (opportunities.includes('Registered Apprenticeship')) {
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
        <h6 class="organization__info-title">${item.organization_name}</h6>
        <div class="organization__info-details">
          <p class="organization__info-org">${item.program_name}</p>
          <div class="organization__info-type">
            <div class="organization__info-icon">
              <img src="/wp-content/plugins/irec-green-connect/public/img/in-person.svg" alt="In-Person or Online" />
            </div>
            <p>${item.remote_or_in_person.join(', ')}</p>
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
      ? ` <div class="organization__link">
    <img src="/wp-content/plugins/irec-green-connect/public/img/org-location.svg" />
    ${item.address}
  </div>`
      : ''
  }
     
      ${
        item.phone
          ? `<div class="organization__link">
            <img src="/wp-content/plugins/irec-green-connect/public/img/phone.svg" />
            ${item.phone}
          </div>`
          : ''
      }
      ${
        item.email
          ? `<a href="mailto:${item.email}" target="_blank" class="organization__link">
            <img src="/wp-content/plugins/irec-green-connect/public/img/email.svg" />
            ${item.email}
          </a>`
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
  const tags = searchParams.get('tags')?.split(',') || [];
  const query = searchParams.get('query');

  // Create OR groups for opportunities and tags
  const opportunitiesFacetFilters = opportunities.map(
    (opp) => `opportunities:${opp}`,
  );
  const tagsFacetFilters = tags.map((tag) => `general_tags:${tag}`);

  // Combine facet filters into an array to use OR logic within the same facet and AND logic between facets
  const combinedFacetFilters = [];
  if (opportunitiesFacetFilters.length > 0) {
    combinedFacetFilters.push(opportunitiesFacetFilters);
  }
  if (tagsFacetFilters.length > 0) {
    combinedFacetFilters.push(tagsFacetFilters);
  }

  orgsSearch.helper
    .setQueryParameter('query', query ?? '')
    .setQueryParameter('facetFilters', [
      ...combinedFacetFilters,
      ['-remote_or_in_person:Online'],
    ])
    .search();

  remoteOrgsSearch.helper
    .setQueryParameter('query', query ?? '')
    .setQueryParameter('facetFilters', [
      ...combinedFacetFilters,
      ['remote_or_in_person:Online'],
    ])
    .search();

  if (window.innerWidth < 1140) {
    const resultsHits = document.getElementById('topOfResults');
    if (resultsHits) {
      resultsHits.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
}

function syncFilterChipsWithURL() {
  let activeFiltersHTML = '';
  const activeFiltersEl = document.querySelector('#activeFilters');
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const opportunities = searchParams.get('opportunities')?.split(',') || [];

  const tags = searchParams.get('tags')?.split(',') || [];
  const query = searchParams.get('query') || '';
  [...opportunities, ...tags, query].forEach((value) =>
    value
      ? (activeFiltersHTML += `<sl-tag size="medium" removable>${value}</sl-tag>`)
      : null,
  );
  if (activeFiltersHTML) {
    activeFiltersHTML += `<sl-tag class="tag__reset" size="medium">
    <img src="/wp-content/plugins/irec-green-connect/public/img/reset.png" alt="" />
    Reset All</sl-tag>`;
  }
  activeFiltersEl.innerHTML = activeFiltersHTML;

  activeFiltersEl.addEventListener('sl-remove', (event) => {
    const tag = event.target;
    const value = tag.innerText;
    const url = new URL(window.location);
    const searchParams = new URLSearchParams(url.search);
    const opportunities = searchParams.get('opportunities')?.split(',') || [];
    const tags = searchParams.get('tags')?.split(',') || [];
    const query = searchParams.get('query');

    if (opportunities.includes(value)) {
      updateQueryParam('opportunities', value, true, false);
    } else if (tags.includes(value)) {
      updateQueryParam('tags', value, true, false);
    } else if (query === value) {
      updateQueryParam('query', value, true, true);
    }
  });

  const tagsReset = document.querySelector('.tag__reset');
  tagsReset?.addEventListener('click', removeFiltersAndSearch);
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
