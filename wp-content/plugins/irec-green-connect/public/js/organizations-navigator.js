function initMap() {}
let geocoder;

document.addEventListener('DOMContentLoaded', () => {
  geocoder = new google.maps.Geocoder();

  const addItemMarker = (item) => {};

  const search = instantsearch({
    indexName: 'organization',
    searchClient: algoliasearch(
      'QVXOOP4L7N',
      'b589196885c2c6d140833e9cb83c4fa0',
    ),
  });

  search.addWidgets([
    instantsearch.widgets.configure({
      hitsPerPage: 12,
    }),
    instantsearch.widgets.infiniteHits({
      container: '#hits',
      showPrevious: false,
      templates: {
        item: (item) => {
          addItemMarker(item);
          return `<div class="organization-card">
          <h6>${item.organization}</h6>
          <div class="organization-image">
            <img src="/wp-content/uploads/2023/09/NREL-Quality-Control-Inspector-72292-scaled.jpg" />
            <div class="organization-tags">
              ${item.tags.map((tag) => `<span>${tag}</span>`)}
            </div>
          </div>
            <p class="organization-sentence">${item.sentence}</p>
            <div class="organization-info">
              <div>
                <p>${item.phone}</p>
                <p>${item.email}</p>
                <p>${item.city}, ${item.state}</p>
              </div>
              <a class="organization-link" href="${
                item.link
              }" target="_blank">Get Started 
              <img src="/wp-content/plugins/irec-green-connect/public/img/arow-right.svg" />
              </a>
            </div>
          </div>

        </div>`;
        },
      },
    }),
  ]);

  const form = document.getElementById('custom-searchbox');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formValues = new FormData(form);
    const zipcode = formValues.get('zipcode');
    try {
      const results = await geocoder.geocode({
        address: zipcode,
      });
      if (!results || !results?.results?.length) {
        throw new Error('No results found for that zipcode.');
      }
      const lat = results.results[0].geometry.location.lat();
      const lng = results.results[0].geometry.location.lng();
      const aroundLatLng = `${lat}, ${lng}`;
      search.helper.setQueryParameter('aroundRadius', 500000);
      search.helper.setQueryParameter('aroundLatLng', aroundLatLng);
      search.helper.search();
    } catch (err) {
      console.log(err);
    }
  });

  search.start();
});
