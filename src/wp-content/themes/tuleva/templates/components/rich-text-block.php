<?php
$bg_class = get_component_background_color_class();
$text = get_sub_field('text');
?>
<?php if (!$text) return; ?>
<section id="<?php the_sub_field('component_id'); ?>" class="py-6 <?php echo $bg_class; ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php echo $text; ?>
            </div>
        </div>
    </div>
</section>
