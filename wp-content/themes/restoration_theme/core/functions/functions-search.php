<?php


function get_count_posts_by_filter($post_type, $filter)
{
    $args = array(
        'post_type' => $post_type,
        's' => $filter
    );
    
    $the_query = new WP_Query( $args );
    return $the_query->found_posts;
}

function get_posts_by_filter($post_type, $data)
{
    $limit = isset($data['limit']) ? $data['limit'] : 4;
    $page = isset($data['paged']) ? $data['paged'] : 1;
    $offset = abs($limit * ($page - 1));

    $args = array(
        'numberposts' => $limit,
        'post_type'   => $post_type,
        'offset'      => $offset,
        'suppress_filters' => true,
        'orderby' => 'date',
        'order'   => 'DESC',
        'post_status' => 'publish',
        'fields' => 'ids'
    );

    if(isset($data['filter']) && !empty($data['filter'])){
        $args['s'] = $data['filter'];
    }

    $posts = get_posts($args);
    return $posts;
}

function get_event_date_formated($event_id)
{
    $event = get_field('date_from', $event_id);

    if ($date_to_1 = get_field('date_to', $event_id)) {
         $event .= ' - ' . $date_to_1;
    }

    if ($hours_from_1 = get_field('hours_from', $event_id)) {
        $event .= ' | ' . $hours_from_1;
    }

    if ($hours_to_1 = get_field('hours_to', $event_id)) {
        $event .= ' - ' . $hours_to_1;
    }

    return $event;
}

?>