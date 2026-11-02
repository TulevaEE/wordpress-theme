<?php
/**
 * The documents every fund page links, held in this page's fields
 * (helpers/acf/fund-disclosures.php). The fund pages read them from here, so the list is the
 * same one, with the same links, as on a fund page.
 */
?>
<main id="main" class="page-container section-spacing">
    <div class="container">
        <div class="row">
            <div class="mx-auto col-lg-9 col-xl-8">
                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php if (get_the_content()) { ?>
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    <?php } ?>
                <?php endwhile; ?>
                <ul class="list-style-arrow mb-5 text-secondary">
                    <li>
                        <a href="<?php echo get_nav_procedure_document_url(); ?>" target="_blank"><?php _e('Procedure for determining net worth of fund', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                        <?php if (get_nav_procedure_upcoming_document_url()): ?>
                            <br>
                            <a href="<?php echo get_nav_procedure_upcoming_document_url(); ?>" target="_blank"><?php _e('Procedure for determining net worth of fund', TEXT_DOMAIN) ?></a><?php printf(__(' (in Estonian, effective from %s)', TEXT_DOMAIN), get_nav_procedure_upcoming_effective_date()); ?>
                        <?php endif; ?>
                    </li>
                    <li>
                        <a href="<?php echo get_esg_document_url(); ?>" target="_blank"><?php _e('Sustainability', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                    </li>
                    <li>
                        <a href="<?php echo get_esg_factors_document_url(); ?>" target="_blank"><?php _e('Non-consideration of adverse impacts of investment decisions on sustainability factors', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                    </li>
                    <li>
                        <a href="<?php echo get_remuneration_document_url(); ?>" target="_blank"><?php _e('Remuneration Policy of Tuleva Fondid AS', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>
