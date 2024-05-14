<section id="<?php the_sub_field('component_id'); ?>">
    <div id="inline-signup-anchor"></div>
    <div class="container row-spacing-half">
        <div class="row text-center row-spacing-bottom-quarter">
            <div class="col">
                <h2><?php the_sub_field('heading'); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <?php if (have_rows('list')) { $i = 0; ?>
                    <?php while (have_rows('list')) { the_row(); $i++; ?>
                       <div class="inline-register__item">
                           <span class="inline-register__number"><?php echo $i; ?></span><span class="inline-register__title"><?php the_sub_field('heading'); ?></span>
                       </div>
                       <p class="inline-register__content">
                            <?php remove_filter('acf_the_content', 'wpautop'); ?>
                            <?php echo get_sub_field('text'); ?>
                            <?php add_filter('acf_the_content', 'wpautop'); ?>
                       </p>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="col-md-5">
                   <div id="inline-login" class="inline-register card p-4 bg-light">
                        <form id="login-form" action="https://pension.tuleva.ee/3rd-pillar-flow/confirm-mandate" method="GET">
                           <div class="form-group mb-2">
                              <label for="monthlyThirdPillarContribution"><span>Igakuine sissemakse</span></label>
                              <div>
                                 <div class="form-group "><input name="monthlyThirdPillarContribution" id="monthlyThirdPillarContribution" type="number" placeholder="Eurot kuus" class="form-control" value=""></div>
                              </div>
                           </div>
                           <div class="form-check checkbox mb-2">
                              <input type="checkbox" id="exchangeExistingThirdPillarUnits" name="exchangeExistingThirdPillarUnits" value="true">
                              <label for="exchangeExistingThirdPillarUnits" class="small">Soovin kogunenud osakud üle tuua</label>
                           </div>
                           <div class="form-group mb-3">
                                <span class="small text-muted">Sisenedes annad ühekordse loa küsida pensioniregistrist sinu andmed. Me ei jaga, müü ega kasuta neid kurjasti.</span>
                           </div>
                           <div><button type="submit" class="btn btn-primary btn-lg btn-block"><span>Logi sisse</span></button></div>
                        </form>
                   </div>
            </div>
        </div>
    </div>
</section>
