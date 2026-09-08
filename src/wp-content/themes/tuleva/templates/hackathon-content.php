<?php
// Tuleva hackathon landing page (page_hackathon.php), ported from the static
// prototype at tuleva.ee/vaata/hakaton/. Copy is English gettext with the
// Estonian translations in lang/et.po, like the other landing pages.
$event_url    = get_permalink();
$register_url = get_app_url('/hackathon');
$ics_url      = get_template_directory_uri() . '/files/tuleva-hakaton.ics';
$share_image  = get_the_post_thumbnail_url(null, 'full') ?: get_template_directory_uri() . '/img/hakaton-share.jpg';
$event_name   = __('Tuleva hackathon', TEXT_DOMAIN);
$venue        = 'Roheline saal, Telliskivi Loomelinnak';

$google_calendar_url = 'https://calendar.google.com/calendar/render?' . http_build_query([
    'action'   => 'TEMPLATE',
    'text'     => $event_name,
    'dates'    => '20261009T143000Z/20261011T130000Z',
    'location' => $venue . ', Telliskivi 60a/5, Tallinn',
    'details'  => $event_url,
], '', '&', PHP_QUERY_RFC3986);

$outlook_calendar_url = 'https://outlook.live.com/calendar/0/deeplink/compose?' . http_build_query([
    'path'     => '/calendar/action/compose',
    'rru'      => 'addevent',
    'subject'  => $event_name,
    'startdt'  => '2026-10-09T14:30:00Z',
    'enddt'    => '2026-10-11T13:00:00Z',
    'location' => $venue . ', Tallinn',
    'body'     => $event_url,
], '', '&', PHP_QUERY_RFC3986);

$event_schema = [
    '@context'            => 'https://schema.org',
    '@type'               => 'Event',
    'name'                => $event_name,
    'description'         => __('At the Tuleva hackathon, your idea could grow into our next line of business.', TEXT_DOMAIN),
    'startDate'           => '2026-10-09T17:30:00+03:00',
    'endDate'             => '2026-10-11T16:00:00+03:00',
    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
    'location'            => [
        '@type'   => 'Place',
        'name'    => $venue,
        'address' => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Telliskivi 60a/5',
            'addressLocality' => 'Tallinn',
            'addressCountry'  => 'EE',
        ],
    ],
    'isAccessibleForFree' => true,
    'organizer'           => ['@type' => 'Organization', 'name' => 'Tulundusühistu Tuleva', 'url' => 'https://tuleva.ee'],
    'image'               => $share_image,
    'url'                 => $event_url,
];

// Grey silhouette shown for mentors and jury members not yet announced
$placeholder_avatar = '<div class="ph" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/></svg></div>';
?>
<main id="main" class="page-container landing-page hackathon-page">

    <script type="application/ld+json"><?php echo wp_json_encode($event_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <section class="hero text-center">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9">
                    <h1 class="mb-4"><?php _e('At the Tuleva hackathon, your idea could grow into our next line of business', TEXT_DOMAIN); ?></h1>
                    <p class="m-0 lead mx-auto">
                        <?php _e('Ten years ago we had had enough of paying banks high fees for poor returns, so we brought low-fee index funds to the market.', TEXT_DOMAIN); ?><br class="d-md-none"><br class="d-md-none">
                        <?php _e('Now we are taking on the next field where there are problems to solve, together.', TEXT_DOMAIN); ?>
                    </p>
                    <div class="cta">
                        <a href="<?php echo esc_url($register_url); ?>" class="btn btn-primary btn-lg"><?php _e('Register', TEXT_DOMAIN); ?></a>
                    </div>
                    <p class="deadline-note m-0 mt-3">
                        <?php _e('Registration closes on', TEXT_DOMAIN); ?><br class="d-md-none"> <b><?php _e('30 September at 23.59', TEXT_DOMAIN); ?></b>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-credentials bg-gray-2 py-4 py-lg-5" aria-label="<?php echo esc_attr__('Practical information', TEXT_DOMAIN); ?>">
        <div class="container">
            <div class="row gy-3 justify-content-center">
                <div class="col-auto d-flex align-items-center justify-content-center text-navy">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="me-3 me-sm-4" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    </svg>
                    <div>
                        <p class="m-0"><?php _e('9–11 October 2026', TEXT_DOMAIN); ?></p>
                        <a href="<?php echo esc_url($ics_url); ?>" class="cal-link d-md-none"><?php _e('Add to calendar', TEXT_DOMAIN); ?></a>
                        <div class="cal-menu dropdown d-none d-md-block">
                            <button type="button" class="cal-link" data-bs-toggle="dropdown" aria-expanded="false"><?php _e('Add to calendar', TEXT_DOMAIN); ?></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo esc_url($google_calendar_url); ?>" target="_blank" rel="noopener"><?php _e('Google Calendar', TEXT_DOMAIN); ?></a></li>
                                <li><a class="dropdown-item" href="<?php echo esc_url($outlook_calendar_url); ?>" target="_blank" rel="noopener">Outlook</a></li>
                                <li><a class="dropdown-item" href="<?php echo esc_url($ics_url); ?>"><?php _e('Apple Calendar (.ics)', TEXT_DOMAIN); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-auto d-flex align-items-center justify-content-center text-navy">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="me-3 me-sm-4" aria-hidden="true">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    <p class="m-0"><span class="d-none d-md-inline"><?php _e('Roheline saal,<br>Telliskivi Creative City', TEXT_DOMAIN); ?></span><span class="d-md-none">Roheline saal (Telliskivi)</span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="miks">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8 text-center">
                    <h2><?php _e('Why are we holding a hackathon?', TEXT_DOMAIN); ?></h2>
                    <p class="lead mx-auto">
                        <?php printf(
                            __('%s what worries they still have unresolved and where Tuleva could step in to help. The hackathon challenges below grew out of those answers. Come and let us build the first solutions to them in three days.', TEXT_DOMAIN),
                            '<a href="' . esc_url(__('https://tuleva.ee/liikmetele/4600-tuleva-liiget-utlesid-mis-vajab-eestis-muutmist/', TEXT_DOMAIN)) . '">' . __('This spring we asked Tuleva members', TEXT_DOMAIN) . '</a>'
                        ); ?>
                    </p>
                    <p class="lead mx-auto mt-4">
                        <?php _e('The best idea gets a budget and support from Tuleva to help it grow into our next product or service.', TEXT_DOMAIN); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing pt-0" id="tsitaat">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8 mid-headline text-center">
                    <p><?php _e('“Tuleva has no major owners dictating where to take the company. It has members, who know best what they need. Come and let us build it together.”', TEXT_DOMAIN); ?></p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col text-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/hakaton-annika.jpg" width="96" height="96" loading="lazy" decoding="async" class="rounded-circle mb-2" alt="Annika Uudelepp">
                    <p class="mb-0 fw-bold">Annika Uudelepp</p>
                    <p class="m-0 small text-secondary"><?php _e('Member of the Management Board of the Tuleva cooperative', TEXT_DOMAIN); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing bg-gray-1" id="valjakutsed">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8 text-center">
                    <h2><?php _e('The hackathon challenges', TEXT_DOMAIN); ?></h2>
                    <p class="lead mx-auto">
                        <?php _e('The shape of the solution is up to you: a product, a service, a tool, a cooperative model or an entirely new logic. Several teams may tackle the same challenge.', TEXT_DOMAIN); ?>
                    </p>
                    <div class="challenges">
                        <div class="challenge">
                            <div class="num" aria-hidden="true">1</div>
                            <h3><?php _e('Sensible borrowing', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Bank margins are high and switching contracts is a hassle. How do we give members a fair and affordable loan, both for an unexpected need and for buying a home?', TEXT_DOMAIN); ?></p>
                            <p class="outcome"><b><?php _e('Expected outcome:', TEXT_DOMAIN); ?></b> <?php _e('a solution that gives members a cheaper and more transparent loan (a cooperative credit line, refinancing or an entirely new approach).', TEXT_DOMAIN); ?></p>
                        </div>
                        <div class="challenge">
                            <div class="num" aria-hidden="true">2</div>
                            <h3><?php _e('Insurance that actually protects', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Fine-print terms and complicated claims handling undermine trust. How do we offer insurance that protects when it is really needed, and does so honestly, simply and smoothly?', TEXT_DOMAIN); ?></p>
                            <p class="outcome"><b><?php _e('Expected outcome:', TEXT_DOMAIN); ?></b> <?php _e('insurance that gives better cover at a better price (an independent broker, a cooperative model, a new product or a partnership).', TEXT_DOMAIN); ?></p>
                        </div>
                        <div class="challenge">
                            <div class="num" aria-hidden="true">3</div>
                            <h3><?php _e('Collective buying power', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('Telecom and energy bills take a large share of income every month. How do we use the membership\'s combined buying power to push fixed costs down and keep prices fair, without members having to keep switching providers themselves?', TEXT_DOMAIN); ?></p>
                            <p class="outcome"><b><?php _e('Expected outcome:', TEXT_DOMAIN); ?></b> <?php _e('a mechanism that gives members a lastingly good price (group purchasing, a cooperative virtual operator, an electricity-buying autopilot or a partnership).', TEXT_DOMAIN); ?></p>
                        </div>
                        <div class="challenge">
                            <div class="num" aria-hidden="true">4</div>
                            <h3><?php _e('Smart wealth management and estate planning', TEXT_DOMAIN); ?></h3>
                            <p><?php _e('How do we help members manage their assets wisely across their whole life, and prepare their estate so that it passes on smoothly and the family is spared problems?', TEXT_DOMAIN); ?></p>
                            <p class="outcome"><b><?php _e('Expected outcome:', TEXT_DOMAIN); ?></b> <?php _e('a tool or service that brings a member\'s assets into one view, shows their future standard of living and helps arrange the estate step by step.', TEXT_DOMAIN); ?></p>
                        </div>
                    </div>
                    <p class="challenges-note mx-auto">
                        <?php _e('We have prepared a background brief for every challenge: the market, the margins, the regulation and our hypothesis. You will find the full briefs in the registration form once you have logged in. Use the briefs as a springboard for developing your idea. Feel free to overturn our hypotheses, too.', TEXT_DOMAIN); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="keda-ootame">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8 text-center">
                    <h2><?php _e('Your skills are exactly what we need', TEXT_DOMAIN); ?></h2>
                    <p class="lead lead--narrow mx-auto">
                        <?php _e('We have a community of bright people. At the hackathon, teams of <span class="text-nowrap">4–5</span> members with different skills and knowledge work together. We are especially keen to see you if you are:', TEXT_DOMAIN); ?>
                    </p>
                    <ul class="diff-list">
                        <li><?php _e('<b>A developer or designer</b> who builds a prototype from the idea.', TEXT_DOMAIN); ?></li>
                        <li><?php _e('<b>A data or AI person</b> who loves digging into data and knows how to put artificial intelligence to work.', TEXT_DOMAIN); ?></li>
                        <li><?php _e('<b>A lawyer or regulation expert</b> who knows what is allowed and how.', TEXT_DOMAIN); ?></li>
                        <li><?php _e('<b>A product manager, marketer, finance or insurance person</b> who sees the whole picture and the business.', TEXT_DOMAIN); ?></li>
                        <li><?php _e('<b>Or simply a bright person</b> with a good idea and the will to make it happen.', TEXT_DOMAIN); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing bg-gray-1" id="formaat">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8 text-center">
                    <h2><?php _e('Schedule', TEXT_DOMAIN); ?></h2>
                    <p class="section-note mx-auto mt-3 mb-0">
                        <?php _e('We will share a more detailed schedule as the hackathon approaches.', TEXT_DOMAIN); ?>
                    </p>
                    <div class="tl">
                        <div class="tl-item">
                            <div class="tl-node" aria-hidden="true"><svg class="icn" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                            <div class="tl-card">
                                <div class="tl-day"><?php _e('Friday 9 October', TEXT_DOMAIN); ?><span class="d-none d-md-inline"> ·</span><br class="d-md-none"> <span class="text-nowrap"><?php _e('17.30–21.00', TEXT_DOMAIN); ?></span></div>
                                <h3><?php _e('We form the teams', TEXT_DOMAIN); ?></h3>
                                <p><?php _e('Opening, introduction of the challenges and team formation.', TEXT_DOMAIN); ?><br class="d-none d-md-inline">
                                   <?php _e('If you come alone, you will find a team on the spot.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="tl-item">
                            <div class="tl-node" aria-hidden="true"><svg class="icn" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                            <div class="tl-card">
                                <div class="tl-day"><?php _e('Saturday 10 October', TEXT_DOMAIN); ?><span class="d-none d-md-inline"> ·</span><br class="d-md-none"> <span class="text-nowrap"><?php _e('9.00–18.00', TEXT_DOMAIN); ?></span></div>
                                <h3><?php _e('We build', TEXT_DOMAIN); ?></h3>
                                <p><?php _e('Workshops, teamwork and mentors. By the evening, the idea has become a clear concept and a first prototype.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                        <div class="tl-item">
                            <div class="tl-node" aria-hidden="true"><svg class="icn" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
                            <div class="tl-card">
                                <div class="tl-day"><?php _e('Sunday 11 October', TEXT_DOMAIN); ?><span class="d-none d-md-inline"> ·</span><br class="d-md-none"> <span class="text-nowrap"><?php _e('9.30–16.00', TEXT_DOMAIN); ?></span></div>
                                <h3><?php _e('We present', TEXT_DOMAIN); ?></h3>
                                <p><?php _e('Final polish, pitches to the jury and recognition.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="mentorid">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8 text-center">
                    <h2><?php _e('Mentors and jury', TEXT_DOMAIN); ?></h2>
                    <p class="lead mx-auto">
                        <?php _e('Throughout the weekend, the teams are joined by experienced people who have built products themselves and know what it takes to turn an idea into a business.', TEXT_DOMAIN); ?>
                    </p>
                    <h3 class="sub-h"><?php _e('Mentors', TEXT_DOMAIN); ?></h3>
                    <p class="sub-p mx-auto">
                        <?php _e('No team is left on its own. Mentors visit the teams and help sharpen the idea.', TEXT_DOMAIN); ?>
                    </p>
                    <p class="sub-p mx-auto">
                        <?php _e('Want to be a mentor yourself? Write to', TEXT_DOMAIN); ?> <a href="mailto:tuleva@tuleva.ee">tuleva@tuleva.ee</a>.
                    </p>
                    <div class="people">
                        <div class="person">
                            <?php echo $placeholder_avatar; ?>
                            <b><?php _e('To be announced', TEXT_DOMAIN); ?></b>
                            <span><?php _e('business model mentor', TEXT_DOMAIN); ?></span>
                        </div>
                        <div class="person">
                            <?php echo $placeholder_avatar; ?>
                            <b><?php _e('To be announced', TEXT_DOMAIN); ?></b>
                            <span><?php _e('designer', TEXT_DOMAIN); ?></span>
                        </div>
                        <div class="person">
                            <?php echo $placeholder_avatar; ?>
                            <b><?php _e('To be announced', TEXT_DOMAIN); ?></b>
                            <span><?php _e('AI architect', TEXT_DOMAIN); ?></span>
                        </div>
                        <div class="person">
                            <?php echo $placeholder_avatar; ?>
                            <b><?php _e('To be announced', TEXT_DOMAIN); ?></b>
                            <span><?php _e('financial regulation lawyer', TEXT_DOMAIN); ?></span>
                        </div>
                    </div>
                    <h3 class="sub-h"><?php _e('Jury', TEXT_DOMAIN); ?></h3>
                    <p class="sub-p mx-auto">
                        <?php _e('The jury assesses the impact of the ideas, the size of the change (is the solution many times better, not 10% better), technical feasibility, viability and fit with Tuleva\'s values.', TEXT_DOMAIN); ?>
                    </p>
                    <div class="people">
                        <div class="person">
                            <?php echo $placeholder_avatar; ?>
                            <b><?php _e('To be announced', TEXT_DOMAIN); ?></b>
                            <span><?php _e('Member of the Tuleva Management Board', TEXT_DOMAIN); ?></span>
                        </div>
                        <div class="person">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/hakaton-kristi-saare.jpg" width="96" height="96" loading="lazy" decoding="async" alt="Kristi Saare">
                            <b>Kristi Saare</b>
                            <span><?php _e('Chair of the Supervisory Board of the Tuleva cooperative', TEXT_DOMAIN); ?></span>
                        </div>
                        <div class="person">
                            <?php echo $placeholder_avatar; ?>
                            <b><?php _e('To be announced', TEXT_DOMAIN); ?></b>
                            <span><?php _e('external expert', TEXT_DOMAIN); ?></span>
                        </div>
                        <div class="person">
                            <?php echo $placeholder_avatar; ?>
                            <b><?php _e('To be announced', TEXT_DOMAIN); ?></b>
                            <span><?php _e('external expert', TEXT_DOMAIN); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing" id="registreeru">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <div class="emphasis-box p-4 p-md-5 text-center text-navy">
                        <h2><?php _e('Ready to join in?', TEXT_DOMAIN); ?></h2>
                        <p class="lead mx-auto">
                            <?php _e('Register, mark your skills and let us know whether you are coming alone or with your team, and whether you already have an idea.', TEXT_DOMAIN); ?>
                        </p>
                        <p class="m-0 mt-4 pt-2"><a href="<?php echo esc_url($register_url); ?>" class="btn btn-lg d-block d-md-inline-block m-0 btn-primary"><?php _e('Register', TEXT_DOMAIN); ?></a></p>
                        <p class="deadline-note m-0 mt-3 pt-1">
                            <?php _e('Registration closes on', TEXT_DOMAIN); ?><br class="d-md-none"> <b><?php _e('30 September at 23.59', TEXT_DOMAIN); ?></b>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing pt-0 qa-block" id="kkk">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-9 col-xl-8">
                    <h2 class="text-center"><?php _e('Frequently asked questions', TEXT_DOMAIN); ?></h2>
                    <div class="faq-list">
                        <div class="qa__question-wrapper" id="kas-pean-olema-liige">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-1"><?php _e('Do I have to be a Tuleva member?', TEXT_DOMAIN); ?></a>
                            <div id="answer-1" class="collapse">
                                <p><?php _e('Yes. The hackathon is for Tuleva members: this is our shared company and we are building it together. If you are not a member yet, you can join and then register.', TEXT_DOMAIN); ?>
                                   <a href="<?php echo esc_url(__('https://tuleva.ee/tulundusyhistu/', TEXT_DOMAIN)); ?>"><?php _e('Read more about the cooperative →', TEXT_DOMAIN); ?></a></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kas-pean-oskama-programmeerida">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-2"><?php _e('Do I need to know how to code?', TEXT_DOMAIN); ?></a>
                            <div id="answer-2" class="collapse">
                                <p><?php _e('No. All kinds of skills are needed: design, data work, legal, business, marketing and product management. A good team is a mixed team.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('It helps if you can use AI assistants (for example Claude or OpenAI Codex). Prototypes at the hackathon are built largely with their help.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kas-mul-peab-olema-idee">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-3"><?php _e('Do I need to have an idea?', TEXT_DOMAIN); ?></a>
                            <div id="answer-3" class="collapse">
                                <p><?php _e('No. You can simply pick a challenge that speaks to you. Ideas are born on the spot with the help of your teammates. Even better if you arrive with at least the seed of an idea for how one of the challenges could be solved.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kas-saan-tulla-uksi">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-4"><?php _e('Can I come alone?', TEXT_DOMAIN); ?></a>
                            <div id="answer-4" class="collapse">
                                <p><?php _e('Yes. You will find a team either when registering, by marking your skills and interests, or on the spot on Friday evening.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="mis-saab-minu-ideest">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-5"><?php _e('What happens to my idea after the hackathon?', TEXT_DOMAIN); ?></a>
                            <div id="answer-5" class="collapse">
                                <p><?php _e('The winning idea gets a budget and support from Tuleva for further development, and if you wish you can keep building it yourself, by agreement also for a fee. We will agree the exact terms with the winner.', TEXT_DOMAIN); ?></p>
                                <p><?php _e('A hackathon produces many possible solutions, and that is the point: together we find the best one.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="kui-palju-osalemine-maksab">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-6"><?php _e('How much does it cost to take part?', TEXT_DOMAIN); ?></a>
                            <div id="answer-6" class="collapse">
                                <p><?php _e('Taking part is free and we take care of the catering. It helps if you have a paid plan for an AI assistant (for example Claude or OpenAI Codex). The limits of the free versions are quickly reached when building a prototype.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>

                        <div class="qa__question-wrapper" id="mida-kaasa-votta">
                            <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-7"><?php _e('What should I bring?', TEXT_DOMAIN); ?></a>
                            <div id="answer-7" class="collapse">
                                <p><?php _e('A laptop, your skills and an open mind. We take care of the rest.', TEXT_DOMAIN); ?></p>
                            </div>
                        </div>
                    </div>
                    <p class="faq-note text-center mt-5 mb-0">
                        <?php _e('Did not find the answer to your question? Write to', TEXT_DOMAIN); ?> <a href="mailto:tuleva@tuleva.ee">tuleva@tuleva.ee</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

</main>
