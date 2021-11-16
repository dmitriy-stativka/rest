<?php
$heading = get_sub_field('heading');
$accordion = get_sub_field('accordion');
?>

<section class="section-accordion">
    <div class="container">
        <h2 class="section-accordion__header"><?php echo $heading; ?></h2>
        <div class="section-accordion__wrap">
            <?php foreach($accordion as $item) : ?>
            <div class="accordion-item">
                <div class="accordion-item__heading">
                    <div class="accordion-item__heading-title">
                        <?php echo $item['title']; ?>
                    </div>
                    <div class="accordion-item__heading-toggle">
                    </div>
                </div>
                
                <div class="accordion-item__body">
                    <?php echo $item['content']; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>