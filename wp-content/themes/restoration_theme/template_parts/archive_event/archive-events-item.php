<?php $event_id = get_query_var('event_id');
if (isset($event_id) && !empty($event_id)) $id = $event_id; ?>
<div <?php post_class('other-events-item', $id); ?>>
    <div class="event-thumbnail">
        <a href="<?php echo get_the_permalink($id) ?>"><?php echo get_the_post_thumbnail($id, 'full') ?></a>
    </div>
    <div class="event-content">
        <?php
        $date_from = wp_date('F d', strtotime(get_field('date_from', $id)));
        $date_to = wp_date('F d', strtotime(get_field('date_to', $id)));
        $hours_from = get_field('hours_from', $id);
        $hours_to = get_field('hours_to', $id);
        ?>
        <div class="event-meta-data"><?php echo ($date_from != $date_to) ? $date_from . ' - ' . $date_to : $date_from ?>
            | <?php echo $hours_from . ' - ' . $hours_to ?></div>
        <div class="event-location"><?php echo get_field('location', $id) ?></div>
        <h4 class="event-title"><?php echo get_the_title($id); ?></h4>
        <div class="event-excerpt">
            <?php echo get_the_excerpt($id); ?>
        </div>
        <div class="event-read-more">
            <a href="<?php echo get_the_permalink($id) ?>">More information</a>
        </div>
    </div>
</div>

