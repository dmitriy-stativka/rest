<?php

 get_header(); ?>

<div class="main-page-wrap">
    <div class="container">
        <?php get_search_form(); ?>

        <?php
            $searchFilter = isset($_GET['s']) ? $_GET['s'] : '';
            $pagesCount =  intval(get_count_posts_by_filter('page', $searchFilter));
            $newsCount =  intval(get_count_posts_by_filter('News', $searchFilter));
            $eventsCount =  intval(get_count_posts_by_filter('Events',$searchFilter));
            $allCount =  $pagesCount + $newsCount + $eventsCount;

            $pages = get_posts_by_filter('page', array( 'filter' => $searchFilter));
            $news = get_posts_by_filter('News', array( 'filter' => $searchFilter));
            $events = get_posts_by_filter('Events', array( 'filter' => $searchFilter));

            $maxPages = ceil($pagesCount / (count($pages) > 0 ? count($pages) : 1));
            $maxNews = ceil($newsCount / (count($news) > 0 ? count($news) : 1));
            $maxEvents = ceil($eventsCount / (count($events) > 0 ? count($events) : 1));

        ?>

        <div class="search-menu">
            <ul>
                <li data-type="all" class="search-desktop-select active" title="All categories"><span class="text">All results</span><span class="cloud"><?php echo $allCount; ?></span></li>
                <li data-type="pages" class="search-desktop-select" title="Pages"><span class="text">Pages</span><span class="cloud"><?php echo $pagesCount; ?></span></li>
                <li data-type="news" class="search-desktop-select" title="News"><span class="text">News</span><span class="cloud"><?php echo $newsCount; ?></span></li>
                <li data-type="events" class="search-desktop-select" title="Events"><span class="text">Events</span><span class="cloud"><?php echo $eventsCount; ?></span></li>
            </ul>
        </div>

        <div class="search-menu-select">
            <select id="search-menu">
                <option value="all" selected>All results</option>
                <option value="pages">Pages</option>
                <option value="news">News</option>
                <option value="events">Events</option>
            </select>
        </div>

        <div class="search-wrap">
            <div class="search-container" data-type="pages">
                <h2>Pages<span class="cloud"><?php echo $pagesCount; ?></span></h2>
                <div class="search-container__wrap" data-post-type="page" data-page="1" data-page-max=<?php echo $maxPages;?> data-filter="<?php echo $searchFilter; ?>">
                    <?php
                        if(!empty($pages)):
                            foreach($pages as $page): ?>
                                <div class="single-sp">
                                    <h4><?php echo get_the_title($page); ?></h4>
                                    <div class="single-sp__descr">
                                        <p>
                                            <?php echo get_the_excerpt($page); ?>
                                        </p>
                                    </div>
                                    <a href="<?php echo get_permalink( $page); ?>" class="single-sp__more">Visit page</a>
                                </div>
                        <?php endforeach; ?>
                     <?php else: ?>
                        <div class="not-found">No pages found!</div>
                    <?php endif; ?>
                </div>
                <?php if (count($pages) < $pagesCount): ?>
                    <button class="btn btn-load-more" data-post-type="page">Load more result pages <i class="fa fa-refresh fa-spin"></i></button>
                <?php endif; ?>
            </div>

            <div class="search-container" data-type="news">
                <h2>News<span class="cloud"><?php echo $newsCount; ?></span></h2>
                <div class="search-container__wrap" data-post-type="News" data-page="1" data-page-max=<?php echo $maxNews;?> data-filter="<?php echo $searchFilter; ?>">
                    <?php
                        if(!empty($news)):
                            foreach($news as $post): ?>
                                <div class="single-sp">
                                <h5><span><?php echo get_the_terms($post, "insights_category")[0] -> name; ?> </span> | <?php echo get_the_date('F d, Y', $post); ?></h5>
                                    <h4><?php echo get_the_title($post); ?></h4>
                                    <div class="single-sp__descr">
                                        <p>
                                            <?php echo get_the_excerpt($post) ?>
                                        </p>
                                    </div>
                                    <a href="<?php echo get_permalink( $post); ?>" class="single-sp__more">Read more</a>
                                </div>
                                <?php endforeach; ?>
                     <?php else: ?>
                        <div class="not-found">No news found!</div>
                    <?php endif; ?>
                </div>
                <?php if (count($news) < $newsCount): ?>
                    <button class="btn btn-load-more" data-post-type="News">Load more result news <i class="fa fa-refresh fa-spin"></i> </button>
                <?php endif; ?>
            </div>

            <div class="search-container" data-type="events">
                <h2>Events<span class="cloud"><?php echo $eventsCount; ?></span></h2>

                <div class="search-container__wrap" data-post-type="Events" data-page="1" data-page-max=<?php echo $maxEvents;?> data-filter="<?php echo $searchFilter; ?>">
                    <?php
                        if(!empty($events)):
                            foreach($events as $post): 

                                if(get_field('select_date')) {
                                    if( in_array(current_time('l'), get_field('select_date') ) ) { ?>
                                        <div class="single-sp single-sp-small">
                                        <h5><?php echo get_event_date_formated($post); ?></h5>
                                        <h4><?php echo get_the_title($post); ?></h4>
                                        <div class="single-sp__descr">
                                            <p>
                                                <?php echo get_the_excerpt($post); ?>
                                            </p>
                                        </div>
                                        <a href="<?php echo get_permalink( $post); ?>" class="single-sp__more">More information</a>
                                    </div>
                                    <?php }
                                }else{ ?>
                                
                                    <div class="single-sp single-sp-small">
                                    <h5><?php echo get_event_date_formated($post); ?></h5>
                                    <h4><?php echo get_the_title($post); ?></h4>
                                    <div class="single-sp__descr">
                                        <p>
                                            <?php echo get_the_excerpt($post); ?>
                                        </p>
                                    </div>
                                    <a href="<?php echo get_permalink( $post); ?>" class="single-sp__more">More information</a>
                                </div>
                                <?php }

                                endforeach; ?>
                    <?php else: ?>
                        <div class="not-found">No events found!</div>
                    <?php endif; ?>
                </div>
                <?php if (count($events) < $eventsCount): ?>
                    <button class="btn btn-load-more" data-post-type="Events">Load more result events <i class="fa fa-refresh fa-spin"></i></button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <section class="email_contact" style="background: #C62A50;">
    <div class="container">
        <div class="email_contact-text">
            <span class="email_contact-title">Get Connected at Restoration</span>
            <p class="email_contact-desc">Sign up to receive emails relevant to your interests</p>
        </div>
        <div class="email_contact-form">
            <?php echo do_shortcode('[ninja_form id="1"]');?>
        </div>
    </div>
</section>  
</div>

<?php get_footer(); ?>
