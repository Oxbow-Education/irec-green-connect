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
      showMore: true,
      templates: {
        item: (item) => {
          if (item.is_internal_resource) {
            return `<p>inernal resource</p>`;
          }
          return `<p>external resource</p>`;
        },
        empty: `<p>No resources found. .</p>`,
      },
      showMoreLabel: 'Load More',
    }),
  ]);

  resourcesSearch.start();
}
