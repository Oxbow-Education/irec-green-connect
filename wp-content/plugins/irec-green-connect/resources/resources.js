document.addEventListener('DOMContentLoaded', () => {
  handleTagsButtons('.filters__user-type__buttons button', 'userType');
  handleTagsButtons('.filters__resource-type__buttons button', 'resourceType');
  syncTagsWithURL('.filters__user-type__buttons button', 'userType');
  syncTagsWithURL('.filters__resource-type__buttons button', 'resourceType');
});

function handleTagsButtons(selector, queryParam) {
  const buttons = document.querySelectorAll(selector);
  buttons.forEach((button) =>
    button.addEventListener('click', () => {
      button.classList.toggle('filter-button--selected');
      const isSelected = button.classList.contains('filter-button--selected');
      const value = button.dataset.value;
      if (isSelected) {
        updateQueryParam(queryParam, value, false, false);
      } else {
        updateQueryParam(queryParam, value, true, false);
      }
    }),
  );
}

function syncTagsWithURL(selector, queryParam) {
  const url = new URL(window.location);
  const searchParams = new URLSearchParams(url.search);
  const values = searchParams.get(queryParam).split(',') || [];
  const buttons = document.querySelectorAll(selector);
  buttons.forEach((button) => {
    if (values.includes(button.dataset.value)) {
      button.classList.add('filter-button--selected');
    } else {
      button.classList.remove('filter-button--selected');
    }
  });
}
