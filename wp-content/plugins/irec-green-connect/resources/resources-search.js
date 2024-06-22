let resourcesSearch;

document.addEventListener('DOMContentLoaded', () => {
  initializeAlgolia();
});

function initializeAlgolia() {
  resourcesSearch = instantsearch({
    indexName: 'resources_newest',
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
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
   <div>${tagsHtml}</div>
   <p class="resource-description clamp-2">${item.short_description}</p>
 </div>
</div>
<button class="read-more-button" data-tag="${item.id}">
 <a class="read-more-button" href="${item.permalink}">
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

  const html = `
      <div onclick="sendExternalEvent('${item.title.replace(/'/g, "\\'")}')"
          class="external-resource-tile resource-tile"
          data-tag="${item.id}">
        <div class="resource-tile-text">
          <h5 class="resource-title clamp-2">${item.title}</h5>
          <div>
            ${tagsHtml}
          </div>
        </div>
        <button class="external-resource-button" data-tag="${item.id}">
          <span class="dashicons dashicons-plus-alt2"></span>
        </button>
      </div>
  `;

  return html;
}
