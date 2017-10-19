<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('container'); ?>">
    <div class="row media-box-slider-responsive">
        <div class="col">
            <div class="media-box-list media-box-slider d-none d-sm-block">
                <ul>
                    <?php
                        if (get_sub_field('persons')) {
                            $persons = get_sub_field('persons');
                            $count = count($persons);
                            $chunk_size = ($count / 8) > 1.5 ? 8 : 4;
                            $chunks = array_chunk($persons, $chunk_size);

                            foreach ($chunks as $key => $value) { ?>
                                <li>
                                    <?php foreach ($value as $key2 => $value2) { ?>
                                        <div class="media-box">
                                            <div class="media-box__media">
                                                <img class="media-box__image" src="<?php echo $value2['image']; ?>" alt="<?php echo $value2['name']; ?>">
                                            </div>
                                            <div class="media-box__body">
                                                <h2 class="media-box__title"><?php echo $value2['name']; ?></h2>
                                                <p><?php echo $value2['description']; ?></p>
                                            </div>
                                        </div>

                                    <?php } ?>
                                </li>
                            <?php }
                        }
                    ?>
                </ul>
            </div>
            <div class="media-box-list media-box-slider d-block d-sm-none">
                <ul>
                    <?php if (have_rows('persons')) {
                        while (have_rows('persons')) { the_row(); ?>
                        <li>
                            <div class="media-box">
                                <div class="media-box__media">
                                    <img class="media-box__image" src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('name'); ?>">
                                </div>
                                <div class="media-box__body">
                                    <h2 class="media-box__title"><?php the_sub_field('name'); ?></h2>
                                    <p><?php the_sub_field('description'); ?></p>
                                </div>
                            </div>
                        </li>
                    <?php  }
                    } ?>
                </ul>
            </div>
        </div>    
    </div>
</div>
