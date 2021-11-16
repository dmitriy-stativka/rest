<div class="section_find_events events-archive">
    <div class="container">
        <section class="events-filter">
            <h3>Find events</h3>
            <form action="" id="events-filter" autocomplete="off">
                <?php wp_nonce_field('events_action', 'events_nonce_field'); ?>
                <div class="events-filter-form__wrapper">
                    <div class="input__wrapper input-date__wrapper">
                        <input type="date" name="date" id="date" placeholder="From"
                               value="<?php echo (!empty($_GET['date'])) ? $_GET['date'] : ''; ?>">
                    </div>
                    <div class="select__wrapper">
                        <select name="category" id="category">
                            <option value="">Category</option>
                            <?php
                            $terms = get_terms([
                                'taxonomy' => 'events_cat',
                                'hide_empty' => true,
                            ]);
                            foreach ($terms as $term):?>
                                <option value="<?php echo $term->term_id ?>" <?php echo (!empty($_GET['category']) && $_GET['category'] == $term->term_id) ? 'selected' : '' ?> ><?php echo $term->name ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <div class="select__wrapper">
                        <select name="location" id="location">
                            <option value="">Location</option>
                            <?php
                            $locations = array();
                            if (have_posts()) {
                                while (have_posts()) {
                                    the_post();
                                    $location = get_field('location', get_the_ID());
                                    if (!in_array($location, $locations)) {
                                        $locations[] = $location;
                                    }
                                }
                            }
                            foreach ($locations as $val): ?>
                                <option value="<?php echo $val ?>" <?php echo (!empty($_GET['location']) && $_GET['location'] == $val) ? 'selected' : '' ?>><?php echo $val ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input__wrapper">
                        <input type="text" name="keyword" id="keyword" placeholder="Keyword"
                               value="<?php echo (!empty($_GET['keyword'])) ? $_GET['keyword'] : ''; ?>">
                    </div>
                    <div class="input__wrapper">
                        <input type="submit" value="Find Events" class="submit">
                    </div>
                </div>
            </form>
        </section>

        <?php
        $today = date('Ymd');
        $args = array(
            'post_type' => 'events',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'date_from',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'date_from',
                    'compare' => '<=',
                    'value' => $today,
                ),
                array(
                    'key' => 'date_to',
                    'compare' => '>=',
                    'value' => $today,
                )

            ),
        );

        $the_query = new WP_Query($args);
        if ($the_query->have_posts()) {
            ?>
            <section class="other-events default" <?php echo (!empty($_GET)) ? 'style="display:none"' : ''; ?>>

                <h2>Today</h2>
                <h3 class="today"><?php echo wp_date('l, F d, Y') ?> </h3>
                <div class="other-events-list">
                    <?php
                    $i = 0;
                    while ($the_query->have_posts()) {

                        $the_query->the_post();

                        if (get_field('select_date', get_the_ID())) {
                            if (in_array(wp_date('l', strtotime($today)), get_field('select_date', get_the_ID()))) {
                                $i++;
                                get_template_part('template_parts/archive_event/archive-events', 'item');
                            }
                        }


                    }
                    if ($i <= 0) {
                        ?>
                        <h2>Not Found</h2>
                        <?php
                    }

                    ?>
                </div>
            </section>
            <?php
        } else { ?>

            <h2>Not Found</h2>

            <?php //endif; ?>
        <?php } ?>
        <?php wp_reset_postdata(); ?>



        <?php
        $week = date('Ymd', strtotime("+2 week"));
        $events_week = get_posts(array(
            'post_type' => 'events',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_key' => 'date_from',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'date_from',
                    'value' => [$today, $week],
                    'type' => 'DATE',
                    'compare' => 'BETWEEN',
                ],
                [
                    'key' => 'date_from',
                    'value' => $today,
                    'type' => 'DATE',
                    'compare' => '!=',
                ],
            ]
        ));

        ?>
        <?php
        $dates = array();
        foreach ($events_week as $id) {
            $array_item = get_field('date_from', $id);
            if (!in_array($array_item, $dates)) {
                $dates[] = $array_item;
            }
        }
        foreach ($dates as $key => $date):?>
            <section class="other-events default" <?php echo (!empty($_GET)) ? 'style="display:none"' : ''; ?>>
                <?php if ($key == 0): ?>
                    <h2>More events this week</h2>
                <?php endif; ?>
                <h3><?php echo wp_date('l, F d, Y', strtotime($date)); ?></h3>
                <div class="other-events-list">
                    <?php foreach ($events_week as $id) {
                        if (get_field('date_from', $id) == $date) {
                            if (get_field('select_date', $id)) {
                                if (in_array(wp_date('l', strtotime($today)), get_field('select_date', $id))) {
                                    get_template_part('template_parts/archive_event/archive-events', 'item');
                                }
                            } else {
                                get_template_part('template_parts/archive_event/archive-events', 'item');
                            }
                        }
                    } ?>
                </div>
            </section>
        <?php endforeach;
        ?>

        <?php /*else: $filters = $_GET; */ ?>
        <?php if (!empty($_GET)): $filters = $_GET; ?>

            <?php $tax_query = array();
            $s = '';
            $filters_string = '';
            $separator = '?';
            foreach ($filters as $key => $val) {
                if ($key != 'nonce' && !empty($val)) {
                    $filters_string .= $separator . $key . '=' . $val;
                    $separator = '&';
                }
            }
            $meta_query = array();
            if (!empty($filters['date'])) {

                $filters_date = date('Ymd', strtotime($filters['date']));

                $meta_query[] = [
                    array(
                        'key' => 'date_from',
                        'compare' => '<=',
                        'value' => $filters_date,
                    ),
                    array(
                        'key' => 'date_to',
                        'compare' => '>=',
                        'value' => $filters_date,
                    )
                ];
            }

            if (!empty($filters['location'])) {
                $meta_query[] = array(
                    'key' => 'location',
                    'value' => $filters['location'],
                    'compare' => '=',
                );
            }
            if (!empty($filters['category'])) {
                $tax_query = array(
                    array(
                        'taxonomy' => 'events_cat',
                        'field' => 'id',
                        'terms' => $filters['category'],
                    )
                );
            }
            if (!empty($filters['keyword'])) {
                $s = $filters['keyword'];
            }

            $args = array(
                'post_type' => 'events',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                //'meta_key' => 'date_from',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                's' => $s,
                'tax_query' => $tax_query,
//                'meta_query' => array(
//                    array(
//                        'key' => 'date_from',
//                        'compare' => '<=',
//                        'value' => $filters_date,
//                    ),
//                    array(
//                        'key' => 'date_to',
//                        'compare' => '>=',
//                        'value' => $filters_date,
//                    )
//                )
                'meta_query' => $meta_query

            );

            if( !empty($filters['date']) ) {
                $args['meta_key'] = 'date_from';
            }

            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) {
                ?>
                <section class="other-events">

                    <h2>Results</h2>
                    <h3><?php echo wp_date('l, F d, Y', strtotime($filters['date'])); ?></h3>

                    <div class="other-events-list">
                        <?php
                        $i = 0;
                        while ($the_query->have_posts()) {

                            $the_query->the_post();
                            if (get_field('select_date', get_the_ID())) {
                                if ( empty($filters['date']) || in_array(wp_date('l', strtotime($filters['date'])), get_field('select_date', get_the_ID()))) {
                                    $i++;
                                    get_template_part('template_parts/archive_event/archive-events', 'item');
                                }
                            }
                        }
                        if ($i <= 0) {
                            ?>
                            <h2>Not Found</h2>
                            <?php
                        }
                        ?>
                    </div>
                </section>
                <?php
            } else { ?>
                <h2>Not Found</h2>
            <?php } ?>
            <?php wp_reset_postdata(); ?>


        <?php endif; ?>

    </div>
</div>