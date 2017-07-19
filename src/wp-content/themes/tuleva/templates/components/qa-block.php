<div class="bg-alt">
    <div class="container counter-block">
        <div class="row row-spacing-half">
            <h2 class="h1-xs text-center"><?php _e('Answers to frequently asked questions', TEXT_DOMAIN) ?></h2>
        </div>
    </div>
</div>

<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('container'); ?>">
    <div class="row">
        <div>
            <?php if (have_rows('questions')) $i = 0; {
                while (have_rows('questions')) { $i++; the_row(); ?>
                    <div class="qa__question-wrapper">
                        <h4 class="btn btn-link qa__question" data-toggle="collapse" data-target="#answer-<?php echo $i; ?>">
                            <?php the_sub_field('question'); ?>
                        </span>
                        </h4>
                        <div id="answer-<?php echo $i; ?>" class="collapse">
                            <?php the_sub_field('answer'); ?>
                        </div>
                    </div>
                    <hr />
                <?php  }
            } ?>
        </div>
    </div>
</div>
