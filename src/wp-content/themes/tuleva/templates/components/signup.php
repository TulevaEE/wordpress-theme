<?php //if (isset($_GET['signup'])) { ?>
    <div id="inline-signup-anchor"></div>

<div class="container row-spacing-half">
    <div class="row text-center row-spacing-bottom-quarter">
        <h1><?php _e('Liitu Tulevaga') ?></h1>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="inline-signup__item">
                <span class="inline-signup__number">1</span><span class="inline-signup__title"><?php _e('Sisesta oma andmed ja saada liitumisavaldus') ?></span>
            </div>
            <p class="inline-signup__content">
                <?php _e('Tuleva liikmeks võib saada iga täisealine inimene, kellel pole kehtivat kriminaalkaristust.
                <a target="_blank" href="https://drive.google.com/open?id=0BxDN-jvgOSUxd1J5LXVKWDlDa1U">Mis on minu õigused ja kohustused Tuleva liikmena?</a>') ?>
            </p>
            <div class="inline-signup__item">
                <span class="inline-signup__number">2</span><span class="inline-signup__title"><?php _e('Maksa ühekordne liitumistasu <span class="text-highlight">100 eurot</span>') ?></span>
            </div>
            <p class="inline-signup__content">
                <?php _e('See on kõigi Tuleva liikmete panus meie ühise ettevõtte arendusse.<br />
                <a href="#membership-fee-usage">Milleks kasutame liitumistasusid?</a>') ?>
            </p>
            <div class="inline-signup__item">
                <span class="inline-signup__number">3</span><span class="inline-signup__title"><?php _e('Suuna pension Tulevasse') ?></span>
            </div>
            <p class="inline-signup__content">
                <?php _e('Kui sinu II sammas pole juba Tuleva pensionifondi suunatud, saad seda kohe mugavalt teha.') ?>
            </p>
        </div>
        <div class="col-md-5">
                <div id="inline-signup" class="inline-signup well well-xl"></div>
                <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/signup.fbfcfd48.js"></script>
                <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/polyfills.8b285ffa.js"></script>
                <!--                    <script type="text/javascript" src="http://localhost:3000/static/js/signup.js"></script>-->
        </div>
    </div>
</div>
<?php //} ?>
