<section id="choose-fund" class="py-6">
    <div class="container text-center">
        <h2 class="mb-5">
            <?php the_title() ?>
        </h2>
        <div class="row">
            <div class="col">
                <div class="card-deck">
                    <div class="card shadow-sm">
                        <?php if( have_rows('left_box') ) while ( have_rows('left_box') ) : the_row(); ?>
                            <?php get_template_part('templates/components/fund-choosing-card'); ?>
                        <?php endwhile; ?>
                    </div>
                    <div class="card shadow-sm">
                        <?php if( have_rows('right_box') ) while ( have_rows('right_box') ) : the_row(); ?>
                            <?php get_template_part('templates/components/fund-choosing-card'); ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
