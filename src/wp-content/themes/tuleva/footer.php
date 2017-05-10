<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3 footer__column">
                <h4 class="footer__title"><?php _e('Membership fee account', TEXT_DOMAIN); ?></h4>
                <div class="footer__column__text">
                    <div class="footer__column__text__row">Tulundusühistu Tuleva</div>
                    <div class="footer__column__text__row">EE132200221064032136</div>
                    <div class="footer__column__text__row">Swedbank</div>
                </div>
            </div>
            <div class="col-md-3 footer__column">
                <h4 class="footer__title">Tulundusühistu Tuleva</h4>
                <div class="footer__column__text">
                    <div class="footer__column__text__row">Telliskivi 60, Tallinn, 10412</div>
                    <div class="footer__column__text__row">+372 644 5100</div>
                    <div class="footer__column__text__row"><?php _e('Reg. Code', TEXT_DOMAIN); ?>: 14041764</div>
                </div>
            </div>
            <div class="col-md-3 ">
                <h4 class="footer__title">Tuleva Fondid AS</h4>
                <div class="footer__column__text">
                    <div class="footer__column__text__row">Telliskivi 60, Tallinn, 10412</div>
                    <div class="footer__column__text__row">+372 644 5100</div>
                    <div class="footer__column__text__row"><?php _e('Activity license nr', TEXT_DOMAIN); ?>: 4.1-1/25</div>
                </div>
            </div>
            <div class="col-md-3 footer__column">
                <div class="footer__social">
                    <a href="https://www.facebook.com/Tuleva-1681747298756866/">
                        <img src="<?php echo get_template_directory_uri() ?>/img/icon-facebook.svg" alt="Facebook">
                    </a>
                    <a href="https://twitter.com/@TulevaEesti">
                        <img src="<?php echo get_template_directory_uri() ?>/img/icon-twitter.svg" alt="Twitter">
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 footer__text">
                <?php
                    $page = get_page_by_path('fondid', OBJECT, 'page');
                    $page_id = icl_object_id($page->ID, 'page', false);
                    $page_url = get_permalink($page_id);
                ?>
                <p><?php printf(__('Before you decide which pension fund you’d like to move your second-pillar units and future contributions to, be sure to read through the important information. The terms of our pension funds can be found %1$shere%2$s. Before you decide which pension fund you’d like to move your second-pillar units and future contributions to, be sure to read through the important information. The terms of our pension funds can be found <a href="hhttp://www.pensionikeskus.ee/en/ii-pillar/funds/fund-fees-comparison/">here.</a> For comparison, information on all Estonian pension funds is available at the Pension Centre. Please note that Tuleva cooperative, Tuleva management company and Tuleva employees do not dispense investment advice, and cannot do so, because we have not sought an activity licence in the field of investment advice. However, we do our best to provide you with truthful, comprehensible information that you can use to make up your own mind. If you have any questions, don’t hesitate to contact us, or consult impartial experts via the Pension Centre’s <a href="http://www.pensionikeskus.ee/en/">information line</a> or the Financial Supervision Authority’s <a href="http://minuraha.ee/kontaktandmed/">money matters page</a>.', TEXT_DOMAIN), '<a href="'.$page_url.'">', '</a>'); ?></p>
                <p><?php _e('Tuleva website <a href="https://tuleva.ee/wp-content/uploads/2017/03/Kodulehekasutajatingimused.pdf">terms of service</a>.', TEXT_DOMAIN); ?></p>
            </div>
        </div>
    </div>
</footer>

<a href="#" class="beacon-toggle">
    <span class="beacon-toggle__icon"></span>
    <span class="beacon-toggle__text"><?php _e('Have a question?', TEXT_DOMAIN); ?></span>
</a>

<?php wp_footer(); ?>

<?php if (ICL_LANGUAGE_CODE === 'et') { ?>
<script>
    window.HS.beacon.config({
        translation: {
          contactLabel: 'Saada sõnum',
          attachFileLabel: 'Lisa fail',
          attachFileError: 'Suurim lubatud faili suurus on 10mb',
          fileExtensionError: 'Sinu üles laetud failiformaat pole lubatud.',
          emailLabel: 'E-maili aadress',
          emailError: 'Palun sisesta korrektne e-maili aadress',
          messageLabel: 'Kuidas saame sind aidata?',
          messageError: 'Palun sisesta sõnum',
          contactSuccessLabel: 'Sõnum saadetud!',
          contactSuccessDescription: 'Aitäh, et tunned Tuleva tegemiste vastu huvi, võtame sinuga esimesel võimalusel ühendust!',
          sendLabel: 'Saada'
        }
    });
</script>
<?php } ?>

</body>
</html>
