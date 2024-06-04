let geocoder;
let map;
let markers = [];
let bounds;
let orgsSearch;
let initialSetup = true;
let boundsChangeTimeout;

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

function initMap() {
  const options = {
    zoom: 9, // Initial zoom level; may adjust later based on bounds
    center: { lat: 30.9843, lng: -91.9623 }, // Initial center; will adjust based on markers
  };
  map = new google.maps.Map(document.getElementById('map'), options);
  bounds = new google.maps.LatLngBounds();
  setupAlgoliaSearch(); // Initialize the Algolia Search setup
  map.addListener('bounds_changed', onBoundsChanged);
}

function addMarker(item) {
  const position = new google.maps.LatLng(item._geoloc.lat, item._geoloc.lng);
  const marker = new google.maps.Marker({
    position: position,
    map: map,
    icon: {
      url: '/wp-content/plugins/irec-green-connect/public/img/marker.svg',
      scaledSize: new google.maps.Size(50, 50),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(25, 50),
    },
  });

  const infoWindow = new google.maps.InfoWindow({
    content: item.organization,
  });

  marker.addListener('click', function () {
    infoWindow.open(map, marker);
  });

  markers.push(marker);
  bounds.extend(position);
}

function clearMarkers() {
  for (let marker of markers) {
    marker.setMap(null); // Remove marker from the map
  }
  markers = []; // Clear the markers array
  bounds = new google.maps.LatLngBounds(); // Reset the bounds
}

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
      <div class="organiztion__link">
        <img src="/wp-content/plugins/irec-green-connect/public/img/phone.svg" />
        ${item.phone}
      </div>
      <div class="organiztion__link">
      <img src="/wp-content/plugins/irec-green-connect/public/img/email.svg" />
      ${item.email}
    </div>

    <a target="_blank" src="${item.url}" class="organization__connect-now">
    Connect Now
    </a>
      
  </div>
</div>
`;
}
document.addEventListener('DOMContentLoaded', () => {
  setupAlgoliaSearch(); // Ensure Algolia is set up after the DOM is loaded if not already called in initMap
});

function setupAlgoliaSearch() {
  orgsSearch = instantsearch({
    indexName: 'organizations-new',
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
  });

  orgsSearch.addWidgets([
    instantsearch.widgets.configure({
      hitsPerPage: 12,
    }),
    instantsearch.widgets.infiniteHits({
      container: '.results__hits',
      showPrevious: false,
      templates: {
        item: (item) => {
          addMarker(item);
          return generateOrgHTML(item); // A function to handle HTML generation
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
