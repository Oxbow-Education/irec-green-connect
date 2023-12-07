let geocoder;
let map;
let markers = [];
let bounds;
let orgsSearch;

// This is just a short term solution. IREC requested that the
// map auto filter by the state selected.
const STATE_ABREVS = {
  Oklahoma: 'OK',
  Pennsylvania: 'PA',
  Wisconsin: 'WI',
};
const STATE_COORDS = {
  Oklahoma: { lat: 35.0078, lng: -97.0929 },
  Pennsylvania: { lat: 41.2033, lng: -77.1945 },
  Wisconsin: { lat: 43.7844, lng: -88.7879 },
};
function prefilterMapBasedOnLocation() {
  const connectNowWrapper = document.getElementById('connectNow');
  const state = connectNowWrapper.dataset.state;
  if (STATE_ABREVS[state]) {
    const abbrev = STATE_ABREVS[state];
    orgsSearch.helper.setQueryParameter('facetFilters', [`state:${abbrev}`]);
    orgsSearch.helper.search();
    const bounds = calculateBounds(STATE_COORDS[state], 75);
    map.fitBounds(bounds);
  }
}
function calculateBounds(centerLatLng, radiusMiles) {
  // Earth's radius in miles
  const earthRadius = 3958.8; // approximate value for the radius of the Earth in miles

  // Convert the radius from miles to radians
  const radiusRadians = radiusMiles / earthRadius;

  const lat = centerLatLng.lat;
  const lng = centerLatLng.lng;

  // Calculate the latitude and longitude bounds
  const north = lat + radiusRadians * (180 / Math.PI);
  const south = lat - radiusRadians * (180 / Math.PI);
  const east =
    lng + (radiusRadians * (180 / Math.PI)) / Math.cos((lat * Math.PI) / 180);
  const west =
    lng - (radiusRadians * (180 / Math.PI)) / Math.cos((lat * Math.PI) / 180);

  return {
    north: north,
    south: south,
    east: east,
    west: west,
  };
}

function setPositionQuery(lat, lng) {
  const aroundLatLng = `${lat}, ${lng}`;
  orgsSearch.helper.setQueryParameter('aroundRadius', 160934);
  orgsSearch.helper.setQueryParameter('aroundLatLng', aroundLatLng);
  orgsSearch.helper.search();
  const bounds = calculateBounds({ lat, lng }, 75);
  map.fitBounds(bounds);
}

function removePositionQuery() {
  orgsSearch.helper.setQueryParameter('aroundRadius', undefined);
  orgsSearch.helper.setQueryParameter('aroundLatLng', undefined);
  orgsSearch.helper.search();

  prefilterMapBasedOnLocation();
}
// Adds a marker to the map
function addMarker(item) {
  const icon = {
    url: '/wp-content/plugins/irec-green-connect/public/img/marker.svg',
    scaledSize: new google.maps.Size(50, 50),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(25, 50),
  };
  const markerOptions = {
    position: item._geoloc,
    map: map,
    icon: icon,
  };
  const marker = new google.maps.Marker(markerOptions);
  const infoWindow = new google.maps.InfoWindow({
    content: item.organization,
  });

  marker.addListener('click', function () {
    infoWindow.open(map, marker);
  });
  markers.push(marker);
  bounds.extend(marker.getPosition());
  map.fitBounds(bounds);
}

// Pulls all organizations to initialize the map markers
async function initMarkers() {
  const organizations = await fetch(
    '/wp-json/wp/v2/organization?per_page=100',
  ).then((response) => {
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json();
  });
  organizations.forEach((org) => addMarker(org?.acf));
  prefilterMapBasedOnLocation();
}

// Initialized the map
function initMap() {
  const options = {
    zoom: 9,
  };
  map = new google.maps.Map(document.getElementById('map'), options);
  initMarkers();
}

async function getZipCodeFromCoordinates(lat, lng) {
  var latlng = new google.maps.LatLng(lat, lng);

  const { results } = await geocoder.geocode({ location: latlng });
  if (results[0]) {
    for (var i = 0; i < results[0].address_components.length; i++) {
      const component = results[0].address_components[i];
      if (component.types.includes('postal_code')) {
        var zipCode = component.short_name;
        return Promise.resolve(zipCode);
      }
    }
  } else {
    return Promise.reject('No results found.');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  // Define google items
  geocoder = new google.maps.Geocoder();
  bounds = new google.maps.LatLngBounds();

  // Init orgsSearch
  orgsSearch = instantsearch({
    indexName: 'organization',
    facets: ['filters'],
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
  });

  // Add and define widgets
  orgsSearch.addWidgets([
    instantsearch.widgets.configure({
      hitsPerPage: 12,
    }),
    instantsearch.widgets.infiniteHits({
      container: '#hits',
      showPrevious: false,
      templates: {
        item: (item) => {
          return `<div class="organization-card">
          <h6 class="organization-tite">${item.organization}</h6>
          <div class="organization-image">
            <img src="${
              !item.is_training_provider
                ? 'http://irec.wherewego.org/wp-content/uploads/2023/11/1-1.png'
                : 'http://irec.wherewego.org/wp-content/uploads/2023/11/2-1-1.png'
            }" />
            <div class="organization-tags">
              ${item.tags.map((tag) => `<span>${tag}</span>`).join('')}
            </div>
          </div>
           <div class="organization-filters">
            ${item.filters.map((filter) => `<span>${filter}</span>`).join('')}
          </div>
        
            <p class="organization-sentence">${item.sentence}</p>
            <div class="organization-info">
              <div>
                <p>${item.phone}</p>
                <p>${item.email}</p>
                <p>${item.city}, ${item.state}</p>
              </div>
              <a data-organization="${
                item.organization
              }" onclick="saveOrgToGa(this)" class="organization-link" href="${
            item.link
          }" target="_blank">Get Started 
              <img src="/wp-content/plugins/irec-green-connect/public/img/link.png" />
              </a>
            </div>
          </div>

        </div>`;
        },
        empty:
          '<p style="col-span: 3">Green Workforce Connect currently connects to organizations active in Oklahoma, Pennsylvania, and Wisconsin.</p>',
      },
    }),
  ]);

  // Handle form submission
  const form = document.getElementById('custom-searchbox');
  const input = document.getElementById('zipcode');
  const clearLocation = document.getElementById('clearLocation');
  input.addEventListener('input', () => {
    if (input.value) {
      clearLocation.classList.remove('hidden');
    } else {
      clearLocation.classList.add('hidden');
    }
  });
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formValues = new FormData(form);
    const zipcode = formValues.get('zipcode');
    if (!zipcode) {
      removePositionQuery();
    }
    try {
      const results = await geocoder.geocode({
        address: zipcode,
      });
      if (!results || !results?.results?.length) {
        throw new Error('No results found for that zipcode.');
      }
      const lat = results.results[0].geometry.location.lat();
      const lng = results.results[0].geometry.location.lng();
      setPositionQuery(lat, lng);
      saveToGA(zipcode);
    } catch (err) {
      console.log(err);
    }
  });

  clearLocation.addEventListener('click', () => {
    input.value = '';
    clearLocation.classList.add('hidden');
  });

  async function getUserLocation() {
    if ('geolocation' in navigator) {
      document.getElementById('connectNow').classList.add('loading');
      navigator.geolocation.getCurrentPosition(
        async (position) => {
          document.getElementById('connectNow').classList.remove('loading');

          var latitude = position.coords.latitude;
          var longitude = position.coords.longitude;
          const zipcode = await getZipCodeFromCoordinates(latitude, longitude);
          saveToGA(zipcode);
          document.getElementById('zipcode').value = zipcode;
          setPositionQuery(latitude, longitude);
          document.getElementById('clearLocation').classList.remove('hidden');
        },
        function (error) {
          document.getElementById('connectNow').classList.remove('loading');

          switch (error.code) {
            case error.PERMISSION_DENIED:
              alert('User denied the request for Geolocation.');
              break;
            case error.POSITION_UNAVAILABLE:
              alert('Location information is unavailable.');
              break;
            case error.TIMEOUT:
              alert('The request to get user location timed out.');
              break;
            case error.UNKNOWN_ERROR:
              alert('An unknown error occurred.');
              break;
          }
        },
      );
    } else {
      document.getElementById('connectNow').classList.remove('loading');

      alert('Geolocation is not supported by this browser.');
    }
  }

  // Attach the function to the button click event
  const geolocButton = document.getElementById('geolocButton');
  geolocButton.addEventListener('click', getUserLocation);

  orgsSearch.start();

  // Filter button functionality
  const filterButtons = document.querySelectorAll('.org-filter');
  filterButtons.forEach((facet) => {
    facet.addEventListener('click', (e) => {
      const wasActive = facet.classList.contains('active');
      filterButtons.forEach((f) => f.classList.remove('active'));

      if (wasActive) {
        facet.classList.remove('active');
        orgsSearch.helper.setQueryParameter('facetFilters', '');
      } else {
        facet.classList.add('active');
        const data = facet.dataset.filter;
        orgsSearch.helper.setQueryParameter('facetFilters', [
          `filters:${data}`,
        ]);
      }
      orgsSearch.helper.search();
    });
  });
});

document
  .getElementById('geolocButton')
  .addEventListener('mouseover', function (event) {
    var tooltip = document.getElementById('tooltip');
    var buttonRect = event.target.getBoundingClientRect();

    tooltip.style.display = 'block';
    tooltip.style.left = buttonRect.left - 67 + 'px'; // Align with the left edge of the button
    tooltip.style.top = buttonRect.top + 50 + 'px'; // Position below the button
  });

document
  .getElementById('geolocButton')
  .addEventListener('mouseout', function () {
    document.getElementById('tooltip').style.display = 'none';
  });

function saveToGA(value) {
  if (!gtag) return;
  gtag('event', 'user_location', {
    connect_now_category: 'connect_now', // Custom parameter
    connect_now_label: 'connect_now_location_input', // Custom parameter
    input_value: value, // Assuming 'value' is the variable holding the input data
  });
}

function saveOrgToGa(link) {
  const organization = link.dataset.organization;
  gtag('event', 'organization_link', {
    connect_now_category: 'connect_now', // Custom parameter
    connect_now_label: 'connect_now_organization_referral', // Custom parameter
    href: link.getAttribute('href'), // Custom parameter
    organization: organization, // Custom parameter
  });
}
