<?php


$args = array(
  'post_type' => 'home-page-bios', // Replace with your custom post type slug
  'posts_per_page' => -1, // Display all posts of the specified type
);

$query = new WP_Query($args);

if ($query->have_posts()) :
?>

  <div class="swiper-container">
    <div class="swiper-wrapper">
      <?php while ($query->have_posts()) : $query->the_post(); ?>
        <div class="swiper-slide">
          <img class="carousel-image" src="<?php the_field('image') ?>" alt="">
        </div>
      <?php endwhile; ?>

    </div>
    <div class="navigation">
      <div class="swiper-prev">
        <img src="/wp-content/plugins/irec-green-connect/public/img/chevron.svg" alt="Previous">
      </div>
      <div class="swiper-next">
        <img src="/wp-content/plugins/irec-green-connect/public/img/chevron.svg" alt="Next">
      </div>
    </div>
  </div>
  <div class="bottom-carousel">

    <div class="quote-wrapper">
      <?php
      $index = 0;
      while ($query->have_posts()) : $query->the_post(); ?>
        <div class="carousel-details <?php if ($index == 0) { ?> active <?php } ?>">
          <div class="details-title-quote">
            <p class="details-title"><?php the_title() ?></p>
            <p class="details-quote"><?php the_field('quote') ?></p>
          </div>
        </div>
      <?php
        $index++;
      endwhile; ?>



    </div>

    <div class="swiper-container-2">
      <div class="swiper-2-overlay"></div>
      <div class="swiper-wrapper">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <div class="swiper-slide">
            <div class="carousel-details">
              <div class="details-title-quote">
                <p class="details-title"><?php the_title() ?></p>
                <p class="details-quote">"<?php the_field('quote') ?>"</p>
              </div>
              <div class="details-name-position">
                <p class="details-name"><?php the_field('name') ?></p>
                <p class="details-position"><?php the_field('position') ?></p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>


    </div>
  </div>




<?php
endif;
wp_reset_postdata();

?>