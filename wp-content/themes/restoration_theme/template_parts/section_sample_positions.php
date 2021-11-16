<section class="sample_positions">
    <div class="container">
    
        <h2 class="sample_positions-title"><?php the_sub_field('title_of_sample_block');?></h2>
        <p class="sample_positions--desc"><?php the_sub_field('description_of_sample_block');?></p>
        <a href="<?php the_sub_field('link_button_of_sample_block');?>" class="sample_positions--btn"><?php the_sub_field('text_button_of_sample_block');?></a>

        <ul class="sample_positions-list">
            <?php
                $params = array(
                    'post_type' => 'sample',
                    'posts_per_page' => 3,
                );
                $query = new WP_Query( $params );
                ?>
                <?php if($query->have_posts()): ?>
                    <?php while ($query->have_posts()): $query->the_post() ?>
                        <?php $image_of_new = get_field('image_of_new'); ?>
                            <li class="sample_positions--item"> 
                                <a href="<?php echo get_field('link_of_item');?>" class="sample_positions--link">
                                    <span class="sample_positions--price"><?php the_field('price');?></span>
                                    <h2 class="sample_positions--title"><?php the_title();?></h2>
                                    <p class="sample_positions--desc"><?php the_field('description_of_positions');?></p>
                                    <i class="sample_positions--apply">Apply</i>
                                </a>
                            </li>
                        <?php endwhile; ?>
                <?php endif; 
            ?>
        </ul>
    </div>  

</section>

<?php
    wp_reset_postdata();
    global $post;
?>