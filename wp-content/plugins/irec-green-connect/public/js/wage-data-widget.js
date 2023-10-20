document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.wage-data').forEach((wageCard) => {
    const wageTitle = wageCard.querySelector('.wage-data-title');
    wageTitle.addEventListener('click', function () {
      console.log('click');
      const description = wageCard.querySelector('.wage-data-description');
      if (description.classList.contains('hidden')) {
        description.classList.remove('hidden');
      } else {
        description.classList.add('hidden');
      }
    });
  });
});
