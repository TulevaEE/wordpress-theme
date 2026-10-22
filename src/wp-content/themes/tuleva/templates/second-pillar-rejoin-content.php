<main id="main" class="page-container landing-page second-pillar-rejoin-page">

    <section class="hero bg-hero-rejoin section-spacing">
        <div class="container">
            <div class="row align-items-center gy-5 gx-xl-5">
                <div class="col-lg-6 text-center text-lg-start text-navy">
                    <span class="eyebrow"><?php _e('Rejoining the II pillar', TEXT_DOMAIN); ?></span>
                    <h1 class="mb-4"><?php _e('Get the <em>tax win</em> again', TEXT_DOMAIN); ?></h1>
                    <p class="m-0 lead"><?php _e('<b>It\'s&nbsp;your&nbsp;money.</b> Direct part of your taxes into your own II&nbsp;pillar&nbsp;account. Apply by <b>30&nbsp;November</b> and saving starts in&nbsp;May.', TEXT_DOMAIN); ?></p>
                </div>

                <div class="col-lg-6" id="kalkulaator">
                    <div class="card calculator rounded-4">
                        <div class="card-body p-2">
                            <div class="bg-gray-2 p-3 rounded-3">
                                <div class="align-items-center row">
                                    <label class="col-sm-6 col-lg-5 col-xl-6 col-form-label pe-0" for="calcSalary"><?php _e('Gross salary', TEXT_DOMAIN); ?></label>
                                    <div class="col-sm-6 col-lg-7 col-xl-6">
                                        <div class="input-group input-group-lg">
                                            <input class="form-control text-end" type="text" id="calcSalary" value="2000" inputmode="numeric" autocomplete="off">
                                            <span class="input-group-text">&euro;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-3 pt-4 pb-3 rejoin-sum" role="status" aria-live="polite">
                                <div class="rejoin-sum__row">
                                    <span class="rejoin-sum__operator" aria-hidden="true"></span>
                                    <span class="rejoin-sum__label"><?php _e('From your net salary', TEXT_DOMAIN); ?></span>
                                    <span class="rejoin-sum__amount" id="resNetSalary">94&nbsp;&euro;</span>
                                </div>
                                <div class="rejoin-sum__row rejoin-sum__row--tax-win">
                                    <span class="rejoin-sum__operator" aria-hidden="true">+</span>
                                    <span class="rejoin-sum__label"><?php _e('Tax win', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" id="taxWinInfo" role="button" tabindex="0"
                                              aria-label="<?php echo esc_attr__('How the tax win is made up', TEXT_DOMAIN); ?>"
                                              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
                                              data-income-tax-label="<?php echo esc_attr__('From income tax', TEXT_DOMAIN); ?>"
                                              data-social-tax-label="<?php echo esc_attr__('From social tax', TEXT_DOMAIN); ?>"
                                              title=""></span>
                                    </span>
                                    <span class="rejoin-sum__amount" id="resTaxWin">106&nbsp;&euro;</span>
                                </div>
                                <div class="rejoin-sum__row rejoin-sum__row--total">
                                    <span class="rejoin-sum__operator" aria-hidden="true">=</span>
                                    <span class="rejoin-sum__label"><?php _e('Into your II pillar each month', TEXT_DOMAIN); ?></span>
                                    <span class="rejoin-sum__amount" id="resTotal">200&nbsp;&euro;</span>
                                </div>
                                <div class="rejoin-sum__ten-years">
                                    <span><?php _e('Saved in 10 years', TEXT_DOMAIN); ?></span>
                                    <span class="fw-medium" id="resTenYears">24&nbsp;000&nbsp;&euro;</span>
                                </div>
                                <a href="<?php echo get_app_url('/2nd-pillar-rejoin'); ?>" class="btn btn-primary btn-lg w-100 mt-4"><?php _e('Start saving again', TEXT_DOMAIN); ?></a>
                            </div>
                        </div>
                    </div>
                    <p class="text-secondary text-center mt-3 mb-0"><?php _e('The calculation assumes the highest possible contribution. Social tax paid into the II pillar also has a small effect on your I pillar.', TEXT_DOMAIN); ?></p>
                </div>

            </div>
        </div>
    </section>

    <!-- The sections below use the savings fund landing page's components (who-cards, emphasis box) -->
    <div class="savings-fund-landing-page">

    <section class="section-spacing bg-gray-1" id="miks">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-11 col-xl-10 text-center">
                    <h2 class="mb-5"><?php _e('Why start saving again?', TEXT_DOMAIN); ?></h2>
                    <div class="who">
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            </div>
                            <h3><?php _e('The easiest way to save', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Your contribution goes into the II pillar straight from your salary and grows automatically along with it. You save every month without noticing.', TEXT_DOMAIN); ?></p>
                        </article>
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            </div>
                            <h3><?php _e('You win on taxes', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('The state adds 4% from your social tax and takes no income tax on the part of your salary you direct into the II pillar.', TEXT_DOMAIN); ?></p>
                        </article>
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <h3><?php _e('The money is yours', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Your II pillar assets are truly yours and can be inherited. From the age of 60 you can take them out all at once or monthly <b>free of income tax</b>.', TEXT_DOMAIN); ?></p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="kuidas">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="mb-4 text-center"><?php _e('How to start', TEXT_DOMAIN); ?></h2>
                    <div class="inline-register__item">
                        <span class="inline-register__number">1</span><span class="inline-register__title"><?php _e('Log in to Tuleva', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('Verify yourself with Smart-ID, mobile-ID, or an ID card. You do not need to be a Tuleva saver.', TEXT_DOMAIN); ?></p>
                    <div class="inline-register__item">
                        <span class="inline-register__number">2</span><span class="inline-register__title"><?php _e('Choose your contribution rate and fund', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('If you do not change your fund, your contributions go where you saved before you took your money out.', TEXT_DOMAIN); ?></p>
                    <div class="inline-register__item">
                        <span class="inline-register__number">3</span><span class="inline-register__title"><?php _e('Sign', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('You submit all the applications at once. We then send you a confirmation by email.', TEXT_DOMAIN); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom" id="alusta">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <div class="emphasis-box p-4 p-md-5 text-center text-navy">
                        <h2><?php _e('Get back to saving', TEXT_DOMAIN); ?></h2>
                        <p class="lead mx-auto"><?php _e('If you took money out of your II pillar in 2021, you can apply now. Do it by <b>30&nbsp;November</b> and your II pillar starts growing again as early as May.', TEXT_DOMAIN); ?></p>
                        <p class="m-0 mt-4 pt-2"><a href="<?php echo get_app_url('/2nd-pillar-rejoin'); ?>" class="btn btn-lg d-block d-md-inline-block m-0 btn-primary"><?php _e('Start saving again', TEXT_DOMAIN); ?></a></p>
                        <ul class="ts-trust">
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span><?php _e('Over <strong>86,000</strong> people save with Tuleva', TEXT_DOMAIN); ?></span></li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span><?php _e('Tuleva <strong>belongs to its savers</strong>', TEXT_DOMAIN); ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom qa-block" id="kkk">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="text-center"><?php _e('Frequently asked questions', TEXT_DOMAIN); ?></h2>
                    <div class="faq-list">
                        <div class="qa__question-wrapper" id="milline-fond-valida">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-1"><?php _e('Which II pillar fund should I choose?', TEXT_DOMAIN); ?></a>
                            <div id="answer-1" class="collapse">
                                <p><?php _e('Look at two things: where the fund invests and how much it charges. An index fund that invests in global equities spreads your money across thousands of companies around the world.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Today you can save with an annual fee below 0.3%. If a fund\'s ongoing charges are above 0.5% a year, you are paying too much.', TEXT_DOMAIN); ?></p>
                                <p><?php printf(__('When you rejoin, you can use the money from the age of 60, so you have time to ride out market swings. That\'s why a low-fee index fund is worth considering. %s', TEXT_DOMAIN), '<a href="' . esc_url(__('https://tuleva.ee/vaata/pensionifondide-vordlus/', TEXT_DOMAIN)) . '">' . __('Compare all II pillar funds →', TEXT_DOMAIN) . '</a>'); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kuhu-lahevad-sissemaksed">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-2"><?php _e('Where do my contributions go if I don\'t choose a fund?', TEXT_DOMAIN); ?></a>
                            <div id="answer-2" class="collapse">
                                <p><?php _e('To the fund where you saved before you took your money out. Banks have renamed many funds over the past five years, so you may not recognise your old fund by its name.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('That\'s why it\'s worth reviewing your fund along with your application. At Tuleva you can choose your fund at the same time as you apply to rejoin the II pillar.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kuidas-raha-katte-saab">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-3"><?php _e('How can I take money out of the II pillar later?', TEXT_DOMAIN); ?></a>
                            <div id="answer-3" class="collapse">
                                <p><?php printf(__('From the age of 60 you can take the money out all at once or %s. If something happens to you, your II pillar assets go to your heirs.', TEXT_DOMAIN), '<a href="' . esc_url(__('https://tuleva.ee/pensioni-valjamaksed/', TEXT_DOMAIN)) . '">' . __('in monthly payments free of income tax', TEXT_DOMAIN) . '</a>'); ?></p>
                                <p><?php _e('You cannot take the money out a second time before then. The only exception is a loss of capacity for work. Most people are better off saving at least until they stop working, so this restriction does not affect most of them.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kui-riik-4-protsenti-ara-votab">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-4"><?php _e('What if the state takes away the 4%?', TEXT_DOMAIN); ?></a>
                            <div id="answer-4" class="collapse">
                                <p><?php _e('Money already paid in cannot be taken back, and your own contribution stays free of income tax even if the state paused its part. The law now also says the state must give at least five years\' notice before reducing the 4%.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Make use of the opportunity while it lasts.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="moju-i-sambale">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-5"><?php _e('How does the II pillar affect my I pillar pension?', TEXT_DOMAIN); ?></a>
                            <div id="answer-5" class="collapse">
                                <p><?php printf(__('The state\'s 4%% comes from your social tax, which would otherwise go to the I pillar. That\'s why your I pillar pension will later be slightly smaller. Overall, saving in the II pillar leaves you ahead. %s', TEXT_DOMAIN), '<a href="' . esc_url(__('https://tuleva.ee/en/analysis/which-is-more-beneficial-pillar-i-or-pillar-ii/', TEXT_DOMAIN)) . '">' . __('See the calculation →', TEXT_DOMAIN) . '</a>'); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kas-pean-olema-tuleva-koguja">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-6"><?php _e('Do I need to be a Tuleva saver?', TEXT_DOMAIN); ?></a>
                            <div id="answer-6" class="collapse">
                                <p><?php _e('No. Log in to Tuleva and submit the application. If you choose a Tuleva fund, you will be one of us. There are already over 86,000 of us Tuleva savers.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom" id="uuri-rohkem">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="mb-0 text-center"><?php _e('Learn more', TEXT_DOMAIN); ?></h2>
                    <div class="resources">
                        <a class="resource resource--post" href="<?php echo esc_url(__('https://tuleva.ee/blogi/', TEXT_DOMAIN)); ?>">
                            <span class="post-title"><?php _e('Is it worth saving in the II pillar again?', TEXT_DOMAIN); ?></span>
                            <span class="post-teaser"><?php _e('Who should rejoin and who might not.', TEXT_DOMAIN); ?></span>
                            <span class="post-more"><?php _e('Read more →', TEXT_DOMAIN); ?></span>
                        </a>
                        <a class="resource resource--post" href="https://tuleva.ee/vaata/pensionifondide-vordlus/">
                            <span class="post-title"><?php _e('II pillar fund comparison', TEXT_DOMAIN); ?></span>
                            <span class="post-teaser"><?php _e('All funds in one table: returns and fees.', TEXT_DOMAIN); ?></span>
                            <span class="post-more"><?php _e('Read more →', TEXT_DOMAIN); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </div>

</main>
