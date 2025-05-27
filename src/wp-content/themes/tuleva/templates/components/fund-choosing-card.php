<div class="<?php echo 'card shadow d-flex flex-column m-0 p-4 br-4 text-left' . (get_sub_field('pill') ? ' card-selected' : ''); ?>">
    <div style="flex: 1 0 0;">
        <?php if (get_sub_field('pill')) : ?>
            <span class="badge rounded-pill text-bg-primary fs-6 fw-medium"
                  style="position: absolute; left: 50%; transform: translate(-50%); margin-top: -37px;">
                <?php echo get_sub_field('pill'); ?>
            </span>
        <?php endif; ?>
        <h2 class="my-3 h4"><?php echo get_sub_field('title'); ?></h2>
        <?php echo get_sub_field('content'); ?>
    </div>
    <div class="my-4 d-flex flex-column flex-sm-row bg-gray-2 br-2 px-4 px-sm-0 py-sm-4 text-center tiles">
        <?php $tile_first = get_sub_field('tile_first'); ?>
        <?php if ($tile_first) : ?>
            <div class="py-4 px-sm-4 py-sm-0" style="flex: 1 0 0;">
                <p class="m-0">
                    <span class="d-block text-secondary"><?php echo $tile_first['label']; ?></span>
                    <span class="mt-1 d-block lead fw-medium"><?php echo $tile_first['value']; ?></span>
                </p>
            </div>
        <?php endif; ?>
        <?php $tile_second = get_sub_field('tile_second'); ?>
        <?php if ($tile_second) : ?>
            <div class="py-4 px-sm-4 py-sm-0" style="flex: 1 0 0;">
                <p class="m-0">
                    <span class="d-block text-secondary"><?php echo $tile_second['label']; ?></span>
                    <span class="mt-1 d-block lead fw-medium"><?php echo $tile_second['value']; ?></span>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-2">
        <?php $button_secondary = get_sub_field('button_secondary'); ?>
        <?php if ($button_secondary) : ?>
            <a class="btn btn-lg border fw-normal" role="button"
               href="<?php echo $button_secondary['link']; ?>" style="flex: 1 0 0;">
                <?php echo $button_secondary['button_text']; ?>
            </a>
        <?php endif; ?>
        <?php $button_primary = get_sub_field('button_primary'); ?>
        <?php if ($button_primary) : ?>
            <a class="btn btn-lg btn-outline-primary fw-medium" role="button"
               href="<?php echo $button_primary['url']; ?>" style="flex: 1 0 0;">
                <?php echo $button_primary['title']; ?>
            </a>
        <?php endif; ?>
    </div>
</div>

