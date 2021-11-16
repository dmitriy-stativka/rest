<section class="custom-grid-blocks grid-blocks <?php echo "grid-". get_sub_field("number_of_columns"); ?>">
    <div class="container">
        <div class="grid-blocks__inner">
            <?php if( get_sub_field("heading") ) : ?>
                <h2><?php echo get_sub_field("heading"); ?></h2>
            <?php endif; ?>

            <div class="grid-blocks__wrap">
                <?php if( have_rows("items_list") ) : ?>
                    <?php while (have_rows("items_list")) : the_row(); ?>
                        <div class="grid-post">
                            <?php if( get_sub_field("image") ) : ?>
                                <?php if( get_sub_field("link") ) : ?>
                                    <a href="<?php echo get_sub_field("link"); ?>" class="grid-post__img">
                                        <img src="<?php echo wp_get_attachment_image_src(get_sub_field("image"), "full")[0]; ?>" alt="<?php echo get_sub_field("title"); ?>">
                                    </a>
                                <?php else : ?>
                                    <img src="<?php echo wp_get_attachment_image_src(get_sub_field("image"), "full")[0]; ?>" alt="<?php echo get_sub_field("title"); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="grid-post__content">
                                <?php if( get_sub_field("link") ) : ?>
                                    <h4>
                                        <a href="<?php echo get_sub_field("link"); ?>">
                                            <?php echo get_sub_field("title"); ?>
                                        </a>
                                    </h4>
                                <?php else : ?>
                                    <h4><?php echo get_sub_field("title"); ?></h4>
                                <?php endif; ?>

                                <p>
                                    <?php echo get_sub_field("content"); ?>

                                    <?php if( get_sub_field("read_more") ) : ?>
                                        <?php if( has_shortcode( get_sub_field("read_more"), "button_shortcode") ) : ?>
                                            <?php echo do_shortcode( get_sub_field("read_more") ); ?>
                                        <?php else : ?>
                                            <a href="<?php echo get_sub_field("link"); ?>" class="grid-post__more" style="font-size: <?php if( get_sub_field("custom_button_font_size")) echo get_sub_field("custom_button_font_size"); ?>">
                                                <?php echo get_sub_field("read_more"); ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>