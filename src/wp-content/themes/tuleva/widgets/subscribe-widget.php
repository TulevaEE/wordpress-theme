<?php

class Subscribe_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // base ID of the widget
            'tu_subscribe_widget',

            // name of the widget
            __('Subscribe form', TEXT_DOMAIN),

            // widget options
            [
                'description' => __('Shows subscribe form.', TEXT_DOMAIN)
            ]

        );
    }

    function form($instance)
    {
        $defaults = [
            'title' => '',
            'facebook_url' => '',
            'twitter_url' => ''
        ];

        $title = isset($instance['title']) ? $instance['title'] : '';
        $facebook_url = isset($instance['facebook_url']) ? $instance['facebook_url'] : '';
        $twitter_url = isset($instance['twitter_url']) ? $instance['twitter_url'] : '';

        // markup for form
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('facebook_url'); ?>"><?php _e('Facebook URL:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('facebook_url'); ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" value="<?php echo esc_attr($facebook_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter_url'); ?>"><?php _e('Twitter URL:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('twitter_url'); ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" value="<?php echo esc_attr($twitter_url); ?>">
        </p>
    <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook_url'] = strip_tags($new_instance['facebook_url']);
        $instance['twitter_url'] = strip_tags($new_instance['twitter_url']);

        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', $instance['title']);
        $facebook_url = isset($instance['facebook_url']) ? $instance['facebook_url'] : '';
        $twitter_url = isset($instance['twitter_url']) ? $instance['twitter_url'] : '';

        echo $before_widget;
    ?>

        <?php if (!empty($title)) { ?>
            <h4 class="footer__title footer__title--light"><?php echo $title; ?></h4>
        <?php } ?>
        <div class="footer__subscribe">
            <?php get_template_part('templates/footer/subscribe-form'); ?>
        </div>
        <div class="footer__social">
            <?php if ($facebook_url) { ?>
                <a href="<?php echo $facebook_url; ?>">
                    <img class="footer__social__facebook" src="<?php echo get_template_directory_uri() ?>/img/btn-facebook.png" alt="Facebook">
                </a>
            <?php } ?>
            <?php if ($twitter_url) { ?>
                <a href="<?php echo $twitter_url; ?>">
                    <img class="footer__social__twitter" src="<?php echo get_template_directory_uri() ?>/img/btn-twitter.png" alt="Twitter">
                </a>
            <?php } ?>
        </div>
<?php
        echo $after_widget;
    }
}
