<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post();

        get_template_part('templates/components/fund-bonds-header');
        get_template_part('templates/components/fund-bonds-details');
        get_template_part('templates/components/fund-manager');

        if (have_rows('fund_components')) {
            while (have_rows('fund_components')) { the_row();
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
            "isin": "EE3600109443",
            "name": "Tuleva World Bonds Pension Fund",
            "managementFeeRate": 0.002,
            "pillar": 2,
            "ongoingChargesFigure": 0.0031,
            "nav": 0.61602,
            "volume": 12448203.1277
          }
        ]';
    }
    $funds = json_decode($json, true);
    $bond = array_search('EE3600109443', array_column($funds, 'isin'));
    ?>

    <script type="text/javascript">
        $(function () {
            $('#bond-fund-volume').html('<?php echo number_format($funds[$bond]['volume'], 0, '.', ' ') ?>');
            $('#bond-fund-nav').html('<?php echo $funds[$bond]['nav'] ?>');
        });
    </script>
</div>
