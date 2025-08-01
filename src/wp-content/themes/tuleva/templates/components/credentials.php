<?php
$members_count = get_field('members_count', 'option');
$members_count_description = get_sub_field('members_count_description');
$security_text = get_sub_field('security_text');
$security_link_text = get_sub_field('security_link_text');
$security_link_url = get_sub_field('security_link_url');
?>
<section id="<?php the_sub_field('component_id'); ?>" class="section-credentials bg-gray-2 py-4 py-lg-5">
    <!-- Credentials -->
    <?php if ($security_text || ($members_count && $members_count_description)) { ?>
        <div class="container">
            <div class="row gx-xl-5">
                <?php if ($security_text) { ?>
                    <div class="col-md-6 d-flex align-items-center justify-content-center text-navy">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-shield-check me-3 me-sm-4" viewBox="0 0 16 16">
                            <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                            <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                        </svg>
                        <div class="d-flex flex-column justify-content-center">
                            <p class="m-0"><?php echo $security_text; ?></p>
                            <?php if ($security_link_url && $security_link_text) { ?>
                                <p class="m-0">
                                    <a id="security" href="<?php echo $security_link_url; ?>"
                                    ><?php echo $security_link_text; ?></a>
                                </p>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($members_count && $members_count_description) { ?>
                    <div class="col-md-6 d-none d-md-flex align-items-center text-navy">
                        <span class="membercount text-nowrap me-3 me-sm-4 display-5 lh-1">
                            <?php echo number_format($members_count, 0, '', ' '); ?>
                        </span>
                        <span style="max-width: 11em"><?php echo $members_count_description; ?></span>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</section>
