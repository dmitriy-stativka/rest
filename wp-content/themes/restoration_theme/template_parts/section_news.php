<section class="news">
    <div class="container">

        <h2 class="news-title">Restoration News</h2>

        <div class="news-items">
            <div class="news-top">
                <?php
                    $params = array(
                        'post_type' => 'news',
                        'posts_per_page' => 3,
                    );
                    $query = new WP_Query( $params );
                    ?>
                    <?php if($query->have_posts()): ?>
                        <?php while ($query->have_posts()): $query->the_post() ?>
                            <?php $image = get_field('image'); ?>
                                <a href="<?php echo get_permalink();?>" class="module">
                                    <div class="module-post">
                                        <?php if (get_field('select_type') == 'Image') : ?>
                                            <img class="module-image" src="<?php echo $image;?>" alt="">
                                        <?php else : ?>
                                        <?php if (get_field('select_type') == 'YouTube') : ?>
                                            <iframe src="https://www.youtube.com/embed/<?php the_sub_field('youtube'); ?>" width="560" height="315" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <?php else : ?>
                                        <?php if (get_field('select_type') == 'Vimeo') : ?>
                                            <iframe src="https://player.vimeo.com/video/<?php the_sub_field('vimeo'); ?>" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                        <?php else : ?>
                                        <?php if (get_field('select_type') == 'Video') : ?>
                                            <video src="<?php the_sub_field('video'); ?>"></video>
                                        <?php else : ?>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        <div class="module-info">
                                            <div class="module-date">
                                                <?php
                                                $terms = get_the_terms(get_the_ID(), "insights_category");
                                                if ($terms) {
                                                    $i = 0;
                                                    foreach ($terms as $term) {
                                                        echo '<span class="module-category">' . $term->name . ' | </span>';
                                                    $i++;
                                                    }
                                                }; ?>
                                                <i class="module-the_date">  <?php the_date();?></i>
                                            </div>
                                            <h2 class="module-title"><?php the_title();?></h2>
                                            <?php if (get_field('select_type') == 'Image') : ?>
                                                <span class="module-btn">Continue reading</span>
                                            <?php else : ?> 
                                                <span class="module-btn">Watch video</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                    <?php endif; 
                ?>
            </div>
            <ul class="news-list">
                <?php
                    $params = array(
                        'post_type' => 'news',
                        'posts_per_page' => 7,
                        'offset' => 3
                    );
                    $query = new WP_Query( $params );
                    ?>
                    <?php if($query->have_posts()): ?>
                        <?php while ($query->have_posts()): $query->the_post() ?>
                            <?php $image_of_new = get_field('image_of_new'); ?>
                                <li class="news_mini--item"> 
                                    <a href="<?php echo get_permalink();?>" class="news_mini">
                                        <h2 class="news_mini-title"><?php the_title();?></h2>
                                        
                                        <div class="news_mini-post">

                                        
                                            <?php 
                                                $terms = get_the_terms(get_the_ID(), "insights_category");
                                                if ($terms) {
                                                    $i = 0;
                                                    foreach ($terms as $term) { ?>
                                                        <span class="news_mini-category"><?php echo $term->name;?></span>
                                                    <?php $i++;
                                                    }
                                                }; 
                                            ?>
                                            <i class="news_mini-date">| <?php the_date();?></i>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                    <?php endif; 
                ?>
            </ul>
        </div>

        <a class="news-more" href="/community/news/">See All News</a>
    </div>
</section>

<?php wp_reset_postdata();?>