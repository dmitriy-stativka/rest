<section class="special">
    <h2 class="special--title"><?php the_sub_field('title_of_block');?></h2>
    <p class="special--desc"><?php the_sub_field('description_of_block');?></p>
    <ul class="special-list">
        <?php while ( have_rows('items_of_block') ) : the_row();?>
                <li class="special-item">
                    <div class="special-item--img">
                        <img src="<?php $image_of_item = get_sub_field('image_of_item'); echo $image_of_item['url'];?>" alt="<?php echo $image_of_item['alt'];?>">                    
                    </div>
                    <h3 class="special-item-title"><?php the_sub_field('title_of_item');?></h3>
                    <span class="special-item-desc"><?php the_sub_field('description_of_item');?></span>
                </li>                         
        <?php endwhile; ?>
    </ul>
</section>