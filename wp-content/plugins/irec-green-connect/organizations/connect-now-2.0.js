// Global variables
let geocoder;
let map;
let markers = [];
let infoWindows = [];
let bounds;
let orgsSearch;
let initialSetup = true;
let boundsChangeTimeout;
let currentFacetFilters = [];
const ALGOLIA_INITIALIZED = 'algolia-initialized';
const URL_UPDATED = 'url-updated';

// Add button listeners for mobile filters
document.addEventListener('DOMContentLoaded', () => {
  const mapViewButton = document.getElementById('mapView');
  mapViewButton.addEventListener('click', () => {
    const results = document.querySelector('.results');
    results.classList.add('hide');
    mapViewButton.classList.add('hide');
    listViewButton.classList.remove('hide');
  });

  const listViewButton = document.getElementById('listView');
  listViewButton.addEventListener('click', () => {
    const results = document.querySelector('.results');
    results.classList.remove('hide');
    mapViewButton.classList.remove('hide');
    listViewButton.classList.add('hide');
  });
});

// hook up drawer functionality
document.addEventListener('DOMContentLoaded', () => {
  const drawer = document.getElementById('drawer');
  const openButton = document.getElementById('drawerButton');

  // const closeButton = drawer.querySelector('sl-button[variant="primary"]');

  openButton.addEventListener('click', () => drawer.show());
  // closeButton.addEventListener('click', () => drawer.hide());
});

document.addEventListener('DOMContentLoaded', () => {
  const opportunityCheckboxes = document.querySelectorAll(
    '.checkbox[name="opportunities"]',
  );

  opportunityCheckboxes.forEach((cb) => {
    cb.addEventListener('sl-change', (e) => {
      const isChecked = e.target.checked;
      const value = e.target.value;
      const name = e.target.name;
      updateQueryParam(name, value, !isChecked);
    });
  });
});

function sendEvent(eventName) {
  const event = new Event(eventName);
  window.dispatchEvent(event);
}

function updateQueryParam(key, value, removeValue = false) {
  const url = new URL(window.location);

  // Retrieve the current value for the parameter, or null if it doesn't exist
  const currentValue = url.searchParams.get(key);

  if (currentValue) {
    // Split the current value into an array by commas
    let values = currentValue.split(',');

    if (removeValue) {
      // Remove the specified value from the array
      values = values.filter((v) => v !== value);
    } else {
      // Add the value if it's not already in the array
      if (!values.includes(value)) {
        values.push(value);
      }
    }

    // Update the parameter with the new array, joined by commas, or delete if empty
    if (values.length > 0) {
      url.searchParams.set(key, values.join(','));
    } else {
      url.searchParams.delete(key);
    }
  } else if (!removeValue) {
    // If the parameter does not exist and we are not removing, set the new value
    url.searchParams.set(key, value);
  }

  // Update the URL in the browser without reloading the page
  window.history.replaceState({ path: url.toString() }, '', url.toString());
  sendEvent(URL_UPDATED);
}

function syncOpportunityCheckboxesWithURL() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const opportunities = searchParams.get('opportunities')?.split(',') || [];
  const opportunityCheckboxes = document.querySelectorAll(
    '.checkbox[name="opportunities"]',
  );
  opportunityCheckboxes.forEach((cb) => {
    if (opportunities.includes(cb.innerText)) {
      cb.checked = true;
    }
  });
}

window.addEventListener(ALGOLIA_INITIALIZED, syncOpportunityCheckboxesWithURL);
