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
<div class="square-events">
    <div class="square-events-content">
        <a href="<?php echo get_the_permalink($id) ?>">
            <div class="events-img">
                <?php echo get_the_post_thumbnail($id) ?>
            </div>
        </a>
        <div class="events-content">
            <div class="event-top">
                <div class="date"><?php echo wp_date('F d, Y', strtotime($date_from)); ?></div>
                <div class="day"><?php echo wp_date('l', strtotime($date_from)); ?></div>
            </div>
            <div class="event-bottom">
                <div class="event-tag"><a
                            href="?category=<?php echo $terms[0]->term_id ?>"><?php echo $terms[0]->name ?></a></div>
                <h4 class="event-title"><a
                            href="<?php echo get_the_permalink($id) ?>"><?php echo get_the_title($id) ?></a></h4>
                <div class="event-meta-data"><?php echo get_field('hours_from', $id) ?>
                    - <?php echo get_field('hours_to', $id) ?></div>
                <div class="event-read-more">
                    <a href="<?php echo get_the_permalink($id) ?>">More information</a>
                </div>
            </div>
        </div>
    </div>
</div>
