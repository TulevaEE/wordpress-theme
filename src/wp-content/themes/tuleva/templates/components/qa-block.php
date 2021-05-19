<div id="<?php the_sub_field('component_id'); ?>" class="container mt-6">
    <div class="row">
        <div class="col">
            <h2 class="text-center"><?php _e('Frequently asked questions', TEXT_DOMAIN) ?></h2>
        </div>
    </div>
</div>

<div class="<?php echo get_component_classes('qa-block qa-block--collapsed container'); ?>">
    <div class="row">
        <div class="mx-md-auto col-md-10 col-lg-8">
            <?php if (have_rows('questions')) $i = 0; {
                while (have_rows('questions')) { $i++; the_row(); ?>
                    <div class="qa__question-wrapper" id="kkk-<?php echo $i; ?>">
                        <a class="btn btn-link qa__question" data-toggle="collapse" href="#answer-<?php echo $i; ?>">
                            <?php the_sub_field('question'); ?>
                        </a>
                        <div id="answer-<?php echo $i; ?>" class="collapse">
                            <?php the_sub_field('answer'); ?>
                        </div>
                    </div>
                    <hr />
                <?php  }
            } ?>
        </div>
    </div>
    <div class="row">
        <div class="mx-md-auto col-lg-10">
            <a href="#" class="qa-block__expand" data-open-text="<?php _e('More questions', TEXT_DOMAIN) ?>" data-close-text="<?php _e('Less questions', TEXT_DOMAIN); ?>"><?php _e('More questions', TEXT_DOMAIN) ?></a>
        </div>
    </div>
</div>
