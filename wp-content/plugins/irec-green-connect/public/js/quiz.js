document.getElementById('quizButton').addEventListener('click', function () {
  showSlide(1); // Start the quiz on the first slide
});

document
  .getElementById('closeButton')
  .addEventListener('click', closeQuizModal);

document.getElementById('overlay').addEventListener('click', function (event) {
  if (event.target.id === 'overlay') {
    closeQuizModal();
  }
});

const radioInputs = document.querySelectorAll('.image-radio');

radioInputs.forEach(function (radio) {
  radio.addEventListener('change', function () {
    // Find the current slide
    const currentSlide = radio.closest('.quiz-slide');

    // update selected styles for label
    const thisSlidesLabels = currentSlide.querySelectorAll('label');
    thisSlidesLabels.forEach((input) => input.classList.remove('selected'));
    radio.closest('label').classList.add('selected');

    // Find the next slide
    const nextSlideNumber =
      parseInt(currentSlide.getAttribute('data-slide')) + 1;
    const nextSlide = document.querySelector(
      `.quiz-slide[data-slide="${nextSlideNumber}"]`,
    );

    // If there's a next slide, show it after a delay (matching the CSS transition time)
    if (nextSlide) {
      setTimeout(() => {
        currentSlide.classList.remove('active');

        nextSlide.classList.add('active');
      }, 300);
    }
  });
});

document.querySelectorAll('.prev-btn').forEach(function (button) {
  button.addEventListener('click', function () {
    let currentSlide = parseInt(
      button.closest('.quiz-slide').getAttribute('data-slide'),
    );
    showSlide(currentSlide - 1);
  });
});

function showSlide(slideNumber) {
  // Hide all slides
  document
    .querySelectorAll('.quiz-slide')
    .forEach((slide) => slide.classList.remove('active'));

  // Show the desired slide
  document
    .querySelector(`.quiz-slide[data-slide="${slideNumber}"]`)
    .classList.add('active');

  let overlay = document.getElementById('overlay');
  let modal = document.getElementById('modal');

  overlay.classList.remove('hidden');
  modal.classList.remove('hidden');

  setTimeout(function () {
    overlay.style.opacity = '1';
    modal.style.opacity = '1';
    modal.style.transform = 'scale(1)';
  }, 50);
}

function closeQuizModal() {
  // Logic remains the same as before
}
