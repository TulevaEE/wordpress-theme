<div id="<?php the_sub_field('component_id'); ?>" class="container">
    <div class="row row-spacing-half">
        <div class="col">
            <h2 class="text-center">
                <?php the_sub_field('heading'); ?>
            </h2>
        </div>
    </div>
    <?php $large_quote = get_sub_field('large_quote'); ?>
    <?php if (have_rows('quotes')) { $i = 0; ?>
        <?php while (have_rows('quotes')) { the_row(); $i++; ?>
            <?php if ($i === 1) { ?>
                <?php get_template_part('templates/components/story/first-quote'); ?>
                <div class="row row-spacing">
                    <div class="offset-md-2 col-md-8 mid-headline">
                        <p><?php echo $large_quote ?></p>
                    </div>
                </div>
            <?php } else { ?>
                <?php get_template_part('templates/components/story/quote'); ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <div class="row text-center row-spacing-bottom-quarter">
        <div class="col">
            <h2 class="text-center">
                <?php the_sub_field('bottom_heading'); ?>
            </h2>
        </div>
    </div>
    <div class="row text-center">
        <div class="mx-md-auto col-lg-8">
            <?php the_sub_field('bottom_text'); ?>
        </div>
    </div>
</div>
