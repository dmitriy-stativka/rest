<?php
define('THEME_DIR', get_template_directory_uri());

add_image_size('full_hd', 1920, 1080);

add_image_size('news-square', 594, 594);
add_image_size('news-loop', 595, 371);

add_action( 'wp_enqueue_scripts', function(){
    if (is_admin()) return; // don't dequeue on the backend
    wp_deregister_script('jquery-core');
    wp_deregister_script('jquery');


    wp_register_script( 'jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', false, null, true );
    wp_register_script( 'jquery', false, array('jquery-core'), null, true );


    wp_enqueue_script( 'jquery' );

    wp_register_script('mas' , 'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js');
	wp_enqueue_script('mas');

    wp_register_script( 'plyr',  get_stylesheet_directory_uri() . '/src/js/vendor/plyr.min.js', array(), null, false );
    wp_enqueue_script( 'plyr');
    wp_enqueue_script( 'slick',  get_stylesheet_directory_uri() . '/src/slick/slick.min.js', array(), null, true );

});


function global_scripts() {
    
    wp_enqueue_style('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css', array());
    wp_enqueue_style('mosaic', 'https://cdn.jsdelivr.net/npm/nanogallery2@3/dist/css/nanogallery2.min.css', array());
    wp_enqueue_style('plyr', get_stylesheet_directory_uri() . '/build/plyr.css', array());
    wp_enqueue_style('main-style', get_stylesheet_directory_uri() . '/build/style.css', array());
    wp_enqueue_style('slick-style', get_stylesheet_directory_uri() . '/src/slick/slick.css', array());
    wp_enqueue_style('slick-theme-style', get_stylesheet_directory_uri() . '/src/slick/slick-theme.css', array());

    wp_enqueue_script('swiper','https://unpkg.com/swiper/swiper-bundle.min.js');



    wp_enqueue_script('mosaic','https://cdn.jsdelivr.net/npm/nanogallery2@3/dist/jquery.nanogallery2.min.js', array());



    // wp_enqueue_script('bundle', get_template_directory_uri() . '/build/bundle.js');
    wp_enqueue_script( 'main',  get_stylesheet_directory_uri() . '/src/js/main.js', array(),  null, true );

    if (is_page_template('page_builder.php')){
        wp_enqueue_style('nice-select', get_stylesheet_directory_uri() . '/build/nice-select.css', array());
        wp_enqueue_script( 'nice-select',  get_stylesheet_directory_uri() . '/src/js/vendor/jquery.nice-select.min.js', array(), null, true );
    }

    if (is_page('donation')){
        $paypal_client_id = (get_field('paypal_mode', 'options') == 1 ) ? get_field('paypal_client_id', 'options') : get_field('paypal_client_id_test', 'options');
        if ($paypal_client_id) {
            wp_enqueue_script('paypal-init', 'https://www.paypal.com/sdk/js?client-id='. $paypal_client_id .'&vault=false&currency=USD', array(), null, true);
            wp_enqueue_script('donation-page', get_stylesheet_directory_uri() . '/src/js/donation-page.js', array('paypal-init'), null, true);
        }
    }

    if (is_archive('events')){
        wp_enqueue_style('nice-select', get_stylesheet_directory_uri() . '/build/nice-select.css', array());
        wp_enqueue_style('datepicker', get_stylesheet_directory_uri() . '/build/datepicker.min.css', array());
        wp_enqueue_script( 'nice-select',  get_stylesheet_directory_uri() . '/src/js/vendor/jquery.nice-select.min.js', array(), null, true );
        wp_enqueue_script( 'moment',  get_stylesheet_directory_uri() . '/src/js/vendor/moment.min.js', array(), null, true );
        wp_enqueue_script( 'datepicker',  get_stylesheet_directory_uri() . '/src/js/vendor/datepicker.min.js', array(), null, true );
        wp_enqueue_script( 'archive-events',  get_stylesheet_directory_uri() . '/src/js/archive-events.js', array(), null, true );
    }

    wp_localize_script( 'main', 'ajax_actions',
        array(
            'url' => admin_url('admin-ajax.php'),
            'paged' => 2
        )
    );

}

add_action('wp_enqueue_scripts', 'global_scripts');


function remove_head_scripts()
{
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
    remove_action('wp_head', 'wp_print_styles', 99);
    remove_action('wp_head', 'wp_enqueue_style', 99);


    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
    add_action('wp_head', 'wp_print_styles', 30);
    add_action('wp_head', 'wp_enqueue_style', 30);
}

//add_action('wp_enqueue_scripts', 'remove_head_scripts');


show_admin_bar(false);


add_theme_support('menus');
add_theme_support('post-thumbnails');
add_image_size( 'event-small', 300, 300);
// SVG support
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


// ACF Options page
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
}


function remove_menus(){
    // remove_menu_page( 'edit.php' ); //Posts
    remove_menu_page( 'edit-comments.php' ); //Comments

}
add_action( 'admin_menu', 'remove_menus' );


add_action( 'after_setup_theme', 'wpse_theme_setup' );
function wpse_theme_setup() {
    add_theme_support( 'title-tag' );
    register_nav_menu( 'primary', 'Primary Menu' );
}


require_once( __DIR__ . '/core/core.php');
function add_menuclass($ulclass) {
	return preg_replace('/<a /', '<a class="header__menu-link"', $ulclass);
 }
 add_filter('wp_nav_menu','add_menuclass');

 add_theme_support( 'custom-logo', array() );


// add_filter('acf/settings/save_json', 'my_acf_json_save_point');
//
//function my_acf_json_save_point( $path ) {
//
//    // update path
//    $path = get_stylesheet_directory() . '/acf-json';
//
//
//    // return
//    return $path;
//
//}
//
//add_filter('acf/settings/load_json', 'my_acf_json_load_point');
//
//function my_acf_json_load_point( $paths ) {
//
//    // remove original path (optional)
//    unset($paths[0]);
//
//
//    // append path
//    $paths[] = get_stylesheet_directory() . '/acf-json';
//
//
//    // return
//    return $paths;
//
//}

add_action( 'init', 'cpt_register' );
function cpt_register() {
    register_post_type('programs', array(
        'labels'             => array(
            'name'               => 'Programs',
            'singular_name'      => 'Programs',
            'add_new'            => 'Add program',
            'add_new_item'       => 'Add new program',
            'edit_item'          => 'Edit program',
            'new_item'           => 'New program',
            'view_item'          => 'View program',
            'search_items'       => 'Search program',
            'not_found'          => 'Not found',
            'not_found_in_trash' => 'No items were found in the basket',
            'parent_item_colon'  => '',
            'menu_name'          => 'Programs'
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'            => array( 'title', 'comments'  )
    ));

    register_post_type('events', array(
        'labels'             => array(
            'name'               => 'Events',
            'singular_name'      => 'Events',
            'add_new'            => 'Add event',
            'add_new_item'       => 'Add new event',
            'edit_item'          => 'Edit event',
            'new_item'           => 'New event',
            'view_item'          => 'View event',
            'search_items'       => 'Search event',
            'not_found'          => 'Not found',
            'not_found_in_trash' => 'No items were found in the basket',
            'parent_item_colon'  => '',
            'menu_name'          => 'Events'
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'            => array( 'title', 'editor', 'comments', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' )
    ));

    register_post_type('services', array(
        'labels'             => array(
            'name'               => 'Services',
            'singular_name'      => 'Services',
            'add_new'            => 'Add service',
            'add_new_item'       => 'Add new service',
            'edit_item'          => 'Edit service',
            'new_item'           => 'New service',
            'view_item'          => 'View service',
            'search_items'       => 'Search service',
            'not_found'          => 'Not found',
            'not_found_in_trash' => 'No items were found in the basket',
            'parent_item_colon'  => '',
            'menu_name'          => 'Services'
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'            => array( 'title', 'comments', 'thumbnail', 'excerpt', 'custom-fields' )
    ));




    register_post_type('sample', array(
        'labels'             => array(
            'name'               => 'Sample Positions',
            'singular_name'      => 'Sample Positions',
            'add_new'            => 'Add positions',
            'add_new_item'       => 'Add new positions',
            'edit_item'          => 'Edit positions',
            'new_item'           => 'New positions',
            'view_item'          => 'View positions',
            'search_items'       => 'Search positions',
            'not_found'          => 'Not found',
            'not_found_in_trash' => 'No items were found in the basket',
            'parent_item_colon'  => '',
            'menu_name'          => 'Sample Positions'
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'            => array( 'title', 'thumbnail', 'custom-fields' )
    ));


    register_post_type('board', array(
        'labels'             => array(
            'name'               => 'Board of Directors',
            'singular_name'      => 'Board of Directors',
            'add_new'            => 'Add item',
            'add_new_item'       => 'Add new item',
            'edit_item'          => 'Edit item',
            'new_item'           => 'New item',
            'view_item'          => 'View item',
            'search_items'       => 'Search item',
            'not_found'          => 'Not found',
            'not_found_in_trash' => 'No items were found in the basket',
            'parent_item_colon'  => '',
            'menu_name'          => 'Board of Directors'
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'            => array( 'title', 'thumbnail', 'custom-fields' )
    ));

    register_taxonomy( 'events_cat', [ 'events' ], [
        'label'                 => '', // определяется параметром $labels->name
        'labels'                => [
            'name'              => 'Categories',
            'singular_name'     => 'Category',
            'search_items'      => 'Search Categories',
            'all_items'         => 'All Categories',
            'view_item '        => 'View Category',
            'parent_item'       => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item'         => 'Edit Category',
            'update_item'       => 'Update Category',
            'add_new_item'      => 'Add New Category',
            'new_item_name'     => 'New Category Name',
            'menu_name'         => 'Categories',
        ],
        'description'           => '', // описание таксономии
        'public'                => true,
        // 'publicly_queryable'    => null, // равен аргументу public
        // 'show_in_nav_menus'     => true, // равен аргументу public
        // 'show_ui'               => true, // равен аргументу public
        // 'show_in_menu'          => true, // равен аргументу show_ui
        // 'show_tagcloud'         => true, // равен аргументу show_ui
        // 'show_in_quick_edit'    => null, // равен аргументу show_ui
        'hierarchical'          => true,

        'rewrite'               => true,
        //'query_var'             => $taxonomy, // название параметра запроса
        'capabilities'          => array(),
        'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
        'show_in_rest'          => null, // добавить в REST API
        'rest_base'             => null, // $taxonomy
        // '_builtin'              => false,
        //'update_count_callback' => '_update_post_term_count',
    ] );

    register_post_type('donations', array(
        'labels'             => array(
            'name'               => 'Donations',
            'singular_name'      => 'Donation',
            'add_new'            => 'Add item',
            'add_new_item'       => 'Add new item',
            'edit_item'          => 'Edit item',
            'new_item'           => 'New item',
            'view_item'          => 'View item',
            'search_items'       => 'Search item',
            'not_found'          => 'Not found',
            'not_found_in_trash' => 'No items were found in the basket',
            'parent_item_colon'  => '',
            'menu_name'          => 'Donations'
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'            => array( 'title', 'editor', 'custom-fields' )
    ));

    register_post_type('custom_post_type', array(
        'labels'             => array(
            'name'               => 'Custom Post Type',
            'singular_name'      => 'Custom Post Type',
            'add_new'            => 'Add item',
            'add_new_item'       => 'Add new item',
            'edit_item'          => 'Edit item',
            'new_item'           => 'New item',
            'view_item'          => 'View item',
            'search_items'       => 'Search item',
            'not_found'          => 'Not found',
            'not_found_in_trash' => 'No items were found in the basket',
            'parent_item_colon'  => '',
            'menu_name'          => 'Custom Post Type'
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'            => array( 'title', 'editor', 'thumbnail' )
    ));

}

add_action( 'wp_ajax_filter_events', 'filter_events' );
add_action( 'wp_ajax_nopriv_filter_events', 'filter_events' );
function filter_events(){
    if (!wp_verify_nonce($_POST['filters']['nonce'], 'events_action')) {
        print 'Undefined';
        exit;
    } else {
        $filters = $_POST['filters'];
        if (!empty($filters['date']) || !empty($filters['category']) || !empty($filters['location']) || !empty($filters['keyword'])) {

            $tax_query = array();
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
            }
            wp_send_json(array(
                'clear' => false,
                'events' => $events,
                'html' => $html,
                'archiveUrl' => get_post_type_archive_link('events'),
                'filterString' => $filters_string,
            ));
        } else{
            wp_send_json(array(
                'clear' => true,
                'archiveUrl' => get_post_type_archive_link('events'),
                'filterString' => '',
            ));
        }
    }
}

add_action( 'init', 'custom_post_status' );
function custom_post_status()
{
    register_post_status('failed', array(
        'label' => _x('Failed', 'order'),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'post_type' => array('donations'),
        'label_count' => _n_noop('Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>'),
    ));

    register_post_status('completed', array(
        'label' => _x('Completed', 'order'),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'post_type' => array('donations'),
        'label_count' => _n_noop('Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>'),
    ));
}

add_shortcode( 'custom_search_form', 'custom_search_form' );

function custom_search_form( ){
    return '<form role="search" method="get" id="searchform" class="searchform" action="'.home_url( "/" ).'">
        <label class="screen-reader-text" for="s">Search for:</label>
        <input type="text" value="'.get_search_query().'" name="s" id="s" placeholder="Find more things Restoration can help you with"/>
        <input type="submit" id="searchsubmit" value="Search" />
</form>';
}

add_action('wp_ajax_paypal_order_create', 'paypal_order_create');
add_action('wp_ajax_nopriv_paypal_order_create', 'paypal_order_create');
function paypal_order_create(){
    parse_str($_POST['form'], $arr);
    $post_title = sanitize_text_field( $arr['first-name']) . ' ' . sanitize_text_field( $arr['last-name']);
    $post_content = '';
    $meta_input = array(
        'first_name' => $arr['first-name'],
        'last_name' => $arr['last-name'],
        'email' => $arr['your-email'],
        'cell_phone' => $arr['your-phone'],
        'country' => $arr['your-country'],
        'address' => $arr['your-address'],
        'city' => $arr['your-city'],
        'state' => $arr['your-state'],
        'zip_code' => $arr['zip-code'],
        'dedication' => $arr['dedication'],
        'dedication-name' => $arr['dedication-name'],
        'amount' => $arr['amount'],
    );
    foreach ($arr as $key => $val){
        $post_content .= '<p>' . $key . ': <strong>'. $val .'</strong> </p>';
    }
    $post_status = ($_POST['success']) ? 'completed' : 'failed';
    $post_id = wp_insert_post(array(
        'post_title'    => $post_title,
        'post_content'  => $post_content,
        'post_type' => 'donations',
        'meta_input' => $meta_input,
        'post_status'   => $post_status,
    ));
    wp_send_json(array(
        'form' => $arr,
        'post_id' => $post_id,
    ));
}

/**
 *	ACF Admin Columns
 *
 */

function add_acf_columns ( $columns ) {
    return array_merge ( $columns, array (
        'amount' => __ ( 'Amount $' ),
        'country'   => __ ( 'Country' )
    ) );
}
add_filter ( 'manage_donations_posts_columns', 'add_acf_columns' );

/*
* Add columns to donations CPT
*/
function donations_custom_column ( $column, $post_id ) {
    switch ( $column ) {
        case 'amount':
            echo get_post_meta ( $post_id, 'amount', true );
            break;
        case 'country':
            echo get_post_meta ( $post_id, 'country', true );
            break;
    }
}
add_action ( 'manage_donations_posts_custom_column', 'donations_custom_column', 10, 2 );

/*
* Add Sortable columns
*/

function my_column_register_sortable( $columns ) {
    $columns['amount'] = 'amount';
    $columns['country'] = 'country';
    return $columns;
}
add_filter('manage_edit-donations_sortable_columns', 'my_column_register_sortable' );


/**
 * Filter the output of Yoast breadcrumbs so each item is an <li> with schema markup
 * @param $link_output
 * @param $link
 *
 * @return string
 */
function doublee_filter_yoast_breadcrumb_items( $link_output, $link ) {

    $new_link_output = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
    $new_link_output .= '<a href="' . $link['url'] . '" itemprop="url">' . $link['text'] . '</a>';
    $new_link_output .= '</li>';

    return $new_link_output;
}
add_filter( 'wpseo_breadcrumb_single_link', 'doublee_filter_yoast_breadcrumb_items', 10, 2 );


/**
 * Filter the output of Yoast breadcrumbs to remove <span> tags added by the plugin
 * @param $output
 *
 * @return mixed
 */
function doublee_filter_yoast_breadcrumb_output( $output ){

    $from = '<span>';
    $to = '</span>';
    $output = str_replace( $from, $to, $output );

    return $output;
}
add_filter( 'wpseo_breadcrumb_output', 'doublee_filter_yoast_breadcrumb_output' );


/**
 * Shortcut function to output Yoast breadcrumbs
 * wrapped in the appropriate markup
 */
function doublee_breadcrumbs() {
    if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb('<ul>', '</ul>');
    }
}

add_filter('acf/format_value/type=text', 'do_shortcode');
add_filter('acf/format_value/type=textarea', 'do_shortcode');