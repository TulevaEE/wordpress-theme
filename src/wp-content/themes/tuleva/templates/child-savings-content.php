<main id="main" class="page-container landing-page child-savings-page">
    <script>
        var LANGCODE = '<?php echo apply_filters( "wpml_current_language", NULL );  ?>'; // eslint-disable-line
    </script>

    <section class="hero bg-hero-child d-flex flex-column overflow-x-hidden">
        <div class="container my-auto">
            <div class="row align-items-center gy-5 gx-xl-5">
                <div class="col-lg-6 text-center text-lg-start text-navy">
                    <span class="eyebrow"><?php _e('Tuleva Savings Fund for your child', TEXT_DOMAIN); ?></span>
                    <h1 class="mb-4"><?php _e('Put your child\'s money to work early', TEXT_DOMAIN); ?></h1>
                    <p class="m-0 lead"><?php _e('The assets belong to your child and grow in Tuleva\'s low-fee index fund. Start with as little as one euro.', TEXT_DOMAIN); ?></p>
                </div>
                <div class="col-lg-6" id="kalkulaator">
                    <div class="card calculator rounded-4">
                        <div class="card-body p-2">
                            <div class="bg-gray-2 p-3 rounded-3">
                                <div class="mb-3 align-items-center row">
                                    <label for="calcAge" class="col-sm-6 col-form-label pe-0"><?php _e('Child\'s age', TEXT_DOMAIN); ?></label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control text-end" id="calcAge" min="0" max="18" step="1" placeholder="0" inputmode="numeric">
                                    </div>
                                </div>

                                <div class="mb-3 align-items-center row">
                                    <label for="calcSum" class="col-sm-6 col-form-label pe-0"><?php _e('Contribution', TEXT_DOMAIN); ?></label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="number" class="form-control text-end" id="calcSum" min="0" max="9999" step="1" placeholder="80" inputmode="numeric">
                                            <span class="input-group-text"><?php _e('&euro;/month', TEXT_DOMAIN); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0 row">
                                    <label for="calcRate" class="col-sm-6 col-form-label py-2 pe-0"><?php _e('Expected yearly return', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" role="button" tabindex="0" aria-label="<?php echo esc_attr__('More information', TEXT_DOMAIN); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo esc_attr__('The final amount depends on the returns that actually materialize, and neither we nor anyone else can guarantee a return.', TEXT_DOMAIN); ?>"></span></label>
                                    <div class="col-sm-6 return-rate">
                                        <input type="range" class="form-range" id="calcRate" min="-10" max="10" step="1" data-unit="%">
                                        <span class="custom-tooltip">0%</span>
                                        <button type="button" class="historic-return-rate small text-secondary border-0 bg-transparent"><?php _e('historic return of stocks 7%', TEXT_DOMAIN); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 p-3 py-4 card rounded-3 text-navy" aria-live="polite">
                                <div class="calc-line win"><span><?php _e('Tax win', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" role="button" tabindex="0" aria-label="<?php echo esc_attr__('More information', TEXT_DOMAIN); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo esc_attr__('The calculation assumes the child sells their fund units gradually over 4 years, between ages 18 and 21, not all at once, and uses their yearly tax-free income each year. Today this is 8,400 € per year if the child has no other income. The amount is set by law and will change over the years.', TEXT_DOMAIN); ?>"></span></span><b id="resTax">&ndash;</b></div>
                                <div class="calc-total"><span><?php _e('At 18, your child has', TEXT_DOMAIN); ?></span><b id="resTotal"><?php _e('17,280 €', TEXT_DOMAIN); ?></b></div>
                            </div>
                            <div class="d-grid">
                                <a href="<?php echo get_app_url('/savings-fund/onboarding/child'); ?>" class="btn btn-primary btn-lg mt-2"><?php _e('Open an account for my child', TEXT_DOMAIN); ?></a>
                            </div>
                            <div class="mt-2 py-2 text-secondary text-center small"><?php _e('Opening an account is free and takes only a few minutes.', TEXT_DOMAIN); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="kuidas">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="mb-4 text-center"><?php _e('Open an account for your child quickly and easily', TEXT_DOMAIN); ?></h2>
                    <div class="inline-register__item">
                        <span class="inline-register__number">1</span><span class="inline-register__title"><?php _e('Log in and choose "For a child"', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('Verify yourself with Smart-ID, mobile-ID, or an ID card.', TEXT_DOMAIN); ?></p>
                    <div class="inline-register__item">
                        <span class="inline-register__number">2</span><span class="inline-register__title"><?php _e('Complete a short verification', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('Answer a few questions. There are no documents to upload.', TEXT_DOMAIN); ?></p>
                    <div class="inline-register__item">
                        <span class="inline-register__number">3</span><span class="inline-register__title"><?php _e('Make the first contribution', TEXT_DOMAIN); ?></span>
                    </div>
                    <p class="inline-register__content"><?php _e('Start with as little as 1 euro. You can also set up a recurring payment, for example in the amount of the child benefit.', TEXT_DOMAIN); ?></p>
                    <div class="mt-5 text-center">
                        <a href="<?php echo get_app_url('/savings-fund/onboarding/child'); ?>" class="btn btn-primary btn-lg"><?php _e('Open an account for my child', TEXT_DOMAIN); ?></a>
                    </div>
                    <p class="steps-note text-center mb-0"><?php _e('If you have several children, you can open a separate account for each one.', TEXT_DOMAIN); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom" id="kingitus">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <div class="gift-banner">
                <div class="gift-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                </div>
                        <div>
                            <span class="chip chip--blue chip-soon"><?php _e('Coming this autumn', TEXT_DOMAIN); ?></span>
                            <h3><?php _e('Gifts straight to your child\'s account', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Grandparents, godparents, and friends will be able to make contributions straight to your child\'s savings fund account, with no steps in between.', TEXT_DOMAIN); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom" id="enne">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="mb-0 text-center"><?php _e('Good to know before you start', TEXT_DOMAIN); ?></h2>
                    <ul class="diff-list">
                <li><b><?php _e('Before investing for your child, save for yourself.', TEXT_DOMAIN); ?></b>
                    <?php printf(
                        __('The greatest gift to a child is a parent who has taken care of their own future: second and third pillar tax benefits used and a rainy-day fund in place. %s', TEXT_DOMAIN),
                        '<a href="https://tuleva.ee/analuusid/miks-voiks-lapsele-kogumist-alustada-hoopis-oma-iii-sambast/">' . __('Why take care of your own third pillar first? →', TEXT_DOMAIN) . '</a>'
                    ); ?></li>
                <li><b><?php _e('Money given to your child stays with your child.', TEXT_DOMAIN); ?></b>
                    <?php _e('The assets belong to the child and withdrawals can only be made to the child\'s own bank account. This gives the assets reasonable protection.', TEXT_DOMAIN); ?></li>
                <li><b><?php _e('Stock markets fluctuate.', TEXT_DOMAIN); ?></b>
                    <?php _e('Stock prices can rise as well as fall over time. The fund is meant for long-term investing – and saving for a child usually is just that. Still, returns cannot be guaranteed.', TEXT_DOMAIN); ?></li>
                <li><b><?php _e('The fund\'s assets are protected.', TEXT_DOMAIN); ?></b>
                    <?php _e('The fund\'s assets are held strictly separate from Tuleva\'s assets at an independent depositary, and Tuleva Fondid AS operates under the supervision of the Estonian Financial Supervision Authority.', TEXT_DOMAIN); ?></li>
                    </ul>
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
                <p class="lead mx-auto"><?php _e('Open an account in just a couple of minutes. The earlier you start, the longer your child\'s assets have to grow.', TEXT_DOMAIN); ?></p>
                <p class="m-0 mt-4 pt-2"><a href="<?php echo get_app_url('/savings-fund/onboarding/child'); ?>" class="btn btn-lg d-block d-md-inline-block m-0 btn-primary"><?php _e('Open an account for my child', TEXT_DOMAIN); ?></a></p>
                        <ul class="ts-trust">
                            <li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span><?php _e('Over <strong>85,000</strong> people save with Tuleva', TEXT_DOMAIN); ?></span></li>
                            <li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span><?php _e('Fee <strong>0.28%</strong> per year, no extra charges', TEXT_DOMAIN); ?></span></li>
                        </ul>
                    </div>
                    <p class="foot-note text-center mx-auto"><?php _e('The calculator\'s result is illustrative: income tax is charged only on earned gains, not on contributions. The tax win calculation assumes the child sells their fund units gradually over 4 years, between ages 18 and 21, not all at once, and uses their yearly tax-free income each year, provided they have no other income. The calculation uses today\'s rates: income tax of 22% and tax-free income of 8,400 € per year. Tax rules will change over the years. No Tuleva fund has a guaranteed return.', TEXT_DOMAIN); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom qa-block" id="kkk">
        <div class="container">
            <h2 class="mb-0 text-center"><?php _e('Frequently asked questions', TEXT_DOMAIN); ?></h2>
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <div class="faq-list">
                        <div class="qa__question-wrapper" id="kkk-1">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-1"><?php _e('Why open a Savings Fund for your child rather than a third pillar?', TEXT_DOMAIN); ?></a>
                            <div id="answer-1" class="collapse">
                                <p><?php _e('The third pillar\'s appeal is the tax benefit: you get income tax back on your contributions. But only a person who earns taxable income themselves gets the refund. A child usually doesn\'t, so paying into a child\'s third pillar creates no tax benefit.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Second, the third pillar is meant for retirement: with the favorable tax rate, the money only becomes available close to retirement age. For a child, this would mean locking the money away for decades. From the Savings Fund, your child can use the money when they need it, for example for education or a first home.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Saving in the child\'s name in the Savings Fund, the tax win comes from the other end: when selling units, the child can use their yearly tax-free income, which today is 8,400 € per year.', TEXT_DOMAIN); ?></p>
                                <p><?php printf(
                                    __('Once your child starts earning income themselves, they should consider their own third pillar too, because then they get a tax benefit as well. If your child already has a third pillar account opened before 2021, keep it as it is: these are separate accounts and don\'t mix. And your own third pillar is worth filling before you start saving for your child. %s', TEXT_DOMAIN),
                                    '<a href="https://tuleva.ee/analuusid/miks-voiks-lapsele-kogumist-alustada-hoopis-oma-iii-sambast/">' . __('Read more →', TEXT_DOMAIN) . '</a>'
                                ); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kkk-2">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-2"><?php _e('What happens if my child withdraws all the money at 18?', TEXT_DOMAIN); ?></a>
                            <div id="answer-2" class="collapse">
                                <p><?php _e('At 18, the decision does indeed pass to your child, because the assets are theirs. In practice, this fear is usually bigger than the actual risk: a young person who has watched their money grow for years treats it as their future assets, not as a windfall.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('What helps most is involving your child in saving early on: show them how the account grows and talk about what you are saving for. That way the money won\'t come as a surprise at 18.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Experience elsewhere shows the same: data on the UK\'s similar product (the Junior ISA) shows that only 6.5% of young people withdrew the whole amount right at 18 (AJ Bell, 2026).', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kkk-3">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-3"><?php _e('How does this differ from LHV\'s Kasvukonto and Swedbank\'s child products?', TEXT_DOMAIN); ?></a>
                            <div id="answer-3" class="collapse">
                                <p><?php _e('LHV\'s Kasvukonto is a platform where you choose yourself which fund your child\'s money is invested in. There are many options, both good and worse ones, and you have to pick the right fund yourself. Tuleva\'s Savings Fund has just one fund, a broad-based index fund with a fee of 0.28% per year, and we stand behind it.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Swedbank\'s child products (Koolifond and its successor, the Lapse Tuleviku fund) mix investing with insurance. The total cost of such products is estimated at around 1.5% per year, and the exact amount depends on the contract. Tuleva\'s fund is meant for investing only. If you need insurance, that is a separate purchase.', TEXT_DOMAIN); ?></p>
                                <p><a href="https://tuleva.ee/vastused/miks-koolifondidest-eemale-hoida/"><?php _e('Read more: 3 reasons to avoid child-labeled savings products', TEXT_DOMAIN); ?></a></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="sissemaksed">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-4"><?php _e('How do contributions work?', TEXT_DOMAIN); ?></a>
                            <div id="answer-4" class="collapse">
                                <p><?php _e('Contributions to your child\'s Savings Fund account can only be made from the child\'s own bank account. Transfer money to your child\'s bank account and make the transfer to the fund from there. This way your child gets an acquisition cost for the units, and in the future only the gain is taxed on sale, not the whole amount.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('If your child doesn\'t have a bank account yet, you can open one in their name at any Estonian bank.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('A grandparent or another close person can also give your child a gift. The simplest way is for them to gift the money to you: you transfer it to your child\'s account and from there to the fund. If a gift lands straight on the child\'s account, investing it requires the court\'s consent by law. That is why we recommend gifting through the parent.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('From this autumn, gifts can also be made straight to your child\'s savings fund account.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kkk-5">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-5"><?php _e('How do withdrawals work?', TEXT_DOMAIN); ?></a>
                            <div id="answer-5" class="collapse">
                                <p><?php _e('Units can be sold at any time and the money is paid only to your child\'s own bank account. This keeps your child\'s assets protected: money cannot move to anyone else\'s account.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('Units bought with the money you contributed yourself can be sold by you as your child\'s representative without extra conditions. If units were bought with the child\'s other money (for example a gift or an inheritance received straight to the child\'s account), the sale requires the court\'s consent by law. You can ask the court for a general consent covering the whole childhood. The state fee is 10 €.', TEXT_DOMAIN); ?></p>
                                <p><?php printf(
                                    __('The state is preparing a legislative change that would make investing in a child\'s name even simpler. %s', TEXT_DOMAIN),
                                    '<a href="https://tuleva.ee/seadusandlus/peagi-saab-lapse-nimel-investeerida-ilma-kohtu-loata/">' . __('We wrote about it on the blog →', TEXT_DOMAIN) . '</a>'
                                ); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kkk-6">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-6"><?php _e('Does the tax return mean extra work?', TEXT_DOMAIN); ?></a>
                            <div id="answer-6" class="collapse">
                                <p><?php _e('Yes, a little. If your child\'s units are sold and taxable income arises, a tax return has to be filed in their name. Saving in the parent\'s name, there would be no such extra work.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="qa__question-wrapper" id="kkk-7">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-7"><?php _e('Can both parents manage the account?', TEXT_DOMAIN); ?></a>
                            <div id="answer-7" class="collapse">
                                <p><?php _e('The account can be opened by either parent who has custody over the child\'s assets, and they act independently as the child\'s representative. We notify the other parent when the account is opened. The option for both parents to see the account and make transactions is coming later.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="mb-0 text-center"><?php _e('Learn more', TEXT_DOMAIN); ?></h2>
                    <div class="resources">
                        <a class="resource resource--post" href="https://tuleva.ee/lapsele-kogumine/kas-koguda-lapse-voi-vanema-nimel/">
                            <span class="post-title"><?php _e('Should you save in the child\'s name or your own?', TEXT_DOMAIN); ?></span>
                            <span class="post-teaser"><?php _e('The pros and cons of each option.', TEXT_DOMAIN); ?></span>
                            <span class="post-more"><?php _e('Read on →', TEXT_DOMAIN); ?></span>
                        </a>
                        <a class="resource resource--post" href="https://tuleva.ee/taiendav-kogumisfond/kas-lapsele-kogudes-on-vaja-investeerimiskontot-kaks-lahenemist-mille-vahel-valida/">
                            <span class="post-title"><?php _e('Do you need an investment account when saving for a child?', TEXT_DOMAIN); ?></span>
                            <span class="post-teaser"><?php _e('Two approaches to choose between.', TEXT_DOMAIN); ?></span>
                            <span class="post-more"><?php _e('Read on →', TEXT_DOMAIN); ?></span>
                        </a>
                    </div>
                    <p class="resource-links">
                        <a href="https://tuleva.ee/taiendav-kogumisfond/"><?php _e('More about the Tuleva Savings Fund →', TEXT_DOMAIN); ?></a>
                        <a href="https://tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/"><?php _e('Fund documents →', TEXT_DOMAIN); ?></a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>
