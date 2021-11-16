<?php
$slider = get_sub_field('slider');
$heading = get_sub_field('heading');

?>

<section class="section-slider">
    <div class="container">
        <h3><?php echo $heading; ?></h3>
        <div class="carousel-slider">
            <?php foreach($slider as $key => $slide) : ?>
                <div class="slide">
                    <img src="<?php echo $slide['slide']['url']; ?>" alt="<?php echo $slide['slide']['title']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>