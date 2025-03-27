<div id="<?php the_sub_field('component_id'); ?>" class="container py-6">
    <div class="row pb-6">
        <div class="mx-auto col-lg-9 col-xl-8">
            <h2 class="m-0 text-center">
                <?php the_sub_field('heading'); ?>
            </h2>
        </div>
    </div>
    <?php $large_quote = get_sub_field('large_quote'); ?>
    <?php if (have_rows('quotes')) { $i = 0; ?>
        <?php while (have_rows('quotes')) { the_row(); $i++; ?>
            <?php if ($i === 1) { ?>
                <?php get_template_part('templates/components/story/first-quote'); ?>
                <div class="row py-6">
                    <div class="mx-auto col-lg-9 col-xl-8 mid-headline text-center">
                        <p><?php echo $large_quote ?></p>
                    </div>
                </div>
            <?php } else { ?>
                <?php get_template_part('templates/components/story/quote'); ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <?php /* Taimar: move from Story component to Goals component
    <div class="row text-center row-spacing-bottom-quarter">
        <div class="mx-auto col-lg-9 col-xl-8 text-center">
            <h2><?php the_sub_field('bottom_heading'); ?></h2>
            <?php the_sub_field('bottom_text'); ?>
        </div>
    </div>
    */ ?>
</div>
