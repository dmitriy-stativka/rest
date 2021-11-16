<section class="donors">
    <div class="container">

    <h2 class="donors--title"><?php the_sub_field('title_of_donors');?></h2>

    <?php if(get_sub_field('desc_of_donors')){ ?>
        <p class="donors--desc"><?php the_sub_field('desc_of_donors');?></p>
    <?php };?>

        <ul class="donors_list">
            <?php while ( have_rows('items_of_donors') ) : the_row();?>
                <li class="donors_item">
                    <a href="<?php the_sub_field('link_of_button');?>">
                        <img src="<?php $image_of_button = get_sub_field('image_of_button'); echo $image_of_button['url'];?>" alt="">
                    </a>
                </li>                            
            <?php endwhile; ?>
        </ul>
    </div>
</section>