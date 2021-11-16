<?php
$top_text = get_sub_field('top_text');
$top_title = get_sub_field('blocks_heading');
$top_subtitle = get_sub_field('blocks_sub_heading');
$blocks = get_sub_field('blocks');
$btn_text = get_sub_field('more_button_text');
$btn_url = get_sub_field('more_button_url');
?>

<section class="stats">
    <div class="container">
        <div class="top-text">
            <?php echo $top_text; ?>
        </div>
        <div class="blocks-content">
            <h4><?php echo $top_title; ?></h4>
            <h5><?php echo $top_subtitle; ?></h5>
            <div class="blocks-wrap">
                <?php foreach ($blocks as $block) : ?>
                <div class="stat-block">
                    <p class="stat-block__top"><?php echo $block['top_text']; ?></p>
                    <p class="stat-block__middle"><?php echo $block['middle_text']; ?></p>
                    <p class="stat-block__bottom"><?php echo $block['bottom_text']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if($btn_text){ ?>
                <a href="<?php echo $btn_url; ?>" class="green-btn"><?php echo $btn_text; ?></a>
            <?php }?>
        </div>
    </div>
</section>