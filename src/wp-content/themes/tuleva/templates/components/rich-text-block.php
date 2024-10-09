<?php
$bg_class = get_component_background_color_class();
$text = get_sub_field('text');
$container_class = get_sub_field('is_full_width') ? 'container-fluid' : 'container';
$column_classes = get_sub_field('is_full_width') ? 'col' : 'mx-auto col-lg-9 col-xl-8';
$padding = get_sub_field('padding');

if ($padding === 'none') {
    $padding_class = '';
} elseif ($padding === 'both') {
    $padding_class = 'py-6';
} elseif ($padding === 'top') {
    $padding_class = 'pt-6';
} elseif ($padding === 'bottom') {
    $padding_class = 'pb-6';
} else {
    $padding_class = 'py-6';
}
?>
<?php if (!$text) return; ?>
<section id="<?php the_sub_field('component_id'); ?>" class="<?php echo $padding_class; ?> <?php echo $bg_class; ?>">
    <div class="<?php echo $container_class; ?>">
        <div class="row">
            <div class="<?php echo $column_classes; ?>">
                <?php echo $text; ?>
            </div>
        </div>
    </div>
</section>
