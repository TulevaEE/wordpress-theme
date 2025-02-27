<div class="<?php echo 'card shadow-sm d-flex flex-column m-0 p-4 br-4 text-left' . (get_sub_field('pill') ? ' card-selected' : ''); ?>">
    <div style="flex: 1 0 0;">
        <?php if (get_sub_field('pill')) : ?>
            <span class="badge badge-pill badge-primary fs-6 fw-medium"
                  style="position: absolute; left: 50%; transform: translate(-50%); margin-top: -37px;">
                <?php echo get_sub_field('pill'); ?>
            </span>
        <?php endif; ?>
        <h2 class="mt-2 mb-2 h4"><?php echo get_sub_field('title'); ?></h2>
        <?php echo get_sub_field('content'); ?>
    </div>
    <div class="my-5 d-flex flex-column flex-sm-row text-center" style="gap: 8px">
        <?php $card_left = get_sub_field('card_left'); ?>
        <?php if ($card_left) : ?>
            <div style="flex: 1 0 0;">
                <div class="bg-gray-2 p-4 br-2">
                    <p class="m-0">
                        <span class="d-block text-secondary"><?php echo $card_left['title']; ?></span>
                        <span class="mt-1 d-block lead fw-medium"><?php echo $card_left['value']; ?></span>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <?php $card_right = get_sub_field('card_right'); ?>
        <?php if ($card_right) : ?>
            <div style="flex: 1 0 0;">
                <div class="bg-gray-2 p-4 br-2">
                    <p class="m-0">
                        <span class="d-block text-secondary"><?php echo $card_right['title']; ?></span>
                        <span class="mt-1 d-block lead fw-medium"><?php echo $card_right['value']; ?></span>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="d-flex flex-column flex-sm-row" style="gap: 8px">
        <?php $button_left = get_sub_field('button_left'); ?>
        <?php if ($button_left) : ?>
            <a class="btn btn-lg border fw-normal" role="button"
               href="<?php echo $button_left['link']; ?>" style="flex: 1 0 0;">
                <?php echo $button_left['button_text']; ?>
            </a>
        <?php endif; ?>
        <?php $button_right = get_sub_field('button_right'); ?>
        <?php if ($button_right) : ?>
            <a class="btn btn-lg btn-outline-primary fw-medium" role="button"
               href="<?php echo $button_right['url']; ?>" style="flex: 1 0 0;">
                <?php echo $button_right['title']; ?>
            </a>
        <?php endif; ?>
    </div>
</div>

