<?php

$jobs      = get_field( 'job_openings' ) ?: [];
$block_uid = uniqid();
?>

<section class="d-flex flex-column section-spacing">
    <div class="container">

        <div class="row align-items-start">
            <div class="col-lg-9 col-xl-8 mx-auto text-center">
                <h2 class="m-0 mb-4"><?php _e('Open positions', TEXT_DOMAIN); ?></h2>
                <p class="lead m-0">
                <?php if ( $jobs ) : ?>
                    <?php _e('Come and join us – help bring to life the plans for the years ahead.', TEXT_DOMAIN); ?>
                <?php else : ?>
                    <?php _e('We’re not actively hiring right now.', TEXT_DOMAIN); ?>
                <?php endif; ?>
                </p>
            </div>
        </div>

        <?php if ( $jobs ) : ?>
            <div class="accordion accordion-flush border-top border-bottom mt-5 mt-sm-6" id="jobs-<?php echo esc_attr( $block_uid ); ?>">

                <?php foreach ( $jobs as $i => $job ) :

                    $row_idx   = $i + 1;
                    $slug      = sanitize_title_with_dashes( $job['title'] );
                    $base_id   = "{$slug}-{$row_idx}-{$block_uid}";
                    ?>

                    <section class="accordion-item">
                        <h3 class="accordion-header" id="heading-<?php echo esc_attr( $base_id ); ?>">
                            <button class="accordion-button collapsed px-0 px-md-3 py-4"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?php echo esc_attr( $base_id ); ?>"
                                    aria-controls="collapse-<?php echo esc_attr( $base_id ); ?>"
                                    aria-expanded="false">
                                <span class="m-0 h3 fw-medium text-color-inherit"><?php echo esc_html( $job['title'] ); ?></span>
                            </button>
                        </h3>

                        <div id="collapse-<?php echo esc_attr( $base_id ); ?>"
                            class="accordion-collapse collapse"
                            data-bs-parent="#jobs-<?php echo esc_attr( $block_uid ); ?>">

                            <div class="accordion-body px-0 px-md-3 py-4 py-lg-5">

                                <div class="column-lg-2 gap-4 gap-lg-5">
                                    <?php echo wp_kses_post( $job['text'] ); ?>
                                </div>

                                <?php if ( ! empty( $job['cta_button_url'] ) ) : ?>
                                    <p class="m-0 mt-5 text-center">
                                        <a href="<?php echo esc_url( $job['cta_button_url'] ); ?>"
                                        class="btn btn-primary btn-lg fs-3 px-4">
                                            <?php echo esc_html( $job['cta_button_text'] ?: __( 'Apply for this job', 'textdomain' ) ); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </section>

                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</section>