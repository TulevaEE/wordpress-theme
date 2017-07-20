    <?php //if (isset($_GET['signup'])) { ?>
      <script type="text/javascript">
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();
      });
      </script>

      <div class="proposal">
        <div class="hero">
          <div class="intro w-container">
            <div class="intro-text">
              <?php

                stream_context_set_default(
                    array(
                        'http' => array(
                            'method' => 'HEAD',
                            'header' => array( 
                                    "Authorization: Bearer b4adb192-29a8-4861-a697-c704947d0023" 
                            )                             
                        )
                    )
                );
                $headers = get_headers('https://onboarding-service.tuleva.ee/v1/members', 1);
                $memberCount = $headers['X-Total-Count'];

                printf( _n( null, 'Tuleva is a growing mutual company. Born as a citizens’s initiative, it has %s owners or members.', $count, TEXT_DOMAIN ), $memberCount );
              ?>    
            </div>
            <p class="lead"><?php _e('If you like Tuleva’s goals, want to support reaching them and want to benefit as an owner from everything Tuleva does in the future, then come and become a Tuleva member.', TEXT_DOMAIN); ?></p>
          </div>
        </div>
        <div class="proposal-wrapper">
          <div class="proposals w-container">
            <div class="proposal">
              <div class="proposal-description">
                <h4 class="proposal-title"><?php _e('Become a member and choose Tuleva pension fund', TEXT_DOMAIN); ?></h4>
                <ul class="benefits w-list-unstyled">
                  <li class="benefit"><?php _e('You accumulate your pension in our joint low-cost pension fund', TEXT_DOMAIN) ?></li>
                  <li class="benefit"><?php _e('Management fee is lower for members - just', TEXT_DOMAIN); ?>&nbsp;<span class="highlight"><strong>0,29%</strong></span><span class="member-fee-information-button" data-toggle="tooltip" data-placement="bottom" title="<?php _e('Tuleva liikmed saavad osa valitsemistasust liikmeboonusena tagasi. Liikmeboonus on omanikutulu, mida saavad igal aastal kõik Tuleva liikmed, kes on oma II samba vara toonud Tuleva pensionifondidesse. Igal aastal kanname Tuleva liikme isiklikule kapitalikontole 0,05% tema Tuleva pensionifondi kogunenud osakute väärtusest. Seega, fondi valitsemise kulude katteks jääb 0,29%.', TEXT_DOMAIN) ?>"></span></li>
                  <li class="benefit last"><?php _e('You support Tuleva development plans and help to improve Estonian pension system', TEXT_DOMAIN); ?></li>
                </ul>
              </div>
              <div class="proposal-cta">
                <div class="fee">
                  <div class="fee-cost">100€</div>
                  <div class="fee-helper"><?php _e('one-off joining fee', TEXT_DOMAIN); ?></div>
                <!-- </div><a class="button w-button" href="#">Astu liikmeks</a></div> -->
                </div><a class="button btn btn-primary" href="#inline-signup-anchor"><?php _e('Become a member', TEXT_DOMAIN); ?></a></div>
            </div>
            <div class="proposal">
              <div class="proposal-description">
                <h4 class="proposal-title"><?php _e('Simply choose Tuleva pension fund', TEXT_DOMAIN); ?></h4>
                <ul class="benefits w-list-unstyled">
                  <li class="benefit"><?php _e('Your save for your pension with a low-cost pension fund where owners care about your returns not bank’s.', TEXT_DOMAIN); ?></li>
                  <li class="benefit last"><?php _e('Three times lower than average management fee', TEXT_DOMAIN); ?>&nbsp;—&nbsp;<span class="highlight"><strong>0,34%</strong></span></li>
                </ul>
              </div>
              <div class="proposal-cta">
                <div class="fee">
                  <div class="fee-cost secondary"><?php _e('FREE', TEXT_DOMAIN); ?></div>
                  <div class="fee-helper"><?php _e('and it takes only 5 minutes', TEXT_DOMAIN); ?></div>
                </div><a class="link" href="https://tuleva.ee/fondid/"><?php _e('Learn about the funds', TEXT_DOMAIN); ?></a><a class="link" href="https://tuleva.ee/kuidas-tuua-pension-tulevasse/"><?php _e('How to change the fund', TEXT_DOMAIN); ?></a></div>
            </div>
          </div>
        </div>
      </div>
    <?php //} ?>
