<?php /* Template Name: Page Regular */ ?>
<div class="page-regular">
    <?php get_header(); ?>
        <div class="main-page-wrap">
        
            <?php if (have_rows('page_builder')):
                while (have_rows('page_builder')) : the_row();
                get_template_part( 'template_parts/' . get_row_layout());
                endwhile;
            endif; ?>

        </div>
    <?php get_footer(); ?>
</div>