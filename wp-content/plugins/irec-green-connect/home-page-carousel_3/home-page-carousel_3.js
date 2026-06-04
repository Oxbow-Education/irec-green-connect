// document.addEventListener('DOMContentLoaded', function () {
//   const carouselButtons = document.querySelectorAll(
//     '.carousel__buttons button',
//   );
//   carouselButtons.forEach((button) => {
//     button.addEventListener('click', function () {
//       console.log(this.dataset.target);
//       const targetId = `${this.dataset.target}Links`;
//       const imageTargetId = `${this.dataset.target}Image`;
//       carouselButtons.forEach((btn) => btn.classList.remove('selected'));
//       button.classList.add('selected');
//       const targetButtons = document.querySelector(targetId);
//       const targetImage = document.querySelector(imageTargetId);
//       const bannerButtons = document.querySelectorAll('.banner-buttons');
//       const images = document.querySelectorAll('.banner-image div');
//       images.forEach((img) => img.classList.remove('open'));
//       bannerButtons.forEach((btn) => btn.classList.remove('open'));
//       targetButtons.classList.add('open');
//       targetImage.classList.add('open');

//     });
//   });
// });

document.addEventListener('DOMContentLoaded', function () {
  const carouselButtons = document.querySelectorAll(
    '.carousel__buttons button',
  );
  const positions = ['open', 'top', 'bottom'];
  const images = document.querySelectorAll('.banner-image div');

  // Initialize positions map
  const positionsMap = {
    '#forJobSeekersImage': 0, // open
    '#forEmployersImage': 1, // top
    '#forWFOrgsImage': 2, // bottom
  };

  function rotatePositions(clickedPosition) {
    // Remove all position classes first
    images.forEach((img) => {
      positions.forEach((pos) => img.classList.remove(pos));
    });

    // Calculate new positions based on clicked button
    images.forEach((img) => {
      const currentPos = positionsMap[`#${img.id}`];
      let newPos;

      if (img.id === clickedPosition.replace('Links', 'Image')) {
        // This is the target image - should be 'open'
        newPos = 0;
      } else {
        // Calculate the new position maintaining relative order
        const targetOriginalPos =
          positionsMap[clickedPosition.replace('Links', 'Image')];
        newPos = (currentPos - targetOriginalPos + 3) % 3;
      }

      // Add new position class
      img.classList.add(positions[newPos]);
    });
  }

  carouselButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const targetId = `${this.dataset.target}Links`;

      // Update button states
      carouselButtons.forEach((btn) => btn.classList.remove('selected'));
      button.classList.add('selected');

      // Update banner buttons visibility
      const bannerButtons = document.querySelectorAll('.banner-buttons');
      bannerButtons.forEach((btn) => btn.classList.remove('open'));
      document.querySelector(targetId).classList.add('open');

      // Rotate the images to their new positions
      rotatePositions(targetId);
    });
  });
});
