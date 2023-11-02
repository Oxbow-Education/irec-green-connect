document.addEventListener('DOMContentLoaded', function () {
  const swiper1 = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 10,

    navigation: {
      nextEl: '.swiper-next',
      prevEl: '.swiper-prev',
    },
  });

  const swiper2 = new Swiper('.swiper-container-2', {
    centeredSlides: false,
    slidesPerView: 1,
    spaceBetween: 10,
    preventClicks: false,
    breakpoints: {
      993: {
        slidesPerView: 4.25,
        centeredSlides: true,
      },
    },
  });

  swiper1.on('slideChange', function () {
    const activeIndex = swiper1.activeIndex;
    swiper2.slideTo(activeIndex);

    const quotes = document.querySelectorAll(
      '.quote-wrapper .carousel-details',
    );
    quotes.forEach((quote, index) => {
      if (index == activeIndex) {
        quote.classList.add('active');
      } else {
        quote.classList.remove('active');
      }
    });

    const slides = document.querySelectorAll(
      '.swiper-container-2 .swiper-slide',
    );
    slides.forEach((slide, index) => {
      if (index < activeIndex) {
        slide.classList.add('hidden');
      } else {
        slide.classList.remove('hidden');
      }
    });
  });
});
