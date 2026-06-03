document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    handleCarouselNavigation();
    handleLinkClicks();
    handleImageContentClicks();
  }, 100); // Small delay to ensure DOM is fully loaded
});

function handleCarouselNavigation() {
  const navButtons = document.querySelectorAll('.navigation');

  navButtons.forEach((button) => {
    // Remove any existing event listeners
    button.replaceWith(button.cloneNode(true));
  });

  // Reselect the buttons after cloning
  const refreshedButtons = document.querySelectorAll('.navigation');

  refreshedButtons.forEach((button) => {
    // Use touchend for mobile and click for desktop
    ['click', 'touchend'].forEach((eventType) => {
      button.addEventListener(eventType, (event) => {
        event.preventDefault();
        event.stopPropagation(); // Prevent the click from bubbling up

        // Add a small delay to prevent double firing with both click and touchend
        if (eventType === 'touchend') {
          button.clickProcessed = true;
          setTimeout(() => {
            button.clickProcessed = false;
          }, 300);
        } else if (button.clickProcessed) {
          return; // Skip if this was already processed by touchend
        }

        console.log('Navigation button clicked'); // Debug log

        const isPrev = button.classList.contains('navigation--left');
        if (isPrev) {
          next();
        } else {
          prev();
        }
      });
    });

    // Add a higher level capture event handler for all relevant events
    ['mousedown', 'touchstart'].forEach((eventType) => {
      button.addEventListener(
        eventType,
        (event) => {
          event.preventDefault();
          event.stopPropagation();
        },
        true,
      );
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
      window.location.href = url;
    }),
  );
}

function handleImageContentClicks() {
  const imageContents = document.querySelectorAll('.slide-image');

  imageContents.forEach((content) => {
    // Exclude the navigation buttons from triggering the slide change
    content.addEventListener('click', (event) => {
      if (!event.target.closest('.carousel__navigation')) {
        // Find the slide containing this content
        const parentSlide = content.closest('.slide');

        // If this isn't the active slide, make it active
        if (!parentSlide.classList.contains('active')) {
          // Find the index of this slide
          const slides = document.querySelectorAll(
            '.home-page-carousel .slide',
          );
          const slideIndex = Array.from(slides).indexOf(parentSlide);

          // Make this slide active
          makeSlideActive(slideIndex);
        }
      }
    });
  });
}

function makeSlideActive(targetIndex) {
  const slides = document.querySelectorAll('.home-page-carousel .slide');

  // No need to do anything if the target is already active
  if (slides[targetIndex].classList.contains('active')) {
    return;
  }

  // Find the currently active slide index
  let activeIndex = 0;
  slides.forEach((slide, i) => {
    if (slide.classList.contains('active')) {
      activeIndex = i;
    }
  });

  // Calculate how many rotations needed to make target active
  const rotationsNeeded =
    (slides.length + targetIndex - activeIndex) % slides.length;

  // Apply rotations
  for (let i = 0; i < rotationsNeeded; i++) {
    rotateSlides('next');
  }
}
