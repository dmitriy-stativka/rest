<?php
$builder = get_sub_field('builder');
?>

<section class="block-builder">
    <div class="container">
        <?php foreach($builder as $section) : ?>
            <?php if($section['acf_fc_layout'] === 'heading') : ?>
                <<?php echo $section['type'] . ' style="color:'.$section['color'].'; text-align:'.$section['aligment'].';"'; ?>><?php echo $section['text']; ?></<?php echo $section['type']; ?>>
            <?php elseif ($section['acf_fc_layout'] === 'text_block') : ?>
                <?php echo $section['text']; ?>
            <?php elseif ($section['acf_fc_layout'] === 'image') : $width = $section['full_width'] === true ? '100%' : 'auto'; ?>
                <div class="image" style="text-align:<?php echo $section['aligment']; ?>;">
                    <img src="<?php echo $section['image']['url']; ?>" alt="<?php echo $section['image']['title']; ?>" style="width:<?php echo $width; ?>;">
                </div>
            <?php elseif ($section['acf_fc_layout'] === 'video') : ?>
                <div class="video" style="text-align:<?php echo $section['aligment']; ?>;">
                    <?php
                    $type = $section['video_source'];
                    $link =  $type === 'local' ? $section['load_video'] : $section['video_link'];
                    if( $type === 'local'): ?>
                        <video poster="<?php echo $section['poster']; ?>" id="player" class="js-player" playsinline controls>
                            <source src="<?php echo $link; ?>" type="video/mp4" />
                        </video>
                    <?php else: ?>
                        <div class="plyr__video-embed js-player" id="player">
                            <iframe
                                src="<?php echo $link; ?>"
                                allowfullscreen
                                allowtransparency    
                                allow="autoplay"
                            ></iframe>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif ($section['acf_fc_layout'] === 'testimonial') : ?>
                <div class="builder-testimonial">
                    <p class="builder-testimonial__text"><?php echo $section['text']; ?></p>
                    <div class="builder-testimonial__wrap">
                        <img src="<?php echo $section['image']['url']; ?>" alt="<?php echo $section['image']['title']; ?>">
                        <div>
                            <p class="builder-testimonial__name"><?php echo $section['name']; ?></p>
                            <p class="builder-testimonial__position"><?php echo $section['position']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>