<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <div class="container">
            <div class="row row-spacing-bottom">
                <div class="col-md-12">
                    <?php if (get_the_title()) { ?>
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php } ?>
                    <h2 class="text-center text-normal"><?php _e('Tuleva’s phone number', TEXT_DOMAIN); ?>: +372 644 5100</h2>
                </div>
            </div>
            <div class="row row-spacing">
                <div class="col-md-12">
                    <div class="contacts-block">
                        <div class="col-md-4 contacts-block__item">
                            <img class="contacts-block__image" src="<?php echo get_template_directory_uri() ?>/img/tonu-pekk.png" alt="Tõnu Pekk">
                            <h2 class="contacts-block__title">Tõnu Pekk</h2>
                            <div class="contacts-block__row">
                                <a href="mailto:tonu.pekk@tuleva.ee">tonu.pekk@tuleva.ee</a>
                            </div>
                            <div class="contacts-block__row">+372 5304 4744</div>
                        </div>
                        <div class="col-md-4 contacts-block__item">
                            <img class="contacts-block__image" src="<?php echo get_template_directory_uri() ?>/img/priit-lepasepp.png" alt="Priit Lepasepp">
                            <h2 class="contacts-block__title">Priit Lepasepp</h2>
                            <div class="contacts-block__row">
                                <a href="mailto:priit.lepasepp@tuleva.ee">priit.lepasepp@tuleva.ee</a>
                            </div>
                            <div class="contacts-block__row">+372 5626 4164</div>
                        </div>
                        <div class="col-md-4 contacts-block__item">
                            <img class="contacts-block__image" src="<?php echo get_template_directory_uri() ?>/img/kristi-saare.png" alt="Kristi Saare">
                            <h2 class="contacts-block__title">Kristi Saare</h2>
                            <div class="contacts-block__row">
                                <a href="mailto:kristi.saare@tuleva.ee">kristi.saare@tuleva.ee</a>
                            </div>
                            <div class="contacts-block__row">+372 5558 8178</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
