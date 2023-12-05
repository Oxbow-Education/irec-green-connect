document.addEventListener('DOMContentLoaded', function () {
  const topResourcesSwiper = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      992: {
        slidesPerView: 3,
      },
      657: {
        slidesPerView: 2,
      },
    },
  });
});
