<div class="section_community_upcoming_events events-archive">
    <div class="container">
        <section class="community-upcoming-events__wrapper">
            <h3>Community upcoming events</h3>
            <div class="community-upcoming-events">
                <?php

                $tomorrow = wp_date('Y-m-d', strtotime("+1 day"));

                $args = array(
                    'post_type' => 'events',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_key' => 'date_from',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'events_cat',
                            'field' => 'slug',
                            'terms' => ['community'],
                            'operator' => 'IN',
                        ),
                    ),
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => 'date_from',
                            'value' => wp_date('Y-m-d'),
                            'compare' => '<=',
                            'type' => 'DATE'
                        ),
                    ),
                );

                $the_query = new WP_Query($args);

                if ($the_query->have_posts()) {
                    $i = 0;
                    while ($the_query->have_posts()) {
                        $the_query->the_post();
                        $day = wp_date('l', strtotime($tomorrow));
                        $day_array = get_field('select_date', get_the_ID());

                        if (!is_null($day_array)) {
                            if (in_array($day, $day_array)) {
                                $i++;
                                if ($i <= 3) {
                                    get_template_part('template_parts/section_upcoming_event_item');
                                }
                            }
                        }
                    }

                    if ($i == 0) {
                        ?>
                        <style>
                            .section_community_upcoming_events {
                                display: none;
                            }
                        </style>
                        <?php
                    }
                }
                wp_reset_postdata();
                ?>


            </div>
        </section>
    </div>
</div>