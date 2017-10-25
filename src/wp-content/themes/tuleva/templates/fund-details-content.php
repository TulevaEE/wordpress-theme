<div class="page-container">
    

    <?php
    get_template_part('templates/components/funds-header');
    ?>

    <?php
        if (have_rows('fund_components')) {
            while (have_rows('fund_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                } elseif (get_row_layout() === 'button') {
                    get_template_part('templates/components/button');
                } elseif (get_row_layout() === 'media') {
                    get_template_part('templates/components/media');
                } elseif (get_row_layout() === 'text_rows_block') {
                    get_template_part('templates/components/text-rows-block');
                } elseif (get_row_layout() === 'comparison_block') {
                    get_template_part('templates/components/comparison-block');
                } elseif (get_row_layout() === 'single_quote_block') {
                    get_template_part('templates/components/single-quote-block');
                } elseif (get_row_layout() === 'fund_contacts_block') {
                    get_template_part('templates/components/fund-contacts-block');
                } elseif (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                }
            }
        }
    ?>

    <?php

    $context = stream_context_create(
        array(
            'http' => array(
                'method' => 'GET',
                'header' => array(
                    "Authorization: Bearer b4adb192-29a8-4861-a697-c704947d0023"
                )
            )
        )
    );

    $json = file_get_contents('https://onboarding-service.tuleva.ee/v1/funds?fundManager.name=Tuleva', false, $context);
    $funds = json_decode($json, true);

    $stock = array_search('EE3600109435', array_column($funds, 'isin'));
    $bond = array_search('EE3600109443', array_column($funds, 'isin'));

    ?>

    <script type="text/javascript">
        $(function () {
            $('#stock-fund-volume').html('<?php echo number_format($funds[$stock]['volume'], 0, '.', ' ') ?>');
            $('#stock-fund-nav').html('<?php echo $funds[$stock]['nav'] ?>');
            $('#bond-fund-volume').html('<?php echo number_format($funds[$bond]['volume'], 0, '.', ' ') ?>');
            $('#bond-fund-nav').html('<?php echo $funds[$bond]['nav'] ?>');
        });
    </script>
</div>
