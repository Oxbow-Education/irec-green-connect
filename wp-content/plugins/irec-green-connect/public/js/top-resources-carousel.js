document.addEventListener('DOMContentLoaded', function () {
  const topResourcesSwiper = new Swiper('.swiper-container', {
    slidesPerView: 3,
    spaceBetween: 30,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    // these don't seem to work :(
    // breakpoints: {
    //   1000: {
    //     slidesPerView: 2,
    //   },
    //   600: {
    //     slidesPerView: 1,
    //   },
    // },
  });
});
