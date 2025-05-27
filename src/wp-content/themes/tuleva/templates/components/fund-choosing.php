<section id="choose-fund" class="py-6">
    <div class="container text-center">
        <h1 class="m-0"><?php the_title(); ?></h1>

        <div class="mt-6 d-flex flex-column flex-lg-row mx-auto gap-4">
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

        <div class="mt-6 text-secondary">
            <?php echo get_field('rich_text'); ?>
        </div>
    </div>
</section>
