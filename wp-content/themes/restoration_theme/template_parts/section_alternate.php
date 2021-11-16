<section class="alternate">
    <div class="container">
        <h2 style="color: <?php the_sub_field('color_of_title');?>" class="alternate--title"> <?php the_sub_field('title_of_alternate');?></h2>
        <?php while ( have_rows('alternate_items') ) : the_row();?>
        <?php if(get_sub_field('left_&_right') == true){ ?>
            <div class="alternate_item">
                <div class="alternate_item-img">
                    <img src="<?php $image_of_alternate = get_sub_field('image_of_alternate'); echo $image_of_alternate['url'];?>" alt="<?php echo $image_of_alternate['alt']?>">
                </div>
                <div class="alternate_item-info">
                    <h2 class="alternate_item-title"><?php the_sub_field('title_of_alternate');?></h2>
                    <div class="alternate_item-text"><?php the_sub_field('text_of_alternate');?></div>
                </div>
            </div>    
        <?php }else{ ?>
            <div class="alternate_item">
                <div class="alternate_item-info">
                    <h2 class="alternate_item-title"><?php the_sub_field('title_of_alternate');?></h2>
                    <div class="alternate_item-text"><?php the_sub_field('text_of_alternate');?></div>
                </div>
                <div class="alternate_item-img">
                    <img src="<?php $image_of_alternate = get_sub_field('image_of_alternate'); echo $image_of_alternate['url'];?>" alt="<?php echo $image_of_alternate['alt']?>">
                </div>            
            </div> 
        <?php }?>                        
        <?php endwhile; ?>
    </div>
</section>