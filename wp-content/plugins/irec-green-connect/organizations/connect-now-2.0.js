// Global variables
let geocoder;
let map;
let markers = [];
let infoWindows = [];
let bounds;
let orgsSearch;
let remoteOrgsSearch;
let initialSetup = true;
let boundsChangeTimeout;
let currentFacetFilters = [];
let isListView = true;

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
  handleMapViewLisViewToggle();
  handleDrawerFunctionality();
  handleOpportunityCheckboxesFunctionality();
  handleTagsButtonSelection();
  handleSearchInput();
  handleResetFilters();
});

window.addEventListener(ALGOLIA_INITIALIZED, () => {
  syncOpportunityCheckboxesWithURL();
  syncTagsButtonsWithURL();
  syncSearchInputWithURL();
});
window.addEventListener(URL_UPDATED, () => {
  syncOpportunityCheckboxesWithURL();
  syncTagsButtonsWithURL();
  syncSearchInputWithURL();
});

// Function definitions
function handleMapViewLisViewToggle() {
  const mapViewButton = document.getElementById('mapView');
  mapViewButton.addEventListener('click', () => {
    const results = document.querySelector('.results');
    results.classList.add('hide');
    mapViewButton.classList.add('hide');
    listViewButton.classList.remove('hide');
    isListView = true;
  });

  const listViewButton = document.getElementById('listView');
  listViewButton.addEventListener('click', () => {
    const results = document.querySelector('.results');
    results.classList.remove('hide');
    mapViewButton.classList.remove('hide');
    listViewButton.classList.add('hide');
    isListView = false;
  });
}
function handleDrawerFunctionality() {
  const drawer = document.getElementById('drawer');
  const openButton = document.getElementById('drawerButton');

  const closeButtons = [drawer.querySelector('.footer__see-results')];

  openButton.addEventListener('click', () => drawer.show());
  closeButtons.forEach((button) =>
    button.addEventListener('click', () => drawer.hide()),
  );
}
function handleOpportunityCheckboxesFunctionality() {
  const opportunityCheckboxes = document.querySelectorAll(
    '.checkbox[name="opportunities"]',
  );

  opportunityCheckboxes.forEach((cb) => {
    cb.addEventListener('sl-change', (e) => {
      clearMarkers();
      const isChecked = e.target.checked;
      const value = e.target.value;
      const name = e.target.name;
      updateQueryParam(name, value, !isChecked);
    });
  });
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
    } else {
      cb.checked = false;
    }
  });
}
function syncTagsButtonsWithURL() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const tags = searchParams.get('tags')?.split(',') || [];
  const tagsButtons = document.querySelectorAll('.tags__button');
  tagsButtons.forEach((tag) => {
    if (tags.includes(tag.innerText)) {
      tag.classList.add('tags__button--selected');
    } else {
      tag.classList.remove('tags__button--selected');
    }
  });
}

function syncSearchInputWithURL() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const query = searchParams.get('query');
  const searchInputs = document.querySelectorAll(
    'form.search input[type="text"]',
  );
  if (query) {
    searchInputs.forEach((input) => {
      input.value = query;
    });
  } else {
    searchInputs.forEach((input) => {
      input.value = '';
    });
  }
}

function handleTagsButtonSelection() {
  const tagsButtons = document.querySelectorAll('.tags__button');
  tagsButtons.forEach((button) => {
    button.addEventListener('click', () => {
      clearMarkers();
      button.classList.toggle('tags__button--selected');
      const shouldRemove = !button.classList.contains('tags__button--selected');
      updateQueryParam('tags', button.innerText, shouldRemove);
    });
  });
}

function handleSearchInput() {
  const searchForms = document.querySelectorAll('form.search');
  const searchInputs = document.querySelectorAll(
    'form.search input[type="text"]',
  );
  searchForms.forEach((form) => {
    const input = form.querySelector('input[type="text"]');
    input.addEventListener('input', () => {
      searchInputs.forEach((otherInput) => {
        if (otherInput.value === input.value) return;
        otherInput.value = input.value;
      });
      clearMarkers();
      updateQueryParam('query', input.value, !Boolean(input.value), true);
    });
    form.addEventListener('submit', (event) => {
      event.preventDefault();
    });
  });
}

function isMobileScreen() {
  return window.innerWidth <= 768;
}

function removeFiltersAndSearch() {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  searchParams.delete('opportunities');
  searchParams.delete('tags');
  searchParams.delete('query');

  // Update the URL with the modified search parameters
  url.search = searchParams.toString();

  window.history.replaceState({ path: url.toString() }, '', url.toString());
  console.log(url.toString());

  // Ensure this is called after the URL update
  sendEvent(URL_UPDATED);
}

function handleResetFilters() {
  const footerReset = document.querySelector('.footer__reset');
  footerReset.addEventListener('click', () => {
    removeFiltersAndSearch();
  });
}
