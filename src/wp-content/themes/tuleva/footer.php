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
                <h4 class="footer__title footer__title--light"><?php _e('I’d like to get updates about Tuleva', TEXT_DOMAIN); ?></h4>
                <div class="footer__subscribe">
                    <?php get_template_part('templates/footer/subscribe-form'); ?>
                </div>
                <div class="footer__social">
                    <?php get_template_part('templates/footer/social'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 footer__text">
                <?php get_template_part('templates/footer/text'); ?>
            </div>
        </div>
    </div>
</footer>

<?php get_template_part('templates/footer/beacon-toggle'); ?>

<?php wp_footer(); ?>

<?php get_template_part('templates/footer/beacon-translations'); ?>

<!-- Google Tag Manager -->
<script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push(
        {'gtm.start': new Date().getTime(),event:'gtm.js'}
    );var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MRRG43');
</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MRRG43" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-76855836-1', 'auto');
  ga('send', 'pageview');
</script>

</body>
</html>
