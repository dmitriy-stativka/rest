<section class="testimonial">
    <div class="container">
        <?php if(get_sub_field('left_right') == true){ ?>
            <div class="testimonial_content">
                <div class="testimonial_text">
                    <h2 class="testimonial--title"><?php the_sub_field('banner_title');?></h2>
                    <i style="<?php the_sub_field('font-size_of_review');?>" class="testimonial--review"><?php the_sub_field('banner_review');?></i>
                    <p class="testimonial--name"><?php the_sub_field('name_reviewer');?></p>
                    <span class="testimonial--position"><?php the_sub_field('position_reviewer');?></span>

                    <?php if(get_sub_field('banner_button_link')){ ?>
                        <a class="testimonial--btn" href="<?php the_sub_field('banner_button_link');?>"><?php the_sub_field('banner_button_text');?></a>
                    <?php }?>
                </div>
                <div class="testimonial_img">
                    <img src="<?php echo get_sub_field('image_banner')['url'];?>" alt="">
                </div>
            </div>
        <?php }else{ ?>
            
            <div class="testimonial_content">
                <div class="testimonial_img">
                    <img src="<?php echo get_sub_field('image_banner')['url'];?>" alt="">
                </div>
                <div class="testimonial_text">
                    <h2 class="testimonial--title"><?php the_sub_field('banner_title');?></h2>
                    <i style="<?php the_sub_field('font-size_of_review');?>;" class="testimonial--review"><?php the_sub_field('banner_review');?></i>
                    <p class="testimonial--name"><?php the_sub_field('name_reviewer');?></p>
                    <span class="testimonial--position"><?php the_sub_field('position_reviewer');?></span>

                    <?php if(get_sub_field('banner_button_link')){ ?>
                        <a class="testimonial--btn" href="<?php the_sub_field('banner_button_link');?>"><?php the_sub_field('banner_button_text');?></a>
                    <?php }?>
                </div>
            </div>
        <?php } ?>
    </div>
</section>