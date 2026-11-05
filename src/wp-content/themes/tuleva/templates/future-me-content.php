<?php
$future_me_url = get_app_url('/future-me');
$login_note = esc_html__('You log in with Smart-ID, mobile-ID or an ID card.', TEXT_DOMAIN);
$payouts_url = esc_url(__('https://tuleva.ee/en/recommendations/going-to-retire/', TEXT_DOMAIN));

$stages = [
    ['age' => __('0-55', TEXT_DOMAIN), 'what' => __('Saving', TEXT_DOMAIN), 'question' => __('How much have you saved so far?', TEXT_DOMAIN), 'x' => 8, 'y' => 53],
    ['age' => '55', 'what' => __('III pillar, if you started saving before 2021', TEXT_DOMAIN), 'question' => __('What will you do with your III pillar savings?', TEXT_DOMAIN), 'x' => 25, 'y' => 33],
    ['age' => '60', 'what' => __('Payouts', TEXT_DOMAIN), 'question' => __('What will you do with your II and III pillar savings?', TEXT_DOMAIN), 'x' => 42, 'y' => 23.1],
    ['age' => '65', 'what' => __('Retirement age', TEXT_DOMAIN), 'question' => __('Do you take your state pension earlier or later?', TEXT_DOMAIN), 'x' => 58, 'y' => 23.1],
    ['age' => '66+', 'what' => __('Later life', TEXT_DOMAIN), 'question' => __('How long do your payouts last?', TEXT_DOMAIN), 'x' => 75, 'y' => 33],
    ['age' => __('Result', TEXT_DOMAIN), 'question' => __('Does your income cover your costs in later life?', TEXT_DOMAIN), 'x' => 92, 'y' => 53],
];

$faq = [
    [
        'question' => __('Who is Future Me for?', TEXT_DOMAIN),
        'answer' => __('Anyone who wants to know whether their income will cover their costs in later life. You do not need to be an expert or save with Tuleva.', TEXT_DOMAIN),
    ],
    [
        'question' => __('What are retirement age and flexible pension?', TEXT_DOMAIN),
        'answer' => __('Retirement age is the age at which you gain the right to a state old-age pension. With the flexible pension you can start receiving it earlier or later. Future Me works out this age and shows you your options.', TEXT_DOMAIN),
    ],
    [
        'question' => __('What are the state pension and the II and III pillar?', TEXT_DOMAIN),
        'answer' => __('The state pension, or I pillar, is a monthly amount paid by the state. Part of your salary goes to the II pillar, and you make III pillar contributions yourself, with an income tax benefit on up to 15% of your gross income, but no more than 6,000 € a year. Your II and III pillar savings can be inherited.', TEXT_DOMAIN),
    ],
    [
        'question' => __('How do I withdraw my II and III pillar money?', TEXT_DOMAIN),
        'answer' => __('You withdraw II and III pillar money with an application: as monthly payouts, also known as a funded pension, or as partial payouts. On favorable terms you can withdraw from the II and III pillar 5 years before your retirement age, and from the III pillar from age 55 if you started saving before 2021. If the fund payouts last at least your statistically remaining years, they are free of income tax. Future Me shows how your chosen payout type and income tax affect your income in later life.', TEXT_DOMAIN)
            . ' <a href="' . $payouts_url . '">' . esc_html__('Read about pension payouts', TEXT_DOMAIN) . '</a>',
    ],
    [
        'question' => __('Can I work while receiving a pension?', TEXT_DOMAIN),
        'answer' => __('Yes. Reaching retirement age does not mean you have to stop working: if you keep working, part of your salary still goes to the II pillar, and you can keep contributing to the III pillar. If you keep contributing, money is added to your II and III pillar, which can increase your future payouts. Once you start II pillar payouts, your II pillar contributions stop for good, but you can keep paying into the III pillar.', TEXT_DOMAIN),
    ],
];
?>
<main id="main" class="page-container landing-page future-me-page">

    <section class="hero bg-hero-future-me">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-xl-8 text-center text-lg-start text-navy">
                    <h1 class="mb-4"><?php _e('What is your retirement age and will your income cover your costs in later life?', TEXT_DOMAIN); ?></h1>
                    <p class="m-0 lead"><?php _e('<strong>Future Me</strong> works out your retirement age and shows whether your income covers your costs in later life. Go through your decisions by age and see the effect of each.', TEXT_DOMAIN); ?></p>
                    <div class="future-me-actions">
                        <a class="btn btn-primary btn-lg" href="<?php echo esc_url($future_me_url); ?>"><?php _e('Open Future Me', TEXT_DOMAIN); ?></a>
                        <a class="future-me-tap-link" href="#how-it-works"><?php _e('See the decisions', TEXT_DOMAIN); ?></a>
                    </div>
                    <p class="future-me-login-note"><?php echo $login_note; ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing bg-gray-1 savings-fund-landing-page">
        <div class="container">
            <div class="who future-me-answers">
                <article class="who-card">
                    <div class="future-me-answer-visual" aria-hidden="true">
                        <svg viewBox="0 0 240 88" focusable="false">
                            <path d="M8 76 Q120 -14 232 76" fill="none" stroke="#c9e2f4" stroke-width="7" stroke-linecap="round"/>
                            <path d="M8 76 Q120 -14 232 76" fill="none" stroke="#006ce6" stroke-width="2.5" stroke-linecap="round"/>
                            <line x1="156" y1="30" x2="156" y2="80" stroke="#002f63" stroke-width="1.5" stroke-dasharray="3 4"/>
                            <circle cx="156" cy="30" r="7" fill="#002f63"/>
                        </svg>
                    </div>
                    <h2><?php _e('When can I retire?', TEXT_DOMAIN); ?></h2>
                    <p><?php _e('Future Me works out the age at which you start receiving your state pension and shows your income and costs at each age.', TEXT_DOMAIN); ?></p>
                </article>
                <article class="who-card">
                    <div class="future-me-answer-visual" aria-hidden="true">
                        <svg viewBox="0 0 240 88" focusable="false">
                            <rect x="62" y="44" width="46" height="36" rx="3" fill="#a9d3f2"/>
                            <rect x="62" y="26" width="46" height="16" rx="3" fill="#006ce6"/>
                            <rect x="62" y="10" width="46" height="14" rx="3" fill="#002f63"/>
                            <rect x="132" y="24" width="46" height="56" rx="3" fill="#c9e2f4"/>
                            <line x1="48" y1="81" x2="192" y2="81" stroke="#002f63" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h2><?php _e('How much money will I have in later life?', TEXT_DOMAIN); ?></h2>
                    <p><?php _e('Future Me shows your income and costs in later life year by year: state pension and II and III pillar payouts on one side, costs on the other. You see your own amount after entering your data.', TEXT_DOMAIN); ?></p>
                </article>
                <article class="who-card">
                    <div class="future-me-answer-visual" aria-hidden="true">
                        <svg viewBox="0 0 240 88" focusable="false">
                            <rect x="24" y="12" width="44" height="68" rx="6" fill="#a9d3f2"/>
                            <rect x="24" y="32" width="44" height="48" rx="6" fill="#006ce6"/>
                            <path d="M76 50 H90" stroke="#002f63" stroke-width="1.5" stroke-dasharray="3 4"/>
                            <path d="M88 45 l6 5 l-6 5" fill="none" stroke="#002f63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="112" cy="50" r="12" fill="#006ce6"/><text x="112" y="54.5" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">€</text>
                            <circle cx="142" cy="50" r="12" fill="#006ce6"/><text x="142" y="54.5" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">€</text>
                            <circle cx="172" cy="50" r="12" fill="#006ce6"/><text x="172" y="54.5" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">€</text>
                            <circle cx="202" cy="50" r="12" fill="#006ce6"/><text x="202" y="54.5" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">€</text>
                        </svg>
                    </div>
                    <h2><?php _e('How do payouts work and how much will I get per month?', TEXT_DOMAIN); ?></h2>
                    <p><?php _e('You can use your II and III pillar savings as monthly payouts. Future Me shows from your data how much you get per month and how income tax affects it.', TEXT_DOMAIN); ?></p>
                    <p class="mt-3"><a class="future-me-tap-link" href="<?php echo $payouts_url; ?>"><?php _e('Read about pension payouts', TEXT_DOMAIN); ?></a></p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="how-it-works">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8 text-center">
                    <h2><?php _e('Which decisions are ahead of you?', TEXT_DOMAIN); ?></h2>
                    <p class="m-0 mt-4"><?php _e('Future Me takes you through your decisions by age and shows the effect of each on the end result.', TEXT_DOMAIN); ?></p>
                </div>
            </div>
            <div class="future-me-arc" id="futureMeArc">
                <svg class="future-me-arc-line" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" focusable="false">
                    <path class="ghost" d="M0 66 Q50 -22 100 66" vector-effect="non-scaling-stroke"/>
                    <path d="M0 66 Q50 -22 100 66" vector-effect="non-scaling-stroke"/>
                </svg>
                <ol class="future-me-stages">
                    <?php foreach ($stages as $index => $stage) : ?>
                        <li class="future-me-stage<?php echo $index === count($stages) - 1 ? ' future-me-stage--result' : ''; ?>" style="--i:<?php echo $index; ?>;--x:<?php echo $stage['x']; ?>%;--y:<?php echo $stage['y']; ?>%">
                            <p class="age"><?php echo esc_html($stage['age']); ?></p>
                            <?php if (isset($stage['what'])) : ?>
                                <p class="what"><?php echo esc_html($stage['what']); ?></p>
                            <?php endif; ?>
                            <p class="question"><?php echo esc_html($stage['question']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
            <p class="future-me-note"><?php _e('Ages are as of 2026. Retirement age depends on your year of birth, so your ages may differ: Future Me works them out from your data.', TEXT_DOMAIN); ?></p>
        </div>
    </section>

    <section class="section-spacing-bottom" id="alusta">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <div class="emphasis-box p-4 p-md-5 text-center text-navy">
                        <h2><?php _e('Meet your future self', TEXT_DOMAIN); ?></h2>
                        <p class="lead mx-auto"><?php _e('What should you do with your II and III pillar? See how each choice affects your income in later life and whether it covers your costs.', TEXT_DOMAIN); ?></p>
                        <p class="m-0 mt-4 pt-2"><a class="btn btn-lg d-block d-md-inline-block m-0 btn-primary" href="<?php echo esc_url($future_me_url); ?>"><?php _e('Open Future Me', TEXT_DOMAIN); ?></a></p>
                        <p class="future-me-login-note"><?php echo $login_note; ?></p>
                    </div>
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
                        <?php foreach ($faq as $index => $row) : ?>
                            <div class="qa__question-wrapper" id="kkk-<?php echo $index + 1; ?>">
                                <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-<?php echo $index + 1; ?>"><?php echo esc_html($row['question']); ?></a>
                                <div id="answer-<?php echo $index + 1; ?>" class="collapse">
                                    <p><?php echo $row['answer']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="application/ld+json"><?php echo faq_schema_json($faq); ?></script>

    <script>
        (function () {
            var arc = document.getElementById('futureMeArc');
            if (!arc || !('IntersectionObserver' in window)) {
                return;
            }
            arc.classList.add('is-waiting');
            new IntersectionObserver(function (entries, observer) {
                if (entries[0].isIntersecting) {
                    arc.classList.remove('is-waiting');
                    observer.disconnect();
                }
            }, { threshold: 0.35 }).observe(arc);
        })();
    </script>
</main>
