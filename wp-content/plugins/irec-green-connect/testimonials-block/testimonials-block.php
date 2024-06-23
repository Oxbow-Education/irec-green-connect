<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/shoelace-autoloader.js"></script>

<sl-tab-group>
  <sl-tab slot="nav" panel="<?php echo esc_html(get_field('tab_heading_title_1', $post_id)); ?>">
    <img src="<?php echo esc_url(get_field('tab_heading_image_1', $post_id)); ?>" />
    <h3><?php echo esc_html(get_field('tab_heading_title_1', $post_id)); ?></h3>
  </sl-tab>

  <sl-tab slot="nav" panel="<?php echo esc_html(get_field('tab_heading_title_2', $post_id)); ?>">
    <img src="<?php echo esc_url(get_field('tab_heading_image_2', $post_id)); ?>" />
    <h3><?php echo esc_html(get_field('tab_heading_title_2', $post_id)); ?></h3>
  </sl-tab>
  <sl-tab slot="nav" panel="<?php echo esc_html(get_field('tab_heading_title_3', $post_id)); ?>">
    <img src="<?php echo esc_url(get_field('tab_heading_image_3', $post_id)); ?>" />
    <h3><?php echo esc_html(get_field('tab_heading_title_3', $post_id)); ?></h3>
  </sl-tab>
  <sl-tab-panel name="<?php echo esc_html(get_field('tab_heading_title_1', $post_id)); ?>">
    <h4><?php echo esc_html(get_field('tab_content_heading_1', $post_id)); ?></h4>
    <p><?php echo esc_html(get_field('tab_content_content_1', $post_id)); ?></p>
    <a href="<?php echo esc_html(get_field('link_url_1', $post_id)); ?>"><?php echo esc_html(get_field('link_text_1', $post_id)); ?></a>
  </sl-tab-panel>
  <sl-tab-panel name="<?php echo esc_html(get_field('tab_heading_title_2', $post_id)); ?>">
    <h4><?php echo esc_html(get_field('tab_content_heading_2', $post_id)); ?></h4>
    <p><?php echo esc_html(get_field('tab_content_content_2', $post_id)); ?></p>
    <a href="<?php echo esc_html(get_field('link_url_2', $post_id)); ?>"><?php echo esc_html(get_field('link_text_2', $post_id)); ?></a>
  </sl-tab-panel>
  <sl-tab-panel name="<?php echo esc_html(get_field('tab_heading_title_3', $post_id)); ?>">
    <h4><?php echo esc_html(get_field('tab_content_heading_3', $post_id)); ?></h4>
    <p><?php echo esc_html(get_field('tab_content_content_3', $post_id)); ?></p>
    <a href="<?php echo esc_html(get_field('link_url_3', $post_id)); ?>"><?php echo esc_html(get_field('link_text_3', $post_id)); ?></a>
  </sl-tab-panel>
</sl-tab-group>