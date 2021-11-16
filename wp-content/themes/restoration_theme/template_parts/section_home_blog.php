<?php
$heading = get_sub_field('heading');
$post_1 = get_sub_field('left_post');
$post_2 = get_sub_field('right_post');

$post_1_img = get_the_post_thumbnail_url($post_1);
$post_2_img = get_the_post_thumbnail_url($post_2);

$post_1_url = get_permalink($post_1);
$post_2_url = get_permalink($post_2);

?>

<section class="home_blog">
    <div class="container">
        <h4><?php echo $heading; ?></h4>
        <div class="home_blog-wrap">
            <a href="<?php echo $post_1_url; ?>" class="home-blog-post">
                <div class="home-blog-post__img">
                    <img src="<?php echo $post_1_img; ?>" alt="blog_img_<?php echo $post_1; ?>">
                </div>
                <div class="home-blog-post__content">
                    <h4><?php echo get_the_title($post_1); ?></h4>
                    <p><?php echo get_the_excerpt($post_1); ?></p>
                </div>
            </a>
            <a href="<?php echo $post_2_url; ?>" class="home-blog-post">
                <div class="home-blog-post__img">
                    <img src="<?php echo $post_2_img; ?>" alt="blog_img_<?php echo $post_2; ?>">
                </div>
                <div class="home-blog-post__content">
                    <h4><?php echo get_the_title($post_2); ?></h4>
                    <p><?php echo get_the_excerpt($post_2); ?></p>
                </div>
            </a>
        </div>
    </div>
</section>