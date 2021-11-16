<section class="email_contact" style="background: <?php the_sub_field('background_of_email_block');?>">
    <div class="container">
        <div class="email_contact-text">
            <span class="email_contact-title"><?php the_sub_field('title_email');?></span>
            <p class="email_contact-desc"><?php the_sub_field('description_email');?></p>
        </div>
        <div class="email_contact-form">
            <?php echo do_shortcode('[ninja_form id="1"]');?>
        </div>
    </div>
</section>