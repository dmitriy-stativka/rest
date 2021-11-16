<section class="programs">
    <div class="container">
        <h2 class="programs_top-title">Programs</h2>
        <div class="programs-list">
            <?php
                $params = array(
                    'post_type' => 'programs',
                    'posts_per_page' => 6
                );
                $query = new WP_Query( $params );
                ?>
                <?php if($query->have_posts()): ?>
                    <?php while ($query->have_posts()): $query->the_post() ?>
                        <?php $icon_of_program = get_field('icon_of_program');
                              $description_program = get_field('description_program');
                            ?>
                                <a href="<?php the_field('custom_link');?>" class="programs-link">
                                    <img class="programs-image" src="<?php echo $icon_of_program['url']?>" alt="">
                                    <h2 class="programs-title"><?php the_title();?></h2>
                                    <p class="programs-desc"><?php echo $description_program;?></p>
                                </a>

                        
                        <?php endwhile; ?>
                <?php endif; 
            ?>
        </div>
    </div>
</section>

<?php
    wp_reset_postdata();
    global $post;
?>