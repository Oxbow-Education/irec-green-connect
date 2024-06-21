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
    const shouldRebound = true;
    console.log({
      shouldRebound,
      isMobileScreen: isMobileScreen(),
      isListView,
    });
    if (shouldRebound) {
      const bounds = map.getBounds();
      const ne = bounds.getNorthEast(); // North East corner
      const sw = bounds.getSouthWest(); // South West corner
      // Convert bounds to the format expected by Algolia
      const algoliaBounds = [sw.lat(), sw.lng(), ne.lat(), ne.lng()].join();
      console.log({ algoliaBounds });
      orgsSearch.helper
        .setQueryParameter('insideBoundingBox', algoliaBounds)
        .search();
    }
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
  // bounds.extend(marker.position);
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
  // bounds = new google.maps.LatLngBounds();
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

    const lat = place.geometry.location.lat();
    const lng = place.geometry.location.lng();
    const description = place.formatted_address; // This gets the location's formatted text address

    updateQueryParam('location', description);

    // Check if the selected place is a state
    if (place.types.includes('administrative_area_level_1')) {
      getBoundsForState(description);
    } else {
      const bounds = { lat: lat, lng: lng };
      map.setCenter(bounds);
      map.setZoom(9);
    }
  });

  function getBoundsForState(stateName) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: stateName }, function (results, status) {
      if (status === 'OK' && results[0] && results[0].geometry.bounds) {
        const bounds = results[0].geometry.bounds;
        map.fitBounds(bounds); // Set map bounds to the state's bounds
      } else {
        console.error(
          'Geocode was not successful for the following reason: ' + status,
        );
      }
    });
  }

  function updateQueryParam(param, value) {
    if (history.pushState) {
      const newurl = new URL(window.location.href);
      newurl.searchParams.set(param, value);
      window.history.pushState({ path: newurl.href }, '', newurl.href);
    }
  }
}

function syncMapToURL() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const description = searchParams.get('location');
  if (description) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: description }, function (results, status) {
      if (status === 'OK' && results[0]) {
        if (results[0].geometry.bounds) {
          map.fitBounds(results[0].geometry.bounds);
        } else if (results[0].geometry.location) {
          map.setCenter(results[0].geometry.location);
          map.setZoom(9);
        }
        console.log(
          'Location geocoded on reload with description:',
          description,
        );
      } else {
        console.error(
          'Geocode was not successful for the following reason: ' + status,
        );
      }
    });
    const autocompleteEl = document.getElementById('autocomplete');
    autocompleteEl.value = description;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  handleAutocomplete();
  syncMapToURL();
});
