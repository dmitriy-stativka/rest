<section class="impact" style="margin-top: <?php the_sub_field('impact_margin_top'); ?>;">
    <h2 class="impact-top-title"><?php the_sub_field('top_title_of_impact');?></h2>

    <?php if(get_sub_field('hide_on_mobile_r') == true){ 
        $hide_r = 'hide_mob';
    }else{ $hide_r = '';}?>

    <svg class="impact--img <?php echo $hide_r;?>" viewBox="0 0 753 3513" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="R" style="mix-blend-mode:multiply">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M687.732 3513H514.326H-159.161L-873.206 2316.72V2378.41V3513H-1620V0H-654.412C-117.935 0 88.4007 75.9405 286.491 251.638C538.188 479.518 661.969 849.794 661.969 1210.53C661.969 1599.83 492.929 2165.57 -37.5634 2303.18L753 3513H687.732Z" fill="<?php the_sub_field('color_of_r');?>"/>
        </g>
    </svg>
    


    <div class="impact__content" style="background: <?php the_sub_field('background_of_block'); ?>;">
        <div class="container">
            <h2 class="impact--title" style="color: <?php the_sub_field('color_of_title'); ?>; font-size: <?php the_sub_field('title_font_size'); ?>"><?php the_sub_field('title_impact'); ?></h2>
            <ul class="impact_list">
                <?php 
                    $size = get_sub_field('item_font-size');
                    $font_face =  get_sub_field('item_font-face');
                    while ( have_rows('items_impact') ) : the_row(); ?>
                    <li class="impact_item">
                        <span class="impact_item--number" style="color: <?php the_sub_field('number_color');?>;"><?php the_sub_field('number_impact');?></span>
                        <?php if(get_sub_field('under_numb_text')){ ?>
                            <i class="impact_item--under_text" style="color: <?php echo get_sub_field('color_for_under_numb_text');?>"><?php the_sub_field('under_numb_text');?> </i>
                        <?php } ?>
                        <p class="impact_item--desc" style="color: <?php the_sub_field('color_of_description');?>;font-size: <?php echo $size; ?>; font-family: <?php echo $font_face; ?>;"><?php the_sub_field('description_impact');?></p>
                    </li>                            
                <?php endwhile; ?>
            </ul>

            <?php if(!empty(get_sub_field('text_impact'))) : ?>
                <p class="impact--text"><?php the_sub_field('text_impact');?></p>
            <?php endif; ?>

            <?php if(!empty(get_sub_field('text_button_1')) || !empty(get_sub_field('text_button_2'))) : ?>
                <div class="impact--buttons">
                    <?php if(!empty(get_sub_field('text_button_1'))) : ?>
                        <a class="impact-btn" href="<?php the_sub_field('link_button_1');?>"><?php the_sub_field('text_button_1');?></a>
                    <?php endif; ?>

                    <?php if(!empty(get_sub_field('text_button_2'))) : ?>
                        <a class="impact-btn impact-btn-white" href="<?php the_sub_field('link_button_2');?>"><?php the_sub_field('text_button_2');?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>