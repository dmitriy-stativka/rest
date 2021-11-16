<div class="section_upcoming_events events-archive">
    <div class="container">
        <div class="top-archive">
            <?php
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<ul class="breadcrumb">', '</ul>');
            }
            ?>
            <h1><?php echo get_sub_field("title_of_past_events"); ?></h1>
        </div>

        <section class="upcoming-events">
            <div class="upcoming-events-list">
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
                            'terms' => ['community'],
                            'operator' => 'NOT IN',
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
                $square_events = get_posts($args);
                foreach ($square_events as $id):
                        if(get_field('select_date', $id)) {
                                if( in_array(date('l'), get_field('select_date', $id) ) ) {

                                    get_template_part('template_parts/section_events_item');
                                }else{ 
                                    get_template_part('template_parts/section_events_item');
                                }
                        }else{ 
                            get_template_part('template_parts/section_events_item');
                        } ?>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>