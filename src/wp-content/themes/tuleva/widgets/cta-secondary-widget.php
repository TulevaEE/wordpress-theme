<?php

class CTA_Secondary_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(

            // base ID of the widget
            'tu_cta_secondary_widget',

            // name of the widget
            __('Secondary CTA widget', TEXT_DOMAIN ),

            // widget options
            [
                'description' => __( 'Shows secondary CTA widget.', TEXT_DOMAIN )
            ]

        );
    }

    function form( $instance ) {
        $defaults = [
            'title' => '',
            'title_url' => '',
            'text' => ''
        ];

        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : '';
        $title_url = isset($instance[ 'title_url' ]) ? $instance[ 'title_url' ] : '';
        $text = isset($instance[ 'text' ]) ? $instance[ 'text' ] : '';

        // markup for form ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title_url' ); ?>"><?php _e( 'Title URL:', TEXT_DOMAIN ); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title_url' ); ?>" name="<?php echo $this->get_field_name( 'title_url' ); ?>" value="<?php echo esc_attr( $title_url ); ?>">
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
        $instance[ 'title_url' ] = strip_tags( $new_instance[ 'title_url' ] );
        $instance[ 'text' ] = strip_tags( $new_instance[ 'text' ] );

        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $title_url = isset($instance[ 'title_url' ]) ? $instance[ 'title_url' ] : '';
        $text = isset($instance[ 'text' ]) ? $instance[ 'text' ] : '';

        echo $before_widget;
        ?>

        <div class="cta-widget cta-widget--secondary">
            <?php if ( ! empty( $title ) ) { ?>
                <h5 class="cta-widget__title--link"><a href="<?php echo $title_url; ?>"><?php echo $title; ?></a></h5>
            <?php } ?>

            <p><?php echo $text; ?></p>
        </div>

        <?php
        echo $after_widget;
    }

}
