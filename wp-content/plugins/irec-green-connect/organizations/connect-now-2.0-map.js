let isProgrammaticChange = false;

let defaultBounds;

document.addEventListener('DOMContentLoaded', () => {
  handleAutocomplete();
  handleResetBounds();
  syncMapToURL();
  handleCurrentLocationFunctionality();
  handleUpdateResetBoundsButton();
});

window.addEventListener(URL_UPDATED, () => {
  handleUpdateResetBoundsButton();
});

function handleUpdateResetBoundsButton() {
  const resetBoundsButton = document.getElementById('resetBoundsButton');
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const bounds = searchParams.get('bounds');
  const location = searchParams.get('location');
  if (location || bounds) {
    resetBoundsButton.classList.remove('hidden');
    return;
  }
  resetBoundsButton.classList.add('hidden');
}

function handleResetBounds() {
  const resetBoundsButton = document.getElementById('resetBoundsButton');
  resetBoundsButton.addEventListener('click', resetBounds);
}

function resetBounds() {
  updateQueryParam('location', '', true, true);
  updateQueryParam('bounds', '', true, true);
  updateBounds(defaultBounds);
}

// Initialize the map with predefined options and event listeners
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    streetViewControl: false,
  });
  defaultBounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(24.5, -125),
    new google.maps.LatLng(49, -66),
  );
  updateBounds(defaultBounds);
  setupAlgoliaSearch();
  map.addListener('bounds_changed', onBoundsChanged);
}

// Function to handle changes in the Map area and update Algolia search
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
      autocompleteEl.value = 'Map area';
      updateQueryParam('location', 'Map area', false, true);
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
      <h1>${item.organization_name}</h1>
      <h2>${item.program_name}</h2>
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

function useTextSearch(request) {
  // If the place doesn't have geometry, use the place name to find the first prediction
  const service = new google.maps.places.PlacesService(
    document.createElement('div'),
  );
  service.textSearch(request, (results, status) => {
    if (
      status === google.maps.places.PlacesServiceStatus.OK &&
      results.length > 0
    ) {
      const firstPrediction = results[0];

      // Update the autocomplete input with the description value for the found place
      const input = document.getElementById('autocomplete');
      input.value = firstPrediction.formatted_address;

      // Use the firstPrediction which has geometry
      usePlace(firstPrediction);
    } else {
      console.error('No predictions found or error in request:', status);
    }
  });
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
      const request = {
        query: place.name,
        fields: ['name', 'geometry', 'formatted_address', 'place_id'],
      };
      useTextSearch(request);
      handleGtagEvent(place);
    } else {
      usePlace(place);
      handleGtagEvent(place);
    }
  });

  function handleGtagEvent(place) {
    const zipcode =
      place.address_components?.find((component) =>
        component.types.includes('postal_code'),
      )?.short_name || '';

    const city =
      place.address_components?.find((component) =>
        component.types.includes('locality'),
      )?.long_name || '';

    const state =
      place.address_components?.find((component) =>
        component.types.includes('administrative_area_level_1'),
      )?.short_name || '';

    gtag('event', 'user_location', {
      category: 'connect_now',
      click_label: 'connect_now_location_input',
      zipcode,
      city,
      state,
    });
  }

  const locationForm = document.querySelector('.location__form');
  locationForm.addEventListener('submit', (e) => e.preventDefault());
}
function usePlace(place) {
  const description = place.formatted_address; // This gets the location's formatted text address
  updateQueryParam('location', description, false, true);
  updateQueryParam('bounds', '', true, true);

  getBoundsForLocation(description);
}
function syncMapToURL() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const description = searchParams.get('location');
  if (description && description != 'Map area') {
    getBoundsForLocation(description);
    const autocompleteEl = document.getElementById('autocomplete');
    autocompleteEl.value = description;
  }

  if (description == 'Map area') {
    const bounds = convertBoundsToGoogleMap(searchParams.get('bounds'));
    if (bounds) {
      updateBounds(bounds);
      setTimeout(() => {
        const zoom = map.getZoom();
        map.setZoom(zoom + 1);
      }, 100);
      const autocomplete = document.querySelector('#autocomplete');
      autocomplete.value = 'Map area';
    } else {
      updateQueryParam('location', '', true, true);
      updateQueryParam('bounds', '', true, true);
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
    const addressComponents = results[0].address_components;

    // Function to get a component of a certain type
    function getAddressComponent(type) {
      return addressComponents.find((component) =>
        component.types.includes(type),
      );
    }

    // Get the state component, if any
    const stateComponent = getAddressComponent('administrative_area_level_1');
    const cityComponent = getAddressComponent('locality');

    const isState = stateComponent && !cityComponent;
    const isCity = cityComponent;

    if (results[0].geometry.bounds) {
      updateBounds(results[0].geometry.bounds);
      if (isState) {
        map.setZoom(6);
      }
      if (isCity) {
        map.setZoom(8);
      }
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
  const currentLocationButton = document.querySelector('.location__current');
  currentLocationButton.addEventListener('click', () => {
    const dialog = document.querySelector('.dialog-overview');
    dialog.show();

    navigator.geolocation.getCurrentPosition(
      (position) => {
        const center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };

        getCityFromCoordinates(center);
        dialog.hide();
      },
      (error) => {
        console.error('Geolocation error:', error.message);
        alert(error.message);
        dialog.hide();
      },
    );
  });
}

function getCityFromCoordinates(center) {
  const geocoder = new google.maps.Geocoder();
  geocoder.geocode({ location: center }, function (results, status) {
    if (status === 'OK' && results[0]) {
      let city = null;
      let state = null;
      let zipcode = null;

      for (const component of results[0].address_components) {
        if (component.types.includes('locality')) {
          city = component.long_name;
        }
        if (component.types.includes('administrative_area_level_1')) {
          state = component.short_name;
        }
        if (component.types.includes('postal_code')) {
          zipcode = component.short_name;
        }
      }

      if (city && state) {
        const location = `${city}, ${state}`;
        const autocomplete = document.querySelector('#autocomplete');
        autocomplete.value = location;

        gtag('event', 'user_location', {
          category: 'connect_now',
          click_label: 'connect_now_current_location',
          city,
          state,
          zipcode: zipcode || '',
        });

        updateQueryParam('location', location, false, true);
        updateQueryParam('bounds', '', true, true);
        getBoundsForLocation(location);
      }
    }
  });
}
