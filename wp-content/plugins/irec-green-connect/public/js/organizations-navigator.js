let geocoder;
let map;
let markers = [];
let bounds;

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

document.addEventListener('DOMContentLoaded', () => {
  // Define google items
  geocoder = new google.maps.Geocoder();
  bounds = new google.maps.LatLngBounds();

  // Init search
  const search = instantsearch({
    indexName: 'organization',
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
  });

  // Add and define widgets
  search.addWidgets([
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
              ${item.tags.map((tag) => `<span>${tag}</span>`)}
            </div>
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
      const aroundLatLng = `${lat}, ${lng}`;
      search.helper.setQueryParameter('aroundRadius', 80467);
      search.helper.setQueryParameter('aroundLatLng', aroundLatLng);
      search.helper.search();
      bounds = new google.maps.LatLngBounds();
      markers.forEach((marker) => marker.setMap(null));
    } catch (err) {
      console.log(err);
    }
  });

  search.start();
});
