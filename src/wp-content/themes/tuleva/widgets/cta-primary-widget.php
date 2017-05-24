<?php

class CTA_Primary_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(

            // base ID of the widget
            'tu_cta_primary_widget',

            // name of the widget
            __('Primary CTA widget', TEXT_DOMAIN ),

            // widget options
            [
                'description' => __( 'Shows primary CTA widget.', TEXT_DOMAIN )
            ]

        );
    }

    function form( $instance ) {
        $defaults = [
            'title' => '',
            'button_text' => '',
            'button_url' => '',
            'text' => ''
        ];

        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : '';
        $button_text = isset($instance[ 'button_text' ]) ? $instance[ 'button_text' ] : '';
        $button_url = isset($instance[ 'button_url' ]) ? $instance[ 'button_url' ] : '';
        $text = isset($instance[ 'text' ]) ? $instance[ 'text' ] : '';

        // markup for form ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button text:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e( 'Button URL:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php echo esc_attr( $button_url ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo esc_attr( $text ); ?>">
        </p>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'button_text' ] = strip_tags( $new_instance[ 'button_text' ] );
        $instance[ 'button_url' ] = strip_tags( $new_instance[ 'button_url' ] );
        $instance[ 'text' ] = strip_tags( $new_instance[ 'text' ] );

        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $button_text = isset($instance[ 'button_text' ]) ? $instance[ 'button_text' ] : '';
        $button_url = isset($instance[ 'button_url' ]) ? $instance[ 'button_url' ] : '';
        $text = isset($instance[ 'text' ]) ? $instance[ 'text' ] : '';

        echo $before_widget;
        ?>

        <div class="cta-widget cta-widget--primary">
            <?php if ( ! empty( $title ) ) { ?>
                <h3 class="cta-widget__title"><?php echo $title; ?></h3>
            <?php } ?>

            <a href="<?php echo $button_url; ?>" class="btn btn-primary btn-lg"><?php echo $button_text; ?></a>
            <p><?php echo $text; ?></p>
        </div>

        <?php
        echo $after_widget;
    }

}
