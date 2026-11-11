<main id="main" class="page-container second-pillar-246-page">

  <?php get_template_part('templates/components/second-pillar-246-hero'); ?>

  <div class="article">

    <h2><?php _e('A small difference in pay, a big difference in the future', TEXT_DOMAIN); ?></h2>

    <p><?php _e('See how much more you save when you choose a higher contribution.<br>These savings are yours and can be inherited.', TEXT_DOMAIN); ?></p>

    <section class="calc" id="calc2" aria-label="<?php esc_attr_e('II pillar savings growth calculator', TEXT_DOMAIN); ?>">

      <div class="calc-main">
        <div class="bg-gray-2 p-3 rounded-3 calc-inputs">
          <div class="mb-3">
            <label for="gross2" class="form-label"><?php _e('Your gross salary', TEXT_DOMAIN); ?></label>
            <input type="text" class="form-control form-control-lg text-end" id="gross2" value="3000" inputmode="numeric" autocomplete="off">
          </div>
          <div class="mb-3">
            <label for="age" class="form-label"><?php _e('Your age', TEXT_DOMAIN); ?></label>
            <input type="text" class="form-control form-control-lg text-end" id="age" value="35" inputmode="numeric" autocomplete="off">
          </div>
          <div>
            <label for="ret" class="form-label"><?php _e('Expected return per year', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" role="button" tabindex="0" aria-label="<?php esc_attr_e('More information', TEXT_DOMAIN); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php esc_attr_e('What you end up with depends on actual returns, and neither we nor anyone else can guarantee a return.', TEXT_DOMAIN); ?>"></span></label>
            <div class="row">
              <div class="col-12 return-rate">
                <input type="range" class="form-range" id="ret" min="-10" max="10" step="1" value="0" data-unit="%">
                <span class="custom-tooltip">0%</span>
                <button type="button" class="historic-return-rate small text-secondary border-0 bg-transparent"><?php _e('historical return on shares 7%', TEXT_DOMAIN); ?></button>
              </div>
            </div>
          </div>
        </div>

        <div class="wealth" id="wealth">
          <h4><?php _e('What you add by age&nbsp;65', TEXT_DOMAIN); ?></h4>
          <div class="wealth-viz">
            <div class="vbars" id="vbars" role="group" aria-label="<?php esc_attr_e('Savings by retirement age at a 2%, 4% or 6% contribution', TEXT_DOMAIN); ?>">
              <button type="button" class="vbar dim" data-r="2" aria-pressed="false"><span class="vtrack"><span class="vval" id="vv2"></span><span class="vcol" id="vc2"></span></span><span class="vlbl">2%</span></button>
              <button type="button" class="vbar dim" data-r="4" aria-pressed="false"><span class="vtrack"><span class="vval" id="vv4"></span><span class="vcol" id="vc4"></span></span><span class="vlbl">4%</span></button>
              <button type="button" class="vbar" data-r="6" aria-pressed="true"><span class="vtrack"><span class="vval" id="vv6"></span><span class="vcol" id="vc6"></span></span><span class="vlbl">6%</span></button>
            </div>
          </div>
        </div>
      </div>

      <div class="calc-breakdown faq-list">
        <div class="qa__question-wrapper">
          <a class="qa__question collapsed" data-bs-toggle="collapse" href="#vara-kasv" role="button" aria-expanded="false" aria-controls="vara-kasv"><?php _e('Where does this growth come from?', TEXT_DOMAIN); ?></a>
          <div id="vara-kasv" class="collapse">
          <div class="stack"><span class="s-net" id="sNet"></span><span class="s-tax" id="sTax"></span><span class="s-state" id="sState"></span><span class="s-grow" id="sGrow"></span></div>
          <div class="legend">
            <div><span class="dot d-net"></span> <?php _ex('From your net salary', 'II pillar 2-4-6 page', TEXT_DOMAIN); ?> <span class="v" id="lNet"></span></div>
            <div><span class="dot d-tax"></span> <?php _e('From income tax', TEXT_DOMAIN); ?> <span class="v" id="lTax"></span></div>
            <div><span class="dot d-state"></span> <?php _e('From social tax', TEXT_DOMAIN); ?> <span class="v" id="lState"></span></div>
            <div><span class="dot d-grow"></span> <?php _e('From returns', TEXT_DOMAIN); ?> <span class="v" id="lGrow"></span></div>
            <div class="legend-total"><?php _e('Total', TEXT_DOMAIN); ?> <span class="v" id="lTotal"></span></div>
          </div>
        </div>
        </div>
      </div>
      <p class="calc-links"><a class="text-reset" data-bs-toggle="collapse" href="#arvutuse-eeldused" role="button" aria-expanded="false" aria-controls="arvutuse-eeldused"><?php _e('How do we calculate this?', TEXT_DOMAIN); ?></a></p>
      <div id="arvutuse-eeldused" class="collapse"><div class="calc-detail"><ul>
          <li><?php _e('The calculation starts from the new year and shows how much you would add to your II&nbsp;pillar with the contribution you choose, on top of what you have already saved.', TEXT_DOMAIN); ?></li>
          <li><?php _e('Income tax 22%, tax-free income 700&nbsp;€ a month, unemployment insurance contribution 1.6%.', TEXT_DOMAIN); ?></li>
          <li><?php _e('The state adds an amount equal to 4% of your gross salary, taken from social tax. The social tax part slightly reduces your <a class="text-reset" href="https://tuleva.ee/en/analysis/which-is-more-beneficial-pillar-i-or-pillar-ii/">I&nbsp;pillar pension</a>.', TEXT_DOMAIN); ?></li>
          <li><?php _e('We have assumed that your salary grows by 3% a year.', TEXT_DOMAIN); ?></li>
          <li><?php _e('We use the expected yearly return you pick in the calculator. Past returns do not tell you what future returns will be. This example is not meant to predict how your savings will grow, but to show how the amount you set aside today affects what you have in the future.', TEXT_DOMAIN); ?></li>
          <li><?php _e('The income tax exemption applies to contributions. When you use the money, the tax depends on when and how you take it out. From early retirement age you can use it on favourable terms and pay only 10% income tax. If you choose a fund pension (taking the money out bit by bit and leaving the rest invested), you pay no income tax at all.', TEXT_DOMAIN); ?></li>
          <li><?php _e('The calculations are illustrative and educational, not personal investment advice. Long-term amounts are illustrative projections, not a promise: the value of an investment can go up as well as down over time. Please note that raising your contribution reduces your monthly net salary.', TEXT_DOMAIN); ?></li>
        </ul></div></div>
    </section>

  </div>

  <section class="closing" id="alusta">
    <div class="emphasis-box p-4 p-md-5 text-center text-navy">
      <h2><?php _e('Save more in your II&nbsp;pillar', TEXT_DOMAIN); ?></h2>
      <p class="lead mx-auto"><?php _e('If you raise your contribution by <strong>30&nbsp;November</strong>, you save more from the new year and get a bigger income tax benefit.', TEXT_DOMAIN); ?></p>
      <p class="m-0 mt-4 pt-2"><a href="<?php echo get_app_url('/2nd-pillar-payment-rate'); ?>" class="btn btn-lg d-block d-md-inline-block m-0 btn-primary"><?php _e('Raise your contribution', TEXT_DOMAIN); ?></a></p>
      <p class="closing-note"><?php _e('You can change your choice every year.', TEXT_DOMAIN); ?></p>
    </div>
  </section>

  <div class="article article-faq qa-block">
    <h2><?php _e('Good to know', TEXT_DOMAIN); ?></h2>

    <div class="faq-list">
    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-1" role="button" aria-expanded="false" aria-controls="kkk-1"><?php _e('Why is it worth saving more in the II&nbsp;pillar?', TEXT_DOMAIN); ?></a>
      <div id="kkk-1" class="collapse">
        <p><?php _e('A basic rule of investing: use the tax-advantaged options first. II&nbsp;pillar contributions are free of income tax. If you set aside <strong>100&nbsp;€</strong> from your pay, <strong>128&nbsp;€</strong> goes into your II&nbsp;pillar. If you invested the same money elsewhere, 100&nbsp;€ would get there.', TEXT_DOMAIN); ?></p>
        <p><?php _e('The state adds 4% of your gross salary, funded from social tax, whether you contribute 2, 4 or 6% yourself. (<a class="inline" href="https://tuleva.ee/en/analysis/which-is-more-beneficial-pillar-i-or-pillar-ii/">See how this affects your I&nbsp;pillar</a>)', TEXT_DOMAIN); ?></p>
      </div>
    </div>

    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-2" role="button" aria-expanded="false" aria-controls="kkk-2"><?php _e('What does raising your contribution mean?', TEXT_DOMAIN); ?></a>
      <div id="kkk-2" class="collapse">
        <p><?php _e('Right now, <strong>2%</strong> of your pay goes into your II&nbsp;pillar by default. Once a year you can change this: choose 4% or 6%, or go back to a lower contribution later.', TEXT_DOMAIN); ?></p>
        <p><?php _e('If you apply by 30&nbsp;November, the new rate applies from 1&nbsp;January. It is deducted from the salary you are paid in January, even if that salary is for work done in December.', TEXT_DOMAIN); ?></p>
      </div>
    </div>

    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-3" role="button" aria-expanded="false" aria-controls="kkk-3"><?php _e('What if I suddenly need the money?', TEXT_DOMAIN); ?></a>
      <div id="kkk-3" class="collapse">
        <p><?php _e('For the unexpected, an emergency fund works best: money you keep separately and can reach straight away. The II&nbsp;pillar is for the long term, so choose a contribution you can comfortably set aside.', TEXT_DOMAIN); ?></p>
        <p><?php _e('Your choice is not final: you can change your contribution every year. You can also take money out of the II&nbsp;pillar if you need to, but tax-wise it is not the wisest move.', TEXT_DOMAIN); ?></p>
      </div>
    </div>

    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-4" role="button" aria-expanded="false" aria-controls="kkk-4"><?php _e('Is it worth raising my contribution if I\'m already over 60?', TEXT_DOMAIN); ?></a>
      <div id="kkk-4" class="collapse">
        <p><?php _e('Yes. The tax benefit does not depend on your age: for every 100&nbsp;€ you set aside from your pay, 128&nbsp;€ goes into your II&nbsp;pillar.', TEXT_DOMAIN); ?></p>
        <p><?php _e('From the age of 60 you can also use your II&nbsp;pillar savings on better terms: income tax is 10%, or 0% with a fund pension paid out regularly.', TEXT_DOMAIN); ?></p>
      </div>
    </div>
    </div>
  </div>

</main>
