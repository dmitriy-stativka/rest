<?php
if ($id) {
    $date_from = get_field('date_from', $id);
    $terms = get_the_terms($id, 'events_cat');

} else {
    $id = get_the_ID();
    $date_from = get_field('date_from', get_the_ID());
    $terms = get_the_terms(get_the_ID(), 'events_cat');
}
?>
<div class="upcoming-event-item">
    <div class="event-thumbnail">
        <?php echo get_the_post_thumbnail($id, 'full') ?>
    </div>
    <div class="event-content">
        <div class="event-tag"><a href="?category=<?php echo $terms[0]->term_id ?>"><?php echo $terms[0]->name ?></a>
        </div>
        <h4 class="event-title"><?php echo get_the_title($id) ?></h4>
        <!--<div class="event-meta-data">April 23 | 6:00PM - 7:00PM | Virtual - Zoom</div>-->
        <div class="event-meta-data">
            <?php echo wp_date('F d', strtotime(get_field('date_from', $id))); ?>
            | <?php echo get_field('hours_from', $id) . ' - ' . get_field('hours_to', $id) ?>
            | <?php echo get_field('location', $id) ?>
        </div>
        <div class="event-read-more">
            <a href="<?php echo get_the_permalink($id) ?>">More information</a>
        </div>
    </div>
</div>

