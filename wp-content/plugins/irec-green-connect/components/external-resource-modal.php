<div 
  class="external-resource-modal-bg <?php
    // if there is a resource query param
    if (intval($_GET['resource'])) {
      $resource_id = intval($_GET['resource']);
    }
    // if this is the selected post, show modal
    if ($resource_id === $post_id) echo 'active';
  ?>"
  data-tag="<?php echo $post_id ?>">
</div>
<div
  class="external-resource-modal <?php
    if ($resource_id === $post_id) echo 'active';
  ?>"
  data-tag="<?php echo $post_id ?>"
>
  <button class="close-modal-btn" data-tag="<?php echo $post_id ?>">
    <svg class="close-modal-svg" xmlns="http://www.w3.org/2000/svg" width="42.762" height="42.762" viewBox="0 0 42.762 42.762">
      <path class="a" d="M15.092,15.092a1.326,1.326,0,0,1,1.888,0l4.4,4.4,4.4-4.4a1.335,1.335,0,1,1,1.888,1.888l-4.4,4.4,4.4,4.4a1.335,1.335,0,1,1-1.888,1.888l-4.4-4.4-4.4,4.4a1.335,1.335,0,1,1-1.888-1.888l4.4-4.4-4.4-4.4A1.326,1.326,0,0,1,15.092,15.092Zm27.67,6.289A21.381,21.381,0,1,1,21.381,0,21.379,21.379,0,0,1,42.762,21.381ZM21.381,2.673A18.708,18.708,0,1,0,40.089,21.381,18.71,18.71,0,0,0,21.381,2.673Z"/>
    </svg>
  </button>
  <div class="modal-content">
    <h5><?php the_title(); ?></h5>
    <p><?php echo get_post_meta($post_id, 'long_description', true); ?></p>
    <p><a target="_blank" href="<?php echo get_post_meta($post_id, 'url', true); ?>"><?php echo get_post_meta($post_id, 'url_text', true); ?></a></p>
  </div>
</div>