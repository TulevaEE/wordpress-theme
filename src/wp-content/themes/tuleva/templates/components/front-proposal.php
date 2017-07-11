    <?php if (isset($_GET['signup'])) { ?>
      <script type="text/javascript">
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();   
      });
      </script>

      <div class="proposal">
        <div class="hero">
          <div class="intro w-container">
            <div class="intro-text"><?php _e('3403 eesti inimest tulid kokku ja tegid&nbsp;omale ise pensionifondi. Veel pole hilja nendega liituda.'); ?></div>
            <p class="lead"><?php _e('Kui sulle Tuleva eesmärgid meeldivad, tahad toetada nende saavutamist ja omanikuna kasu saada kõigest, mida Tuleva edaspidi ette võtab, kutsume ka sind Tuleva liikmeks astuma.'); ?></p>
          </div>
        </div>
        <div class="proposal-wrapper">
          <div class="proposals w-container">
            <div class="proposal">
              <div class="proposal-description">
                <h4 class="proposal-title"><?php _e('Astu liikmeks ja vali Tuleva pensionifond'); ?></h4>
                <ul class="benefits w-list-unstyled">
                  <li class="benefit"><?php _e('Kogud pensioni meie ühises madalate kuludega pensionifondis') ?></li>
                  <li class="benefit"><?php _e('Liikmele on valitsemistasu soodsam — kõigest'); ?>&nbsp;<span class="highlight"><strong>0,29%</strong></span><span class="member-fee-information-button" data-toggle="tooltip" data-placement="bottom" title="Tuleva liikmed saavad osa valitsemistasust liikmeboonusena tagasi. Liikmeboonus on omanikutulu, mida saavad igal aastal kõik Tuleva liikmed, kes on oma II samba vara toonud Tuleva pensionifondidesse. Igal aastal kanname Tuleva liikme isiklikule kapitalikontole 0,05% tema Tuleva pensionifondi kogunenud osakute väärtusest. Seega, fondi valitsemise kulude katteks jääb 0,29%."></span></li>
                  <li class="benefit last"><?php _e('Toetad Tuleva arengut ja aitad teha Eesti pensionisüsteemi paremaks'); ?></li>
                </ul>
              </div>
              <div class="proposal-cta">
                <div class="fee">
                  <div class="fee-cost">100€</div>
                  <div class="fee-helper"><?php _e('ühekordne liitumistasu'); ?></div>
                <!-- </div><a class="button w-button" href="#">Astu liikmeks</a></div> -->
                </div><a class="button btn btn-primary" href="#inline-signup-anchor"><?php _e('Astu liikmeks'); ?></a></div>
            </div>
            <div class="proposal">
              <div class="proposal-description">
                <h4 class="proposal-title"><?php _e('Vali lihtsalt Tuleva pensionifond'); ?></h4>
                <ul class="benefits w-list-unstyled">
                  <li class="benefit"><?php _e('Kogud pensioni madalate kuludega pensionifondis, mille omanikud hoolivad sinu, mitte panga vara kasvust.'); ?></li>
                  <li class="benefit last"><?php _e('Keskmisest kolm korda soodsam valitsemistasu'); ?>&nbsp;—&nbsp;<span class="highlight"><strong>0,34%</strong></span></li>
                </ul>
              </div>
              <div class="proposal-cta">
                <div class="fee">
                  <div class="fee-cost secondary"><?php _e('TASUTA'); ?></div>
                  <div class="fee-helper"><?php _e('ja võtab vaid 5 minutit'); ?></div>
                </div><a class="link" href="https://tuleva.ee/fondid/"><?php _e('Tutvu fondidega'); ?></a><a class="link" href="https://tuleva.ee/kuidas-tuua-pension-tulevasse/"><?php _e('Fondivahetuse juhend'); ?></a></div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>