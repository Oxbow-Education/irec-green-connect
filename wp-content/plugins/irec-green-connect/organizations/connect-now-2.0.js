let geocoder;
let map;
let markers = [];
let bounds;
let orgsSearch;

function initMap() {
  const options = {
    zoom: 9,
  };
  map = new google.maps.Map(document.getElementById('map'), options);
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
function addMarker(item) {
  const icon = {
    url: '/wp-content/plugins/irec-green-connect/public/img/marker.svg',
    scaledSize: new google.maps.Size(50, 50),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(25, 50),
  };
  const markerOptions = {
    ...(item._geoloc?.lat ? { position: item._geoloc } : {}),
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
  if (item._geoloc?.lat) {
    bounds.extend(marker.getPosition());
  }

  map.fitBounds(bounds);
}

document.addEventListener('DOMContentLoaded', () => {
  // Define google items
  geocoder = new google.maps.Geocoder();
  bounds = new google.maps.LatLngBounds();

  // Init orgsSearch
  orgsSearch = instantsearch({
    indexName: 'organizations-new',
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
      container: '.results__hits',
      showPrevious: false,
      templates: {
        item: (item) => {
          return `<div class="organization-card">
          <h6 class="organization-tite">${item.title}</h6>
        </div>`;
        },
        empty:
          '<p style="col-span: 3">Green Workforce Connect currently connects to organizations active in Oklahoma, Pennsylvania, and Wisconsin.</p>',
      },
    }),
  ]);
  orgsSearch.start();
});
