<?php
$heading = get_sub_field('heading');
$heading_color = get_sub_field('heading_color');
$blocks = get_sub_field('blocks');
$read_more = get_sub_field('read_more_button');
$read_more_text = get_sub_field('read_more_text');
$read_more_display = get_sub_field('from_new_string');
$read_more_color = get_sub_field('color');

$grid = count($blocks);

if($grid > 4) {
    $grid = 4;
}
?>

<section class="grid-blocks grid-<?php echo $grid; ?>">
    <div class="container">
        <div class="grid-blocks__inner">
            <h2 style="color:<?php echo $heading_color; ?>;"><?php echo $heading; ?></h2>
            <div class="grid-blocks__wrap">
                <?php foreach($blocks as $block) : ?>
                    <div class="grid-post">

                    

                        <a href="<?php the_field('custom_link', $block);?>" class="grid-post__img">
                            <img src="<?php echo get_the_post_thumbnail_url($block); ?>" alt="post_img_<?php echo $block; ?>">
                        </a>
                        <div class="grid-post__content">
                            <h4>
                                <a href="<?php the_field('custom_link', $block);?>"><?php echo get_the_title($block); ?></a>
                            </h4>
                            <p>


                                
                                
                                <?php
                                    the_field('content_text', $block);

                                if($read_more) : ?>
                                    <a href="<?php the_field('custom_link', $block);?>" class="grid-post__more" style="display:<?php echo $read_more_display == true ? 'block' : 'inline'; ?>; margin-top:<?php echo $read_more_display == true ? '20px' : '0'; ?>; color:<?php echo $read_more_color; ?>;"><?php echo $read_more_text; ?></a>
                                <?php endif;
                                ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>