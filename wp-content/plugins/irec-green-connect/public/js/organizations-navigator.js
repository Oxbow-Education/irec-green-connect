let geocoder;
let map;
let markers = [];
let bounds;
let orgsSearch;

function setPositionQuery(lat, lng) {
  const aroundLatLng = `${lat}, ${lng}`;
  orgsSearch.helper.setQueryParameter('aroundRadius', 80467);
  orgsSearch.helper.setQueryParameter('aroundLatLng', aroundLatLng);
  orgsSearch.helper.search();
  bounds = new google.maps.LatLngBounds();
  markers.forEach((marker) => marker.setMap(null));
}
// Adds a marker to the map
const addMarker = (item) => {
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
};

// Pulls all organizations to initialize the map markers
const initMarkers = async () => {
  const organizations = await fetch(
    '/wp-json/wp/v2/organization?per_page=100',
  ).then((response) => {
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json();
  });
  organizations.forEach((org) => addMarker(org?.acf));
};

// Initialized the map
function initMap() {
  const options = {
    zoom: 8,
  };
  map = new google.maps.Map(document.getElementById('map'), options);
  initMarkers();
}

const getZipCodeFromCoordinates = async (lat, lng) => {
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
};

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
          addMarker(item);
          return `<div class="organization-card">
          <h6>${item.organization}</h6>
          <div class="organization-image">
            <img src="/wp-content/uploads/2023/09/NREL-Quality-Control-Inspector-72292-scaled.jpg" />
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
              <a class="organization-link" href="${
                item.link
              }" target="_blank">Get Started 
              <img src="/wp-content/plugins/irec-green-connect/public/img/arow-right.svg" />
              </a>
            </div>
          </div>

        </div>`;
        },
      },
    }),
  ]);

  // Handle form submission
  const form = document.getElementById('custom-searchbox');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formValues = new FormData(form);
    const zipcode = formValues.get('zipcode');
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
    } catch (err) {
      console.log(err);
    }
  });

  const getUserLocation = async () => {
    if ('geolocation' in navigator) {
      navigator.geolocation.getCurrentPosition(
        async (position) => {
          var latitude = position.coords.latitude;
          var longitude = position.coords.longitude;
          const zipcode = await getZipCodeFromCoordinates(latitude, longitude);
          console.log({ zipcode });
          document.getElementById('zipcode').value = zipcode;
          setPositionQuery(latitude, longitude);
        },
        function (error) {
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
      alert('Geolocation is not supported by this browser.');
    }
  };

  // Attach the function to the button click event
  var geolocButton = document.getElementById('geolocButton');
  geolocButton.addEventListener('click', getUserLocation);

  orgsSearch.start();

  const filterButtons = document.querySelectorAll('.org-filter');
  filterButtons.forEach((facet) => {
    facet.addEventListener('click', (e) => {
      filterButtons.forEach((f) => f.classList.remove('active'));
      facet.classList.add('active');
      const data = e.target.dataset.filter;
      orgsSearch.helper.setQueryParameter('facetFilters', [`filters:${data}`]);
      orgsSearch.helper.search();
    });
  });
});
