let resourcesSearch;
let resourcesSearchIndex;
const searchClient = algoliasearch(
  'QVXOOP4L7N',
  'b589196885c2c6d140833e9cb83c4fa0',
);

document.addEventListener('DOMContentLoaded', () => {
  initializeAlgolia();
});

window.addEventListener(ALGOLIA_INITIALIZED, () => {
  syncAlgoliaWithURL([
    {
      paramValue: 'userType',
      facet: 'user_type',
    },
    {
      paramValue: 'resourceType',
      facet: 'resource_type',
    },
  ]);
  syncExternalResourceWithURL();
  handleExternalResourceClick();
});

window.addEventListener(URL_UPDATED, () => {
  syncAlgoliaWithURL([
    {
      paramValue: 'userType',
      facet: 'user_type',
    },
    {
      paramValue: 'resourceType',
      facet: 'resource_type',
    },
  ]);
  syncExternalResourceWithURL();
});
function initializeAlgolia() {
  resourcesSearch = instantsearch({
    indexName: 'resources_newest',
    searchClient,
  });
  resourcesSearch.addWidgets([
    instantsearch.widgets.configure({ hitsPerPage: 20 }),
    instantsearch.widgets.infiniteHits({
      container: '#hits',
      showPrevious: false,
      class: 'resources-wrapper',
      showMore: true,
      templates: {
        item: (item) => {
          if (item.is_internal_resource) {
            return generateInternalResourceHTML(item);
          }
          return generateExternalResourceHTML(item);
        },
        empty: `<p>No resources found. .</p>`,
      },
      showMoreLabel: 'Load More',
    }),
  ]);

  resourcesSearch.start();
  resourcesSearchIndex = searchClient.initIndex('resources_newest');

  sendEvent(ALGOLIA_INITIALIZED);
}

function generateInternalResourceHTML(item) {
  const tagsHtml = Array.isArray(item.resource_type)
    ? item.resource_type
        .map((tag) => `<div class="resource-tag">${tag}</div>`)
        .join('')
    : '';

  return `<div onclick="sendEvent('${item.title.replace(/'/g, "\\'")}')"
  class="internal-resource-tile resource-tile"
  data-tag="${item.permalink}">
<div>
 <img class="post-thumbnail" src="${item.thumbnail_url}" alt="Thumbnail for ${
    item.title
  }">
 <div class="resource-tile-text">
   <h5 class="resource-title clamp-2">${item.title}</h5>
   <div class="resource-tags-container">${tagsHtml}</div>
   <p class="resource-description clamp-2">${item.short_description}</p>
 </div>
</div>
<button class="read-more-button" data-tag="${item.objectID}">
 <a class="read-more-button" href="${item.link}">
   Read More
 </a>
</button>
</div>`;
}

function generateExternalResourceHTML(item) {
  // Generate HTML for tags
  const tagsHtml = Array.isArray(item.resource_type)
    ? item.resource_type
        .map((tag) => `<div class="resource-tag">${tag}</div>`)
        .join('')
    : '';
  console.log({ item });
  const html = `
      <div
          class="external-resource-tile resource-tile"
          data-tag="${item.objectID}">
        <div class="resource-tile-text">
          <h5 class="resource-title clamp-2">${item.title}</h5>
          <div class="resource-tags-container">
            ${tagsHtml}
          </div>
        </div>
        <button class="external-resource-button" data-tag="${item.objectID}">
          <span class="dashicons dashicons-plus-alt2"></span>
        </button>
      </div>
  `;

  return html;
}

function syncAlgoliaWithURL(properties) {
  const url = new URL(window.location);
  const resource = url.searchParams.get('resource');
  if (resource) return;
  let facetFilters = [];
  properties.forEach((property) => {
    const { facet, paramValue } = property;
    const values = url.searchParams.get(paramValue)?.split(',') || [];
    const filters = values.map((val) => `${facet}:${val}`);
    facetFilters.push(...filters);
  });

  resourcesSearch.helper
    .setQueryParameter('facetFilters', facetFilters)
    .search();
}

async function syncExternalResourceWithURL() {
  const url = new URL(window.location);
  const resourceId = url.searchParams.get('resource');
  if (resourceId) {
    const resource = await resourcesSearchIndex.getObject(resourceId);
    const resourceModalHTML = generateModalHTML(resource);
    const externalResourceModal = document.getElementById(
      'externalResourceModal',
    );
    externalResourceModal.innerHTML = resourceModalHTML;
    externalResourceModal.show();
  }
}

function handleExternalResourceClick() {
  window.addEventListener('click', (event) => {
    const target = event.target;
    const externalResourceButton = target.closest('.external-resource-button');

    if (Boolean(externalResourceButton)) {
      updateQueryParam(
        'resource',
        externalResourceButton.dataset.tag,
        false,
        true,
      );
    }
  });
}

function generateModalHTML(resource) {
  // Generate tags HTML
  let tagsHTML = '';
  if (Array.isArray(resource.tags)) {
    tagsHTML = resource.tags
      .map((tag) => `<div class="resource-tag">${tag}</div>`)
      .join('');
  }

  // Create the HTML string
  const html = `

<div class="external-resource-modal active" data-tag="${resource.objectId}">
<button onclick="closeModal()" class="external-resource-modal-close"> <svg class="close-modal-svg" xmlns="http://www.w3.org/2000/svg" width="42.762" height="42.762" viewBox="0 0 42.762 42.762">
<path class="a" d="M15.092,15.092a1.326,1.326,0,0,1,1.888,0l4.4,4.4,4.4-4.4a1.335,1.335,0,1,1,1.888,1.888l-4.4,4.4,4.4,4.4a1.335,1.335,0,1,1-1.888,1.888l-4.4-4.4-4.4,4.4a1.335,1.335,0,1,1-1.888-1.888l4.4-4.4-4.4-4.4A1.326,1.326,0,0,1,15.092,15.092Zm27.67,6.289A21.381,21.381,0,1,1,21.381,0,21.379,21.379,0,0,1,42.762,21.381ZM21.381,2.673A18.708,18.708,0,1,0,40.089,21.381,18.71,18.71,0,0,0,21.381,2.673Z" />
</svg></button>
  
<div class="modal-content">
    <h5 class="modal-title">${resource.title}</h5>
    <div>${tagsHTML}</div>
    <p>${resource.long_description}</p>
    <p><a target="_blank" href="${resource.url}">${resource.url_text}</a></p>
  </div>
</div>`;

  return html;
}

function closeModal() {
  updateQueryParam('resource', '', true, true);
  const externalResourceModal = document.getElementById(
    'externalResourceModal',
  );
  externalResourceModal.hide();
}
