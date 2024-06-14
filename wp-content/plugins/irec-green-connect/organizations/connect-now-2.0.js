// Global variables
let geocoder;
let map;
let markers = [];
let infoWindows = [];
let bounds;
let orgsSearch;
let initialSetup = true;
let boundsChangeTimeout;

// Initialize the map with predefined options and event listeners
function initMap() {
  const mapOptions = {
    zoom: 9,
    center: { lat: 30.9843, lng: -91.9623 },
  };
  map = new google.maps.Map(document.getElementById('map'), mapOptions);
  bounds = new google.maps.LatLngBounds();
  setupAlgoliaSearch();
  map.addListener('bounds_changed', onBoundsChanged);
}

// Function to handle changes in the map bounds and update Algolia search
function onBoundsChanged() {
  if (boundsChangeTimeout) clearTimeout(boundsChangeTimeout);

  boundsChangeTimeout = setTimeout(() => {
    const bounds = map.getBounds();
    const ne = bounds.getNorthEast(); // North East corner
    const sw = bounds.getSouthWest(); // South West corner

    // Convert bounds to the format expected by Algolia
    const algoliaBounds = [sw.lat(), sw.lng(), ne.lat(), ne.lng()].join();

    // Update Algolia search to only show results within the current map bounds
    orgsSearch.helper
      .setQueryParameter('insideBoundingBox', algoliaBounds)
      .search();
  }, 500);
}

// Add a marker to the map for each item
function addMarker(item) {
  const iconOptions = {
    url: '/wp-content/plugins/irec-green-connect/public/img/marker.svg',
    scaledSize: new google.maps.Size(50, 50),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(25, 50),
  };

  const marker = new google.maps.Marker({
    position: new google.maps.LatLng(item._geoloc.lat, item._geoloc.lng),
    map: map,
    icon: iconOptions,
  });

  const infoWindow = new google.maps.InfoWindow({
    content: generateInfoWindowContent(item),
  });

  marker.addListener('click', () => handleMarkerClick(marker, infoWindow));
  markers.push(marker);
  bounds.extend(marker.position);
  infoWindows.push(infoWindow);
}

// Handle clicks on markers
function handleMarkerClick(marker, infoWindow) {
  infoWindows.forEach((win) => win.close());
  markers.forEach((mk) =>
    mk.setIcon('/wp-content/plugins/irec-green-connect/public/img/marker.svg'),
  );
  marker.setIcon(
    '/wp-content/plugins/irec-green-connect/public/img/marker-selected.svg',
  );
  infoWindow.open(map, marker);
}

// Generate HTML content for the info window
function generateInfoWindowContent(item) {
  return `
    <div class="info-window">
      <h1>${item.title}</h1>
      <p>${item.description}</p>
      <a target="_blank" href="${item.url}">Connect Now
      <img src="/wp-content/plugins/irec-green-connect/public/img/external-link-white.png" />
      </a>
    </div>
  `;
}

// Clear all markers from the map
function clearMarkers() {
  markers.forEach((marker) => marker.setMap(null));
  markers = [];
  infoWindows = [];
  bounds = new google.maps.LatLngBounds();
}

// Setup Algolia search after the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  if (!orgsSearch) setupAlgoliaSearch();
  setTimeout(() => {
    initializeSearchFromURL();
  }, 200);
});

// Function to extract the query parameter and initialize the search
function initializeSearchFromURL() {
  const urlParams = new URLSearchParams(window.location.search);
  const query = urlParams.get('query');

  if (query) {
    const searchInput = document.querySelector('#algoliaSearch input');
    if (searchInput) {
      searchInput.value = query; // Set the input field to reflect the query from URL
    }
    clearMarkers();
    orgsSearch.helper.setQuery(query).search(); // Set the initial search query in Algolia
  }
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

  orgsSearch.addWidgets([
    instantsearch.widgets.configure({ hitsPerPage: 12 }),
    instantsearch.widgets.infiniteHits({
      container: '.results__hits',
      showPrevious: false,
      templates: {
        item: (item) => {
          addMarker(item);
          return generateOrgHTML(item);
        },
        empty: '<p>No organizations found in this area.</p>',
      },
    }),
  ]);

  orgsSearch.on('render', () => {
    if (markers.length > 0 && initialSetup) {
      map.fitBounds(bounds);
      initialSetup = false;
    }
  });

  orgsSearch.start();
}

// Add event listener to handle input changes and update Algolia search
document.addEventListener('DOMContentLoaded', function () {
  const searchForm = document.getElementById('algoliaSearch');

  if (searchForm) {
    const searchInput = searchForm.querySelector('input');
    const clearSearch = searchForm.querySelector('.search__clear');
    searchForm.addEventListener('submit', function (event) {
      event.preventDefault();
      clearMarkers();
      const query = searchInput.value;

      // Update the Algolia search query
      orgsSearch.helper.setQuery(query).search();

      // Update the URL with the query parameter
      const url = new URL(window.location);
      url.searchParams.set('query', query);
      history.pushState(null, '', url.toString());
    });
    clearSearch.addEventListener('click', function (event) {
      // Update the Algolia search query
      orgsSearch.helper.setQuery('').search();
      searchInput.value = '';

      // Update the URL with the query parameter
      const url = new URL(window.location);
      url.searchParams.remote('query');
      history.pushState(null, '', url.toString());
    });
  }

  if (!orgsSearch) setupAlgoliaSearch(); // Initialize Algolia search if not already done
});

function generateOrgHTML(item) {
  return `<div class="organization">
  <div class="organization__container">
    <div class="organization__info">
      <div class="organization__info-img">
        <img src="/wp-content/plugins/irec-green-connect/public/img/org-1.png" alt="Organization Image" />  
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
      <div class="organiztion__link">
        <img src="/wp-content/plugins/irec-green-connect/public/img/org-location.svg" />
        ${item.address}
      </div>
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
