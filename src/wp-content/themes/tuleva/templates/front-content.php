<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!--
    <div class="container row-spacing-half">
        <div class="row">
            <h1 class="text-center landing-page-headline"><?php the_field('heading'); ?></h1>
        </div>
    </div>
    -->
    <?php if (isset($_GET['signup'])) { ?>
      <div class="proposal">
        <div class="hero">
          <div class="intro w-container">
            <div class="intro-text"><?php _e('3403 eesti inimest tulid kokku ja tegid&nbsp;omale ise pensionifondi. Veel pole hilja nendega liituda.'); ?></div>
            <p class="lead"><?php _e('Kui sulle Tuleva eesmärgid meeldivad, tahad toetada nende saavutamist ja omanikuna kasu saada kõigest, mida Tuleva edaspidi ette võtab, kutsume ka sind Tuleva liikmeks astuma.'); ?></p>
          </div>
        </div>
        <div class="proposal-wrapper">
          <div class="proposals w-container">
            <div class="proposal">
              <div class="proposal-description">
                <h4 class="proposal-title"><?php _e('Astu liikmeks ja vali Tuleva pensionifond'); ?></h4>
                <ul class="benefits w-list-unstyled">
                  <li class="benefit"><?php _e('Kogud pensioni meie ühises madalate kuludega pensionifondis') ?></li>
                  <li class="benefit"><?php _e('Liikmele on valitsemistasu soodsam — kõigest'); ?>&nbsp;<span class="highlight"><strong>0,29%</strong></span></li>
                  <li class="benefit last"><?php _e('Toetad Tuleva arengut ja aitad teha Eesti pensionisüsteemi paremaks'); ?></li>
                </ul>
              </div>
              <div class="proposal-cta">
                <div class="fee">
                  <div class="fee-cost">100€</div>
                  <div class="fee-helper"><?php _e('ühekordne liitumistasu'); ?></div>
                <!-- </div><a class="button w-button" href="#">Astu liikmeks</a></div> -->
                </div><a class="button btn btn-primary" href="#inline-signup-anchor"><?php _e('Astu liikmeks'); ?></a></div>
            </div>
            <div class="proposal">
              <div class="proposal-description">
                <h4 class="proposal-title"><?php _e('Vali lihtsalt Tuleva pensionifond'); ?></h4>
                <ul class="benefits w-list-unstyled">
                  <li class="benefit"><?php _e('Kogud pensioni madalate kuludega pensionifondis, mille omanikud hoolivad sinu, mitte panga vara kasvust.'); ?></li>
                  <li class="benefit last"><?php _e('Keskmisest kolm korda soodsam valitsemistasu'); ?>&nbsp;—&nbsp;<span class="highlight"><strong>0,34%</strong></span></li>
                </ul>
              </div>
              <div class="proposal-cta">
                <div class="fee">
                  <div class="fee-cost secondary"><?php _e('TASUTA'); ?></div>
                  <div class="fee-helper"><?php _e('ja võtab vaid 5 minutit'); ?></div>
                </div><a class="link" href="https://tuleva.ee/fondid/"><?php _e('Tutvu fondidega'); ?></a><a class="link" href="https://tuleva.ee/kuidas-tuua-pension-tulevasse/"><?php _e('Fondivahetuse juhend'); ?></a></div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>


   <divid="inline-signup-anchor"></div>
   <div id="text-block-component" class="container row-spacing-half">
        <div class="row">
            <div class="col-md-4 col-md-offset-8">
                <?php if (isset($_GET['signup'])) { ?>
                    <div id="inline-signup" class="inline-signup well well-xl"></div>
                    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/signup.fbfcfd48.js"></script>
                    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/polyfills.8b285ffa.js"></script>
<!--                    <script type="text/javascript" src="http://localhost:3000/static/js/signup.js"></script>-->
                <?php } ?>
            </div>
        </div>
    </div>


<!--        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseExample"-->
<!--                aria-expanded="false" aria-controls="collapseExample">-->
<!--            Button with data-target-->
<!--        </button>-->
<!--        <div class="collapse" id="collapseExample">-->
<!--            <div class="well">-->
<!--                ...-->
<!--            </div>-->
<!--        </div>-->

<!--        --><?php //get_template_part('templates/components/reasons'); ?>
<!--        --><?php //get_template_part('templates/components/founders'); ?>

        <?php

        if (have_rows('front_components')) {
            while (have_rows('front_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                    get_template_part('templates/components/reasons');
                } elseif (get_row_layout() === 'button') {
                    get_template_part('templates/components/button');
                } elseif (get_row_layout() === 'text_block') {
                    get_template_part('templates/components/text-block');
                } elseif (get_row_layout() === 'text_rows_block') {
                    get_template_part('templates/components/text-rows-block');
                } elseif (get_row_layout() === 'quotes_block') {
                    get_template_part('templates/components/quotes-block');
                } elseif (get_row_layout() === 'testimonial_slider') {
                    get_template_part('templates/components/testimonial-slider');
                } elseif (get_row_layout() === 'hero_block') {
                    get_template_part('templates/components/hero-block');
                } elseif (get_row_layout() === 'people_slider') {
                    get_template_part('templates/components/people-slider');
                } elseif (get_row_layout() === 'tabs') {
                    get_template_part('templates/components/tabs');
                }
            }
        }
    ?>

    <?php endwhile; ?>
</div>
