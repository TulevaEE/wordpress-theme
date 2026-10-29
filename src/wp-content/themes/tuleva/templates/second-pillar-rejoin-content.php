<?php require_once get_template_directory() . '/helpers/second-pillar-rejoin.php'; ?>
<?php $investor_count_over = tuleva_investor_count_over(ICL_LANGUAGE_CODE); ?>
<main id="main" class="page-container landing-page second-pillar-rejoin-page">

    <section class="hero bg-hero-rejoin section-spacing">
        <div class="container">
            <div class="row align-items-center gy-5 gx-xl-5">
                <div class="col-lg-6 text-center text-lg-start text-navy">
                    <h1 class="mb-4"><?php _e('Save in the <em>II pillar</em> again and win on taxes', TEXT_DOMAIN); ?></h1>
                    <p class="m-0 lead"><?php echo get_second_pillar_rejoin_lead(get_current_time_tallinn()); ?></p>
                    <ul class="rejoin-facts">
                        <li><?php _e('You choose your contribution: 2, 4 or 6%', TEXT_DOMAIN); ?></li>
                        <li><?php _e('4% of your pay comes from social tax', TEXT_DOMAIN); ?></li>
                        <li><?php _e('Your part is free of income tax', TEXT_DOMAIN); ?></li>
                    </ul>
                </div>

                <div class="col-lg-6" id="kalkulaator">
                    <div class="card calculator rounded-4">
                        <div class="card-body p-2">
                            <div class="bg-gray-2 p-3 rounded-3">
                                <div class="align-items-center row">
                                    <label class="col-sm-6 col-lg-5 col-xl-6 col-form-label pe-0" for="calcSalary"><?php _e('Your gross salary', TEXT_DOMAIN); ?></label>
                                    <div class="col-sm-6 col-lg-7 col-xl-6">
                                        <div class="input-group input-group-lg">
                                            <input class="form-control text-end" type="text" id="calcSalary" value="2000" inputmode="numeric" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center row mt-2" role="radiogroup" aria-labelledby="calcRateLabel">
                                    <span class="col-sm-6 col-lg-5 col-xl-6 col-form-label pe-0" id="calcRateLabel"><?php _e('Your contribution', TEXT_DOMAIN); ?></span>
                                    <div class="col-sm-6 col-lg-7 col-xl-6">
                                        <div class="btn-group w-100 rejoin-rate">
                                            <?php foreach ([2, 4, 6] as $rate) { ?>
                                                <input type="radio" class="btn-check" name="calcRate" id="calcRate<?php echo $rate; ?>" value="<?php echo $rate; ?>" autocomplete="off"<?php echo $rate === 6 ? ' checked' : ''; ?>>
                                                <label class="btn btn-outline-primary" for="calcRate<?php echo $rate; ?>"><?php echo $rate; ?>%</label>
                                            <?php } ?>
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
                                    <span class="rejoin-sum__label"><?php _e('From your taxes', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" id="taxWinInfo" role="button" tabindex="0"
                                              aria-label="<?php echo esc_attr__('What this amount is made of', TEXT_DOMAIN); ?>"
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
                                <a href="<?php echo get_app_url('/2nd-pillar-rejoin'); ?>" class="btn btn-primary btn-lg w-100 mt-4"><?php _e('Start saving again', TEXT_DOMAIN); ?></a>
                            </div>
                        </div>
                    </div>
                    <p class="text-secondary text-center mt-3 mb-0"><?php printf(
                        __('Social tax paid into the II&nbsp;pillar %s.', TEXT_DOMAIN),
                        '<a class="text-reset" href="' . esc_url(__('https://tuleva.ee/en/sotsmaks', TEXT_DOMAIN)) . '">' . __('slightly reduces your I&nbsp;pillar', TEXT_DOMAIN) . '</a>'
                    ); ?></p>
                </div>

            </div>
        </div>
    </section>

    <section class="section-spacing rejoin-after-hero" id="valik">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-11 col-xl-10 text-center">
                    <h2 class="mb-5"><?php _e('Take it home as pay or let it grow in the II pillar?', TEXT_DOMAIN); ?></h2>
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-8">
                            <svg id="rejoinFigure" class="w-100 h-auto" viewBox="70 170 660 430" aria-hidden="true">
                                <g id="rejoinFigureSource" display="none">
                                    <g fill="none" stroke="#002F63" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="356" y="28" width="100" height="58" rx="5" fill="#FFFFFF"/>
                                        <rect x="350" y="22" width="100" height="58" rx="5" fill="#FFFFFF"/>
                                        <path id="rejoinFigureTakeHomeArrow" d="M340 51 C240 51 220 150 220 322.4"/>
                                        <path id="rejoinFigureTakeHomeArrowHead" d="M208 310.4 L220 324.4 L232 310.4"/>
                                        <path d="M460 51 C550 51 580 110 580 172"/>
                                        <path d="M568 160 L580 174 L592 160"/>
                                    </g>
                                    <rect x="134" y="196" width="176" height="34" style="fill: var(--gray-bg-1)"/>
                                    <g text-anchor="middle" font-weight="700" fill="#002F63">
                                        <text x="400" y="63" font-size="32">€</text>
                                        <text x="400" y="128" font-size="28"><?php printf(esc_html__('%s of gross pay', TEXT_DOMAIN), '<tspan id="rejoinFigureContribution">120&nbsp;€</tspan>'); ?></text>
                                        <text x="222" y="221" font-size="22" font-weight="400" fill="#6B7074"><?php printf(esc_html__('−%s income tax', TEXT_DOMAIN), '<tspan id="rejoinFigureIncomeTax">26&nbsp;€</tspan>'); ?></text>
                                    </g>
                                </g>
                                <rect id="rejoinFigureTakeHomeBar" x="110" y="338.4" width="220" height="131.6" rx="6" fill="#C5DDF4"/>
                                <rect id="rejoinFigureTakeHomeBarEdge" x="110" y="458" width="220" height="12" fill="#C5DDF4"/>
                                <g id="rejoinFigureTaxWinInfo" class="rejoin-figure__tax-win" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="">
                                    <rect id="rejoinFigureTaxWinBar" x="470" y="190" width="220" height="148.4" rx="6" fill="#008300"/>
                                    <rect id="rejoinFigureTaxWinBarEdge" x="470" y="326.4" width="220" height="12" fill="#008300"/>
                                </g>
                                <rect id="rejoinFigurePillarBaseBar" x="470" y="338.4" width="220" height="131.6" fill="#C5DDF4"/>
                                <g stroke="#002F63" stroke-width="3" stroke-linecap="round">
                                    <line x1="90" y1="470" x2="350" y2="470"/>
                                    <line x1="450" y1="470" x2="710" y2="470"/>
                                </g>
                                <line x1="400" y1="190" x2="400" y2="590" stroke="#ADB5BD" stroke-width="2" stroke-dasharray="6 6"/>
                                <circle cx="400" cy="404" r="30" fill="#FFFFFF" stroke="#ADB5BD" stroke-width="2"/>
                                <g text-anchor="middle" font-weight="700" fill="#002F63">
                                    <text id="rejoinFigureTakeHomeLabel" x="220" y="413" font-size="26"><tspan id="rejoinFigureTakeHome">94&nbsp;€</tspan></text>
                                    <g id="rejoinFigureTaxWinLabel" transform="translate(0 264.2)" fill="#FFFFFF" pointer-events="none">
                                        <text x="580" y="-12" font-size="22"><?php esc_html_e('From your taxes', TEXT_DOMAIN); ?></text>
                                        <text x="580" y="20" font-size="26"><tspan id="rejoinFigureTaxWin">106&nbsp;€</tspan></text>
                                    </g>
                                    <text id="rejoinFigurePillarBaseLabel" x="580" y="413" font-size="26"><tspan id="rejoinFigurePillarBase">94&nbsp;€</tspan></text>
                                    <text x="400" y="412" font-size="22"><?php esc_html_e('or', TEXT_DOMAIN); ?></text>
                                    <text x="220" y="526" font-size="40"><?php printf(esc_html__('%s a month', TEXT_DOMAIN), '<tspan id="rejoinFigureTakeHomeTotal">94&nbsp;€</tspan>'); ?></text>
                                    <text x="220" y="566" font-size="26"><?php esc_html_e('to your bank account', TEXT_DOMAIN); ?></text>
                                    <text x="580" y="526" font-size="40"><?php printf(esc_html__('%s a month', TEXT_DOMAIN), '<tspan id="rejoinFigurePillarTotal">200&nbsp;€</tspan>'); ?></text>
                                    <text x="580" y="566" font-size="26"><?php esc_html_e('to your II pillar account', TEXT_DOMAIN); ?></text>
                                </g>
                            </svg>
                            <p class="text-secondary mt-3 mb-0"><?php printf(esc_html__('For a gross salary of %1$s and a %2$s contribution', TEXT_DOMAIN), '<span id="rejoinFigureSalary">2000&nbsp;€</span>', '<span id="rejoinFigureRate">6%</span>'); ?>.<span class="d-block"><?php printf(
                                __('The social tax part %s.', TEXT_DOMAIN),
                                '<a class="text-reset text-nowrap" href="' . esc_url(__('https://tuleva.ee/en/sotsmaks', TEXT_DOMAIN)) . '">' . __('slightly reduces your I&nbsp;pillar', TEXT_DOMAIN) . '</a>'
                            ); ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing bg-gray-1 savings-fund-landing-page" id="miks">
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
                            <p><?php _e('Your contribution goes into the II pillar automatically, straight from your salary. You don\'t have to do a thing.', TEXT_DOMAIN); ?></p>
                        </article>
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            </div>
                            <h3><?php _e('You get more from taxes', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('More than twice as much goes into your II pillar as comes off your pay.', TEXT_DOMAIN); ?></p>
                        </article>
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <h3><?php _e('The money is yours', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Your II pillar assets are truly yours and can be inherited. You can take the money out from the age of 60.', TEXT_DOMAIN); ?></p>
                        </article>
                    </div>
                    <p class="mt-4 mb-0"><a href="https://tuleva.ee/soovitused/ii-sambas-uuesti-koguma/"><?php _e('Read more on the blog about who can rejoin and why it pays to start saving again →', TEXT_DOMAIN); ?></a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- The sections below use the savings fund landing page's components (who-cards, emphasis box) -->
    <div class="savings-fund-landing-page">

    <section class="section-spacing" id="kuidas">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-11 col-xl-10 text-center">
                    <h2><?php _e('How do I start saving in the II pillar again?', TEXT_DOMAIN); ?></h2>
                    <ol class="rejoin-steps list-unstyled mt-5 mb-0">
                        <li>
                            <span class="rejoin-steps__number" aria-hidden="true">1</span>
                            <h3><?php _e('Log in to Tuleva', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('You don\'t need to be a Tuleva saver.', TEXT_DOMAIN); ?></p>
                        </li>
                        <li>
                            <span class="rejoin-steps__number" aria-hidden="true">2</span>
                            <h3><?php _ex('Review', 'Step in the II pillar rejoin instructions', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Your contribution size and fund choice.', TEXT_DOMAIN); ?></p>
                        </li>
                        <li>
                            <span class="rejoin-steps__number" aria-hidden="true">3</span>
                            <h3><?php _e('Sign', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('All the applications with one signature.', TEXT_DOMAIN); ?></p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom" id="alusta">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <div class="emphasis-box p-4 p-md-5 text-center text-navy">
                        <h2><?php _e('Keep saving in the II pillar', TEXT_DOMAIN); ?></h2>
                        <p class="lead mx-auto"><?php echo get_second_pillar_rejoin_closing_lead(get_current_time_tallinn()); ?></p>
                        <p class="m-0 mt-4 pt-2"><a href="<?php echo get_app_url('/2nd-pillar-rejoin'); ?>" class="btn btn-lg d-block d-md-inline-block m-0 btn-primary"><?php _e('Start saving again', TEXT_DOMAIN); ?></a></p>
                        <ul class="ts-trust">
                            <?php if ($investor_count_over) { ?>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span><?php printf(__('Over <strong>%s</strong> people save with Tuleva', TEXT_DOMAIN), $investor_count_over); ?></span></li>
                            <?php } ?>
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
                        <div class="qa__question-wrapper" id="millal-saan-uuesti-liituda">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-7"><?php _e('I took money out of the II pillar after 2021. When can I rejoin?', TEXT_DOMAIN); ?></a>
                            <div id="answer-7" class="collapse">
                                <p><?php _e('You can start saving again five years after you submitted your withdrawal application. You can apply before then. If you apply by the end of November, contributions start in May; by the end of March, in September; and by the end of July, in January.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kas-saan-hiljem-liituda">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-9"><?php _e('If I don\'t want to apply now, can I do it later?', TEXT_DOMAIN); ?></a>
                            <div id="answer-9" class="collapse">
                                <p><?php _e('Yes. You can apply at any time and there is no final deadline. Contributions start three times a year, depending on when you apply:', TEXT_DOMAIN); ?></p>
                                <ul>
                                    <li><?php _e('applied by 31 March: in September', TEXT_DOMAIN); ?></li>
                                    <li><?php _e('applied by 31 July: in January', TEXT_DOMAIN); ?></li>
                                    <li><?php _e('applied by 30 November: in May', TEXT_DOMAIN); ?></li>
                                </ul>
                                <p><?php _e('The sooner you start, the longer you gain from the tax win.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="ei-maleta-millal-raha-valja-votsin">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-8"><?php _e('I don\'t remember when I took money out of the II pillar. Where can I check?', TEXT_DOMAIN); ?></a>
                            <div id="answer-8" class="collapse">
                                <p><?php printf(
                                    __('%1$s and look at the Your choices box. The II pillar row shows whether you can already rejoin or from which date you can. You can also check it in the %2$s.', TEXT_DOMAIN),
                                    '<a href="' . get_app_url('/') . '">' . __('Log in to Tuleva', TEXT_DOMAIN) . '</a>',
                                    '<a href="' . esc_url(__('https://public.pensionikeskus.ee/public/login.do?locale=en', TEXT_DOMAIN)) . '">' . __('Pensionikeskus self-service', TEXT_DOMAIN) . '</a>'
                                ); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kuidas-raha-katte-saab">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-1"><?php _e('How can I take money out of the II pillar later?', TEXT_DOMAIN); ?></a>
                            <div id="answer-1" class="collapse">
                                <p><?php printf(__('From the age of 60 you can take the money out all at once or %s. If something happens to you, your II pillar assets go to your heirs.', TEXT_DOMAIN), '<a href="' . esc_url(__('https://tuleva.ee/pensioni-valjamaksed/', TEXT_DOMAIN)) . '">' . __('in monthly payments free of income tax', TEXT_DOMAIN) . '</a>'); ?></p>
                                <p><?php _e('You cannot take the money out a second time before then. The only exception is a loss of capacity for work. After rejoining you also can\'t stop contributions again. Most people are better off saving at least until they stop working, so these restrictions do not affect most of them.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="moju-i-sambale">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-2"><?php _e('How does the II pillar affect my I pillar pension?', TEXT_DOMAIN); ?></a>
                            <div id="answer-2" class="collapse">
                                <p><?php printf(__('The state\'s 4%% comes from your social tax, which would otherwise go to the I pillar. That\'s why your I pillar pension will later be slightly smaller. Overall, saving in the II pillar leaves you ahead. %s', TEXT_DOMAIN), '<a href="' . esc_url(__('https://tuleva.ee/en/analysis/which-is-more-beneficial-pillar-i-or-pillar-ii/', TEXT_DOMAIN)) . '">' . __('See the calculation →', TEXT_DOMAIN) . '</a>'); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kas-pean-olema-tuleva-koguja">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-3"><?php _e('Do I need to be a Tuleva saver?', TEXT_DOMAIN); ?></a>
                            <div id="answer-3" class="collapse">
                                <p><?php _e('No. Log in to Tuleva and submit the application. If you choose a Tuleva fund, you will be one of us.', TEXT_DOMAIN); ?><?php if ($investor_count_over) { echo ' '; printf(__('There are already over %s of us Tuleva savers.', TEXT_DOMAIN), $investor_count_over); } ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="milline-fond-valida">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-4"><?php _e('Which II pillar fund should I choose?', TEXT_DOMAIN); ?></a>
                            <div id="answer-4" class="collapse">
                                <p><?php _e('Look at two things: where the fund invests and how much it charges. An index fund that invests in global equities spreads your money across thousands of companies around the world.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Today you can save with an annual fee below 0.3%. If a fund\'s ongoing charges are above 0.5% a year, you are paying too much.', TEXT_DOMAIN); ?></p>
                                <p><?php printf(__('When you rejoin, you can use the money from the age of 60, so you have time to ride out market swings. That\'s why a low-fee index fund is worth considering. %s', TEXT_DOMAIN), '<a href="' . esc_url(__('https://tuleva.ee/vaata/pensionifondide-vordlus/', TEXT_DOMAIN)) . '">' . __('Compare all II pillar funds →', TEXT_DOMAIN) . '</a>'); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kuhu-lahevad-sissemaksed">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-5"><?php _e('Where do my contributions go if I don\'t choose a fund?', TEXT_DOMAIN); ?></a>
                            <div id="answer-5" class="collapse">
                                <p><?php _e('To the fund where you saved before you took your money out. Banks have renamed many funds over the past five years, so you may not recognise your old fund by its name.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('That\'s why it\'s worth reviewing your fund along with your application. At Tuleva you can choose your fund at the same time as you apply to rejoin the II pillar.', TEXT_DOMAIN); ?></p>
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
                        <a class="resource resource--post" href="https://tuleva.ee/analuusid/miks-tahaksid-paljud-ii-sambast-lahkunud-hakata-uuesti-koguma/">
                            <span class="post-title"><?php _e('Why would many who left the II pillar like to start saving again?', TEXT_DOMAIN); ?></span>
                            <span class="post-teaser"><?php _e('We asked savers who left the II pillar what they think about rejoining.', TEXT_DOMAIN); ?></span>
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
