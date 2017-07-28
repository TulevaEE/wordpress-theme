<?php //if (isset($_GET['signup'])) { ?>
    <div id="inline-signup-anchor"></div>

<div class="container row-spacing-half">
    <div class="row text-center row-spacing-bottom-quarter">
        <h1><?php _e('Join Tuleva', TEXT_DOMAIN) ?></h1>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="inline-signup__item">
                <span class="inline-signup__number">1</span><span class="inline-signup__title"><?php _e('Enter your details and send membership application', TEXT_DOMAIN) ?></span>
            </div>
            <p class="inline-signup__content">
                <?php _e('Tuleva member can be anybody of legal age who does not have current criminal record. <a href="#rights-and-obligations">What are my rights and obligations as a Tuleva member?</a>', TEXT_DOMAIN) ?>
            </p>
            <div class="inline-signup__item">
                <span class="inline-signup__number">2</span><span class="inline-signup__title"><?php _e('Pay one-off joining fee <span class="text-highlight">100 euros</span>', TEXT_DOMAIN) ?></span>
            </div>
            <p class="inline-signup__content">
                <?php _e('This is the contribution of all Tuleva members to our joint entreprise.<br /><a href="#membership-fee-usage">What we do with joining fees?</a>', TEXT_DOMAIN) ?>
            </p>
            <div class="inline-signup__item">
                <span class="inline-signup__number">3</span><span class="inline-signup__title"><?php _e('Choose Tuleva pension fund', TEXT_DOMAIN) ?></span>
            </div>
            <p class="inline-signup__content">
                <?php _e('If your II pillar is not already in Tuleva pension fund, you can comfortably do it here.', TEXT_DOMAIN) ?>
            </p>
        </div>
        <div class="col-md-5">
                <div id="inline-signup" class="inline-signup well well-xl"></div>
            <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/polyfills.15825c54.js"></script>
                <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/signup.d49a1ff9.js"></script>
                <!--                    <script type="text/javascript" src="http://localhost:3000/static/js/signup.js"></script>-->
        </div>
    </div>
</div>
<?php //} ?>
