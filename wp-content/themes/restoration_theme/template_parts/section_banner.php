<div class="banner">

    <?php if(get_sub_field('choice') == true){ ?>
        <div class="banner_img">
            <?php if(get_sub_field('show_r') == true){ ?>
                <svg class="banner_img-svg" viewBox="0 0 753 3513" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="R" style="mix-blend-mode:multiply">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M687.732 3513H514.326H-159.161L-873.206 2316.72V2378.41V3513H-1620V0H-654.412C-117.935 0 88.4007 75.9405 286.491 251.638C538.188 479.518 661.969 849.794 661.969 1210.53C661.969 1599.83 492.929 2165.57 -37.5634 2303.18L753 3513H687.732Z" fill="<?php the_sub_field('color_banner');?>"/>
                    </g>
                </svg>
            <?php } ?>
            
            <img src="<?php echo get_sub_field('image_banner')['url'];?>" alt="">
        </div>

        <div class="banner_info" style="background: <?php echo get_sub_field('color_banner');?>">
            <div class="banner_info-content">
                <h3 class="banner_info-title"><?php echo get_sub_field('title_of_banner');?></h3>
                <p class="banner_info-text"><?php echo get_sub_field('text_of_banner');?></p>
                <a style="<?php if(get_sub_field('width_button') == true) { ?> display: block; <?php }; ?>" class="banner_info-btn" href="<?php echo get_sub_field('button_link_of_banner');?>">
                    <?php echo get_sub_field('button_text_of_banner');?>
                </a>
            </div>
        </div>
    <?php }else{ ?>
        <div class="banner_info" style="background: <?php echo get_sub_field('color_banner');?>">
            <div class="banner_info-content">
                <h3 class="banner_info-title"><?php echo get_sub_field('title_of_banner');?></h3>
                <p class="banner_info-text"><?php echo get_sub_field('text_of_banner');?></p>
                <a style="<?php if(get_sub_field('width_button') == true) { ?> display: block; <?php }; ?>" class="banner_info-btn" href="<?php echo get_sub_field('button_link_of_banner');?>">
                    <?php echo get_sub_field('button_text_of_banner');?>
                </a>
            </div>
        </div>

        <div class="banner_img">
            <?php if(get_sub_field('show_r') == true){ ?>
                <svg class="banner_img-svg" viewBox="0 0 753 3513" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="R" style="mix-blend-mode:multiply">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M687.732 3513H514.326H-159.161L-873.206 2316.72V2378.41V3513H-1620V0H-654.412C-117.935 0 88.4007 75.9405 286.491 251.638C538.188 479.518 661.969 849.794 661.969 1210.53C661.969 1599.83 492.929 2165.57 -37.5634 2303.18L753 3513H687.732Z" fill="<?php the_sub_field('color_banner');?>"/>
                    </g>
                </svg>
            <?php }?>

            <img src="<?php echo get_sub_field('image_banner')['url'];?>" alt="">
        </div>
    <?php }?>
</div>