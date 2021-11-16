<?php
$form = get_sub_field('form');
$heading = get_sub_field('heading');
$sub_heading = get_sub_field('sub_heading');
?>

<section class="section-form">
    <div class="container">
        <h2 class="section-form__header"><?php echo $heading; ?></h2>
        <h4 class="section-form__subheader"><?php echo $sub_heading; ?></h4>
        <?php echo do_shortcode("$form"); ?>
    </div>
</section>