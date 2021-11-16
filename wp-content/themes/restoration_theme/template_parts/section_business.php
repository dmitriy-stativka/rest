<?php
$heading = get_sub_field('heading');
$text = get_sub_field('business_text');
?>

<section class="business-text">
    <div class="container">
        <div class="business-text__wrapper">
            <h2 class="top-text" style="color: <?php the_sub_field('color_of_heading');?>">
                <?php echo $heading; ?>
            </h2>

            <?php if(get_sub_field('date_of_post')){ ?>             
                <span class="business-date" style="color: <?php the_sub_field('color_of_date');?>">
                    <?php the_sub_field('date_of_post'); ?>
                    <?php
                        if(get_field('hours_from') && get_field("hours_to")) {
                            echo " | ". get_field('hours_from'). " - ". get_field("hours_to");
                        }
                    ?>
                </span>
            <?php }?>
            
            <?php if(get_sub_field('location')){ ?> 
                <a onclick="$('html, body').animate({scrollTop:$('.map').offset().top}, '2000', 'swing');" class="business-location">
                    <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.4C12 8.73715 10.9914 11.0306 9.34448 13.1155C8.131 14.6517 6.60164 16 6 16C5.39836 16 3.869 14.6517 2.65552 13.1155C1.00864 11.0306 0 8.73715 0 6.4C0 2.86538 2.68629 0 6 0C9.31371 0 12 2.86538 12 6.4ZM3.80262 12.0845C4.34558 12.7719 4.94009 13.3956 5.49332 13.8834C5.71583 14.0795 6 14.2995 6 14.2995C6 14.2995 6.31603 14.0514 6.50668 13.8834C7.05991 13.3956 7.65442 12.7719 8.19738 12.0845C9.63698 10.262 10.5 8.29972 10.5 6.4C10.5 3.74903 8.48528 1.6 6 1.6C3.51472 1.6 1.5 3.74903 1.5 6.4C1.5 8.29972 2.36302 10.262 3.80262 12.0845ZM3.75 6.4C3.75 5.07452 4.75736 4 6 4C7.24264 4 8.25 5.07452 8.25 6.4C8.25 7.72548 7.24264 8.8 6 8.8C4.75736 8.8 3.75 7.72548 3.75 6.4ZM6 5.6C6.41421 5.6 6.75 5.95817 6.75 6.4C6.75 6.84183 6.41421 7.2 6 7.2C5.58579 7.2 5.25 6.84183 5.25 6.4C5.25 5.95817 5.58579 5.6 6 5.6Z" fill="#3E316B"/>
                    </svg>
                    <?php the_sub_field('location');?>
                </a>
            <?php }?>

            <div class="business-text__content">
                <?php echo $text; ?>
            </div>

            <?php if(get_sub_field('text_of_button_calendar')){ ?>
                <a class="business-calender" style="<?php the_sub_field('style_of_button_calendar');?>" href="<?php the_sub_field('link_of_button_calendar');?>">
                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 19H12V15H8V19ZM12 13V9H8V13H12ZM14 15V19H17.0049C17.5543 19 18 18.5531 18 17.9999V15H14ZM18 9H14V13H18V9ZM2 15V17.9999C2 18.5525 2.44631 19 2.99509 19H6V15H2ZM6 13V9H2V13H6ZM18 5.00011C18 4.44751 17.5537 4 17.0049 4C17 4.55237 16.5561 5 16 5C15.4477 5 15 4.55628 15 4.00019L13 4C13 4.55237 12.5561 5 12 5C11.4477 5 11 4.55628 11 4.00019L9 4C9 4.55237 8.55614 5 8 5C7.44772 5 7 4.55628 7 4.00019L5 4C5 4.55237 4.55614 5 4 5C3.44772 5 3 4.55628 3 4.00019C2.44573 4 2 4.44694 2 5.00011V7H18V5.00011ZM20 5.00011V17.9999C20 19.6564 18.6601 21 17.0049 21H2.99509C1.34053 21 0 19.6559 0 17.9999V5.00011C0 3.34359 1.33994 2 2.99509 2L3 0.999807C3 0.447629 3.44386 0 4 0C4.55228 0 5 0.443717 5 0.999807V2H7V0.999807C7 0.447629 7.44386 0 8 0C8.55229 0 9 0.443717 9 0.999807V2H11V0.999807C11 0.447629 11.4439 0 12 0C12.5523 0 13 0.443717 13 0.999807V2H15V0.999807C15 0.447629 15.4439 0 16 0C16.5523 0 17 0.443717 17 0.999807V2C18.6595 2 20 3.34415 20 5.00011Z" fill="white"/>
                    </svg>
                    <?php the_sub_field('text_of_button_calendar');?>
                </a>
            <?php }?>
        </div>
    </div>
</section>