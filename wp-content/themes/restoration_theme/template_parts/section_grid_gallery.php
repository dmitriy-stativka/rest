<section class="grid_gallery">
    <h2 class="grid_gallery--title"><?php the_sub_field('title_of_grid_gallery');?></h2>
    <div class="grid">
        <?php while ( have_rows('gallery_grid') ) : the_row();?>
            <div class="grid--img">
                <img src="<?php $image_of_gallery = get_sub_field('image_of_gallery'); echo $image_of_gallery['url'];?>" alt="<?php echo $image_of_gallery['alt'];?>">
            </div>    
        <?php endwhile; ?>
    </div>
</section>