let search;

document.addEventListener('DOMContentLoaded', () => {
  // Init search
  search = instantsearch({
    indexName: 'full_site_search',
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
  });

  // Add and define widgets
  search.addWidgets([
    instantsearch.widgets.configure({
      hitsPerPage: 5,
    }),
    instantsearch.widgets.hits({
      container: '#fullSearchHits',
      templates: {
        item: (item) => `<div class="search-result">
        <a href="${item.link}">${item.title}</a>
        </div>`,
        empty: '',
      },
      transformItems: function (items) {
        // Only display hits when there is a query
        if (search.helper.state.query === '') {
          return [];
        }
        return items;
      },
    }),
    {
      // Custom widget to display "no results" message
      render({ results }) {
        const noResultsMessage = document.getElementById('no-results-message');
        if (results.query && results.nbHits === 0) {
          noResultsMessage.style.display = 'block';
        } else {
          noResultsMessage.style.display = 'none';
        }
      },
    },
  ]);

  // Handle form submission
  const form = document.getElementById('full-site-searchbox');
  const input = document.getElementById('fullSiteSearch');
  input.addEventListener('input', async (e) => {
    e.preventDefault();
    const keyword = e.target.value;
    try {
      search.helper.setQuery(keyword);
      search.helper.search();
    } catch (err) {
      console.log(err);
    }
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const searchResults = document.querySelectorAll('.search-result');
    if (searchResults?.length) {
      searchResults[0].querySelector('a')?.click();
    }
  });

  const searchToggle = document.getElementById('searchToggle');
  searchToggle.addEventListener('click', () => {
    form.classList.toggle('hidden');
    form[0].focus();
  });
  search.start();
});
