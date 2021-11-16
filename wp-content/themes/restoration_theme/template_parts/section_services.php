<?php

$heading = get_sub_field('heading');
$services = get_sub_field('services');
$i = 0
?>

<section class="services">
    <div class="container">
        <h2><?php echo $heading; ?></h2>
        <div class="services-wrap">
            <div class="services-inner">
                <?php foreach($services as $service) : ?>


                    <?php if($i % 2 == 0) : ?>
                        </div>
                        <div class="services-inner">
                    <?php endif; ?>

                    <div class="service-item">
                        <div class="service-item__img">
                            <?php if( get_field("custom_link", $service) ) : ?>
                                <a href="<?php echo get_field("custom_link", $service); ?>">
                                    <?php echo get_the_post_thumbnail($service); ?>
                                </a>
                                <h4 class="service-item__title">
                                    <a href="<?php echo get_field("custom_link", $service); ?>">
                                        <?php echo get_the_title($service); ?>
                                    </a>
                                </h4>
                            <?php else : ?>
                                <?php echo get_the_post_thumbnail($service); ?>
                                <h4 class="service-item__title"><?php echo get_the_title($service); ?></h4>
                            <?php endif; ?>
                        </div>
                        <!-- <div class="service-item__content"><?php echo do_shortcode(get_the_content(null, false, $service)); ?></div> -->
                        <div class="service-item__content"><?php echo get_field('content_text', $service); ?></div>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        </div>
    </div>
</section>