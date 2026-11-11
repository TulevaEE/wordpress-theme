<?php
/**
 * Hero of the II pillar 2-4-6 landing page: the heading and the monthly contribution
 * calculator. Also shown on the front page through the second_pillar_payment_rate_hero
 * layout, so the two never drift apart.
 */
?>
<section class="hero">
  <div class="hero-in">
    <div class="hero-text">
      <h1><?php _e('Raise your II&nbsp;pillar contribution', TEXT_DOMAIN); ?></h1>
      <p class="dek"><?php _e('For every 100&nbsp;€ you set aside from your pay, 128&nbsp;€ goes into your II&nbsp;pillar.', TEXT_DOMAIN); ?></p>
      <p class="dek-note"><?php _e('The difference is your income tax benefit. <strong>The deadline is 30&nbsp;November.</strong>', TEXT_DOMAIN); ?></p>
    </div>
    <div class="hero-card">
      <section class="card calculator rounded-4" id="calc" aria-label="<?php esc_attr_e('II pillar tax benefit calculator', TEXT_DOMAIN); ?>">
        <div class="card-body p-2">
          <div class="card bg-gray-2 p-3 rounded-3">
            <div class="mb-3">
              <label for="gross" class="form-label"><?php _e('Your gross salary', TEXT_DOMAIN); ?></label>
              <input type="text" class="form-control form-control-lg text-end" id="gross" value="3000" inputmode="numeric" autocomplete="off">
            </div>
            <div>
              <span class="form-label d-block" id="rateLbl"><?php _e('Your contribution from gross salary', TEXT_DOMAIN); ?></span>
              <div class="btn-group d-flex" id="segRate" role="group" aria-labelledby="rateLbl">
                <input type="radio" class="btn-check" name="rate" id="rate2" value="2" autocomplete="off">
                <label for="rate2" class="btn btn-outline-primary w-100">2%</label>
                <input type="radio" class="btn-check" name="rate" id="rate4" value="4" autocomplete="off">
                <label for="rate4" class="btn btn-outline-primary w-100">4%</label>
                <input type="radio" class="btn-check" name="rate" id="rate6" value="6" autocomplete="off" checked>
                <label for="rate6" class="btn btn-outline-primary w-100">6%</label>
              </div>
            </div>
          </div>
          <div class="px-3 pt-4 pb-3 calculator-sum" role="status" aria-live="polite">
            <div class="calculator-sum__row">
              <span class="calculator-sum__operator" aria-hidden="true"></span>
              <span class="calculator-sum__label"><?php _ex('From your net salary', 'II pillar 2-4-6 page', TEXT_DOMAIN); ?></span>
              <span class="calculator-sum__amount" id="cNet">140 €</span>
            </div>
            <div class="calculator-sum__row calculator-sum__row--tax-win">
              <span class="calculator-sum__operator" aria-hidden="true">+</span>
              <span class="calculator-sum__label"><?php _e('From income tax', TEXT_DOMAIN); ?></span>
              <span class="calculator-sum__amount" id="cTax">40 €</span>
            </div>
            <div class="calculator-sum__row calculator-sum__row--secondary">
              <span class="calculator-sum__operator" aria-hidden="true">+</span>
              <span class="calculator-sum__label"><?php _e('From social tax', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" role="button" tabindex="0" aria-label="<?php esc_attr_e('More information', TEXT_DOMAIN); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php esc_attr_e('The state adds 4% of your gross salary, funded from social tax.', TEXT_DOMAIN); ?>"></span></span>
              <span class="calculator-sum__amount" id="cState">120 €</span>
            </div>
            <div class="calculator-sum__row calculator-sum__row--total">
              <span class="calculator-sum__operator" aria-hidden="true">=</span>
              <span class="calculator-sum__label"><?php _e('Into your II pillar each month', TEXT_DOMAIN); ?></span>
              <span class="calculator-sum__amount" id="cInflow">300 €</span>
            </div>
          </div>
          <div class="d-grid">
            <a class="btn btn-primary btn-lg mt-2" href="<?php echo get_app_url('/2nd-pillar-payment-rate'); ?>"><?php _e('Raise your contribution', TEXT_DOMAIN); ?></a>
          </div>
          <p class="mt-2 mb-0 py-2 text-secondary text-center small"><?php _e('Log in and make your choice. You can change it every year.', TEXT_DOMAIN); ?></p>
        </div>
      </section>
    </div>
  </div>
</section>
