<?php
$visible_questions_count = 10;
?>
<section id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('mt-0 qa-block qa-block--collapsed container'); ?>">
    <h2 class="m-0 section-spacing-bottom text-center"><?php _e('Frequently asked questions', TEXT_DOMAIN) ?></h2>
    <div class="row">
        <div class="mx-auto col-lg-9 col-xl-8">
            <?php if (have_rows('questions')) $i = 0; {
                while (have_rows('questions')) { $i++; the_row(); ?>
                    <div class="<?php echo get_qa_question_wrapper_classes(['qa__question-wrapper'], $i, $visible_questions_count); ?>" id="kkk-<?php echo $i; ?>">
                        <a class="qa__question collapsed" data-bs-toggle="collapse" href="#answer-<?php echo $i; ?>">
                            <?php echo get_sub_field('question'); ?>
                        </a>
                        <div id="answer-<?php echo $i; ?>" class="collapse">
                            <?php echo get_sub_field('answer'); ?>
                        </div>
                    </div>
                <?php  }
            } ?>
        </div>
    </div>
    <?php if ($i > $visible_questions_count) { ?>
        <div class="row">
            <div class="mx-auto col-lg-9 col-xl-8 text-center">
                <a href="#" class="qa-block__expand" data-open-text="<?php _e('More questions', TEXT_DOMAIN) ?>" data-close-text="<?php _e('Less questions', TEXT_DOMAIN); ?>"><?php _e('More questions', TEXT_DOMAIN) ?></a>
            </div>
        </div>
    <?php } ?>
</section>