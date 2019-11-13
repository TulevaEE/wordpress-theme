<section id="main-header">
    <div class="bg-hero-main d-flex flex-column">
        <div class="container my-auto">
            <div class="row align-items-center py-5">
                <div class="col-lg-6 text-center text-lg-left pr-lg-5 pr-lg-6">
                    <h1><?php _e('A pension should benefit you. Not your bank.', TEXT_DOMAIN); ?></h1>
                    <p class="lead text-navy"><?php _e('Tuleva pension funds belong to their investors — with up to 3.3 times lower fees than bank-run funds.*', TEXT_DOMAIN); ?></p>

                    <?php
                    if (ICL_LANGUAGE_CODE=='et') {
                        echo '<a href="/tasud-alla/" class="btn btn-primary btn-lg btn-block mb-3">Miks valida Tuleva?</a>';
                    }
                    if (ICL_LANGUAGE_CODE=='en') {
                        echo '<a href="/en/transfer-pension-tuleva/" class="btn btn-primary btn-lg btn-block mb-3">Choose the right fund for you</a>';
                    }
                    ?>
                    <p class="small text-navy mb-md-5 mb-lg-0"><?php _e('Switching funds is free (and takes only five minutes).', TEXT_DOMAIN); ?></p>
                </div>
                <div class="col-lg-6">
                    <div class="card calculator shadow-md">
                        <div class="card-body p-3 text-center">
                            <form class="d-flex flex-row justify-content-center pb-3">
                                <div class="d-inline-block align-items-center text-medium mr-2">
                                    <span class="calculator__heading"><?php _e("If you're", TEXT_DOMAIN); ?></span>
                                    <input class="form-control d-inline-block mx-1" id="age" type="number" value="29" min="18" max="65" onkeyup="calculateSaving()" onchange="calculateSaving()">
                                    <span class="calculator__heading"><?php _e("years old and earn", TEXT_DOMAIN); ?></span>
                                    <input class="form-control d-inline-block mx-1" id="netWage" type="number" value="1800" step="100" onkeyup="calculateSaving()" onchange="calculateSaving()">
                                    <span class="calculator__heading"><?php _e("euros per month (net),", TEXT_DOMAIN); ?>
                                        <?php _e("your second pillar", TEXT_DOMAIN); ?>
                                        <a id="calculator" href="#calculatorModal"><?php _e("could", TEXT_DOMAIN); ?></a> <?php _e("grow", TEXT_DOMAIN); ?></span>
                                </div>
                            </form>
                            <div>
                                <div class="p-3 calculator__comparison-row">
                                    <select class="form-control calculator__comparison-fund" name="pensionFunds" id="comparisonFund" onchange="calculateSaving()">
                                        <option value="average">
                                        <?php _e("Estonian pension fund average", TEXT_DOMAIN); ?>
                                        </option>
                                        <option value="LIK75">
                                            LHV Pensionifond Indeks
                                        </option>
                                        <option value="LXK00">
                                            LHV Pensionifond XS
                                        </option>
                                        <option value="LSK00">
                                            LHV Pensionifond S
                                        </option>
                                        <option value="LMK25">
                                            LHV Pensionifond M
                                        </option>
                                        <option value="LLK50">
                                            LHV Pensionifond L
                                        </option>
                                        <option value="LXK75">
                                            LHV Pensionifond XL
                                        </option>
                                        <option value="NPK50">
                                            Luminor Pensionifond A
                                        </option>
                                        <option value="NPK75">
                                            Luminor Pensionifond A Pluss
                                        </option>
                                        <option value="NPK25">
                                            Luminor Pensionifond B
                                        </option>
                                        <option value="NPK00">
                                            Luminor Pensionifond C
                                        </option>
                                        <option value="SEK75">
                                            SEB Energiline Pensionifond
                                        </option>
                                        <option value="SIK75">
                                            SEB Energiline Pensionifond Indeks
                                        </option>
                                        <option value="SEK00">
                                            SEB Konservatiivne Pensionifond
                                        </option>
                                        <option value="SEK25">
                                            SEB Optimaalne Pensionifond
                                        </option>
                                        <option value="SEK50">
                                            SEB Progressiivne Pensionifond
                                        </option>
                                        <option value="SWK00">
                                            Swedbank Pensionifond K1 (Konservatiivne strateegia)
                                        </option>
                                        <option value="SWK25">
                                            Swedbank Pensionifond K2 (Tasakaalustatud strateegia)
                                        </option>
                                        <option value="SWK50">
                                            Swedbank Pensionifond K3 (Kasvustrateegia)
                                        </option>
                                        <option value="SWK75">
                                            Swedbank Pensionifond K4 (Aktsiastrateegia)
                                        </option>
                                        <option value="SWK99">
                                            Swedbank Pensionifond K90-99 (Elutsükli strateegia)
                                        </option>
                                    </select>
                                    <div class="calculator__comparison-result">
                                        <h5 class="mb-0" id="future-value">485 144€</h5>
                                        <small class="text-secondary text-nowrap"><?php _e("Annual fees", TEXT_DOMAIN); ?> <span id="fund-fee">1,34%</span></small>
                                    </div>
                                </div>
                                <div class="p-3 calculator__comparison-row">
                                    <h6 class="calculator__comparison-fund"><?php _e("In Tuleva pension fund", TEXT_DOMAIN); ?></h6>
                                    <div class="calculator__comparison-result">
                                        <h5 class="mb-0" id="future-value-tuleva">545 654€</h5>
                                        <small class="text-secondary"><?php _e('Annual fees <span id="fund-fee">0.47%</span>', TEXT_DOMAIN); ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="calculator__savings">
                                <h6 class="calculator__savings-title"><?php _e("Cost of high fees", TEXT_DOMAIN); ?></h6>
                                <h1 class="calculator__sum" id="tuleva-saving">-</h1>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (ICL_LANGUAGE_CODE=='et') {
                        echo '<a href="https://tuleva.ee/tasud-alla/" class="btn btn-link btn-block text-medium text-uppercase text-center my-3">Miks tasud olulised on?</a>';
                    }
                    ?>

                </div>
            </div>
        </div>
        <!-- Credentials -->
        <div class="container-fluid bg-blueish-gray d-none d-sm-block py-4">
            <div class="container">
                <div class="row">
                    <div class="col col-md-6 d-flex">
                        <img src="<?php echo get_template_directory_uri() ?>/img/icon-lock.svg" alt="Tuleva on turvaline valik" class="mr-4">
                        <div class="d-flex flex-column justify-content-center">
                            <p class="mb-2 text-navy"><?php _e('Every bit as safe as a bank-run fund.', TEXT_DOMAIN); ?></p>
                            <a id="security" href="#securityModal" class="text-uppercase text-medium"><?php _e('Find out more', TEXT_DOMAIN); ?></a>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-flex align-items-center">
                        <span class="membercount mr-4">6302</span>
                        <p class="mb-0 text-navy"><?php _e('Estonians invest in Tuleva pension funds.', TEXT_DOMAIN); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
