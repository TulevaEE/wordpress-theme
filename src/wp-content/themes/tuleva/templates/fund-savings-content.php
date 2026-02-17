<main id="main" class="page-container">
    <?php if (have_posts()) while (have_posts()) : the_post();
        $isin = get_field('fund_isin');

        get_template_part('templates/components/fund-savings-header');
        get_template_part('templates/components/fund-savings-details');
        get_template_part('templates/components/fund-manager');

        if (have_rows('fund_components')) {
            while (have_rows('fund_components')) {
                the_row();
                if (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                }
            }
        }

    endwhile;

    if ($isin) {
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
                "isin": "' . $isin . '",
                "name": "Tuleva Savings Fund",
                "managementFeeRate": 0.0019,
                "pillar": null,
                "ongoingChargesFigure": 0.0029,
                "nav": 1.0000,
                "volume": 0
              }
            ]';
        }
        $funds = json_decode($json, true);
        if (!empty($funds)) {
            $fund_index = array_search($isin, array_column($funds, 'isin'));
            if ($fund_index !== false) {
                ?>
                <script type="text/javascript">
                    $(function () {
                        $('#savings-fund-volume').html('<?php echo number_format($funds[$fund_index]['volume'], 0, '.', ' ') ?>');
                        $('#savings-fund-nav').html('<?php echo $funds[$fund_index]['nav'] ?>');
                    });
                </script>
                <?php
            }
        }
    }
    ?>
</main>
