<section id="choose-fund" class="section-spacing">
    <div class="container">
        <h1 class="m-0 text-center"><?php the_title(); ?></h1>

        <div class="section-spacing-top d-flex flex-column flex-lg-row mx-auto gap-3 gap-sm-4">
            <?php if ( have_rows('box_primary') ) : ?>
                <?php while ( have_rows('box_primary') ) : the_row(); ?>
                    <?php get_template_part('templates/components/fund-choosing-card'); ?>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php if ( have_rows('box_secondary') ) : ?>
                <?php while ( have_rows('box_secondary') ) : the_row(); ?>
                    <?php get_template_part('templates/components/fund-choosing-card'); ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="section-spacing-top text-secondary text-center">
            <?php echo get_field('rich_text'); ?>
        </div>
    </div>
</section>
