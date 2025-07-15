<?php

class CTA_Button_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(

            // base ID of the widget
            'tu_cta_button_widget',

            // name of the widget
            __('CTA Button widget', TEXT_DOMAIN ),

            // widget options
            [
                'description' => __( 'Shows a CTA Button.', TEXT_DOMAIN )
            ]

        );
    }

    function form( $instance ) {
        $defaults = [
            'button_text' => '',
            'button_url' => '',
        ];

        $button_text = isset($instance[ 'button_text' ]) ? $instance[ 'button_text' ] : '';
        $button_url = isset($instance[ 'button_url' ]) ? $instance[ 'button_url' ] : '';

        // markup for form ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button text:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e( 'Button URL:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php echo esc_attr( $button_url ); ?>">
        </p>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'button_text' ] = strip_tags( $new_instance[ 'button_text' ] );
        $instance[ 'button_url' ] = strip_tags( $new_instance[ 'button_url' ] );

        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );

        $button_text = isset($instance[ 'button_text' ]) ? $instance[ 'button_text' ] : '';
        $button_url = isset($instance[ 'button_url' ]) ? $instance[ 'button_url' ] : '';

        echo $before_widget;
        ?>

        <a href="<?php echo $button_url; ?>" class="btn btn-lg btn-primary"><?php echo $button_text; ?></a>

        <?php
        echo $after_widget;
    }

}
