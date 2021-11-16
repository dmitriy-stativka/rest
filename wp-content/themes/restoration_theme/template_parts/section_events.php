<?php
$event_1 = get_sub_field('event_1');
$event_2 = get_sub_field('event_2');

$event_1_img = get_the_post_thumbnail_url($event_1, 'event-small');
$event_2_img = get_the_post_thumbnail_url($event_2, 'event-small');

$event_1_url = get_permalink($event_1);
$event_2_url = get_permalink($event_2);

$event_1_date = get_field('date_from', $event_1);

if ($date_to_1 = get_field('date_to', $event_1)) {
    $event_1_date .= ' - ' . $date_to_1;
}

if ($hours_from_1 = get_field('hours_from', $event_1)) {
    $event_1_date .= ' | ' . $hours_from_1;
}

if ($hours_to_1 = get_field('hours_to', $event_1)) {
    $event_1_date .= ' - ' . $hours_to_1;
}

$event_2_date = get_field('date_from', $event_2);

if ($date_to_2 = get_field('date_to', $event_2)) {
    $event_2_date .= ' - ' . $date_to_2;
}

if ($hours_from_2 = get_field('hours_from', $event_2)) {
    $event_2_date .= ' | ' . $hours_from_2;
}

if ($hours_to_2 = get_field('hours_to', $event_2)) {
    $event_2_date .= ' - ' . $hours_to_2;
}

?>

<section class="events">
    <div class="container">
        <div class="events-wrap">
            <div class="events-inner <?php if( !$event_1 || !$event_2 ) echo "-one-element"; ?>">
                <?php if( $event_1 ) : ?>
                    <a href="<?php echo $event_1_url; ?>" class="home-event">
                        <div class="home-event__img">
                            <img src="<?php echo $event_1_img; ?>" alt="event_img_<?php echo $event_1; ?>">
                        </div>
                        <div class="home-event__content">
                            <span class="event-date"><?php echo $event_1_date; ?></span>
                            <h4><?php echo get_the_title($event_1); ?></h4>
                            <p><?php echo get_the_excerpt($event_1); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if( $event_2 ) : ?>
                    <a href="<?php echo $event_2_url; ?>" class="home-event">
                        <div class="home-event__img">
                            <img src="<?php echo $event_2_img; ?>" alt="event_img_<?php echo $event_2; ?>">
                        </div>
                        <div class="home-event__content">
                            <span class="event-date"><?php echo $event_2_date; ?></span>
                            <h4><?php echo get_the_title($event_2); ?></h4>
                            <p><?php echo get_the_excerpt($event_2); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
            <a href="<?php the_sub_field('more_button_url'); ?>"  id="<?php the_sub_field('btn_hover_events'); ?>" class="events-more" style="background: <?php the_sub_field('button_color');?>"><?php the_sub_field('more_button_text'); ?></a>
        </div>
    </div>
</section>