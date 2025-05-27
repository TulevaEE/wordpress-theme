<section id="<?php the_sub_field('component_id'); ?>" class="py-5 cta-change<?php echo get_sub_field('background_color') === 'blue' ? ' bg-blue-washed' : ''; ?>">
    <div class="container">
      <div class="row">
        <p class="col-12 d-flex flex-column flex-md-row justify-content-center align-items-md-baseline m-0 text-center">
          <span class="mb-4 mb-md-0 me-md-4 fs-5">
              <?php
              $isMarch = (date('m') == 3);
              $isJuly = (date('m') == 7);
              $isNovember = (date('m') == 11);
              $dayOfMonth = (int)date('j');

              if ($isMarch && $dayOfMonth > 21) {
                  _e('The deadline for this fund exchange period is <b>March 31.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
              } elseif ($isJuly && $dayOfMonth > 21) {
                  _e('The deadline for this fund exchange period is <b>July 31.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
              } elseif ($isNovember && $dayOfMonth > 21) {
                  _e('The deadline for this fund exchange period is <b>November 30.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
              } else {
                  echo get_sub_field('text');
              }
              ?>
          </span>
          <a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-primary btn-lg"><?php the_sub_field('button_text'); ?></a>
        </p>
      </div>
    </div>
</section>
