<?php

add_action( 'wp_ajax_nopriv_get_news', 'get_news' );
add_action( 'wp_ajax_get_news', 'get_news' );

function get_news() {

    $cat = intval($_POST['cat']);
    $limit = isset($_POST['limit']) ? $_POST['limit'] : 0;
    $page = isset($_POST['paged']) ? $_POST['paged'] : 1;
    $offset = abs($limit * ($page - 1));

    $args = array(
        'numberposts' => $limit,
        'post_type'   => 'news',
        'offset'      => $offset,
        'suppress_filters' => true,
        'fields' => 'ids'
    );

    if($cat != 0 ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'insights_category',
                'field' => 'id',
                'terms' => intval($cat)
            )
        );
    }

    $news = get_posts($args);

    $html = '';

    if ( !empty($news) ) {

        $i = 1;

        $html .= '<div class="classic-row">';

            foreach($news as $new) {

                $categories = get_the_terms( $new, 'insights_category' );
                $is_video = (get_field('select_type', $new) == 'Image') ? false : true;

                $html .= '<div class="single-news">
                            <div class="single-news__img">
                                <a href="'. get_the_permalink($new) .'">';
                                    if($is_video) {
                                        $html .= '<div class="play-icon"></div>';
                                    }

                                    $html .= '<img src="'.get_the_post_thumbnail_url( $new, 'news-loop') .'">
                                </a>
                            </div>
                            <div class="single-news__content">
                                <div class="single-news__content-meta"><span class="cat">'. $categories[0]->name .'</span> | '. get_the_date('F d, Y', $new) .'</div>
                                <h4><a href="'. get_the_permalink($new) .'">'. get_the_title($new) .'</a></h4>
                                <div class="description">
                                    <p>'. get_the_excerpt($new) .'</p>
                                </div>';

                                    if($is_video) {
                                        $text = "Watch video";
                                    }else {
                                        $text = "Read more";
                                    }

                                    $html .= '<a href="'. get_the_permalink($new) .'" class="continue">'. $text .'</a>
                                </div>
                            </div>';
            }

        $html .= '</div>';

        $response = array( 'success' => true, 'data' => $html );
    }else {
        $html = '<div class="not-found">No news Found!</div>';
        $response = array( 'success' => false, 'data' => $html );
    }

    wp_send_json($response);
}

add_action( 'wp_ajax_nopriv_get_news_by_cat', 'get_news_by_cat' );
add_action( 'wp_ajax_get_news_by_cat', 'get_news_by_cat' );

function get_news_by_cat() {

    $cat = $_POST['cat'];
    $limit = isset($_POST['limit']) ? $_POST['limit'] : 0;

    $args = array(
        'numberposts' => $limit,
        'post_type'   => 'news',
        'suppress_filters' => true,
        'fields' => 'ids'
    );

    if($cat != 0 ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'insights_category',
                'field' => 'id',
                'terms' => intval($cat)
            )
        );
    }

    $news = get_posts($args);

    $html = '';

    if ( !empty($news) ) {

        $i = 1;

        $html .= '<div class="first-row">';

        foreach( $news as $new ) {

            $categories = get_the_terms( $new, 'insights_category' );

            if($i <= 2)  {
                $html .= '<div class="square-news">
                                <div class="content">
                                    <a href="<?php the_permalink(); ?>">
                                        <div class="news-img">
                                            '. get_the_post_thumbnail($new, 'news-square') .'
                                        </div>
                                        <div class="news-content">
                                            <div class="news-content__meta"><span class="cat">'. $categories[0]->name .'</span> | '. get_the_date('F d, Y', $new) .'</div>
                                            <h4>'. get_the_title($new) .'</h4>
                                            <span class="continue">Continue reading</span>
                                        </div>
                                    </a>
                                </div>
                            </div>';
            }else {
                $is_video = (get_field('select_type', $new) == 'Image') ? false : true;

                $html .= '<div class="single-news">
                            <div class="single-news__img">
                                <a href="'. get_the_permalink($new) .'">';
                if($is_video) {
                    $html .= '<div class="play-icon"></div>';
                }

                $html .= get_the_post_thumbnail($new, 'news-loop') .'
                                </a>
                            </div>
                            <div class="single-news__content">
                                <div class="single-news__content-meta"><span class="cat">'. $categories[0]->name .'</span> | '. get_the_date('F d, Y', $new) .'</div>
                                <h4><a href="'. get_the_permalink($new) .'">'. get_the_title($new) .'</a></h4>
                                <div class="description">
                                    <p>'. get_the_excerpt($new) .'</p>
                                </div>';

                if($is_video) {
                    $text = "Watch video";
                }else {
                    $text = "Read more";
                }

                $html .= '<a href="'. get_the_permalink($new) .'" class="continue">'. $text .'</a>
                                </div>
                            </div>';
            }

            if($i % 2 == 0) {
                $html .= '</div>
                            <div class="classic-row">';
            }

            $i++;
        }

        $html .= '</div>';
        $response = array( 'success' => true, 'data' => $html );
    }else {
        $html = '<div class="not-found">No news Found!</div>';
        $response = array( 'success' => false, 'data' => $html );
    }

    wp_send_json($response);

}

add_action( 'wp_ajax_nopriv_get_search_posts_by_filter', 'get_search_posts_by_filter' );
add_action( 'wp_ajax_get_search_posts_by_filter', 'get_search_posts_by_filter' );

function get_search_posts_by_filter()
{ 
    if(isset($_POST['postType']) || !empty(isset($_POST['postType'])))
    {
        $postType =  $_POST['postType'];

        $args = array();
        $args['limit'] = isset($_POST['limit']) ? $_POST['limit'] : 4;
        $args['paged']= isset($_POST['paged']) ? $_POST['paged'] : 1;
        $args['filter'] = isset($_POST['filter']) ? $_POST['filter'] : '';
    
        $posts = get_posts_by_filter($postType, $args);
        $additionalClass =  $postType == "Events" ? 'single-sp-small' : '';
        $linkText = 'Visit page';
        $html = '';

        foreach($posts as $item)
        {
            $html .= ' <div class="single-sp ' . $additionalClass .  '">';
            
            if($postType == "News")
            {
                $html .= '<h5><span>' . get_the_terms($item, "insights_category")[0] -> name . '</span> | ' .  get_the_date('F d, Y', $item) . '</h5>';
                $linkText = 'Read more';
            }
            
            else if($postType == "Events")
            {
                $html .= '<h5>' . get_event_date_formated($item) . '</h5>';
                $linkText = 'More information';
            }

            $html .= '<h4>' . get_the_title($item) . '</h4>';
            $html .= '<div class="single-sp__descr">';
            $html .= '<p>' .  get_the_excerpt($item) . '</p>';
            $html .= '</div>';
            $html .= '<a href="' . get_permalink( $item). '" class="single-sp__more">' . $linkText . '</a>';
            $html .= '</div>';
        }


        $response = array( 'success' => true, 'data' => $html );
        wp_send_json($response);
    }

    $response = array( 'success' => false, 'data' => $html );
    wp_send_json($response);
}