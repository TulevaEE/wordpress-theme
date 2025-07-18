<?php
$text = get_sub_field('text') ? get_sub_field('text') : __('Are we on the right track?', TEXT_DOMAIN);
$additional_text = get_sub_field('additional_text') ? get_sub_field('additional_text') : __('Join us!', TEXT_DOMAIN);
$button_url = get_sub_field('button_url') ? get_sub_field('button_url') : '#signup-block';
$button_text = get_sub_field('button_text') ? get_sub_field('button_text') : __('Become a member', TEXT_DOMAIN);
?>
<section id="<?php the_sub_field('component_id'); ?>" class="section-spacing-half bg-blue-2">
    <div class="container">
      <div class="row text-center">
        <div class="d-flex w-100 justify-content-center align-items-center">
          <h5 class="mb-0 me-3"><?php echo $text;  ?>
            <span class="d-none d-lg-inline-block"> <?php echo $additional_text; ?></span>
          </h5>
          <a href="<?php echo $button_url; ?>" class="btn btn-primary btn-lg"><?php echo $button_text; ?></a>
        </div>
      </div>
    </div>
</section>
