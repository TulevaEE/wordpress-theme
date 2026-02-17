<main id="main" class="page-container">
    <?php if (have_posts()) while (have_posts()) : the_post();

        get_template_part('templates/components/fund-stocks-header');
        get_template_part('templates/components/fund-stocks-details');
        get_template_part('templates/components/fund-manager');

        if (have_rows('fund_components')) {
            while (have_rows('fund_components')) {
                the_row();
                if (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                }
            }
        }

    endwhile; ?>

    <?php
    $context = stream_context_create(
        [
            'http' => [
                'method' => 'GET',
                'timeout' => 1,
            ]
        ]
    );
    if ($_SERVER['SERVER_NAME'] !== 'localhost') {
        $json = file_get_contents('https://onboarding-service.tuleva.ee/v1/funds?fundManager.name=Tuleva', false, $context);
    } else {
        $json = '[
          {
            "fundManager": {
              "name": "Tuleva"
            },
            "isin": "EE3600109435",
            "name": "Tuleva World Stocks Pension Fund",
            "managementFeeRate": 0.0024,
            "pillar": 2,
            "ongoingChargesFigure": 0.0031,
            "nav": 1.28834,
            "volume": 732956944.21689
          }
        ]';
    }

    $funds = json_decode($json, true);
    if (!empty($funds)) {
        $stock = array_search('EE3600109435', array_column($funds, 'isin'));
        if ($stock !== false) {
    ?>

    <script type="text/javascript">
        $(function () {
            $('#stock-fund-volume').html('<?php echo number_format($funds[$stock]['volume'], 0, '.', ' ') ?>');
            $('#stock-fund-nav').html('<?php echo $funds[$stock]['nav'] ?>');
        });
    </script>
    <?php
        }
    } ?>
</main>
