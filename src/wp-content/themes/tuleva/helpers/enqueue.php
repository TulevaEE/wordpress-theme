<?php

class ThemeEnqueue
{
    private function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 12);
    }

    function __construct()
    {
        $this->init();
    }

    public function enqueue_assets()
    {
        include_once 'enqueue_versions.php';
        $css_path = get_template_directory_uri() . '/css/';
        $js_path = get_template_directory_uri() . '/js/';

        $assets = [
            // CSS assets
            [
                'handle' => 'font-style',
                'src' => 'https://fonts.googleapis.com/css?family=Merriweather:400,400i,700,700i|Roboto:400,400i,500,500i,700,700i&amp;subset=latin-ext',
                'deps' => 'normalize',
                'ver' => '1.0.0',
                'media' => 'all',
                'enqueue' => true
            ],
            [
                'handle' => 'main-style',
                'src' => $css_path . 'main.css',
                'deps' => 'normalize',
                'ver' => $CSS_ENQUEUE_VERSION,
                'media' => 'all',
                'enqueue' => true
            ],
            // JS assets
            [
                'handle' => 'jquery',
                'src' => $js_path . 'vendor/jquery-3.7.1.min.js',
                'ver' => '3.7.1',
                'js_in_header' => true,
                'enqueue' => true
            ],
            [
                'handle' => 'bootstrap',
                'src' => $js_path . 'vendor/bootstrap.bundle.min.js',
                'deps' => 'jquery',
                'ver' => '5.3.6',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'jquery.event.move',
                'src' => $js_path . 'vendor/jquery.event.move.js',
                'deps' => 'jquery',
                'ver' => '2.0.0',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'jquery.event.swipe',
                'src' => $js_path . 'vendor/jquery.event.swipe.js',
                'deps' => 'jquery',
                'ver' => '0.5.0',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'unslider',
                'src' => $js_path . 'vendor/unslider.js',
                'deps' => 'jquery',
                'ver' => '2.0.0',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'animatedModal',
                'src' => $js_path . 'vendor/animatedModal.min.js',
                'deps' => 'jquery',
                'ver' => '1.0.0',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'rangeslider',
                'src' => $js_path . 'vendor/rangeslider.min.js',
                'deps' => 'jquery',
                'ver' => '2.3.0',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'slider',
                'src' => $js_path . 'slider.js',
                'deps' => ['jquery', 'rangeslider'],
                'ver' => '20102019',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'countdown',
                'src' => $js_path . 'vendor/countdown.min.js',
                'deps' => 'jquery',
                'ver' => '2.6.1',
                'js_in_header' => false,
                'enqueue' => true
            ],
            [
                'handle' => 'main-script',
                'src' => $js_path . 'main.js',
                'deps' => 'jquery',
                'ver' => $JS_ENQUEUE_VERSION,
                'js_in_header' => false,
                'enqueue' => true
            ]
        ];
        array_push(
            $assets,
            [
                'handle' => 'calculator',
                'src' => $js_path . 'calculator.js',
                'deps' => 'jquery',
                'ver' => $CALCULATOR_JS_ENQUEUE_VERSION,
                'js_in_header' => false,
                'enqueue' => true
            ]
        );

        /* Get file that contains SimplyEnqueue class */
        require_once(get_template_directory() . '/lib/enqueue.php');

        // Deregisters jquery in case it was registered before
        wp_deregister_script('jquery');

        $enqueue = new SimplyEnqueue;
        $enqueue->register($assets);
        $enqueue->init_instance();
    }
}

new ThemeEnqueue;
