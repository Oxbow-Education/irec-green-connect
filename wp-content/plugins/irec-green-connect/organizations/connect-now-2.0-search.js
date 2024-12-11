let previousQuery = '';
let previousFilters = {
  opportunities: '',
  tags: '',
};
let typingTimeout;
let filteringTimeout;

function triggerSearchEvent(query) {
  if (query && query !== previousQuery) {
    gtag('event', 'search', {
      category: 'connect_now',
      query,
    });
    previousQuery = query;
  }
}
function triggerFilterEvent(opportunities, tags) {
  const currentFilters = {
    opportunities: opportunities.join(','),
    tags: tags.join(','),
  };

  if (
    currentFilters.opportunities !== previousFilters.opportunities ||
    currentFilters.tags !== previousFilters.tags
  ) {
    gtag('event', 'filter_click', {
      category: 'connect_now_filter_click',
      opportunities: currentFilters.opportunities,
      tags: currentFilters.tags,
    });
    previousFilters = currentFilters;
  }
}
// Debounce function to delay the execution until the user stops filtering
function debounceFilter(opportunities, tags) {
  clearTimeout(filteringTimeout);
  filteringTimeout = setTimeout(() => {
    triggerFilterEvent(opportunities, tags);
  }, 500); // Adjust the delay as needed (500ms is common)
}

// Debounce function to delay the execution until the user stops typing
function debounceSearch(query) {
  clearTimeout(typingTimeout);
  typingTimeout = setTimeout(() => {
    triggerSearchEvent(query);
  }, 500); // Adjust the delay as needed (500ms is common)
}

// Setup Algolia search after the DOM is loaded
document.addEventListener('DOMContentLoaded', async () => {
  if (!orgsSearch) setupAlgoliaSearch();
  syncFilterChipsWithURL();
  await fetchAndAddAllHitsToMap();
});

// Listen to URL state and update Algolia parameters to match
window.addEventListener(URL_UPDATED, async () => {
  syncAlgoliaWithURL();
  syncFilterChipsWithURL();
  await fetchAndAddAllHitsToMap();
});
// Initialize the algolia query to the url parameters when the search is initialized
window.addEventListener(ALGOLIA_INITIALIZED, async () => {
  syncAlgoliaWithURL();
  syncNumberOfResults();
  await fetchAndAddAllHitsToMap();
});

async function fetchAndAddAllHitsToMap() {
  if (!orgsSearch) return;
  const index = orgsSearch.client.initIndex('organizations-new');
  const query = orgsSearch.helper.state.query;
  const facetFilters = orgsSearch.helper.state.facetFilters;

  const allHits = await fetchAllHits(index, query, facetFilters);
  allHits.forEach((hit) => addMarker(hit));
}

async function fetchAllHits(index, query, facetFilters) {
  let hits = [];
  let page = 0;
  let results;

  do {
    results = await index.search(query, {
      page,
      facetFilters,
    });
    hits = hits.concat(results.hits);
    page++;
  } while (results.nbHits > hits.length);

  return hits;
}

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
  if (opportunities.includes('Registered Apprenticeship')) {
    return 'APPRENTICESHIP';
  }
  if (opportunities.includes('Training')) {
    return 'TRAINING';
  }
  if (opportunities.includes('Bids & Contracts')) {
    return 'BIDS & CONTRACTS';
  }
  if (opportunities.includes('Hiring')) {
    return 'HIRING';
  }

  if (opportunities.includes('Information')) {
    return 'INFORMATION';
  }
}
function generateOrgHTML(item) {
  const orgImageName = getImageName(item.opportunities);

  return `<div  class="organization">
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
      ${item.opportunities
        ?.map((opp) => `<p>${opp}</p>`)
        .sort()
        .join('')}
    </div>
    <p class="organization__info-description">${item.description}</p>
    <div class="organization__tags">
      ${item.general_tags
        ?.sort()
        .map((tag) => `<div class="organization__tag">${tag}</div>`)
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
          ? `<a href="mailto:${item.email}" target="_blank" class="organization__link organization__link--email">
            <img src="/wp-content/plugins/irec-green-connect/public/img/email.svg" />
            ${item.email}
          </a>`
          : ''
      }
    
    <a data-organization="${item.organization_name}" target="_blank" href="${
    item.url || `mailto:${item.email}`
  }" class="organization__connect-now" onclick="saveOrgToGa(this)">
    Connect Now
    </a>
      
  </div>
</div>
`;
}

function formatValue(input) {
  if (input === 'Diversity Equity and Inclusion') {
    return 'Diversity, Equity, and Inclusion';
  }
  return input;
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
  const tagsFacetFilters = tags.map(
    (tag) => `general_tags:${formatValue(tag)}`,
  );

  // Combine facet filters into an array to use OR logic within the same facet and AND logic between facets
  const combinedFacetFilters = [];
  if (opportunitiesFacetFilters.length > 0) {
    combinedFacetFilters.push(opportunitiesFacetFilters);
  }
  if (tagsFacetFilters.length > 0) {
    combinedFacetFilters.push(tagsFacetFilters);
  }

  // Trigger the search event only after the user has stopped typing
  debounceSearch(query);

  // Trigger the filter event only after the user has stopped changing filters
  debounceFilter(opportunities, tags);

  orgsSearch.helper
    .setQueryParameter('query', query ?? '')
    .setQueryParameter('facetFilters', [
      ...combinedFacetFilters,
      'remote_or_in_person:In-Person',
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

function saveToGA(value) {
  if (!gtag) return;
  gtag('event', 'user_location', {
    category: 'connect_now', // Custom parameter
    click_label: 'connect_now_location_input', // Custom parameter
    value: value, // Assuming 'value' is the variable holding the input data
  });
}

function saveOrgToGa(link) {
  const organization = link.dataset.organization;
  gtag('event', 'organization_link', {
    category: 'connect_now', // Custom parameter
    click_label: 'connect_now_organization_referral', // Custom parameter
    href: link.getAttribute('href'), // Custom parameter
    title: organization, // Custom parameter
  });
}
