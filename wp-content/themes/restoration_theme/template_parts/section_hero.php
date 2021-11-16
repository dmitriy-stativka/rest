<section class="hero-section">
    <?php if(get_sub_field('content_block_or_video') === true):
            $video = get_sub_field('video');
            $type = $video['video_source'];
            $link =  $type === 'local' ? $video['load_video'] : $video['video_link'];
            if( $type === 'local'): ?>
                <video poster="<?php echo $video['poster']; ?>" id="player" class="js-player" playsinline controls>
                    <source src="<?php echo $link; ?>" type="video/mp4" />
                </video>
            <?php else: ?>
                <div class="plyr__video-embed js-player" id="player">
                    <iframe
                        src="<?php echo $link;  ?>"
                        allowfullscreen
                        allowtransparency    
                        allow="autoplay"
                    ></iframe>
                </div>
           <?php endif; ?>
    <?php else:
        $content = get_sub_field('content'); ?>
        <img class="hero-section_image" src="<?php echo $content['hero_image']['url'];?>" alt="<?php echo $content['hero_image']['alt'];?>">    
        <div class="container">
            <div class="hero">
                <?php if (!empty($content['pre_title'])) : ?>
                    <p class="hero__pretitle" style="color: <?php echo $content['color_of_pre-title'];?>;"><?php echo $content['pre_title'];?></p>
                <?php endif; ?>

                <h1 class="hero__title" 
                        style="
                        font-size: <?php echo $content['title_font-size']; ?>;
                        --hero-title-mobile: <?php echo $content['title_mobile_font-size']; ?>;
                        ">
                    <?php echo $content['hero_title'];?>
                </h1>
                <p class="hero__description">
                    <?php echo $content['hero_description'];?>
                </p>

                <?php if(isset($content['hero_button_text']) && !empty($content['hero_button_text'])): ?>       
                    <a href="<?php echo $content['hero_button_link'];?>" id="<?php echo $content['button_color_hover'];?>" class="hero__more-btn btn" style="background-color: <?php echo $content['button_color'];?>; <?php echo $content['button_padding']; ?>">
                        <?php echo $content['hero_button_text'];?>
                    </a>   
                <?php endif; ?>
               
                <?php 
                    if( get_sub_field('show_share_social_links') == true ){ ?>
                        <div class="post_share">
                            <span class="post_share-span">Share</span>
                            <?php echo do_shortcode('[Sassy_Social_Share]');?>
                        </div>
                <?php }?>
            </div>
        </div>
    <?php endif;?>    
</section>