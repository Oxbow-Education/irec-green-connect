<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/shoelace-autoloader.js"></script>

<div class="testimonials-block">
  <!-- Desktop Tab Group -->
  <div class="desktop-tabs">
    <sl-tab-group style="--indicator-color: transparent">
      <!-- Iterative PHP code for each tab -->
      <?php for ($i = 1; $i <= 3; $i++) : ?>
        <sl-tab slot="nav" panel="<?php echo esc_html(get_field('tab_heading_title_' . $i, $post_id)); ?>">
          <img class="testimonials-block__image" src="<?php echo esc_url(get_field('tab_heading_image_' . $i, $post_id)); ?>" />
          <h3 class="testimonials-block__tab-heading"><?php echo esc_html(get_field('tab_heading_title_' . $i, $post_id)); ?></h3>
        </sl-tab>
      <?php endfor; ?>
      <!-- Tab Panels -->
      <?php for ($i = 1; $i <= 3; $i++) : ?>
        <sl-tab-panel name="<?php echo esc_html(get_field('tab_heading_title_' . $i, $post_id)); ?>">
          <h4 class="tab-content-heading"><?php echo esc_html(get_field('tab_content_heading_' . $i, $post_id)); ?></h4>
          <div class="tab-content">
            <p class="tab-content-content"><?php echo esc_html(get_field('tab_content_content_' . $i, $post_id)); ?></p>
            <a href="<?php echo esc_url(get_field('link_url_' . $i, $post_id)); ?>"><?php echo esc_html(get_field('link_text_' . $i, $post_id)); ?></a>
          </div>
        </sl-tab-panel>
      <?php endfor; ?>
    </sl-tab-group>
  </div>

  <!-- Mobile Accordion Group -->
  <div class="mobile-accordion">
    <?php for ($i = 1; $i <= 3; $i++) : ?>
      <div class="testimonial-section">
        <div class="testimonials__header">
          <img class="testimonials-block__image" src="<?php echo esc_url(get_field('tab_heading_image_' . $i, $post_id)); ?>" />
          <h3 class="testimonials-block__tab-heading"><?php echo esc_html(get_field('tab_heading_title_' . $i, $post_id)); ?></h3>
        </div>
        <div class="testimonial-content">
          <h4 class="tab-content-heading"><?php echo esc_html(get_field('tab_content_heading_' . $i, $post_id)); ?></h4>
          <p class="tab-content-content"><?php echo esc_html(get_field('tab_content_content_' . $i, $post_id)); ?></p>
          <a href="<?php echo esc_url(get_field('link_url_' . $i, $post_id)); ?>" class="tab-content-link"><?php echo esc_html(get_field('link_text_' . $i, $post_id)); ?></a>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</div>