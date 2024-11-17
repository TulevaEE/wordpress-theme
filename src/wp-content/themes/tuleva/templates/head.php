<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php get_template_part('templates/head/analytics'); ?>
    <?php wp_head(); ?>
    <script>

    <!-- Temporary fix for iOS zooming crash -->
    if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
        const handleTouchEvent = (event) => {
            if (event.type.startsWith("gesture")) {
                event.preventDefault();
            }
        };

        const attachTouchListeners = () => {
            document.addEventListener('gesturestart', handleTouchEvent, { passive: false });
            document.addEventListener('gesturechange', handleTouchEvent, { passive: false });
            document.addEventListener('gestureend', handleTouchEvent, { passive: false });
            console.log("attached iOS touch event listeners");
        };

        attachTouchListeners();
    }
    </script>
</head>
