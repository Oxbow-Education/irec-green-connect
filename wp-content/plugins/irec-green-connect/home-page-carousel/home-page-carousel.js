document.addEventListener('DOMContentLoaded', () => {
  handleCarouselNavigation();
  handleLinkClicks();
});

function handleCarouselNavigation() {
  const navButtons = document.querySelectorAll('.navigation');
  navButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const isPrev = button.classList.contains('navigation--left');
      if (isPrev) {
        prev();
      } else {
        next();
      }
    });
  });
}

function prev() {
  rotateSlides('prev');
}
function next() {
  rotateSlides('next');
}

function rotateSlides(direction) {
  const slides = document.querySelectorAll('.home-page-carousel .slide');
  const currentState = Array.from(slides).map(
    (slide) => slide.className.split(' ')[1],
  ); // Get current states

  let newState = [];

  if (direction === 'next') {
    // Rotate state array to the right
    newState = [
      currentState[currentState.length - 1],
      ...currentState.slice(0, -1),
    ];
  } else {
    // Rotate state array to the left
    newState = [...currentState.slice(1), currentState[0]];
  }

  // Apply new classes to slides
  slides.forEach((slide, index) => {
    slide.className = `slide ${newState[index]}`;
  });
}

function handleLinkClicks() {
  const links = document.querySelectorAll('.link');
  links.forEach((link) =>
    link.addEventListener('click', () => {
      const url = link.dataset.link;
      window.open(url);
    }),
  );
}
