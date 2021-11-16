<?php

/*
Plugin Name: Custom Cron
Description: Custom Cron
Author: Suleiman
Version: 0.0.1
*/

include_once "cc_fields.php";

//if (!is_admin()) {
    cc_do_cron();
//}

function cc_do_cron()
{
    $interval = get_field('interval', 'options');
    $last_run = strtotime(get_field('last_run', 'options'));

    $from_day = strtotime(get_field("from_day", 'options'));
    $to_day = strtotime(get_field("to_day", 'options'));

    if (!$last_run) {
        $last_run = 0;
    }

    $now = time();

    if ($from_day < $now && $to_day > $now) {
        if ($last_run + $interval < $now) {
            update_field("last_run", date('Y-m-d 00:00:00'), 'options');
            add_action('init', 'cc_job');
        }
    }
}

/**
 * @throws Exception
 */
function cc_job()
{
    $unusual_days = [];
    $days = [];

    foreach (get_field("unusual_days", 'options') as $day) {
        array_push($unusual_days, $day);
    }

    $state = [
        'regular' => [
            'regular_start_time' => get_field("regular_start_time", 'options'),
            'regular_end_time' => get_field("regular_end_time", 'options'),
        ],
        'unusual' => [
            'unusual_days' => $unusual_days,
            'unusual_start_time' => get_field("unusual_start_time", 'options'),
            'unusual_end_time' => get_field("unusual_end_time", 'options'),
        ]
    ];

    foreach ($state['unusual']['unusual_days'] as $day) {
        array_push( $days, cc_days_filter(getAllDaysInAMonth(date("Y"), date('m'), $day, 7)));
    }

    foreach ($days as $date) {

        foreach ($date as $day) {
            if( date('m') == $day->format('m') ) {

                if( date('d') == $day->format('d') ) {
                    cc_update_event($state['unusual']['unusual_start_time'], $state['unusual']['unusual_end_time']);
                    break 2;
                } else {
                    cc_update_event($state['regular']['regular_start_time'], $state['regular']['regular_end_time']);
                }
            }
        }
    }
}

//filter days
function cc_days_filter($days) {

    $interval_days = get_field('interval_days', 'options');

    foreach($days as $key => $value ) {
        if ($key == 0) {
            $target_day = $value->format('d');
        }

        if ( $value->format('d') - $target_day != $interval_days && $key > 0) {
            unset($days[$key]);
        } else {
            $target_day = $value->format('d');
        }
    }

    return $days;
}

//update event time
function cc_update_event($start_time, $end_time) {
    $event_ID = get_field('event', 'options');
    $id = $event_ID[0];

    $args = [
        'p' => $id,
        'post_type' => 'events'
    ];

    $post = new WP_Query($args);

    if( $post->have_posts() ) {
        while ($post->have_posts()) { $post->the_post();
            update_field('hours_from', $start_time, $id);
            update_field('hours_to', $end_time, $id);
        }
    }
}

/**
 * Get an array of \DateTime objects for each day (specified) in a year and month
 *
 * @param integer $year
 * @param integer $month
 * @param string $day
 * @param integer $daysError    Number of days into month that requires inclusion of previous Monday
 * @return array|\DateTime[]
 * @throws Exception      If $year, $month and $day don't make a valid strtotime
 */

function getAllDaysInAMonth($year, $month, $day = 'Monday', $daysError = 3) {
    $dateString = 'first '.$day.' of '.$year.'-'.$month;
    if (!strtotime($dateString)) {
        throw new \Exception('"'.$dateString.'" is not a valid strtotime');
    }
    $startDay = new \DateTime($dateString);
    if ($startDay->format('j') > $daysError) {
        $startDay->modify('- 7 days');
    }
    $days = array();
    while ($startDay->format('Y-m') <= $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT)) {
        $days[] = clone($startDay);
        $startDay->modify('+ 7 days');
    }
    return $days;
}

