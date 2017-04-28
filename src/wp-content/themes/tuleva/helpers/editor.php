<?php

/**
 *  Adds Tiny MCE styles dropdown menu
 */
function tiny_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'tiny_mce_buttons_2');

/**
 * Callback function to filter the MCE settings
 */
function tiny_mce_before_init_insert_formats( $init_array ) {
    $style_formats = array(
        /**
         *   URL: https://www.tinymce.com/docs/configure/content-formatting/#formatparameters
         *   inline:  Tag name of the inline element to use as a wrapper, for example "span". Will be used to wrap the current selection.
         *   block:   Tag name of the block element to use as a wrapper, for example "h1". Existing block elements within the selection will be replaced with this block element.
         *   selector:    CSS3 selector pattern that will be used to find elements within the selection. It can be used to apply classes to specific elements only, for example only to odd rows in a table.
         *   classes:     Space separated list of classes that will be applied to the selected or newly created inline/block element.
         *   styles:  Key/value object with CSS styles to apply to the selected or newly created inline/block element (e.g. color, backgroundColor, textDecoration, etc).
         *   attributes:  Key/value object with attributes to apply to the selected or newly created inline/block element.
         *   exact:   Makes sure that the format won't be merged with other wrappers having the same format. We use it to avoid conflicts between text-decorations for underline and strikethough formats.
         *   wrapper:     States that the format is a container format for block elements. For example a div wrapper or blockquote.
        */
        array(
            'title' => 'Unstyled list',
            'selector' => 'ul',
            'classes' => 'list-unstyled'
        ),
        array(
            'title' => 'Arrow style list',
            'selector' => 'ul',
            'classes' => 'list-style-arrow'
        ),
        array(
            'title' => 'Text muted',
            'inline' => 'span',
            'classes' => 'text-muted'
        ),
        array(
            'title' => 'Text highlighted',
            'inline' => 'span',
            'classes' => 'text-highlight'
        )
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );

    return $init_array;

}
add_filter( 'tiny_mce_before_init', 'tiny_mce_before_init_insert_formats' );

add_editor_style( 'css/editor.css' );
