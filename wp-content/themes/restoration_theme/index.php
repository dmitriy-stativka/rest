<?php get_header(); ?>

    <div class="main-page-wrap" style="margin-top: 200px;">
        <?php if (have_posts()) : ?>
            <?php /* Start the Loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile;
        endif; ?>
    </div>


<?php get_footer(); ?>