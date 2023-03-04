<?php if( have_rows('hero') ) while ( have_rows('hero') ) : the_row(); ?>
<section class="bg-hero-jobs d-flex flex-column">
    <div class="container my-auto">
        <div class="row align-items-center py-5">
            <div class="col-lg-6 mx-auto">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>
                    <p class="lead text-navy mb-3"><?php the_sub_field('lead_text'); ?></p>
                <div class="text-navy mb-5"><?php the_sub_field('text'); ?></div>
            </div>
            <div class="col-lg-6">
                <img class="img-fluid" src="<?php the_sub_field('image'); ?>" alt="">
            </div>
        </div>
    </div>
</section>
<?php endwhile; ?>
