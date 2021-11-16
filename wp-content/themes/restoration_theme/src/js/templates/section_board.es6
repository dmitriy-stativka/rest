const swiperPerson = new Swiper('.board-popup-swiper', {
    slidesPerView: 1,
    slidesPerGroup: 1,
    simulateTouch: false,
    navigation: false
})

$('.board-item').on('click', function(){
    $('.board-popup').addClass('show');
    const slideIndex = $(this).attr('data-index');
    swiperPerson.slideTo(slideIndex, 0);
    $('html').addClass('overflowHiden');
})

$('.board-popup-close').on('click', function(){
    $('.board-popup').removeClass('show');
    $('html').removeClass('overflowHiden');
})