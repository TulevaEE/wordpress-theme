<?php
$unique_id = uniqid('_');
$unique_parent_id = 'accordion-parent' . $unique_id;
?>
<div class="accordion-parent <?php echo $unique_parent_id; ?>">
    <section class="d-flex flex-column">
        <div class="container my-auto">
            <div class="row align-items-center">
                <?php if( have_rows('job_openings') ): ?>
                    <?php while( have_rows('job_openings') ): the_row(); ?>
                        <?php
                            $job_id = hyphenate_string(get_sub_field('title') . $unique_id);
                            $thumbnail_url = '';
                            $image = get_sub_field('image');

                            if ($image) {
                                $thumbnail_url = $image['sizes']['thumbnail'];
                            }
                        ?>
                        <div class="col-lg-6 text-center py-5 toggle-parent">
                            <div class="col-12 pb-2">
                                <?php if ($thumbnail_url) { ?>
                                    <img class="w-25 image-circle mb-2" src="<?php echo $thumbnail_url; ?>" alt="<?php the_sub_field('title'); ?>">
                                <?php } ?>
                                <h3 class="mb-3"><?php the_sub_field('title'); ?></h3>
                                <a class="btn btn-outline-primary btn-lg collapsed" data-toggle="collapse" data-target="#<?php echo $job_id; ?>" href="#<?php echo $job_id; ?>" aria-controls="<?php echo $job_id; ?>">
                                    <span class="collapsed__shown"><?php the_sub_field('open_link_text'); ?></span>
                                    <span class="collapsed__hidden"><?php the_sub_field('close_link_text'); ?></span>
                                </a>
                            </div>
                            <div class="col-12 text-left collapse-lg-none mt-5">
                                <?php the_sub_field('text') ?>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php if( have_rows('job_openings') ): ?>
        <?php while( have_rows('job_openings') ): the_row(); ?>
            <?php $job_id = hyphenate_string(get_sub_field('title') . $unique_id); ?>
            <section id="<?php echo $job_id; ?>" class="collapse collapse-lg-block bg-blue-washed" data-parent=".<?php echo $unique_parent_id; ?>" aria-expanded="false">
                <div class="container my-auto">
                    <div class="row align-items-center py-5">
                        <div class="col-12 column-lg-2">
                            <?php the_sub_field('text') ?>
                        </div>
                        <div class="col-12 pt-5 text-center">
                            <a href="<?php the_sub_field('cta_button_url'); ?>" class="btn btn-primary btn-lg"><?php the_sub_field('cta_button_text'); ?></a>
                        </div>
                    </div>
                </div>
            </section>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
