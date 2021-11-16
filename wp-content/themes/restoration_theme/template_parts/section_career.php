<section class="career">
    <div class="container">
        <div class="career-info" style="background: <?php the_sub_field('background_color_of_block');?>">

                <h2 class="career-info--title"><?php the_sub_field('title_of_career');?></h2>
        
            <div class="career-info-row">
                <?php if(get_sub_field('left_&_right_block') == true){ ?>
                    <div class="career-info-img">
                        <?php $image_person = get_sub_field('image_person');?>
                        <img src="<?php echo $image_person['url'];?>" alt="<?php echo $image_person['alt'];?>">
                    </div>
                    <div class="career-info-desc">
                        <span class="career-info-testimonial">Testimonial</span>
                        <i class="career-info-review"><?php the_sub_field('review');?></i>
                        <b class="career-info-name"><?php the_sub_field('name_person');?></b>
                        <p class="career-info-position"><?php the_sub_field('position_of_person');?></p>
                        <?php if(get_sub_field('read_more_button')){ ?>
                            <a class="career-info-more" href="<?php the_sub_field('read_more_testimonials_link');?>"><?php the_sub_field('read_more_button');?></a>
                        <?php };?>

                    </div>
                <?php }else{ ?>
                    <div class="career-info-desc">
                        <span class="career-info-testimonial">Testimonial</span>
                        <i class="career-info-review"><?php the_sub_field('review');?></i>
                        <b class="career-info-name"><?php the_sub_field('name_person');?></b>
                        <p class="career-info-position"><?php the_sub_field('position_of_person');?></p>
                        <?php if(get_sub_field('read_more_button')){ ?>

                            <a class="career-info-more" href="<?php the_sub_field('read_more_testimonials_link');?>"><?php the_sub_field('read_more_button');?></a>
                            
                        <?php };?>
                    </div>
                    <div class="career-info-img">
                        <?php $image_person = get_sub_field('image_person');?>
                        <img src="<?php echo $image_person['url'];?>" alt="<?php echo $image_person['alt'];?>">
                    </div>
                <?php } ?>
            </div>
            
            <?php if(get_sub_field('descripton_ofcareer')){ ?>
                <p class="career-info-description"><?php the_sub_field('descripton_ofcareer');?></p>
            <?php };?>
    
            <?php if(get_sub_field('link_of_button')){ ?>
                <a class="career-info-btn" href="<?php the_sub_field('link_of_button');?>"><?php the_sub_field('text_of_button');?></a>
            <?php };?>
        </div>
    </div>
</section>