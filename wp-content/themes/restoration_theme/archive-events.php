<?php get_header(); ?>
<div class="events-archive">
    <div class="container">

        <div class="top-archive">
            <?php
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<ul class="breadcrumb">', '</ul>');
            }
            ?>
            <h1>Upcoming events</h1>
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
                            'compare' => '>=',
                        )
                    )

                );
                $square_events = get_posts($args);
                foreach ($square_events as $id):
                    $date_from = get_field('date_from', $id);
                    $terms = get_the_terms($id, 'events_cat');
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
                                    <div class="event-tag"><a href="?category=<?php echo $terms[0]->term_id ?>"><?php echo $terms[0]->name ?></a></div>
                                    <h4 class="event-title"><a href="<?php echo get_the_permalink($id) ?>"><?php echo get_the_title($id) ?></a></h4>
                                    <div class="event-meta-data"><?php echo get_field('hours_from', $id) ?>
                                        - <?php echo get_field('hours_to', $id) ?></div>
                                    <div class="event-read-more">
                                        <a href="<?php echo get_the_permalink($id) ?>">More information</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </section>

        <section class="community-upcoming-events__wrapper">
            <h3>Community upcoming events</h3>
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
                            'compare' => '>=',
                        )
                    )

                );

                $upcoming_events = get_posts($args);
                foreach ($upcoming_events as $id) : $terms = get_the_terms($id, 'events_cat'); ?>
                <div class="upcoming-event-item">
                    <div class="event-thumbnail">
                        <?php echo get_the_post_thumbnail($id, 'full') ?>
                    </div>
                    <div class="event-content">
                        <div class="event-tag"><a href="?category=<?php echo $terms[0]->term_id ?>"><?php echo $terms[0]->name ?></a></div>
                        <h4 class="event-title"><?php echo get_the_title($id) ?></h4>
                        <!--<div class="event-meta-data">April 23 | 6:00PM - 7:00PM | Virtual - Zoom</div>-->
                        <div class="event-meta-data">
                            <?php echo get_field('date_from', $id) ?> | <?php echo get_field('hours_from', $id) . ' - ' . get_field('hours_to', $id) ?> | <?php echo get_field('location', $id) ?>
                        </div>
                        <div class="event-read-more">
                            <a href="<?php echo get_the_permalink($id) ?>">More information</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </section>
        <section class="events-filter">
            <h3>Find events</h3>
            <form action="" id="events-filter" autocomplete="off">
                <?php wp_nonce_field('events_action','events_nonce_field'); ?>
                <div class="events-filter-form__wrapper">
                    <div class="input__wrapper input-date__wrapper">
                        <input type="text" name="date" id="date" placeholder="From" value="<?php echo (!empty($_GET['date'])) ? $_GET['date'] : ''; ?>">
                    </div>
                    <div class="select__wrapper">
                        <select name="category" id="category">
                            <option value="">Category</option>
                            <?php
                            $terms = get_terms( [
                                'taxonomy' => 'events_cat',
                                'hide_empty' => true,
                            ] );
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
                        <input type="text" name="keyword" id="keyword" placeholder="Keyword" value="<?php echo (!empty($_GET['keyword'])) ? $_GET['keyword'] : ''; ?>">
                    </div>
                    <div class="input__wrapper">
                        <input type="submit" value="Find Events" class="submit">
                    </div>
                </div>
            </form>
        </section>

        <?php /*if (empty($_GET)):*/?>

            <?php
            $today = wp_date('Y-m-d');
            $events = get_posts(array(
                'post_type' => 'events',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'date_from',
                        'value' => $today,
                        'type' => 'DATE',
                        'compare' => '<=',
                    ],
                    [
                        'key' => 'date_to',
                        'value' => $today,
                        'type' => 'DATE',
                        'compare' => '>=',
                    ],
                ]
            ));
            ?>
            <?php if (!empty($events)): ?>
            <section class="other-events default" <?php echo (!empty($_GET))? 'style="display:none"' : ''; ?>>
                <h2>Today</h2>
                <h3><?php echo wp_date('l, F d, Y') ?> </h3>
                <div class="other-events-list">
                    <?php foreach ($events as $id) {
                        get_template_part('template_parts/archive_event/archive-events', 'item');
                    } ?>
                </div>
            </section>
            <?php endif; ?>
            <?php
            $week = wp_date('Y-m-d', strtotime("+1 week"));
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
            <?php if (!empty($events_week)):
            $dates = array();
            foreach ($events_week as $id) {
                $array_item = get_field('date_from', $id);
                if (!in_array($array_item, $dates)) {
                    $dates[] = $array_item;
                }
            }
            foreach ($dates as $key => $date):?>
                <section class="other-events default" <?php echo (!empty($_GET))? 'style="display:none"' : ''; ?>>
                    <?php if ($key == 0): ?>
                        <h2>More events this week</h2>
                    <?php endif; ?>
                    <h3><?php echo wp_date('l, F d, Y', strtotime($date)); ?></h3>
                    <div class="other-events-list">
                        <?php foreach ($events_week as $id) {
                            if (get_field('date_from', $id) == $date) {
                                get_template_part('template_parts/archive_event/archive-events', 'item');
                            }
                        } ?>
                    </div>
                </section>
            <?php endforeach;
            endif; ?>


        <?php /*else: $filters = $_GET; */?>
        <?php if(!empty($_GET)): $filters = $_GET; ?>

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
                $meta_query[] = array(
                    'key' => 'date_from',
                    'value' => $filters['date'],
                    'type' => 'DATE',
                    'compare' => '>=',
                );
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

            $events = get_posts(array(
                'post_type' => 'events',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_key' => 'date_from',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'fields' => 'ids',
                's' => $s,
                'sentence' => true,
                'tax_query' => $tax_query,
                'meta_query' => $meta_query,
            ));


            if (!empty($events)) {
                $html = '';
                $dates = array();
                foreach ($events as $id) {
                    $array_item = get_field('date_from', $id);
                    if (!in_array($array_item, $dates)) {
                        $dates[] = $array_item;
                    }
                }


                foreach ($dates as $key => $date): ob_start(); ?>
                    <section class="other-events">
                        <?php if ($key == 0): ?>
                            <h2>Results</h2>
                        <?php endif; ?>
                        <h3><?php echo wp_date('l, F d, Y', strtotime($date)); ?></h3>
                        <div class="other-events-list">
                            <?php foreach ($events as $id) {
                                if (get_field('date_from', $id) == $date) {
                                    set_query_var('event_id', $id);
                                    get_template_part('template_parts/archive_event/archive-events', 'item');
                                }
                            } ?>
                        </div>
                    </section>
                    <?php $html .= ob_get_clean(); endforeach;
            } else {
                $html = '<p class="no-events-result">No results</p>';
            } ?>
            <?php echo $html; ?>
        <?php endif; ?>


    </div>
    <section class="email_contact" style="background: #c62a50;">
        <div class="container">
            <div class="email_contact-text">
                <span class="email_contact-title">Get Connected at Restoration</span>
                <p class="email_contact-desc">Sign up to receive emails relevant to your interests</p>
            </div>
            <div class="email_contact-form">
                <?php echo do_shortcode('[ninja_form id="1"]'); ?>
            </div>
        </div>
    </section>
</div>
<?php get_footer() ?>
