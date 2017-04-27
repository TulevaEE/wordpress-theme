<div class="page-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="submenu">
                    <ul class="submenu__list">
                        <li class="submenu__item submenu__item--current">
                            <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>">Kõik artiklid</a>
                        </li>
                        <li class="submenu__item">
                            <a href="#">Olulisemad artiklid</a>
                        </li>
                    </ul>
                    <a class="pull-right" href="#">RSS voog</a>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="content-area">
                <ul class="post-list <?php if ($paged > 1) { echo ' post-list--paged'; } ?>">
                    <?php
                    $temp = $wp_query; $wp_query= null;
                    $isPaged = $paged > 1;
                    $posts_number = $isPaged ? 6 : 5;
                    $i = 0;
                    $wp_query = new WP_Query(); $wp_query->query('posts_per_page=' . $posts_number . '&paged='.$paged);
                    while ($wp_query->have_posts()) : $wp_query->the_post();
                    $i++;
                    $isFeatured = !$isPaged && $i === 1;
                    ?>
                    <li class="post-list__item<?php if ($isFeatured) { echo ' post-list__item--wide'; } ?>">
                        <?php if (has_post_thumbnail()) { ?>
                            <div class="post-list__item__image">
                                <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title(); ?>"></a>
                            </div>
                        <?php } ?>
                        <h3 class="post-list__item__title<?php if ($isFeatured) { echo ' h2'; } ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="post-list__item__meta">
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" class="post-list__item__author"><?php echo get_the_author(); ?></a> <?php the_date(); ?>
                        </div>
                        <div class="post-list__item__description"><?php the_excerpt(); ?></div>
                    </li>
                    <?php endwhile; ?>
                </ul>

                <?php if ($paged > 1) { ?>
                    <div class="pagination">
                        <div class="pagination__previous"><?php next_posts_link('Vanemad artiklid'); ?></div>
                        <div class="pagination__next"><?php previous_posts_link('Uuemad artiklid'); ?></div>
                    </div>
                <?php } else { ?>
                    <div class="pagination">
                        <div class="pagination__previous"><?php next_posts_link('Vanemad artiklid'); ?></div>
                    </div>
                <?php } ?>

                <?php wp_reset_postdata(); ?>
            </div>
            <div class="widget-area">
                <div class="cta-widget cta-widget--primary">
                    <h3 class="cta-widget__title">Kuidas saad sina Tulevast kasu?</h3>
                    <a href="#" class="btn btn-primary btn-lg">Sisene Tuleva äppi</a>
                    <p>Näitame sulle, kui palju sa säästaksid Tuleva pensioni-fondi kliendina ja/või Tuleva liikmena.</p>
                </div>
                <div class="cta-widget cta-widget--secondary">
                    <h3 class="cta-widget__title--link"><a href="#">Too pension Tulevasse</a></h3>
                    <p>Juhend, kuidas vahetada fondi internetipangas või pensionikeskuses.</p>
                </div>
            </div>
        </div>
    </div>
</div>
