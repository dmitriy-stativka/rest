<div class="section_upcoming_events events-archive">
    <div class="container">
        <div class="top-archive">
            <?php
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<ul class="breadcrumb">', '</ul>');
            }
            ?>
            <h1><?php echo get_sub_field("title"); ?></h1>
        </div>

        <section class="upcoming-events">
            <div class="upcoming-events-list">
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
                            'operator' => 'NOT IN',
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
                    ?>

                    <?php
                    $i = 0;
                    $day = date('l', strtotime($tomorrow));
                    while ($the_query->have_posts()) {

                        $the_query->the_post();
                        $day_array = get_field('select_date', get_the_ID());

                        if ($day_array) {
                            if (in_array($day, $day_array)) {
                                $i++;
                                if ($i <= 3) {
                                    get_template_part('template_parts/section_events_item');
                                }
                            }
                        }
                    }
                    if ($i == 0) {
                        ?>
                        <style>
                            .events-archive h1 {
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
