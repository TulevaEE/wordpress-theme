<main id="main" class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post();

        get_template_part('templates/components/fund-third-header');
        get_template_part('templates/components/fund-third-details');
        get_template_part('templates/components/fund-manager');

        if (have_rows('page_components')) {
            while (have_rows('page_components')) { the_row();
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
            "isin": "EE3600001707",
            "name": "Tuleva III Pillar Pension Fund",
            "managementFeeRate": 0.0021,
            "pillar": 3,
            "ongoingChargesFigure": 0.0031,
            "nav": 1.1636,
            "volume": 356619411.5331
          }
        ]';
    }
    $funds = json_decode($json, true);
    if (!empty($funds)) {
        $third = array_search('EE3600001707', array_column($funds, 'isin'));
        if ($third !== false) {
    ?>

    <script type="text/javascript">
        $(function () {
            $('#third-fund-volume').html('<?php echo number_format($funds[$third]['volume'], 0, '.', ' ') ?>');
            $('#third-fund-nav').html('<?php echo $funds[$third]['nav'] ?>');
        });
    </script>
    <?php
        }
    } ?>
</main>
