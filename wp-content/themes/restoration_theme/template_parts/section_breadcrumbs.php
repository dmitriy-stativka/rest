<?php
$breadcrubms = get_sub_field('breadcrumbs');
?>

<section class="breadcrumbs" style="<?php the_sub_field('css_styles');?>">
    <div class="container">
        <ul class="breadcrumb">
            <?php foreach($breadcrubms as $item) : ?>
                <li>
                    <?php if(isset($item['link']) && !empty($item['link'])): ?>
                        <a href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a>
                    <?php else: ?>
                        <?php echo $item['title']; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>