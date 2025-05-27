<?php
$members_count = get_field('members_count', 'option');
$members_count_description = get_sub_field('members_count_description');
$security_text = get_sub_field('security_text');
$security_link_text = get_sub_field('security_link_text');
$security_link_url = get_sub_field('security_link_url');
?>
<section id="<?php the_sub_field('component_id'); ?>">
    <!-- Credentials -->
    <?php if ($security_text || ($members_count && $members_count_description)) { ?>
        <div class="container-fluid bg-blueish-gray py-4">
            <div class="container">
                <div class="row">
                    <?php if ($security_text) { ?>
                        <div class="col col-md-6 d-flex">
                            <img src="<?php echo get_template_directory_uri() ?>/img/icon-lock.svg"
                                 alt="<?php echo $security_text; ?>" class="me-4"/>
                            <div class="d-flex flex-column justify-content-center">
                                <p class="m-0 text-navy"><?php echo $security_text; ?></p>
                                <?php if ($security_link_url && $security_link_text) { ?>
                                    <a id="security" href="<?php echo $security_link_url; ?>"
                                       ><?php echo $security_link_text; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($members_count && $members_count_description) { ?>
                        <div class="col-md-6 d-none d-md-flex align-items-center">
                            <span class="membercount text-nowrap me-4">
                                <?php echo number_format($members_count, 0, '', ' '); ?>
                            </span>
                            <p class="mb-0 text-navy"><?php echo $members_count_description; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</section>
