<?php

class Help_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(

            // base ID of the widget
            'tu_help_widget',

            // name of the widget
            __('Ask help', TEXT_DOMAIN ),

            // widget options
            [
                'description' => __( 'Shows help widget.', TEXT_DOMAIN )
            ]

        );
    }

    function form( $instance ) {
        $defaults = [
            'title' => '',
            'button_label' => '',
            'faq_url' => ''
        ];

        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : '';
        $button_label = isset($instance[ 'button_label' ]) ? $instance[ 'button_label' ] : '';
        $faq_url = isset($instance[ 'faq_url' ]) ? $instance[ 'faq_url' ] : '';


        // markup for form ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_label' ); ?>"><?php _e( 'Button label:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_label' ); ?>" name="<?php echo $this->get_field_name( 'button_label' ); ?>" value="<?php echo esc_attr( $button_label ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'faq_url' ); ?>"><?php _e( 'FAQ page URL:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'faq_url' ); ?>" name="<?php echo $this->get_field_name( 'faq_url' ); ?>" value="<?php echo esc_attr( $faq_url ); ?>">
        </p>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'button_label' ] = strip_tags( $new_instance[ 'button_label' ] );
        $instance[ 'faq_url' ] = strip_tags( $new_instance[ 'faq_url' ] );

        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $button_label = isset($instance[ 'button_label' ]) ? $instance[ 'button_label' ] : '';
        $faq_url = isset($instance[ 'faq_url' ]) ? $instance[ 'faq_url' ] : '';

        echo $before_widget;
        ?>

        <?php if ( ! empty( $title ) ) { ?>
            <h2 class="footer__title h4"><?php echo $title; ?></h2>
        <?php } ?>

        <?php if ( ! empty( $faq_url ) ) { ?>
            <div class="d-flex">
                <p class="small w-50 mb-1"><?php echo sprintf( __('See also our %sFAQ%s page.', TEXT_DOMAIN), '<a href="'. $faq_url .'">', '<a>'); ?></p>
            </div>
        <?php } ?>

        <p class="mb-3"><?php _e('Ask Pirje from Tuleva team', TEXT_DOMAIN); ?></p>

        <a class="btn btn-outline-primary footer-help" href="#"><?php echo $button_label; ?></a>
        <a href="#" class="footer-help-close">
            <span class="footer-help-close__icon"></span>
        </a>

        <?php
        echo $after_widget;
    }

}
