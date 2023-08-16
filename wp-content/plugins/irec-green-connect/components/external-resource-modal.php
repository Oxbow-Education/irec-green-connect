<div class="external-resource-modal-bg" data-tag="modal-<?php echo $post_id ?>-bg"></div>
<div class="external-resource-modal" data-tag="modal-<?php echo $post_id ?>">
  <button class="close-modal-btn" data-tag="<?php echo $post_id ?>"><img src="http://irec-green-connect.local/wp-content/uploads/2023/08/circle-xmark-light.png"></button>
  <div class="modal-content">
    <h5><?php the_title(); ?></h5>
    <p><?php the_content(); ?></p>
    <p><a target="_blank" href="<?php echo get_post_meta($post_id, 'url', true); ?>"><?php echo get_post_meta($post_id, 'url_text', true); ?></a></p>
  </div>
</div>