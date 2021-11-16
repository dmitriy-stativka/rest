<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since 1.0.0
 */

get_header();

$args = array(
    'taxonomy' => 'insights_category',
);

$cats = get_categories($args);

?>

<div class="news-archive">
    <div class="container">

        <div class="top-archive">
            <ul class="breadcrumb">
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li><a href="<?php echo site_url(); ?>/community">Community</a></li>
                <li>News</li>
            </ul>

            <h1>Restoration News</h1>
            <p class="sub-title">Restoration articles and latest news</p>
        </div>

        <div class="categories-menu">
            <ul>
                <li data-cat="0" class="active" title="All categories">All categories</li>

                <?php foreach($cats as $cat) : ?>
                    <li data-cat="<?php echo $cat->term_id; ?>" title="<?php echo $cat->name; ?>"><?php echo $cat->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="categories-menu-select">
            <select id="categories-menu">
                <option value="0" selected>All categories</option>

                <?php foreach($cats as $cat) : ?>
                    <option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="news-archive__wrap">

            <?php if ( have_posts() ) : $i = 1; ?>

                <div class="first-row">

                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php $categories = get_the_terms( get_the_ID(), 'insights_category' ); ?>
                        <?php if(!empty($categories[0]->name)) {
                            $cat = $categories[0]->name . ' | ';
                        } ?>
                        <?php if($i <= 2) : ?>
                            <div class="square-news">
                                <div class="content">
                                    <a href="<?php the_permalink(); ?>">
                                        <div class="news-img">
                                            <?php the_post_thumbnail('news-square'); ?>
                                        </div>
                                        <div class="news-content">
                                            <div class="news-content__meta"><span class="cat"><?php echo $cat; ?></span><?php echo get_the_date('F d, Y'); ?></div>
                                            <h4><?php the_title(); ?></h4>
                                            <span class="continue">Continue reading</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php else:; $is_video = (get_field('select_type') == 'Image') ? false : true; ?>
                        <div class="single-news">
                            <div class="single-news__img">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if($is_video) : ?>
                                        <div class="play-icon"></div>
                                    <?php endif; ?>

                                    <?php the_post_thumbnail('news-loop'); ?>
                                </a>
                            </div>
                            <div class="single-news__content">
                                <div class="single-news__content-meta"><span class="cat"><?php echo $cat; ?></span><?php echo get_the_date('F d, Y', get_the_ID()); ?></div>
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <div class="description">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="continue"><?php echo $is_video ? 'Watch video' : 'Read more'; ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($i % 2 == 0) : ?>
                            </div>
                            <div class="classic-row">
                        <?php endif; ?>

                    <?php $i++; endwhile; ?>

                </div>

            <?php else : ?>

                <div class="not-found">No news Found!</div>

            <?php endif; ?>

        </div>

        <button class="btn btn-load-more">Load more Articles and news</button>
    </div>
    <section class="email_contact" style="background: #c62a50;">
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
