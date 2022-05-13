<?php

class Text_Rows_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // base ID of the widget
            'tu_text_rows_widget',

            // name of the widget
            __('Text rows', TEXT_DOMAIN),

            // widget options
            [
                'description' => __('Shows text rows.', TEXT_DOMAIN)
            ]

        );
    }

    function form($instance)
    {
        $defaults = [
            'title' => '',
            'row_1' => '',
            'row_2' => '',
            'row_3' => '',
            'row_4' => ''
        ];

        $title = isset($instance['title']) ? $instance['title'] : '';
        $row_1 = isset($instance['row_1']) ? $instance['row_1'] : '';
        $row_2 = isset($instance['row_2']) ? $instance['row_2'] : '';
        $row_3 = isset($instance['row_3']) ? $instance['row_3'] : '';
        $row_4 = isset($instance['row_4']) ? $instance['row_4'] : '';

        // markup for form
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('row_1'); ?>"><?php _e('Text row 1:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('row_1'); ?>" name="<?php echo $this->get_field_name('row_1'); ?>" value="<?php echo esc_attr($row_1); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('row_2'); ?>"><?php _e('Text row 2:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('row_2'); ?>" name="<?php echo $this->get_field_name('row_2'); ?>" value="<?php echo esc_attr($row_2); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('row_3'); ?>"><?php _e('Text row 3:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('row_3'); ?>" name="<?php echo $this->get_field_name('row_3'); ?>" value="<?php echo esc_attr($row_3); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('row_4'); ?>"><?php _e('Text row 4:', TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('row_4'); ?>" name="<?php echo $this->get_field_name('row_4'); ?>" value="<?php echo esc_attr($row_4); ?>">
        </p>
    <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['row_1'] = strip_tags($new_instance['row_1']);
        $instance['row_2'] = strip_tags($new_instance['row_2']);
        $instance['row_3'] = strip_tags($new_instance['row_3']);
        $instance['row_4'] = strip_tags($new_instance['row_4']);

        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', $instance['title']);
        $row_1 = isset($instance['row_1']) ? $instance['row_1'] : '';
        $row_2 = isset($instance['row_2']) ? $instance['row_2'] : '';
        $row_3 = isset($instance['row_3']) ? $instance['row_3'] : '';
        $row_4 = isset($instance['row_4']) ? $instance['row_4'] : '';

        echo $before_widget;
    ?>

        <?php if (!empty($title)) { ?>
            <h4 class="footer__title"><?php echo $title; ?></h4>
        <?php } ?>

        <div class="footer__column__text">
            <?php if ($row_1) { ?>
                <div class="footer__column__text__row"><?php echo $row_1; ?></div>
            <?php } ?>
            <?php if ($row_2) { ?>
                <div class="footer__column__text__row"><?php echo $row_2; ?></div>
            <?php } ?>
            <?php if ($row_3) { ?>
                <div class="footer__column__text__row"><?php echo $row_3; ?></div>
            <?php } ?>
            <?php if ($row_4) { ?>
                <div class="footer__column__text__row"><?php echo $row_4; ?></div>
            <?php } ?>
        </div>
<?php
        echo $after_widget;
    }
}
