<div class="section_community_upcoming_events events-archive">
    <div class="container">
        <section class="community-upcoming-events__wrapper">
            <h3><?php echo get_sub_field("title_of_community_past_events"); ?></h3>
            <div class="community-upcoming-events">
                <?php

                $args = array(
                    'post_type' => 'events',
                    'post_status' => 'publish',
                    'posts_per_page' => 3,
                    'meta_key' => 'date_from',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'fields' => 'ids',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'events_cat',
                            'field' => 'slug',
                            'terms' => 'community',
                        ),
                    ),
                    'meta_query' => array(
                        array(
                            'key' => 'date_from',
                            'value' => wp_date('Y-m-d'),
                            'type' => 'DATE',
                            'compare' => '<',
                        )
                    )

                );
                $upcoming_events = get_posts($args);
                foreach ($upcoming_events as $id) : $terms = get_the_terms($id, 'events_cat'); ?>

                        <?php 
                            if(get_field('select_date', $id)) {
                                if( in_array(date('l'), get_field('select_date', $id) ) ) { 
                                    get_template_part('template_parts/section_upcoming_event_item');
                                }else{  
                                    get_template_part('template_parts/section_upcoming_event_item');
                                }
                            }else{ 
                                get_template_part('template_parts/section_upcoming_event_item');
                            } 
                            
                        ?>
                        <!-- 
                        <div class="upcoming-event-item">
                        <div class="event-thumbnail">
                            <?php echo get_the_post_thumbnail($id, 'full') ?>
                        </div>
                        <div class="event-content">
                            <div class="event-tag"><a href="?category=<?php echo $terms[0]->term_id ?>"><?php echo $terms[0]->name ?></a></div>
                            <h4 class="event-title"><?php echo get_the_title($id) ?></h4>
                            <div class="event-meta-data">April 23 | 6:00PM - 7:00PM | Virtual - Zoom</div>
                            <div class="event-meta-data">
                                <?php echo get_field('date_from', $id) ?> | <?php echo get_field('hours_from', $id) . ' - ' . get_field('hours_to', $id) ?> | <?php echo get_field('location', $id) ?>
                            </div>
                            <div class="event-read-more">
                                <a href="<?php echo get_the_permalink($id) ?>">More information</a>
                            </div>
                        </div>
                    </div> -->
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>