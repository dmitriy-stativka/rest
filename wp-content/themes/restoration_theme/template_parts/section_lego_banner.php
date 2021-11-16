<?php
$image = get_sub_field('image');
$heading = get_sub_field('heading');
$text = get_sub_field('text');
?>

<section class="lego-banner" style="background-image: url(<?php echo $image; ?>);">
    <div class="container">
        <h2 class="lego-banner__heading"><?php echo $heading; ?></h2>
        <p class="lego-banner__text"><?php echo $text; ?></p>
    </div>
</section>