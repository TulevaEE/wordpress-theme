  <div id="modal-calculator" class="modal-full">
    <div class="close-button-modal-calculator">
      <img src="<?php echo get_template_directory_uri() ?>/img/icon-close.svg" alt="Close">
    </div>
    <div class="modal-full__container">

      <div class="container pt-3 pt-md-6">
        <div class="modal-full__content">
          <div class="row">
            <div class="col">
              <h1 class="text-center mb-1 mb-md-6"><?php _e('How does the calculator work?', TEXT_DOMAIN) ?></h1>
            </div>
          </div>
          <div class="row mb-5">
            <div class="col-md-7 p-4">
              <p><?php _e("The world's leading analysts have determined that fees are the surest predictor of returns: the lower the fees, the better prospects for growth. (And higher fees correlate with poorer results.)", TEXT_DOMAIN) ?></p>
              <p><?php _e('Expected returns depend greatly on the evolving rate of return, and neither we nor anyone else are able to guarantee a 5% annual return.', TEXT_DOMAIN) ?></p>
              <p><?php _e('In a low-cost index fund your assets grow hand-in-hand with the average returns of world markets. Low-cost index funds have outperformed Estonian pension funds every year to date, but past performance is no guarantee of future performance.', TEXT_DOMAIN) ?></p>
            </div>
            <div class="col-md-5 p-4 bg-blue-washed rounded">
              <h3 class="mb-3"><?php _e('Assumptions', TEXT_DOMAIN) ?></h3>
              <div class="d-flex flex-row flex-wrap">
                <div class="d-flex justify-content-between eeldus">
                  <p class="mb-0"><?php _e("Funds' average annual return before management fees", TEXT_DOMAIN) ?></p>
                  <p class="mb-0 text-bold">5%</p>
                </div>
                <div class="d-flex justify-content-between eeldus">
                  <p class="mb-0"><?php _e('Average annual salary growth', TEXT_DOMAIN) ?></p>
                  <p class="mb-0 text-bold">3%</p>
                </div>
                <div class="d-flex justify-content-between eeldus">
                  <p class="mb-0"><?php _e('Minimum eligible age', TEXT_DOMAIN) ?></p>
                  <p class="mb-0 text-bold"><?php _e('21yrs', TEXT_DOMAIN) ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
