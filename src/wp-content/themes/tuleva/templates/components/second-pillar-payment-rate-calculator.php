<?php
require_once get_template_directory() . '/helpers/deadline-calculations.php';
$now = get_current_time_tallinn();
$effective_year = get_second_pillar_payment_rate_effective_year($now);
$net_salary_label = $effective_year === (int) $now->format('Y') + 1
    ? __('Net salary from next year', TEXT_DOMAIN)
    : sprintf(__('Net salary %d', TEXT_DOMAIN), $effective_year);
$contribution_label = __('Contribution per month', TEXT_DOMAIN);
$tax_win_label = __('Yearly gain from income tax', TEXT_DOMAIN);
$tax_win_words = explode(' ', $tax_win_label);
$tax_win_last_word = array_pop($tax_win_words);
?>
<div class="card calculator payment-rate-calculator">
    <div class="card-body">
        <div class="payment-rate-calculator__inputs">
            <div class="payment-rate-calculator__field">
                <label for="paymentRateSalary" class="payment-rate-calculator__label"><?php _e('Enter your gross salary', TEXT_DOMAIN); ?></label>
                <div class="payment-rate-calculator__salary">
                    <input type="text" id="paymentRateSalary" value="2000" inputmode="numeric" autocomplete="off">
                    <span aria-hidden="true">€</span>
                    <svg class="payment-rate-calculator__edit" aria-hidden="true" width="18" height="18" viewBox="0 0 16 16" fill="currentColor"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/></svg>
                </div>
            </div>
            <fieldset class="payment-rate-calculator__field">
                <legend class="payment-rate-calculator__label" id="paymentRateLabel"><?php _e('Your II pillar contribution', TEXT_DOMAIN); ?></legend>
                <div class="payment-rate-calculator__rates">
                    <?php foreach ([2, 4, 6] as $rate) { ?>
                        <input type="radio" class="btn-check" name="paymentRate" id="paymentRate<?php echo $rate; ?>"
                               value="<?php echo $rate; ?>" autocomplete="off" <?php echo $rate === 6 ? 'checked' : ''; ?>>
                        <label for="paymentRate<?php echo $rate; ?>" class="payment-rate-calculator__rate"><?php echo $rate; ?>%</label>
                    <?php } ?>
                </div>
            </fieldset>
        </div>
        <hr class="payment-rate-calculator__divider">
        <div class="payment-rate-calculator__results">
            <p class="visually-hidden" role="status"></p>
            <dl class="payment-rate-calculator__figures">
                <div class="payment-rate-calculator__figure">
                    <dt><?php echo esc_html($net_salary_label); ?></dt>
                    <dd data-figure="netSalary" data-label="<?php echo esc_attr($net_salary_label); ?>">1595&nbsp;€</dd>
                </div>
                <div class="payment-rate-calculator__figure">
                    <dt><?php echo esc_html($contribution_label); ?></dt>
                    <dd data-figure="contribution" data-label="<?php echo esc_attr($contribution_label); ?>">120&nbsp;€</dd>
                </div>
                <div class="payment-rate-calculator__figure payment-rate-calculator__figure--tax-win">
                    <dt>
                        <?php echo esc_html(implode(' ', $tax_win_words)); ?>
                        <span class="text-nowrap"><?php echo esc_html($tax_win_last_word); ?><span class="inline-help d-inline-block"
                            role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="<?php echo esc_attr__('The calculation assumes 22% income tax, a 700 € monthly tax-free allowance and a 1.6% unemployment insurance contribution.', TEXT_DOMAIN); ?>"></span></span>
                        <span class="payment-rate-calculator__note" data-tax-win-note
                              data-tax-win="<?php echo esc_attr__('goes to your II pillar account', TEXT_DOMAIN); ?>"
                              data-tax-free="<?php echo esc_attr__('The tax-free allowance covers your whole salary', TEXT_DOMAIN); ?>"><?php _e('goes to your II pillar account', TEXT_DOMAIN); ?></span>
                    </dt>
                    <dd data-figure="yearlyTaxWin" data-label="<?php echo esc_attr($tax_win_label); ?>">316&nbsp;€</dd>
                </div>
            </dl>
            <a href="<?php echo get_app_url('/2nd-pillar-payment-rate'); ?>" class="btn btn-primary btn-lg payment-rate-calculator__cta">
                <?php _e('Increase contribution', TEXT_DOMAIN); ?>
            </a>
            <p class="payment-rate-calculator__fine-print">
                <?php _e('Submitting the application is <strong>free</strong> and takes only 2 minutes.', TEXT_DOMAIN); ?>
            </p>
        </div>
    </div>
</div>
