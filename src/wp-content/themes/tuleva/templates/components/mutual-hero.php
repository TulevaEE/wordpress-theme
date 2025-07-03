<section id="<?php the_sub_field('component_id'); ?>" class="bg-hero-mutual section-spacing">
    <div class="container">
        <div class="row text-navy">
            <div class="mx-auto col-lg-11 col-xl-9 text-center">
                <h1 class="m-0"><?php the_sub_field('heading'); ?></h1>
            </div>
            <?php if (get_sub_field('quote')) { ?>
                <div class="mx-auto mt-5 pb-6 col-lg-9 col-xl-8 text-center">
                    <p class="mb-4 fs-4 text-balance">“<?php the_sub_field('quote'); ?>”</p>
                    <?php if (get_sub_field('source')) { ?>
                        <p class="m-0 fs-5">
                            <?php if (get_sub_field('source_link_url')) { ?>
                                <a href="<?php the_sub_field('source_link_url'); ?>" class="text-navy text-bold">
                            <?php } ?>
                                <?php the_sub_field('source'); ?><?php
                                    if (get_sub_field('source_link_url')) { ?></a><?php } ?>, <?php the_sub_field('source_description'); ?>
                        </p>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</section>