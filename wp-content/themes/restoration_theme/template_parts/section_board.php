<section class="board">
    <div class="container">
        <h2 class="board--title"><?php the_sub_field('title_of_block');?></h2>
        <div class="board-list">



            <?php
                $featured_posts = get_sub_field('board_of_directors');
                $index = 0;
                if( $featured_posts ): ?>
                    <?php foreach( $featured_posts as $post ):  ?>

                        <div class="board-item" data-index="<?php echo $index;?>">
                            <div class="board-img">
                                <img src="<?php $image_of_person = get_field('image_of_person'); echo $image_of_person['url'];?>" alt="<?php echo $image_of_person['alt'];?>">
                            </div>
                            <i class="board-name"><?php the_title(); ?></i>
                            <span class="board-position"><?php the_field('position_of_person');?></span>
                            <p class="board-minidesc"><?php the_field('mini_description');?></p>
                        </div>


                    <?php $index++; endforeach; ?>
                    <?php 
                    // Reset the global post object so that the rest of the page works correctly.
                    wp_reset_postdata(); ?>
                <?php endif; ?>






        </div>
    </div>

    <div class="board-popup">
        <div class="board-popup-swiper">
            <div class="swiper-wrapper">

            <?php
                $featured_posts = get_sub_field('board_of_directors');
                $index = 0;
                if( $featured_posts ): ?>
                    <?php foreach( $featured_posts as $post ):  ?>
                        <?php $image = get_field('image'); ?>
                        <div class="swiper-slide">
                            <div class="board-popup-item" data-index="<?php echo $index;?>">
                                <div class="board-popup-img">
                                    <img src="<?php $image_of_person = get_field('image_of_person'); echo $image_of_person['url'];?>" alt="<?php echo $image_of_person['alt'];?>">
                                </div>
                                <div class="board-popup-info">
                                    <h2 class="board-popup-name"><?php the_title();?></h2>
                                    <span class="board-position"><?php the_field('position_of_person');?></span>

                                    <div class="board-popup-description">
                                        <?php the_field('description_of_person');?>
                                    </div>
                                </div>
                            <div class="board-popup-item-close"></div>
                            </div>
                        </div>
                    <?php $index++; endforeach; ?>
                    <?php 
                    // Reset the global post object so that the rest of the page works correctly.
                    wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
    wp_reset_postdata();
    global $post;
?>