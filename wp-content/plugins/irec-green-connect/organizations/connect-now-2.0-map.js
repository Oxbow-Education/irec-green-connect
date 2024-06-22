let isProgrammaticChange = false;

// Initialize the map with predefined options and event listeners
function initMap() {
  map = new google.maps.Map(document.getElementById('map'));

  const defaultBounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(24.5, -125),
    new google.maps.LatLng(49, -66),
  );
  updateBounds(defaultBounds);
  setupAlgoliaSearch();
  map.addListener('bounds_changed', onBoundsChanged);
}

// Function to handle changes in the map bounds and update Algolia search
function onBoundsChanged() {
  if (boundsChangeTimeout) clearTimeout(boundsChangeTimeout);
  boundsChangeTimeout = setTimeout(() => {
    const bounds = map.getBounds();

    const algoliaBounds = [
      bounds.getSouthWest().lat().toFixed(20),
      bounds.getSouthWest().lng().toFixed(20),
      bounds.getNorthEast().lat().toFixed(20),
      bounds.getNorthEast().lng().toFixed(20),
    ].join(',');

    orgsSearch.helper
      .setQueryParameter('insideBoundingBox', algoliaBounds)
      .search();

    const autocompleteEl = document.getElementById('autocomplete');
    if (!isProgrammaticChange) {
      autocompleteEl.value = 'Map Bounds';
      updateQueryParam('location', 'Map Bounds', false, true);
      updateQueryParam('bounds', algoliaBounds, false, true);
    }

    isProgrammaticChange = false;
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
}

function handleAutocomplete() {
  const input = document.getElementById('autocomplete');
  const options = {
    types: ['(regions)'],
    componentRestrictions: { country: 'US' },
  };

  const autocomplete = new google.maps.places.Autocomplete(input, options);
  autocomplete.setFields([
    'geometry',
    'formatted_address',
    'address_components',
    'types',
  ]);

  autocomplete.addListener('place_changed', function () {
    const place = autocomplete.getPlace();
    if (!place.geometry) {
      console.error("Autocomplete's returned place contains no geometry!");
      return;
    }

    const description = place.formatted_address; // This gets the location's formatted text address

    updateQueryParam('location', description, false, true);
    updateQueryParam('bounds', '', false, true);

    getBoundsForLocation(description);
  });
}

function syncMapToURL() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const description = searchParams.get('location');
  if (description != 'Map Bounds') {
    getBoundsForLocation(description);
    const autocompleteEl = document.getElementById('autocomplete');
    autocompleteEl.value = description;
  }

  if (description == 'Map Bounds') {
    const bounds = convertBoundsToGoogleMap(searchParams.get('bounds'));
    if (bounds) {
      updateBounds(bounds);
      const zoom = map.getZoom();
      map.setZoom(zoom + 1);
    } else {
      updateQueryParam('location', '', true, true);
    }
  }
}

function convertBoundsToGoogleMap(boundsParam) {
  // Step 1: Decode the URL-encoded string
  const decodedBounds = decodeURIComponent(boundsParam);

  // Step 2: Parse the bounds into latitude and longitude values
  const boundsArray = decodedBounds.split(',').map(Number);

  if (boundsArray.length !== 4) {
    console.error('Invalid bounds parameter');
    return null;
  }

  const [southLat, westLng, northLat, eastLng] = boundsArray;

  // Step 3: Create a google.maps.LatLngBounds object
  const bounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(southLat, westLng),
    new google.maps.LatLng(northLat, eastLng),
  );

  return bounds;
}
function getBoundsForLocation(location) {
  const geocoder = new google.maps.Geocoder();

  // Check if the location is an address (string) or coordinates (object)
  if (typeof location === 'string') {
    geocoder.geocode({ address: location }, function (results, status) {
      handleGeocodeResults(results, status);
    });
  } else if (typeof location === 'object' && location.lat && location.lng) {
    geocoder.geocode({ location: location }, function (results, status) {
      handleGeocodeResults(results, status);
    });
  } else {
    console.error(
      'Invalid location input. Please provide an address or coordinates.',
    );
  }
}

function handleGeocodeResults(results, status) {
  if (status === 'OK' && results[0]) {
    if (results[0].geometry.bounds) {
      console.log(results[0].geometry.bounds);
      updateBounds(results[0].geometry.bounds);
    } else if (results[0].geometry.location) {
      updateCenterZoom(results[0].geometry.location, 12);
    }
  } else {
    console.error(
      'Geocode was not successful for the following reason: ' + status,
    );
  }
}

function updateCenterZoom(center, zoom) {
  isProgrammaticChange = true;
  map.setCenter(center);
  map.setZoom(zoom);
}

function updateBounds(bounds) {
  isProgrammaticChange = true;
  map.fitBounds(bounds);
}

function handleCurrentLocationFunctionality() {
  const currentLocationButton = document.querySelector('.current__location');
  currentLocationButton.addEventListener('click', () => {
    navigator.geolocation.getCurrentPosition((position) => {
      const center = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };

      getBoundsForLocation(center);
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  handleAutocomplete();
  syncMapToURL();
});
