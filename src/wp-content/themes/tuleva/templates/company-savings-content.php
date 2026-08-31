<main id="main" class="page-container landing-page savings-fund-landing-page company-savings-page">

    <section class="hero">
        <div class="container">
            <div class="row align-items-center gy-5 gx-xl-5">
                <div class="col-lg-6 text-center text-lg-start text-navy">
                    <span class="eyebrow"><?php _e('Additional Investment Fund', TEXT_DOMAIN); ?><span class="d-none d-sm-inline"> <?php _e('for your company', TEXT_DOMAIN); ?></span></span>
                    <h1 class="mb-4"><?php _e('Now your company can save in Tuleva too', TEXT_DOMAIN); ?></h1>
                    <p class="m-0 lead"><?php _e('Put your company\'s idle cash to work.', TEXT_DOMAIN); ?></p>
                </div>

                <div class="col-lg-6" id="kalkulaator">
                    <div class="card calculator rounded-4">
                        <div class="card-body p-2">
                            <div class="bg-gray-2 p-3 rounded-3">
                                <div class="mb-3 align-items-center row">
                                    <label class="col-sm-6 col-form-label pe-0" for="calcAmount"><?php _e('Company\'s idle cash', TEXT_DOMAIN); ?></label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input class="form-control text-end" type="number" id="calcAmount" min="0" max="1000000" step="100" value="5000" inputmode="numeric">
                                            <span class="input-group-text">&euro;</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 align-items-center row">
                                    <label class="col-sm-6 col-form-label pe-0" for="calcMonthly"><?php _e('I add every month', TEXT_DOMAIN); ?></label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input class="form-control text-end" type="number" id="calcMonthly" min="0" max="100000" step="10" value="200" inputmode="numeric">
                                            <span class="input-group-text"><?php _e('&euro;/month', TEXT_DOMAIN); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0 row">
                                    <label for="calcRate" class="col-sm-6 col-form-label py-2 pe-0"><?php _e('Expected yearly return', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" role="button" tabindex="0" aria-label="<?php echo esc_attr__('More information', TEXT_DOMAIN); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo esc_attr__('The final amount depends on the returns that actually materialize, and neither we nor anyone else can guarantee a return.', TEXT_DOMAIN); ?>"></span></label>
                                    <div class="col-sm-6 return-rate">
                                        <input type="range" class="form-range" id="calcRate" min="-10" max="10" step="1" value="0" data-unit="%">
                                        <span class="custom-tooltip">0%</span>
                                        <button type="button" class="historic-return-rate small text-secondary border-0 bg-transparent"><?php _e('historic return of stocks 7%', TEXT_DOMAIN); ?></button>
                                    </div>
                                </div>
                            </div>

                            <div class="px-3 pt-4 pb-3">
                                <div class="d-flex justify-content-between align-items-center align-items-sm-baseline calc-result">
                                    <span><?php _e('Your company has in 20 years', TEXT_DOMAIN); ?></span>
                                    <span class="calc-sum" id="resTotal" role="status" aria-live="polite">53&nbsp;000&nbsp;&euro;</span>
                                </div>
                                <a href="<?php echo get_app_url('/savings-fund/onboarding'); ?>" class="btn btn-primary btn-lg w-100 mt-3"><?php _e('Open a company account', TEXT_DOMAIN); ?></a>
                                <p class="calc-fine mt-3 mb-0 text-center"><?php _e('Opening an account is free and takes only a few minutes.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                    </div>
                    <p class="calc-note"><?php _e('Share prices can rise and fall over time. Returns are not guaranteed.', TEXT_DOMAIN); ?></p>
                </div>

            </div>
        </div>
    </section>

    <section class="section-spacing bg-gray-1" id="miks">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-11 col-xl-10 text-center">
                    <h2 class="mb-5"><?php _e('Why invest through Tuleva?', TEXT_DOMAIN); ?></h2>
                    <div class="who">
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                            </div>
                            <h3><?php _e('A low fee', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('A fund fee of 0.28% a year, with no purchase, sale or other extra fees.', TEXT_DOMAIN); ?></p>
                        </article>
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <h3><?php _e('No LEI code needed', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('You buy units directly from the fund manager and save 50-100 euros a year.', TEXT_DOMAIN); ?></p>
                        </article>
                        <article class="who-card">
                            <div class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <h3><?php _e('Tuleva belongs to its savers', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('The more of us who save together, the lower we can push the fees.', TEXT_DOMAIN); ?></p>
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
                        <span class="inline-register__number">1</span><span class="inline-register__title"><?php _e('Log in and choose "For a company"', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('Verify yourself with Smart-ID, mobile-ID, or an ID card.', TEXT_DOMAIN); ?></p>
                    <div class="inline-register__item">
                        <span class="inline-register__number">2</span><span class="inline-register__title"><?php _e('Fill in the questionnaire', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('If the company has two shareholders, both of them have to verify themselves.', TEXT_DOMAIN); ?></p>
                    <div class="inline-register__item">
                        <span class="inline-register__number">3</span><span class="inline-register__title"><?php _e('Make the first contribution', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('Start with as little as 1 euro.', TEXT_DOMAIN); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom" id="alusta">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <div class="emphasis-box p-4 p-md-5 text-center text-navy">
                        <h2><?php _e('Start today', TEXT_DOMAIN); ?></h2>
                        <p class="lead mx-auto"><?php _e('Open an account in just a couple of minutes and put your company\'s idle cash to work.', TEXT_DOMAIN); ?></p>
                        <p class="m-0 mt-4 pt-2"><a href="<?php echo get_app_url('/savings-fund/onboarding'); ?>" class="btn btn-lg d-block d-md-inline-block m-0 btn-primary"><?php _e('Open a company account', TEXT_DOMAIN); ?></a></p>
                        <ul class="ts-trust">
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span><?php _e('More than <strong>200</strong> companies already save in Tuleva', TEXT_DOMAIN); ?></span></li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span><?php _e('Fee <strong>0.28%</strong> per year, no extra charges', TEXT_DOMAIN); ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing bg-gray-1" id="kkk">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="text-center"><?php _e('Frequently asked questions', TEXT_DOMAIN); ?></h2>
                    <div class="faq-list">
                        <div class="qa__question-wrapper" id="kas-minu-osauhing-saab-investeerida">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-1"><?php _e('Can my company invest?', TEXT_DOMAIN); ?></a>
                            <div id="answer-1" class="collapse">
                                <p><?php _e('Under the fund rules, units can be acquired by a private limited company registered in Estonia that has up to two shareholders who are Estonian citizens or residents, and whose shareholders are at the same time also members of the company\'s management board and its beneficial owners.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('When you join, you will see straight away whether your company is eligible.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="osauhingu-kaudu-voi-eraisikuna">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-2"><?php _e('Should I invest through my company or as a private person?', TEXT_DOMAIN); ?></a>
                            <div id="answer-2" class="collapse">
                                <p><?php _e('Look at where your income arrives. If the income arrives in the company, invest through the company. If it arrives in your personal account, investing as a private person through an investment account is simpler. In terms of taxes there is usually no great difference, so do not create a company just for investing.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kuidas-raamatupidamises-kajastada">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-3"><?php _e('How do I account for this in bookkeeping?', TEXT_DOMAIN); ?></a>
                            <div id="answer-3" class="collapse">
                                <p><?php _e('The fund does not pay out dividends, so there is usually nothing to declare on an ongoing basis. The units are recorded as a financial investment, and your accountant decides the exact treatment.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('We make reporting easy: you get the information needed for the annual report (the acquisition cost of the units and their value on the balance sheet date) from Tuleva in ready-made form.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kui-kiiresti-saan-osakud-muua">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-4"><?php _e('How quickly can I sell the units?', TEXT_DOMAIN); ?></a>
                            <div id="answer-4" class="collapse">
                                <p><?php _e('You can sell units at any time and the money arrives in the company\'s account within three business days.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kui-osauhingu-struktuur-muutub">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-5"><?php _e('What happens if the company\'s structure changes?', TEXT_DOMAIN); ?></a>
                            <div id="answer-5" class="collapse">
                                <p><?php _e('If the company no longer meets the conditions (for example a third shareholder joins), the company cannot acquire further units. In certain cases we have the right to redeem the fund units unilaterally. The value of the existing units is preserved.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kas-osauhing-teenib-liikmeboonust">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-6"><?php _e('Do Tuleva members earn the member bonus when investing through a company?', TEXT_DOMAIN); ?></a>
                            <div id="answer-6" class="collapse">
                                <p><?php _e('No. When investing in the Additional Investment Fund through a company, the company cannot earn the member bonus, because the articles of association of the Tuleva cooperative do not allow it.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('If you are a member of the Tuleva cooperative and you invest in the Additional Investment Fund as a private person, you earn a member bonus of 0.05% of the value of your assets every year.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="uuri-rohkem">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="mb-0 text-center"><?php _e('Learn more', TEXT_DOMAIN); ?></h2>
                    <div class="resources">
                        <a class="resource resource--post" href="<?php echo esc_url(__('https://tuleva.ee/taiendav-kogumisfond/kas-investeerida-oma-ou-kaudu-kellele-see-sobib-ja-kellele-mitte/', TEXT_DOMAIN)); ?>">
                            <span class="post-title"><?php _e('Should you invest through your own company?', TEXT_DOMAIN); ?></span>
                            <span class="post-teaser"><?php _e('Who it suits and who it does not.', TEXT_DOMAIN); ?></span>
                            <span class="post-more"><?php _e('Read more →', TEXT_DOMAIN); ?></span>
                        </a>
                        <a class="resource resource--post" href="<?php echo esc_url(__('https://tuleva.ee/taskuhaaling/nuud-saavad-tulevas-koguda-ka-osauhingud/', TEXT_DOMAIN)); ?>">
                            <span class="post-title"><?php _e('Podcast about companies', TEXT_DOMAIN); ?></span>
                            <span class="post-teaser"><?php _e('How saving through a company works.', TEXT_DOMAIN); ?></span>
                            <span class="post-more"><?php _e('Listen →', TEXT_DOMAIN); ?></span>
                        </a>
                    </div>
                    <p class="resource-links">
                        <a href="<?php echo esc_url(__('https://tuleva.ee/taiendav-kogumisfond/', TEXT_DOMAIN)); ?>"><?php _e('More about the Tuleva Additional Investment Fund →', TEXT_DOMAIN); ?></a>
                        <a href="<?php echo esc_url(__('https://tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/', TEXT_DOMAIN)); ?>"><?php _e('Fund documents →', TEXT_DOMAIN); ?></a>
                    </p>
                </div>
            </div>
        </div>
    </section>

</main>
