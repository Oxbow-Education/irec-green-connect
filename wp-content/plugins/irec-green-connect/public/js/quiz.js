const toggleHTMLScrollLock = () => {
  const html = document.querySelector('html');
  html.classList.toggle('no-scroll');
};

document.getElementById('quizButton').addEventListener('click', function () {
  showSlide(1); // Start the quiz on the first slide
  toggleHTMLScrollLock();
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
  radio.addEventListener('click', function () {
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
  if (
    slideNumber == 'MATCH' ||
    slideNumber == 'MAYBE' ||
    slideNumber == 'NOT'
  ) {
    document.getElementById('modal').classList.add('results');
    const resultsImage = document.querySelector('#modal .results-image');

    resultsImage.setAttribute(
      'src',
      `/wp-content/plugins/irec-green-connect/public/img/${slideNumber}.svg`,
    );
  }
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
  let overlay = document.getElementById('overlay');
  let modal = document.getElementById('modal');
  overlay.style.opacity = '0';
  modal.style.opacity = '0';
  modal.style.transform = 'scale(0)';

  const form = document.getElementById('quizForm');
  form.reset();
  const radioLabels = document.querySelectorAll('#modal .selected');
  radioLabels.forEach((input) => input.classList.remove('selected'));
  toggleHTMLScrollLock();

  setTimeout(function () {
    overlay.classList.add('hidden');
    modal.classList.add('hidden');
    modal.classList.remove('results');
  }, 50);
}

const quizForm = document.getElementById('quizForm');

const getMatchScore = (score) => {
  if (score >= 14) {
    return 'MATCH';
  }
  if (score >= 7) {
    return 'MAYBE';
  }
  return 'NOT';
};

quizForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  let score = 0;
  formData.forEach((value) => {
    score += Number(value);
  });

  const matchScore = getMatchScore(score);
  showSlide(matchScore);
});

const inlineBlocks = document.querySelectorAll('.inline-block');
inlineBlocks.forEach((button) => {
  button.addEventListener('click', (e) => {
    const slide = button.closest('.quiz-slide');
    slide.scrollTop = slide.scrollHeight;
  });
});

const connectNowLinks = document
  .querySelectorAll('.connect-now a')
  .forEach((link) => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      const src = link.getAttribute('href');
      window.open(src, '_blank');
    });
  });
