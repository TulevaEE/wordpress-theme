<div class="<?php
    if (get_sub_field('has_background_color')) {
        echo 'bg-alt ';
    }
    if (get_sub_field('spacing') === 'half') {
        echo 'row-spacing-half';
    } else {
        echo 'row-spacing';
    } ?>">
    <div class="container">
        <div class="row">
            <div class="quotes-box<?php
                if (!get_sub_field('has_padding')) {
                    echo ' no-padding';
                } ?>">
                <?php for( $i = 1; $i < 4; $i++ ) { ?>
                    <div class="col-md-4 quotes-box__item">
                        <div class="quotes-box__content">
                            <div class="quotes-box__text"><?php the_sub_field('quote_' . $i . '_text'); ?></div>
                            <div class="quotes-box__author">
                                <img class="quotes-box__author__image" src="<?php the_sub_field('quote_' . $i . '_author_image'); ?>" alt="<?php the_sub_field('quote_' . $i . '_author'); ?>">
                                <span class="quotes-box__author__info">
                                    <?php if (get_sub_field('quote_' . $i . '_author_url')) { ?>
                                        <a href="<?php the_sub_field('quote_' . $i . '_author_url'); ?>">
                                    <?php } ?>
                                        <?php the_sub_field('quote_' . $i . '_author'); ?>
                                    <?php if (get_sub_field('quote_' . $i . '_author_url')) { ?>
                                        </a>
                                    <?php } ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
