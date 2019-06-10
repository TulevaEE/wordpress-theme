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
            'button_label' => ''
        ];

        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : '';
        $button_label = isset($instance[ 'button_label' ]) ? $instance[ 'button_label' ] : '';


        // markup for form ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_label' ); ?>"><?php _e( 'Button label:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_label' ); ?>" name="<?php echo $this->get_field_name( 'button_label' ); ?>" value="<?php echo esc_attr( $button_label ); ?>">
        </p>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'button_label' ] = strip_tags( $new_instance[ 'button_label' ] );

        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $button_label = isset($instance[ 'button_label' ]) ? $instance[ 'button_label' ] : '';

        echo $before_widget;
        ?>

        <?php if ( ! empty( $title ) ) { ?>
            <h4 class="footer__title"><?php echo $title; ?></h4>
        <?php } ?>
            <div class="d-flex">
                <p class="small w-50 mr-3"><?php _e('Ask from Tuleva Community Manager Kristi Saare.', TEXT_DOMAIN); ?></p>
            </div>
            <a class="btn btn-outline-primary footer-help" href="#"><?php echo $button_label; ?></a>
            <a href="#" class="footer-help-close">
                <span class="footer-help-close__icon"></span>
            </a>
        <?php
        echo $after_widget;
    }

}
