<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php get_template_part('templates/head/analytics'); ?>
    <?php wp_head(); ?>
    <script>

    <!-- Temporary fix for iOS zooming crash -->
    if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
        (function handleProblematicElementsWithDelayedRestoration() {
            console.log("Adjusting problematic elements for iOS zoom with delayed restoration...");

            // Prefixes for problematic classes
            const problematicClassPrefixes = [
                'ContainerFramecss_',
                'ContainerGradient-',
            ];

            // IDs for problematic elements
            const problematicIDs = [
                'foundersModal',
                'modal-brutotulu',
                'modal-III_pillar_calculator',
                'modal-ostude_kalkulaator',
                'modal-payment-info',
                'modal-question-fee',
                'modal-question-joining-fee',
                'modal-question-profit',
                'modal-question-vote',
                'modal-security',
            ];

            // Function to apply fixes to problematic elements
            function applyFixes() {
                const elements = findProblematicElements();
                elements.forEach(el => {
                    const computedStyle = window.getComputedStyle(el);
                    // Neutralize problematic styles
                    if (computedStyle.opacity === "0") el.style.display = "none"; // Hide invisible elements
                });

                console.log("Applied fixes to zoom problematic elements.");
            }

            // Function to find problematic elements based on prefixes and IDs
            function findProblematicElements() {
                const elements = [];

                // Match elements with class prefixes
                problematicClassPrefixes.forEach(prefix => {
                    const matchingElements = Array.from(document.querySelectorAll(`[class^="${prefix}"]`));
                    elements.push(...matchingElements);
                });

                // Match elements with specific IDs
                problematicIDs.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) elements.push(el);
                });

                return elements;
            }

            let zooming = false;

            function onGestureStart() {
                if (!zooming) {
                    zooming = true;
                    applyFixes();
                }
            }

            function onGestureEnd() {
                if (zooming) {
                    zooming = false;
                }
            }

            window.addEventListener("gesturestart", onGestureStart);
            window.addEventListener("gestureend", onGestureEnd);
        })();
    }
    </script>
</head>
