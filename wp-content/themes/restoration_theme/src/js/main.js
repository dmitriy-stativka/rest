jQuery(document).ready(function ($) {


    const links = document.querySelectorAll('a[href*="/event/"]');
    links.forEach(link => {
        link.addEventListener('click', redirect);
    })

    function redirect(e) {
        e.preventDefault();
        window.open('https://www.restorationplaza.org/events/', '_blank');
    }


    $('.carousel-slider').slick({
        'arrows': true,
        'dots': true,
        'slidesToShow': 1,
        'adaptiveHeight': true,
        responsive: [
            {
                breakpoint: 1066,
                settings: {
                    'arrows': false,
                }
            },
        ]
    });

    $('.search .search-btn, .search .search__close').on("click", function (e) {
        e.preventDefault();
        if (mobileMenuIsActive()) {
            $('.search-box').toggle();
        } else {
            $('.search-box').fadeToggle();
        }
        $('.search-btn').toggleClass('active');
    });

    $('.mobile-close').on('click', function (e) {
        e.preventDefault();
        toggleMenu();
    });

    $("#categories-menu").on("change", function () {
        let cat = $(this).val();
        $('.categories-menu li').removeClass('active');
        $('.categories-menu li[data-cat="' + cat + '"]').addClass('active');

        jQuery.ajax({
            url: ajax_actions.url,
            type: "POST",
            data: {
                'action': 'get_news_by_cat',
                'paged': 1,
                'limit': 5,
                'cat': cat
            },
            success: function (response) {

                $('.news-archive__wrap').html(response.data);
                $('.btn.btn-load-more').text("Load more Articles and news").css("pointer-events", "initial")
                ajax_actions.paged = 2;
            }
        });
    });

    $('.categories-menu li').on('click', function (e) {
        e.preventDefault();
        $('.categories-menu li').removeClass('active');
        $(this).addClass('active');
        let cat = $(this).data('cat');
        $("#categories-menu").val(cat);

        jQuery.ajax({
            url: ajax_actions.url,
            type: "POST",
            data: {
                'action': 'get_news_by_cat',
                'paged': 1,
                'limit': 5,
                'cat': cat
            },
            success: function (response) {

                $('.news-archive__wrap').html(response.data);
                $('.btn.btn-load-more').text("Load more Articles and news").css("pointer-events", "initial")
                ajax_actions.paged = 2;
            }
        });
    });

    $('.btn.btn-load-more').on('click', function (e) {

        let that = $(this);
        let cat = $('.categories-menu li.active').data('cat');

        jQuery.ajax({
            url: ajax_actions.url,
            type: "POST",
            data: {
                'action': 'get_news',
                'paged': ajax_actions.paged,
                'limit': 5,
                'cat': cat
            },
            success: function (response) {
                if (response.success) {
                    $('.news-archive__wrap').append(response.data);
                }
                ajax_actions.paged++;
            }
        });
    });

    $('.accordion-item__heading').on('click', function (e) {
        $(this).toggleClass('opened');
        $(this).parent().find('.accordion-item__body').slideToggle();
    });

    $('body').on('click', '.mobile-collapse', function (e) {
        e.preventDefault();
        if ($(this).parent().siblings('div').length > 0 && mobileMenuIsActive())
            $(this).parent().toggleClass('active');
    });

    $('.wpmm-item-title').on('click', function (e) {
        e.preventDefault();
        if ($(this).siblings('div').length > 0 && mobileMenuIsActive())
            $(this).toggleClass('active');
    });

    $("li.wpmm-submenu-right > a.header__menu-link").each(function () {
        let text = $(this).text();
        $(this).html('<span>' + text + '</span><span class="mobile-collapse"></span>');
    });

    wpmmMobileMenuActive();
    stickyMenuActive();

    $(window).on('resize load scroll', function () {
        wpmmMobileMenuActive();
        stickyMenuActive();
    });

    //Toggle on desktop search post type
    $('.search-desktop-select').on('click', function () {
        if (!$(this).hasClass('active')) {
            let nextTab = $(this).data('type');
            let searchMenu = $('#search-menu');
            searchMenu
                .val(nextTab)
                .trigger('change');
        }
    });

    // Change visible search post type
    $('#search-menu').on('change', function () {
        let currentTabValue = $(this).val();
        let previeosTab = $('.search-desktop-select.active');
        let currentTab = $('.search-desktop-select[data-type="' + currentTabValue + '"');

        previeosTab.removeClass('active');
        currentTab.addClass('active');

        if (currentTabValue == 'pages') {
            $('.search-container[data-type="pages"]').show("fast");
            $('.search-container[data-type="news"]').hide("fast");
            $('.search-container[data-type="events"]').hide("fast");
        } else if (currentTabValue == 'news') {
            $('.search-container[data-type="news"]').show("fast");
            $('.search-container[data-type="pages"]').hide("fast");
            $('.search-container[data-type="events"]').hide("fast");
        } else if (currentTabValue == 'events') {
            $('.search-container[data-type="events"]').show("fast");
            $('.search-container[data-type="news"]').hide("fast");
            $('.search-container[data-type="pages"]').hide("fast");
        } else {
            $('.search-container').show('fast');
        }
    });

    // Search Load More Posts
    $(".btn-load-more").on('click', function () {
        let postType = $(this).attr('data-post-type');
        let container = $('.search-container__wrap[data-post-type="' + postType + '"]');

        if (container === undefined)
            return;

        let currentPage = parseInt(container.attr('data-page'));
        let maxPage = parseInt(container.attr('data-page-max'));
        let filter = container.attr('data-filter');
        let button = $('.btn-load-more[data-post-type="' + postType + '"]');

        if (currentPage < maxPage) {
            button.prop('disabled', true);
            button.addClass('loading');

            $.post(ajax_actions, {
                action: 'get_search_posts_by_filter',
                paged: currentPage + 1,
                filter: filter,
                postType: postType
            })
                .done(function (data) {
                    if (data['success']) {
                        $(container).append(data['data']);
                        $(container).attr('data-page', currentPage + 1);
                        button.removeClass('loading');
                        button.prop('disabled', false);

                        if (maxPage <= currentPage + 1) {
                            button.hide();
                        }
                    }
                });
        } else {
            button.hide();
        }

    });

    if ($('.donation__form').length > 0) {
        $('.donation__form').find('select').niceSelect();
    }


    /* switch amount of donation */
    let amountValue;
    let fomMain = jQuery('#payment-form');
    let amountButton = jQuery('.donation__amount-item ');
    let amountEnter = jQuery('.donation__enter-amount input');
    amountValue = jQuery('.donation__amount-item.active').data('price');

    amountEnter.on('input', function () {
        jQuery(this).addClass('active');
        amountButton.removeClass('active');
        amountValue = jQuery(this).val();
    });

    amountButton.click(function () {
        amountButton.removeClass('active');
        amountEnter.val('').removeClass('active');
        jQuery(this).addClass('active');
        amountValue = jQuery(this).data('price');
    });

    let dedicationName = fomMain.find('[name="dedication-name"]');
    fomMain.find('[name="dedication"]').on('change', function () {
        if (jQuery(this).val() !== 'none') {
            dedicationName.attr('required', 'required')
        } else {
            dedicationName.removeAttr('required').val('');
        }
    });

    let statesContainer = jQuery('.input-group-states');
    let statesUS = statesContainer.html();
    let statesOther = '<label for="your-city">State * <input type="text" name="your-state" id="your-state" placeholder="" data-placeholder="State" required></label>';
    jQuery('.input-group-country').on('change', function () {
        let country = jQuery(this).find(':selected').val();
        if (country !== 'United States') {
            statesContainer.html(statesOther);
        } else {
            statesContainer.html(statesUS);
        }
    });

    fomMain.on('submit', function (e) {
        e.preventDefault();
        let data = new Object();
        let form_serialize = fomMain.serialize();

        data.action = 'paypal_order_create';
        data.form = form_serialize + '&amount=' + amountValue;
        data.success = true;

        console.log(data);

        jQuery.ajax({
            method: 'POST',
            url: ajax_actions.url,
            data: data,
            success: function (response) {
                console.log(response);
                location.reload();
            }
        });
    });

    /*  end  */

});

document.addEventListener('DOMContentLoaded', () => {
    const players = Array.from(document.querySelectorAll('.js-player')).map
    (p =>
            new Plyr(p),
        {
            controls: [
                'play-large', // The large play button in the center
                'play', // Play/pause playback
                'mute', // Toggle mute
                'fullscreen', // Toggle fullscreen
                'progress', // The progress bar and scrubber for playback and buffering
                'current-time', // The current time of playback
            ],
            youtube: {
                controls: 0,
                rel: 0,
                vq: 'hd720',
                playsinline: 1,
                iv_load_policy: 3,
                modestbranding: 1,
                showinfo: 0,
                enablejsapi: 1
            },
            vimeo: {
                controls: 0,
                quality: "1080p",
                loop: false,
                byline: false,
                portrait: false,
                title: false,
                speed: true,
                transparent: 0,
                gesture: "media"
            },
            clickToPlay: true,
            quality: 1080
        }
    );
});

let mobileMenuIsActive = function () {
    return jQuery('header.header').hasClass('header-mobile');
};

let mobileHeader = document.getElementById('mobile-header');
let header = document.getElementById('header');
let menuWrapper = document.getElementById('menu-wrapper');
let menu = document.getElementById('wp-megamenu-primary');

function toggleMenu() {
    $("body").toggleClass("-open");
    disableDocumentScroll(false);

    $('header.header-mobile').toggleClass('active');
    if ($('header.header-mobile').hasClass('active')) {
        $('#content, .main-page-wrap').addClass('header-overlay');
    } else {
        $('#content, .main-page-wrap').removeClass('header-overlay');
    }
}

$(window).on("resize", function () {

    if ($("body").hasClass("-open")) {
        if ($(window).width() < 992) {
            disableDocumentScroll(true);
        } else {
            $("body").removeAttr("style");
        }
    }

});

let scrollPos = 0;

//disable scroll for document
function disableDocumentScroll(resize) {

    if (!resize) {
        scrollPos = $(window).scrollTop();
    }

    if ($("body").hasClass("-open")) {
        $("body.-open").css({
            "top": -scrollPos,
            "overflow": "hidden",
            "position": "fixed",
            "left": 0,
            "right": 0
        });
    } else {
        $("body").removeAttr("style");
        window.scrollTo(0, scrollPos);
    }
}

menu.onmouseout = function (event) {
    jQuery('.header').removeClass('white');
};

menu.onmouseover = function (event) {
    if (!mobileMenuIsActive() && !jQuery('.header').hasClass('sticky') && !jQuery('body').hasClass('post-type-archive-events') && !jQuery('body').hasClass('post-type-archive-news'))
        jQuery('.header').addClass('white');
};

$(window).on('load', function () {
    $('.hero').addClass('hero-animate');
    $('.hero-section_image').addClass('hero-section_image-animate');
})

// Add mobile class if mobile menu active
function wpmmMobileMenuActive() {
    let current_width = parseInt($(window).width());
    const responsive_breakpoint = 1024;

    if (current_width < (responsive_breakpoint + 1)) {
        if (!mobileMenuIsActive()) {
            $('header.header').addClass('header-mobile');
            if ($('header.header').hasClass('active')) {
                $('html, body').css('overflow', 'hidden');
                $('#content, .main-page-wrap').addClass('header-overlay');
            }
        }
    } else {
        if (mobileMenuIsActive()) {
            $('header.header').removeClass('header-mobile');
            $('html, body').css('overflow', 'visible');
            $('#content, .main-page-wrap').removeClass('header-overlay');
        }
    }
}

// Add sticky class if scroll down
function stickyMenuActive() {
    if (mobileMenuIsActive()) {
        if (window.pageYOffset >= mobileHeader.offsetTop)
            mobileHeader.classList.add("sticky")
        else
            mobileHeader.classList.remove("sticky");
    } else {
        if (window.pageYOffset >= menuWrapper.offsetTop + menuWrapper.offsetHeight + 90)
            header.classList.add("sticky")

        else
            header.classList.remove("sticky");
    }

}

$(window).on('load', function () {
    $('.grid').masonry({
        itemSelector: '.grid--img',
    });
})


const swiperPerson = new Swiper('.board-popup-swiper', {
    slidesPerView: 1,
    slidesPerGroup: 1,
    simulateTouch: false,
    navigation: false,
    breakpoints: {
        320: {
            simulateTouch: false,
        },
        380: {
            simulateTouch: false,

        },
        767: {
            simulateTouch: false,

        },
        1024: {
            simulateTouch: false,
        },
    }

})

$('.board-item').on('click', function () {
    $('.board-popup').addClass('show');
    const slideIndex = $(this).attr('data-index');
    swiperPerson.slideTo(slideIndex, 0);

    $('body').addClass('shadow');
    $('html').addClass('overflowHiden');
})

$('.board-popup-item-close').on('click', function () {
    $('.board-popup').removeClass('show');
    $('body').removeClass('shadow');
    $('html').removeClass('overflowHiden');
})

$('.popup-connect-close').on('click', function () {
    $('.popup-connect').removeClass('show-popup');
    $('html').removeClass('overflowHiden');
})


let clicksCount = (sessionStorage.getItem('showPopup')) ? sessionStorage.getItem('showPopup') : 0;
document.addEventListener('click', listenerClicks);

function listenerClicks(e) {
    clicksCount++;
    sessionStorage.setItem('showPopup', clicksCount);

    if (sessionStorage.getItem('showPopup') == 3) {
        if (e.target.tagName == "A" || e.target.tagName == "IMG") {

            console.log(e.target.tagName);
            e.preventDefault();
        }
        $('.popup-connect').addClass('show-popup');
        document.removeEventListener('click', listenerClicks);
    }
}


$('.pastEvents a').on('click', function () {
    var d = new Date();
    var curr_date = d.getDate() - 1;
    var curr_month = d.getMonth() + 1;
    var curr_year = d.getFullYear();
    window.location.href = '/past-events/?events_nonce_field=30e9acb488&_wp_http_referer=%2Fevents%2F&date=' + curr_year + "-" + curr_month + "-" + curr_date + '&category=&location=&keyword='
})


$('.input-dolar input').on('change', function () {
    if ($('.input-dolar input').val() <= 0) {
        $(this).addClass('zero-error');
        $('.input-dolar-error').show();
        $(this).val('0');
    } else {
        $(this).removeClass('zero-error');
        $('.input-dolar-error').hide();
    }
})

window.addEventListener('load', () => {
    const redirectArray = [
        "https://secureservercdn.net/198.71.233.35/qkc.722.myftpupload.com/21134-2/",
        "https://restorationplaza.org/let-us-help-you-find-your-next-job/",
        "https://restorationplaza.org/managing-stress-and-anxiety-during-the-covid-19-crisis/",
        "https://restorationplaza.org/featured-amsterdam-news/",
        "https://restorationplaza.org/book-signing-jacqueline-woodson/",
        "https://restorationplaza.org/press-release-new-economic-solutions-center/",
        "https://restorationplaza.org/hattie-carthan-community-garden/",
        "https://restorationplaza.org/here-and-now/",
        "https://restorationplaza.org/nyc-dot-pressrelease/",
        "https://restorationplaza.org/pnyesc/",
        "https://restorationplaza.org/bdeesc/",
        "https://restorationplaza.org/bkresc/",
        "https://restorationplaza.org/ny1esc/",
        "https://restorationplaza.org/tccbb/",
        "https://restorationplaza.org/jdnychaja/",
        "https://restorationplaza.org/jdmcnycha/",
        "https://restorationplaza.org/tdbankeducation/",
        "https://restorationplaza.org/statement-recent-elections/",
        "https://restorationplaza.org/citi-bike-expansion/",
        "https://restorationplaza.org/rfps-bike-share2016/",
        "https://restorationplaza.org/nytimes-citi-bike-restoration/",
        "https://restorationplaza.org/restoration-pfc-sia/",
        "https://restorationplaza.org/restorations-farm-pfc-sia/",
        "https://restorationplaza.org/mayor-de-blasio-citi-bike-2016/",
        "https://restorationplaza.org/cm-laurie-cumbo-ce/",
        "https://restorationplaza.org/restoration-bill-de-blasio-bed-stuy/",
        "https://restorationplaza.org/citi-bike-omar-arias/",
        "https://restorationplaza.org/nyc-bike-op-ed-new-york/",
        "https://restorationplaza.org/brooklyn-is-africa-amsterdam-news/",
        "https://restorationplaza.org/restoration-staff-reading-partners/",
        "https://restorationplaza.org/tracey-capers-brooklyn-mag/",
        "https://restorationplaza.org/billie-holday-theatre-new-york-times/",
        "https://restorationplaza.org/support-elsie-richardson-scholarship/",
        "https://restorationplaza.org/citi-bike-doubles-since-2015/",
        "https://restorationplaza.org/press-release-bike-share/",
        "https://restorationplaza.org/bike-share-bed-stuy/",
        "https://restorationplaza.org/omar-arias-nycha-journal/",
        "https://restorationplaza.org/may-bike-month/",
        "https://restorationplaza.org/bike-share-citi-bike-rfp/",
        "https://restorationplaza.org/room-rental-changes/",
        "https://restorationplaza.org/community-ambassador-program/",
        "https://restorationplaza.org/social-media-revamp/",
        "https://restorationplaza.org/syep-at-restoration-2/",
        "https://restorationplaza.org/comptroller-dinapoli-report-gentrification-in-bedford-stuyvesant/",
        "https://restorationplaza.org/yuna-beauty-supply/",
        "https://restorationplaza.org/youth-arts-academy/",
        "https://restorationplaza.org/weatherization-assistance-program/",
        "https://restorationplaza.org/universal-processing-solutions-inc/",
        "https://restorationplaza.org/u-s-post-office/",
        "https://restorationplaza.org/tonys-country-life/",
        "https://restorationplaza.org/thriftway-pharmacy/",
        "https://restorationplaza.org/the-new-cutting-edge/",
        "https://restorationplaza.org/the-learning-center-of-bedford-stuyvesant/",
        "https://restorationplaza.org/the-gospel-den/",
        "https://restorationplaza.org/the-butcher-shop/",
        "https://restorationplaza.org/tastee-pattee-ltd/",
        "https://restorationplaza.org/tajasia-soul-food-restaurant/",
        "https://restorationplaza.org/tai-king-restaurant-inc/",
        "https://restorationplaza.org/superb-driving-school/",
        "https://restorationplaza.org/super-foodtown-supermarket/",
        "https://restorationplaza.org/assemblywoman-tremaine-wright/",
        "https://restorationplaza.org/star-security-training-corp/",
        "https://restorationplaza.org/spencer-place-bed-and-breakfast/",
        "https://restorationplaza.org/solano-optical/",
        "https://restorationplaza.org/s-r-y-design-associates/",
        "https://restorationplaza.org/sco/",
        "https://restorationplaza.org/rubins-beauty-salon/",
        "https://restorationplaza.org/rose-beauty-supply/",
        "https://restorationplaza.org/room-hall-rental-social-civic-functions/",
        "https://restorationplaza.org/ronak-newsstand-and-candy-shop/",
        "https://restorationplaza.org/prosperity-hardware/",
        "https://restorationplaza.org/popeyes-fried-chicken/",
        "https://restorationplaza.org/planned-parenthood-project-street-beat/",
        "https://restorationplaza.org/phoenix-house-recovery-center/",
        "https://restorationplaza.org/sp-plus-parking-garage/",
        "https://restorationplaza.org/opportunities-for-a-better-tomorrow-obt/",
        "https://restorationplaza.org/one-world-health-fitness-center/",
        "https://restorationplaza.org/nostrand-wines-and-liquors/",
        "https://restorationplaza.org/noel-pointer-foundation/",
        "https://restorationplaza.org/nicholas-brooklyn/",
        "https://restorationplaza.org/michelles-beauty-salon/",
        "https://restorationplaza.org/mcdonalds/",
        "https://restorationplaza.org/manu-fashions/",
        "https://restorationplaza.org/malik-sportwear-inc/",
        "https://restorationplaza.org/m-a-d-discount-hardware/",
        "https://restorationplaza.org/llj-meat-market/",
        "https://restorationplaza.org/little-sun-people/",
        "https://restorationplaza.org/lana-discount-beauty-supply/",
        "https://restorationplaza.org/juke-joint-juice-jaffe/",
        "https://restorationplaza.org/john-b-jemmot-all-year-tax-service/",
        "https://restorationplaza.org/jeffs-express-inc/",
        "https://restorationplaza.org/international-african-arts-festival/",
        "https://restorationplaza.org/george-candle/",
        "https://restorationplaza.org/fulton-outfitters/",
        "https://restorationplaza.org/fulton-gourmet-deli-and-grocery/",
        "https://restorationplaza.org/fox-media-corporation/",
        "https://restorationplaza.org/fostering-change-for-children/",
        "https://restorationplaza.org/five-star-pharmacy/",
        "https://restorationplaza.org/first-vision-optical/",
        "https://restorationplaza.org/ez-bz-fashions/",
        "https://restorationplaza.org/edwingouldservices/",
        "https://restorationplaza.org/curves-fitness/",
        "https://restorationplaza.org/cross-boro-realty/",
        "https://restorationplaza.org/county-pharmacy/",
        "https://restorationplaza.org/councilmember-robert-cornegy/",
        "https://restorationplaza.org/construction-online/",
        "https://restorationplaza.org/community-planning-board-3/",
        "https://restorationplaza.org/college-of-new-rochelle/",
        "https://restorationplaza.org/citibank/",
        "https://restorationplaza.org/chase-bank/",
        "https://restorationplaza.org/carver-federal-savings-bank/",
        "https://restorationplaza.org/bvsj-watchful-eye-program/",
        "https://restorationplaza.org/burger-king/",
        "https://restorationplaza.org/brownstoners-of-bedford-stuyvesant/",
        "https://restorationplaza.org/brooklyn-community-service/",
        "https://restorationplaza.org/brooklyn-community-pride-center/",
        "https://restorationplaza.org/bravo-supermarket/",
        "https://restorationplaza.org/birdels-tapes-and-records/",
        "https://restorationplaza.org/billie-holiday-theatre/",
        "https://restorationplaza.org/big-brothers-discount-hardware-appliance/",
        "https://restorationplaza.org/bedford-stuyvesant-family-health-center/",
        "https://restorationplaza.org/bed-stuy-pharmacy/",
        "https://restorationplaza.org/bed-stuy-family-health-center-wic-program/",
        "https://restorationplaza.org/bed-stuy-community-legal-services/",
        "https://restorationplaza.org/bed-stuy-gateway-bid/",
        "https://restorationplaza.org/asterix-party-place/",
        "https://restorationplaza.org/aphrika-hair/",
        "https://restorationplaza.org/alis-trinidad-roti/",
        "https://restorationplaza.org/apple-bees-bed-stuy/",
        "https://restorationplaza.org/2020-art/",
        "https://restorationplaza.org/bk-reader-restoration-hosts-open-house-to-highlight-importance-of-fulton-streets-preservation/",
        "https://restorationplaza.org/mentalhealthawarenessday/",
        "https://restorationplaza.org/citi-bike-reaches-crown-heights/",
        "https://restorationplaza.org/talib-kweli-and-les-nubians-to-headline-restorationrocks-during-bed-stuy-alive/",
        "https://restorationplaza.org/preservetheplaza/",
        "https://restorationplaza.org/brooklyncommunitypridecenter/",
        "https://restorationplaza.org/36thannual10kcommunityrun/",
        "https://restorationplaza.org/genevieve-and-alicia/",
        "https://restorationplaza.org/mayoral-debates-2017/",
        "https://restorationplaza.org/restoration-connection-october-2017/",
        "https://restorationplaza.org/cumbe-center-comes-home-to-bed-stuy/",
        "https://restorationplaza.org/broadway-world-billie-holiday-theatres-autumn-sweeps-2017-audelco-awards/",
        "https://restorationplaza.org/restorations-50th-anniversary-gala-is-a-smashing-success/",
        "https://restorationplaza.org/jobs-plus-cuts-the-ribbon-on-a-new-site-in-east-new-york/",
        "https://restorationplaza.org/the-old-settler-spins-a-web-of-charm-and-laughter-at-billie-holiday-theatre/",
        "https://restorationplaza.org/robert-f-kennedy-memorial-holiday-party-2017/",
        "https://restorationplaza.org/usda-aims-to-improve-food-and-nutrition-education-for-low-income-communities/",
        "https://restorationplaza.org/citibike-tumblr-a-brooklynites-journey-to-biking/",
        "https://restorationplaza.org/nycbetterbikeshare/",
        "https://restorationplaza.org/broadway-world-the-billie-holiday-theatre-and-restorationart-present-the-new-york-premiere-of-a-small-oak-tree-runs-red/",
        "https://restorationplaza.org/rfkholidaypartywintercheer/",
        "https://restorationplaza.org/event/idnyc-enrollment-esc-central/2018-09-05/",
        "https://restorationplaza.org/bk-reader-50-years-of-restoration-building-assets-and-community-wealth/",
        "https://restorationplaza.org/next-city-nyc-aims-to-reduce-the-disease-of-financial-distress/",
        "https://restorationplaza.org/dance-garment-of-destiny/",
        "https://restorationplaza.org/brooklyn-paper-proud-moment-local-pride-center-gets-120000-to-start-internship-program-for-lgbtq-youths/",
        "https://restorationplaza.org/restoration-selected-as-semi-finalist-in-age-smart-employer-awards/",
        "https://restorationplaza.org/elsie-e-richardson-scholarship-awards-3-students-for-2017-2018-academic-year/",
        "https://restorationplaza.org/adam-clayton-powell-jr-comes-to-life-in-one-man-show/",
        "https://restorationplaza.org/wadiyah-latif-retires-from-restoration-after-47-years-of-service/",
        "https://restorationplaza.org/enterprise-awards-550000-in-hud-section-4-grants-in-new-york/",
        "https://restorationplaza.org/department-of-consumer-affairs-releases-report-on-the-ways-neighborhoods-influence-residents-financial-health/",
        "https://restorationplaza.org/billie-holiday-theatre-presents-a-small-oak-tree-runs-red/",
        "https://restorationplaza.org/sonia-sanchez-recalls-herstory-and-poetry-in-stella-adler-actors-conversation/",
        "https://restorationplaza.org/restoration-hosts-mixer-to-promote-tourism-and-business-in-bed-stuy/",
        "https://restorationplaza.org/kings-county-politics-cornegy-gives-bed-stuy-seniors-new-tools-to-fight-tickets/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-to-offer-free-tax-prep-service-for-low-income-new-yorkers/",
        "https://restorationplaza.org/kings-county-politics-jeffries-wright-confront-bed-stuy-gentrification/",
        "https://restorationplaza.org/an-interview-with-dr-indira-etwaroo/",
        "https://restorationplaza.org/restoration-dance-youth-ensemble-takes-los-angeles/",
        "https://restorationplaza.org/bk-reader-brooklyn-historical-society-honored-with-ny-museum-associations-award-of-merit/",
        "https://restorationplaza.org/restorationisawardedbrooklyncenturayawards/",
        "https://restorationplaza.org/bk-reader-bed-stuy-crown-heights-listed-among-the-nations-most-gentrified-areas/",
        "https://restorationplaza.org/hugh-price-conversation-and-book-signing/",
        "https://restorationplaza.org/broadway-world-50in50-womans-voices-initiative-curated-by-playwright-dominique-morriseau-comes-to-the-billie-holiday-theatre/",
        "https://restorationplaza.org/ny-daily-news-the-black-panther-moment-to-seize-to-promote-arts-diversity/",
        "https://restorationplaza.org/brooklyn-business-center-helps-pave-the-way-to-success-for-local-business/",
        "https://restorationplaza.org/basing-bike-share-in-community-bbspchat/",
        "https://restorationplaza.org/bk-reader-what-happens-when-history-becomes-his-story-and-not-ours/",
        "https://restorationplaza.org/bk-reader-the-high-price-of-knowledge/",
        "https://restorationplaza.org/tax-season-update/",
        "https://restorationplaza.org/shopsmall-fulton-street-prince-deli-smoke-shop/",
        "https://restorationplaza.org/stress-management-support-group/",
        "https://www.eventbrite.com/e/promoting-bike-share-through-ride-leadership-and-community-events-tickets-42434322200#new_tab",
        "https://restorationplaza.org/bk-reader-photos-bed-stuys-madam-c-j-walker-awards-celebrates-beauty-entrepreneurs-of-color/",
        "https://restorationplaza.org/local-resident-kweli-campbell-leads-first-community-ride-of-the-season/",
        "https://tockify.com/cac1368/detail/264/1527721200000#new_tab",
        "https://restorationplaza.org/forbes-the-story-behind-moms-of-black-boys-united/",
        "https://restorationplaza.org/brooklyn-business-center-hosts-seoul-credit-guarantee-foundation/",
        "https://restorationplaza.org/volunteer-spotlight-anthony-f/",
        "https://restorationplaza.org/shopsmall-fulton-street-noel-pointer-foundation/",
        "https://restorationplaza.org/amny-brooklyn-and-gentrification-will-bed-stuy-feel-the-starbucks-effect-from-new-coffee-shop/",
        "https://restorationplaza.org/bk-reader-the-billie-holiday-theatre-closes-its-2017-18-season-with-pulitzer-prize-finalist-yellowman/",
        "https://restorationplaza.org/ny-times-review-a-living-archive-of-contemporary-black-dance/",
        "https://restorationplaza.org/free-wealth-building-wednesday-series-aims-to-build-financial-confidence/",
        "https://restorationplaza.org/broadway-world-brooklyn-jazz-hall-of-fame-awards-and-induction-ceremony-set-for-wednesday/",
        "https://restorationplaza.org/bed-stuy-patch-3300-affordable-apartments-coming-to-central-brooklyn-governor/",
        "https://restorationplaza.org/amny-vital-brooklyn-funding-initiative-outlined-by-cuomo-in-bed-stuy/",
        "https://restorationplaza.org/restoration-rides-into-national-bike-month/",
        "https://restorationplaza.org/11th-annual-urban-arts-festival-taking-place-at-bed-stuy-restoration-may-22nd-in-partnership-with-honeybaked-ham-and-restoration-art/",
        "https://restorationplaza.org/almost-2000-nycha-residents-and-lower-income-new-yorkers-received-2-8-million-in-refunds-through-nychas-free-tax-prep-program/",
        "https://restorationplaza.org/restoration-joins-campaign-to-increase-workforce-development-funding/",
        "https://restorationplaza.org/brooklyn-daily-eagle-spike-lees-party-honoring-prince-set-june-9th/",
        "https://restorationplaza.org/restoration-receives-best-of-new-york-award/",
        "https://restorationplaza.org/adapt-community-network-flushing-avenue-day-program-hosts-open-house/",
        "https://restorationplaza.org/ny-times-betty-davis-was-a-raw-funk-pioneer-her-decades-of-silence-are-over/",
        "https://www.eventbrite.com/e/the-racial-wealth-gap-series-shemeka-brathwaite-on-your-distinctive-factor-tickets-46191085781#new_tab",
        "https://restorationplaza.org/restoration-and-citi-bike-to-host-june-23rd-birthday-bike-bash/",
        "https://restorationplaza.org/nyc-office-of-the-mayor-mayor-de-blasio-announces-fifth-anniversary-of-citibike-in-new-york-city/",
        "https://restorationplaza.org/ny-daily-news-thank-you-bobby-kennedy-for-caring-when-others-didnt/",
        "https://restorationplaza.org/economic-solutions-center-highlights-community-members-at-success-celebrations/",
        "https://restorationplaza.org/report-a-shared-future-inclusion-through-homeownership/",
        "https://restorationplaza.org/birthday-bike-bash/",
        "https://restorationplaza.org/bk-reader-omari-hardwick-explores-relationships-black-fatherhood-and-gun-violence-in-one-man-show-at-billie/",
        "https://restorationplaza.org/citi-bike-profile-becoming-a-bike-share-believer/",
        "https://restorationplaza.org/event/wealth-building-wednesdays-10-top-businesses-to-start-now/#new_tab",
        "https://restorationplaza.org/brooklyn-daily-eagle-spike-in-rat-complaints-prompts-bp-adams-to-hold-rat-summit/",
        "https://restorationplaza.org/woodhull-farmers-market-returns-to-north-bed-stuy/",
        "https://restorationplaza.org/restoration-becomes-polling-site-and-training-location/",
        "https://restorationplaza.org/nyc-office-of-the-mayor-mayor-announces-low-cost-citi-bike-memberships-will-be-available-to-all-snap-recipients/",
        "https://restorationplaza.org/new-york-times-citi-bike-expands-discount-memberships-to-reach-more-low-income-new-yorkers/",
        "https://restorationplaza.org/citi-bike-multi-modal/",
        "https://slate.com/business/2018/05/citi-bike-at-five-years-is-a-huge-success-for-new-yorkers.html#new_tab",
        "https://restorationplaza.org/job-fair-2018-employee-form/",
        "https://restorationplaza.org/job-fair-2018-employer-form/",
        "https://restorationplaza.org/the-more-things-change-the-more-things-stay-the-same-art-exhibition-debuts-at-restoration/",
        "https://bedstuyrestoration1.tumblr.com/#new_tab",
        "https://medium.com/@gioncarlovalentine/teresa-shares-her-story-of-leaving-america-and-returning-to-rebuild-her-life-and-her-community-b516bea2fee#new_tab",
        "https://bedstuyrestoration1.tumblr.com/private/171909789579/tumblr_p5njpf8Eoc1x8sj5t#new_tab",
        "https://restorationplaza.org/brownstoner-producer-charles-hobson-remembers-inside-bedford-stuyvesant-on-its-50th-anniversary/",
        "https://restorationplaza.org/report-bedford-stuyvesant-restoration-corporations-farm-to-early-care-program-a-foundation-for-healthier-stronger-central-brooklyn-families-and-communities/",
        "https://restorationplaza.org/brownstoner-producer-charles-hobson-remembers-inside-bedford-stuyvesant-on-its-50th-anniversary-2/",
        "https://restorationplaza.org/where-are-they-now-elsie-e-richardson-scholarship-winners-2017/",
        "https://restorationplaza.org/citi-bike-profile-biking-for-the-culture/",
        "https://restorationplaza.org/fresh-food-fresh-faces-north-bed-stuy-farmers-market/",
        "https://restorationplaza.org/new-york-state-governor-cuomo-announces-next-step-in-1-4-billion-vital-brooklyn-initiative/",
        "https://restorationplaza.org/nyc-cool-roofs-wants-to-keep-bed-stuy-cool/",
        "https://restorationplaza.org/working-together-to-improve-community-level-health-the-evolution-of-the-new-york-city-food-fitness-partnership/",
        "https://tockify.com/cac1368/detail/299/1536359400000#new_tab",
        "https://restorationplaza.org/event/paraderide-2018-09-08/#new_tab",
        "http://centerforblackliterature.org/rediscovering-zora-neale-hurston/#new_tab",
        "https://restorationplaza.org/broadway-world-def-poetry-jam-reunion-coming-to-the-billie-holiday-theatre/",
        "https://tockify.com/cac1368/detail/297/1537038000000#new_tab",
        "https://wibo.works/wibo-fall-registration/#new_tab",
        "https://restorationplaza.org/stress-management-workshop/",
        "https://restorationplaza.org/tony-award-winner-kenny-leon-to-direct-colman-domingos-dot-at-the-billie-holiday-theatre/",
        "https://restorationplaza.org/skylight-open/",
        "https://restorationplaza.org/wibo-brings-16-week-workshop-to-restoration/",
        "https://tockify.com/cac1368/detail/304/1537574400000#new_tab",
        "https://restorationplaza.org/event/cbr-fraternities/#new_tab",
        "https://restorationplaza.org/restoration-partners-with-a-list-preparing-students-for-college-success/",
        "https://restorationplaza.org/associated-press-report-one-third-of-households-struggle-to-pay-energy-bills/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-to-host-37th-annual-10k-run-and-fun-walk/",
        "https://restorationplaza.org/john-hope-bryant-wbw/",
        "https://restorationplaza.org/glimpses-from-the-inaugural-bedford-stuyvesant-crown-heights-film-festival/",
        "http://www.bed-stuyalive.org/home-.html#new_tab",
        "https://restorationplaza.org/bk-reader-legalizing-marijuana-what-it-could-mean-for-new-yorkers/",
        "https://nyc.electiondayworker.com/r/rA_4yzXNYkmL7ahEXYi0dw#new_tab",
        "https://restorationplaza.org/event/sat-prep-class/#new_tab",
        "https://restorationplaza.org/bedford-stuyvesant-corporation-celebrates-annual-gala/",
        "https://restorationplaza.org/transportation-alternatives-tracey-capers-is-a-convert/",
        "https://restorationplaza.org/event/money-talks-are-you-listening/#new_tab",
        "https://restorationplaza.org/event/job-fair-workforce-hiring-event-2/2019-05-15/#new_tab",
        "https://restorationplaza.org/finalists-spark-prize/",
        "https://restorationplaza.org/a-guide-to-growing-good-food-jobs-in-new-york-city/",
        "https://restorationplaza.org/skylight-open-2/",
        "https://restorationplaza.org/wibo-event/",
        "https://restorationplaza.org/nycs-better-bike-share-partnership/",
        "https://restorationplaza.org/womenysk-depelsha-mcgruder-named-cheif-operating-officer-of-new-york-public-radio/",
        "https://restorationplaza.org/event/screeninghirirng6-18/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-to-host-annual-robert-f-kennedy-memorial-party/",
        "https://restorationplaza.org/build-bike-share-for-equity-first/",
        "https://restorationplaza.org/member-profile-krystal-brown/",
        "https://www.brooklyngives.org/multigive?org_id=460234&campaign_id=30786&user_campaign_id=#new_tab",
        "https://restorationplaza.org/atiyas-story/",
        "https://www.facebook.com/events/524603028008392/#new_tab",
        "https://restorationplaza.org/event/workforce-development-orientation/",
        "https://restorationplaza.org/rfk_2018/#new_tab",
        "https://restorationplaza.org/b-j-handal-receives-microfinance-award/",
        "https://restorationplaza.org/dance-magazine-congratulations-to-dance-magazine-award-honoree-ronald-k-brown/",
        "https://restorationplaza.org/14364-2/",
        "https://restorationplaza.org/happy-new-year/",
        "https://restorationplaza.org/donate/",
        "https://restorationplaza.org/brooklyn-daily-eagle-brooklyns-first-black-administrative-judge-dies-at-94/",
        "https://restorationplaza.org/smart-cities-dive-as-bike-share-expands-neighborhood-perception-is-key/",
        "https://restorationplaza.org/tax-season-volunteer2020/",
        "https://restorationplaza.org/50in50-letters-to-our-daughters-global-call-for-women-writers-with-curatorial-statement/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-offers-free-tax-prep-service-for-all-new-yorkers/",
        "https://restorationplaza.org/skylight-open-3/",
        "https://restorationplaza.org/event/stress-management-support-group-sessions/2019-03-18/#new_tab",
        "https://restorationplaza.org/tax-prep-2019/#new_tab",
        "https://restorationplaza.org/tax-prep-faq-2020/",
        "https://restorationplaza.org/restoration-jobs-plus-sties-provide-free-tax-prep-to-nycha-residents/",
        "https://restorationplaza.org/black-history-month/",
        "https://restorationplaza.org/event/orientation-for-youth-adult-services/2019-04-10/#new_tab",
        "https://restorationplaza.org/event/screening-hriring-event-2019-09-26/",
        "https://restorationplaza.org/bed-stuys-trinidadian-bakery-wins-james-beard-award/",
        "https://restorationplaza.org/screening-hiring-event/",
        "https://restorationplaza.org/better-bike-share-volunteer/",
        "https://restorationplaza.org/all-for-one-survey-administrator/",
        "https://restorationplaza.org/volunteer-tax-preparer/",
        "https://restorationplaza.org/event/entrepreneurial-empowerment-social-media-presentation/#new_tab",
        "https://restorationplaza.org/workforce-development/#new_tab",
        "https://restorationplaza.org/bk-reader-billie-holiday-theatre-wins-100k-grant-to-fund-discounted-tickets/",
        "https://restorationplaza.org/its-tea-time-in-bed-stuy/",
        "https://restorationplaza.org/social-justice-in-central-brooklyn/",
        "https://restorationplaza.org/workforce-development/",
        "https://restorationplaza.org/cash-bail-strips-wealth-from-low-income-communities/",
        "https://restorationplaza.org/a-talk-with-dr-john-flateau/",
        "https://restorationplaza.org/a-salute-to-indira-pauletta-and-toni-powerhouses-in-brooklyns-arts-and-culture-scene-share-their-agency-with-50-women-writers/",
        "https://restorationplaza.org/economic-solutions-center-expands-2-financial-empowerment-centers-in-central-brooklyn/",
        "https://restorationplaza.org/jerrell-gray-super-saver-a-profile/",
        "https://restorationplaza.org/content-coordinator/",
        "https://restorationplaza.org/graphic-design-assistant/",
        "https://restorationplaza.org/event/access-to-capital-panel-discussion-and-meetings/#new_tab",
        "https://restorationplaza.org/glenn-edward-sharpe-in-memoriam/",
        "https://restorationplaza.org/bww-review-50-in-50-letters-to-our-daughters-at-kumble-theater/",
        "https://restorationplaza.org/stress-management-support-group-2/",
        "https://restorationplaza.org/event/nyc-comptroller-scott-stringer-at-bedford-stuyvesant-march-26/",
        "https://restorationplaza.org/event/prep-for-prep-open-house/",
        "https://restorationplaza.org/restoration-kicks-off-community-planning-process-to-re-envision-restoration-plaza/",
        "https://restorationplaza.org/event/entrepreneurial-empowerment-access-to-capital/#new_tab",
        "https://restorationplaza.org/restoration-president-colvin-grannum-speaks-on-the-redesign-of-the-cultural-hub-in-bed-stuy/",
        "https://restorationplaza.org/administrative-assistant/",
        "https://restorationplaza.org/volapp/",
        "https://restorationplaza.org/restoration-launches-corporate-wide-volunteer-program/",
        "https://restorationplaza.org/economic-solutions-center-pilot-brooklyn-savers-club-program-help-residents-save-over-34000/",
        "https://restorationplaza.org/inclusiv-announces-partnership-between-bedford-stuyvesant-restoration-and-concord-federal-credit-union/",
        "https://restorationplaza.org/food-bank-for-nyc-offers-free-tax-prep-to-low-income-brooklynites/",
        "https://restorationplaza.org/the-billie-holiday-theatre-presents-the-world-premiere-of-a-walk-into-slavery/",
        "https://restorationplaza.org/event/volunteer-meeting-2019-09-25/2019-09-25/#new_tab",
        "https://restorationplaza.org/event/yaa-open-house/2019-09-14/",
        "https://restorationplaza.org/volunteer-interview-johnathan-liang/",
        "https://restorationplaza.org/holiday/",
        "https://restorationplaza.org/celebrate-national-bike-month-with-kick-off-event-and-community-bike-rides/",
        "https://restorationplaza.org/youth-services-at-restoration/",
        "https://restorationplaza.org/financial-counseling/",
        "https://restorationplaza.org/community-bike-rides/",
        "https://restorationplaza.org/broadway-world-brooklyn-jazz-hall-of-fame-juneteenth-ceremony-celebrate-african-american-freedom-day/",
        "https://restorationplaza.org/doctors-advice-take-two-bike-rides-and-call-me-in-the-morning/",
        "https://restorationplaza.org/the-real-deal-nycha-taps-development-team-to-perform-350m-renovations-takeover-managing-properties/",
        "https://restorationplaza.org/why-black-credit-unions-matter-for-financial-empowerment/",
        "https://restorationplaza.org/broadway-world-hadestown-be-more-chill-and-more-set-for-broadway-in-the-boros/",
        "https://restorationplaza.org/nyc-partnership-connects-bike-share-and-public-benefits/",
        "https://restorationplaza.org/restoration-credit-club/",
        "https://restorationplaza.org/restoration-saver-club/",
        "https://restorationplaza.org/annual-financial-reports/",
        "https://restorationplaza.org/home-aquisition/",
        "https://restorationplaza.org/home-preservation/",
        "https://restorationplaza.org/home-events/",
        "https://restorationplaza.org/street-team-member/",
        "https://restorationplaza.org/central-brooklyn-jazz-consortiums-20th-anniversary/",
        "https://restorationplaza.org/crown-heights-restaurant-opens-second-location-thanks-to-community-financial-institution-collaboration-led-by-brooklyn-business-center/",
        "https://www.eventbrite.com/e/ypoc-young-professionals-of-color-brooklyn-tickets-64563608491#new_tab",
        "https://restorationplaza.org/grow-your-business-skills-with-google/",
        "https://restorationplaza.org/moshood-creations-sets-up-shop-in-restoration-plaza/",
        "https://restorationplaza.org/place-based-grants-will-investigate-how-to-diversify-nyc-tech/",
        "https://restorationplaza.org/interview-with-moshood-rj/",
        "https://restorationplaza.org/spudz-opens-first-specialty-hand-cut-french-fries-restaurant-in-brooklyn/",
        "https://restorationplaza.org/the-citi-bike-expansion-plan-is-great-heres-how-to-do-it-right/",
        "https://restorationplaza.org/mercy-college-prepares-to-welcome-the-college-of-new-rochelle-students-this-fall/",
        "https://restorationplaza.org/restorationart-commemorates-arrival-of-first-slave-ship-with-speak-freedom-artist-gathering/",
        "https://restorationplaza.org/mercy-college-to-continue-to-serve-students-at-restoration-plaza-location/",
        "https://restorationplaza.org/summers-at-the-woodhull-youthmarket/",
        "https://restorationplaza.org/all-4-one-ashlynn/",
        "https://restorationplaza.org/cfp_event_form/",
        "https://restorationplaza.org/10k2019vol/",
        "https://restorationplaza.org/cafe-mocha-radio-shows-9th-annual-salute-her-awards-to-honor-black-women-of-broadway-and-new-york-theatre/",
        "https://restorationplaza.org/38th-annual-10k-run-and-5k-walk-wellness-is-wealth/",
        "https://restorationplaza.org/the-off-broadway-billie-holiday-theatre-announces-major-highlight-of-the-billies-2019-2020-season-with-new-windows-festival/",
        "https://restorationplaza.org/event/national-census-day-instagram-dance-party-to-celebrate-census-completion/",
        "https://restorationplaza.org/jrt/",
        "https://restorationplaza.org/esc-orientation/",
        "https://restorationplaza.org/temp-post/",
        "https://restorationplaza.org/a-coop-grows-in-brooklyn/",
        "https://restorationplaza.org/rfk_2019/",
        "https://restorationplaza.org/new-year-for-reinvision/",
        "https://restorationplaza.org/its-hard-to-believe-it-but-its-true-they-give-money-to-those-who-save/",
        "https://restorationplaza.org/sock-away-400-for-rainy-day-under-new-brooklyn-program/",
        "https://restorationplaza.org/bp-adams-and-restoration-announce-new-free-matching-program-to-help-residents-build-a-financial-savings/",
        "https://restorationplaza.org/dcwp-announces-expansion-of-financial-empowerment-centers/",
        "https://restorationplaza.org/dcwp-continue-expansion-of-nyc-free-tax-prep/",
        "https://restorationplaza.org/wp-content/uploads/Restoration-COVID-19-Final.pdf",
        "https://secureservercdn.net/198.71.233.35/qkc.722.myftpupload.com/event/managing-a-full-time-gig-and-side-hustle-how-to-recession-proof-your-career/",
        "https://restorationplaza.org/checking-in-with-you/",
        "https://restorationplaza.org/the-most-important-thing-you-can-do-at-home/",
        "https://restorationplaza.org/department-of-consumer-and-worker-protection-launches-remote-nyc-free-tax-prep-services/",
        "https://restorationplaza.org/register-for-our-free-self-prep-taxes/",
        "https://restorationplaza.org/economic_solutions/",
        "https://restorationplaza.org/financial-guidance-for-difficult-times-empowerment-webinars-from-bed-stuy-restoration-corp/",
        "https://restorationplaza.org/disrupting-the-racial-wealth-gap-2/",
        "https://restorationplaza.org/restoration-president-colvin-grannums-opinion-editorial-in-crains-on-how-black-and-latinx-populations-need-help-recovering-economically-from-the-pandemic/",
        "https://restorationplaza.org/smart-financial-management-during-covid-19/",
        "https://restorationplaza.org/addressing-debt-in-black-communities/",
        "https://restorationplaza.org/lets-make-this-time-truly-different/",
        "https://restorationplaza.org/financial-guidance-for-difficult-times-empowerment-webinars-from-bed-stuy-restoration-corp-2/",
        "https://www.businessinsider.com/new-business-owners-ppp-loans-how-to-raise-capital-cash-2020-6#new_tab",
        "https://restorationplaza.org/restoration-covid-19-emergency-fund/",
        "https://restorationplaza.org/diaper-giveaway/",
        "https://restorationplaza.org/watch-1-min-happy-reaction-during-diaper-giveaway/",
        "https://restorationplaza.org/watch/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-launches-emergency-covid-19-relief-fund/",
        "https://restorationplaza.org/new-york-city-launches-first-ever-tenant-resource-portal-to-help-renters-avoid-eviction/",
        "https://restorationplaza.org/restorations-covid-response/",
        "https://restorationplaza.org/at-restoration-your-success-is-our-success/",
        "https://restorationplaza.org/how-and-why-to-start-investing/",
        "https://restorationplaza.org/restoration-offers-fresh-food-and-fresh-recipes-and-tips/",
        "https://restorationplaza.org/make-sure-you-receive-your-stimulus-check/",
        "https://restorationplaza.org/solar-one-training-10-26/",
        "https://restorationplaza.org/institute-of-nonprofit-practice-classes/",
        "https://restorationplaza.org/expanding-the-nycha-to-tech-pipeline/",
        "https://restorationplaza.org/black-bean-kale-and-butternut-chili/",
        "https://restorationplaza.org/covid-19-emergency-fund/",
        "https://restorationplaza.org/the-bikeshare-systems-offering-free-rides-on-election-day/",
        "https://restorationplaza.org/brooklyn-navy-yard-equity-incubator/",
        "https://restorationplaza.org/grant-recipient-kharisa/",
        "https://restorationplaza.org/breakthrough-technology-fellowship/",
        "https://restorationplaza.org/vote-2020/",
        "https://restorationplaza.org/a-video-message-from-award-winning-global-architect-sir-david-adjaye/",
        "https://restorationplaza.org/bsr-virtual-gala/",
        "https://restorationplaza.org/neighborhoods-now/",
        "https://restorationplaza.org/success-stories/",
        "https://restorationplaza.org/resume-support/",
        "https://restorationplaza.org/restoration-gala-2020-thank-you/",
        "https://restorationplaza.org/passing-of-honorable-david-n-dinkins/",
        "https://restorationplaza.org/prepping-for-thanksgiving-nycha-residents/",
        "https://restorationplaza.org/celebrating-thanksgiving-with-restoration/",
        "https://restorationplaza.org/young-adults-in-tech-thrive-during-covid-19/",
        "https://restorationplaza.org/winter-wonderland-opens-in-bed-stuys-marcy-plaza-for-holidays-2020/",
        "https://restorationplaza.org/covid-financial-literacy-2020-nyc/",
        "http://www.icontact-archive.com/archive?c=1039795&f=11986&s=12497&m=606296&t=7ac7a1e8b6d946baae310a39687337f186f851b5e16bfa6fcd1ac70c2f74639a",
        "https://restorationplaza.org/brooklyn-business-center-shop-local-support-our-merchants/",
        "https://restorationplaza.org/thank-you-restoration-gala-attendees/",
        "https://restorationplaza.org/bed-stuy-restoration-guides-working-class-families-toward-success/",
        "https://restorationplaza.org/foundation-for-financial-planning-announces-communities-of-color-initiative-sponsored-by-bny-mellons-pershing/",
        "https://restorationplaza.org/restoration-tapped-for-microsofts-community-skills-program-grant/",
        "https://restorationplaza.org/robert-bob-annibale-board-member/",
        "https://restorationplaza.org/financial-emp-center/",
        "https://restorationplaza.org/mental-health-esc/",
        "https://restorationplaza.org/social-supports/",
        "https://restorationplaza.org/now-is-the-time-to-disrupt-and-close-the-racial-wealth-gap/",
        "https://restorationplaza.org/understanding-and-addressing-the-racial-gap/",
        "https://restorationplaza.org/cameron-arrington-board-member/",
        "https://restorationplaza.org/erika-irish-brown-board-member/",
        "https://restorationplaza.org/benjamin-a-glascoe-board-member/",
        "https://restorationplaza.org/antonia-yuille-williams-board-member/",
        "https://restorationplaza.org/joseph-g-sponholz-board-member/",
        "https://restorationplaza.org/peter-m-williams-board-member/",
        "https://restorationplaza.org/wayne-c-winborne-board-member/",
        "https://restorationplaza.org/food-access-and-equity/",
        "https://restorationplaza.org/mental-health-chn/",
        "https://restorationplaza.org/physical-activity/",
        "https://restorationplaza.org/keeping-dreams-alive-breaking-barriers-through-tech-careers-by-yemisi-onayemi/",
        "https://restorationplaza.org/nyc-black-educators-coalition-mayoral-forum/",
        "https://restorationplaza.org/restoration-covid-19-emergency-fund/",
        "https://restorationplaza.org/at-restoration-young-leaders-are-prepared-to-lead-empowered-for-success/",
        "https://restorationplaza.org/congratulations-to-our-board-member-depelsha-mcgruder-for-joining-the-board-of-oaktree-capital-group-llc/",
        "https://restorationplaza.org/restoration-was-just-recognized-by-economic-mobility-pathways-empath-for-all-of-our-efforts/",
        "https://restorationplaza.org/as-nyc-charts-recovery-from-covid-19-brooklyn-borough-president-adams-puts-forward-series-of-recommendations-to-boost-urban-agriculture-in-the-city/",
        "https://restorationplaza.org/food-forward-nyc-city-releases-10-year-food-policy-plan/",
        "https://restorationplaza.org/give-us-your-feedback-on-our-restoration-website/",
        "https://restorationplaza.org/join-the-emergency-savings-challenge-by-wednesday-march-10-2021/",
        "https://restorationplaza.org/coalition-calls-on-nyc-leaders-to-kick-cars-off-25-percent-of-city-streets/",
        "https://slgr.typeform.com/to/LIFMDcms",
        "https://restorationplaza.org/in-pursuit-of-racial-economic-equity-sunday-supper-2021/",
        "https://restorationplaza.org/sunday-supper-march-2021-book-lists/",
        "https://restorationplaza.org/new-covid-19-vaccination-site-opens-in-bed-stuy/",
        "https://slgr.typeform.com/to/hnNiCn0N",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-opens-covid-19-vaccination-site/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-tax-site-irs-extends-tax-deadline-to-may-17-2021/",
        "https://restorationplaza.org/environmental-leadership-program-acceptance-congratulations-to-restoration-staffer/",
        "https://restorationplaza.org/damage-from-virus-utility-bills-overwhelm-some-households/",
        "https://restorationplaza.org/new-lafayette-gardens-community-center-advisory-board-member-congratulations-to-restoration-staffer/",
        "https://restorationplaza.org/darrells-story-restoration-client-story-foundation-for-financial-planning/",
        "https://restorationplaza.org/at-restoration-resilience-is/",
        "https://restorationplaza.org/restoration-partners-with-st-nicks-alliance-and-wins-buildings-of-excellence-competition-awards-from-nyserda/",
        "https://restorationplaza.org/2021-breakthrough-technology-fellowship-demo-day/",
        "https://restorationplaza.org/resilience-is-remaining-steadfast-even-in-the-face-of-a-pandemic/",
        "https://restorationplaza.org/hussains-story-restoration-volunteer-financial-planner-foundation-for-financial-planning/",
        "https://restorationplaza.org/congratulations-to-all-the-2021-breakthrough-technology-fellows/",
        "https://restorationplaza.org/resilience-is-brittneys-next-step/",
        "https://restorationplaza.org/resilience-is-nurturing-entrepreneurship-to-keep-our-neighborhoods-safe/",
        "https://meet.google.com/hfs-jhrz-pmq",
        "https://restorationplaza.org/broadband-discount-for-eligible-households/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-releases-report-on-advancing-equity-and-opportunity-within-micromobility/",
        "https://restorationplaza.org/introducing-restorations-new-mental-health-provider/",
        "https://restorationplaza.org/2021-restore-brooklyn-annual-benefit-gala/",
        "https://restorationplaza.org/restoration-2021-gala-save-the-date-monday-november-15-2021/",
        "https://restorationplaza.org/bedford-stuyvesant-restoration-corporation-study-calls-for-new-york-state-to-develop-central-brooklyn-food-hub-that-will-support-equitable-locally-controlled-food-system/",
        "https://restorationplaza.org/central-bk-food-hub-would-create-community-wealth-support-vulnerable-residents-study-finds/",
        "https://restorationplaza.org/change-capital-fund-report-essential-yet-invisible-community-organizations-in-the-time-of-covid/",
        "https://restorationplaza.org/making-affordable-housing-greener-using-financing-from-new-york-city-energy-efficiency-corporation-nyceec/",
        "https://restorationplaza.org/bed-stuy-neighborhood-career-fair-july-15-2021/",
        "https://zoom.us/meeting/register/tJ0qfuGspzItGdNR4VywvAOYq6t3Zu-PiHj9",
        "https://www.secureservercdn.net/198.71.233.35/qkc.722.myftpupload.com/21134-2/",
        "https://www.restorationplaza.org/let-us-help-you-find-your-next-job/",
        "https://www.restorationplaza.org/managing-stress-and-anxiety-during-the-covid-19-crisis/",
        "https://www.restorationplaza.org/featured-amsterdam-news/",
        "https://www.restorationplaza.org/book-signing-jacqueline-woodson/",
        "https://www.restorationplaza.org/press-release-new-economic-solutions-center/",
        "https://www.restorationplaza.org/hattie-carthan-community-garden/",
        "https://www.restorationplaza.org/here-and-now/",
        "https://www.restorationplaza.org/nyc-dot-pressrelease/",
        "https://www.restorationplaza.org/pnyesc/",
        "https://www.restorationplaza.org/bdeesc/",
        "https://www.restorationplaza.org/bkresc/",
        "https://www.restorationplaza.org/ny1esc/",
        "https://www.restorationplaza.org/tccbb/",
        "https://www.restorationplaza.org/jdnychaja/",
        "https://www.restorationplaza.org/jdmcnycha/",
        "https://www.restorationplaza.org/tdbankeducation/",
        "https://www.restorationplaza.org/statement-recent-elections/",
        "https://www.restorationplaza.org/citi-bike-expansion/",
        "https://www.restorationplaza.org/rfps-bike-share2016/",
        "https://www.restorationplaza.org/nytimes-citi-bike-restoration/",
        "https://www.restorationplaza.org/restoration-pfc-sia/",
        "https://www.restorationplaza.org/restorations-farm-pfc-sia/",
        "https://www.restorationplaza.org/mayor-de-blasio-citi-bike-2016/",
        "https://www.restorationplaza.org/cm-laurie-cumbo-ce/",
        "https://www.restorationplaza.org/restoration-bill-de-blasio-bed-stuy/",
        "https://www.restorationplaza.org/citi-bike-omar-arias/",
        "https://www.restorationplaza.org/nyc-bike-op-ed-new-york/",
        "https://www.restorationplaza.org/brooklyn-is-africa-amsterdam-news/",
        "https://www.restorationplaza.org/restoration-staff-reading-partners/",
        "https://www.restorationplaza.org/tracey-capers-brooklyn-mag/",
        "https://www.restorationplaza.org/billie-holday-theatre-new-york-times/",
        "https://www.restorationplaza.org/support-elsie-richardson-scholarship/",
        "https://www.restorationplaza.org/citi-bike-doubles-since-2015/",
        "https://www.restorationplaza.org/press-release-bike-share/",
        "https://www.restorationplaza.org/bike-share-bed-stuy/",
        "https://www.restorationplaza.org/omar-arias-nycha-journal/",
        "https://www.restorationplaza.org/may-bike-month/",
        "https://www.restorationplaza.org/bike-share-citi-bike-rfp/",
        "https://www.restorationplaza.org/room-rental-changes/",
        "https://www.restorationplaza.org/community-ambassador-program/",
        "https://www.restorationplaza.org/social-media-revamp/",
        "https://www.restorationplaza.org/syep-at-restoration-2/",
        "https://www.restorationplaza.org/comptroller-dinapoli-report-gentrification-in-bedford-stuyvesant/",
        "https://www.restorationplaza.org/yuna-beauty-supply/",
        "https://www.restorationplaza.org/youth-arts-academy/",
        "https://www.restorationplaza.org/weatherization-assistance-program/",
        "https://www.restorationplaza.org/universal-processing-solutions-inc/",
        "https://www.restorationplaza.org/u-s-post-office/",
        "https://www.restorationplaza.org/tonys-country-life/",
        "https://www.restorationplaza.org/thriftway-pharmacy/",
        "https://www.restorationplaza.org/the-new-cutting-edge/",
        "https://www.restorationplaza.org/the-learning-center-of-bedford-stuyvesant/",
        "https://www.restorationplaza.org/the-gospel-den/",
        "https://www.restorationplaza.org/the-butcher-shop/",
        "https://www.restorationplaza.org/tastee-pattee-ltd/",
        "https://www.restorationplaza.org/tajasia-soul-food-restaurant/",
        "https://www.restorationplaza.org/tai-king-restaurant-inc/",
        "https://www.restorationplaza.org/superb-driving-school/",
        "https://www.restorationplaza.org/super-foodtown-supermarket/",
        "https://www.restorationplaza.org/assemblywoman-tremaine-wright/",
        "https://www.restorationplaza.org/star-security-training-corp/",
        "https://www.restorationplaza.org/spencer-place-bed-and-breakfast/",
        "https://www.restorationplaza.org/solano-optical/",
        "https://www.restorationplaza.org/s-r-y-design-associates/",
        "https://www.restorationplaza.org/sco/",
        "https://www.restorationplaza.org/rubins-beauty-salon/",
        "https://www.restorationplaza.org/rose-beauty-supply/",
        "https://www.restorationplaza.org/room-hall-rental-social-civic-functions/",
        "https://www.restorationplaza.org/ronak-newsstand-and-candy-shop/",
        "https://www.restorationplaza.org/prosperity-hardware/",
        "https://www.restorationplaza.org/popeyes-fried-chicken/",
        "https://www.restorationplaza.org/planned-parenthood-project-street-beat/",
        "https://www.restorationplaza.org/phoenix-house-recovery-center/",
        "https://www.restorationplaza.org/sp-plus-parking-garage/",
        "https://www.restorationplaza.org/opportunities-for-a-better-tomorrow-obt/",
        "https://www.restorationplaza.org/one-world-health-fitness-center/",
        "https://www.restorationplaza.org/nostrand-wines-and-liquors/",
        "https://www.restorationplaza.org/noel-pointer-foundation/",
        "https://www.restorationplaza.org/nicholas-brooklyn/",
        "https://www.restorationplaza.org/michelles-beauty-salon/",
        "https://www.restorationplaza.org/mcdonalds/",
        "https://www.restorationplaza.org/manu-fashions/",
        "https://www.restorationplaza.org/malik-sportwear-inc/",
        "https://www.restorationplaza.org/m-a-d-discount-hardware/",
        "https://www.restorationplaza.org/llj-meat-market/",
        "https://www.restorationplaza.org/little-sun-people/",
        "https://www.restorationplaza.org/lana-discount-beauty-supply/",
        "https://www.restorationplaza.org/juke-joint-juice-jaffe/",
        "https://www.restorationplaza.org/john-b-jemmot-all-year-tax-service/",
        "https://www.restorationplaza.org/jeffs-express-inc/",
        "https://www.restorationplaza.org/international-african-arts-festival/",
        "https://www.restorationplaza.org/george-candle/",
        "https://www.restorationplaza.org/fulton-outfitters/",
        "https://www.restorationplaza.org/fulton-gourmet-deli-and-grocery/",
        "https://www.restorationplaza.org/fox-media-corporation/",
        "https://www.restorationplaza.org/fostering-change-for-children/",
        "https://www.restorationplaza.org/five-star-pharmacy/",
        "https://www.restorationplaza.org/first-vision-optical/",
        "https://www.restorationplaza.org/ez-bz-fashions/",
        "https://www.restorationplaza.org/edwingouldservices/",
        "https://www.restorationplaza.org/curves-fitness/",
        "https://www.restorationplaza.org/cross-boro-realty/",
        "https://www.restorationplaza.org/county-pharmacy/",
        "https://www.restorationplaza.org/councilmember-robert-cornegy/",
        "https://www.restorationplaza.org/construction-online/",
        "https://www.restorationplaza.org/community-planning-board-3/",
        "https://www.restorationplaza.org/college-of-new-rochelle/",
        "https://www.restorationplaza.org/citibank/",
        "https://www.restorationplaza.org/chase-bank/",
        "https://www.restorationplaza.org/carver-federal-savings-bank/",
        "https://www.restorationplaza.org/bvsj-watchful-eye-program/",
        "https://www.restorationplaza.org/burger-king/",
        "https://www.restorationplaza.org/brownstoners-of-bedford-stuyvesant/",
        "https://www.restorationplaza.org/brooklyn-community-service/",
        "https://www.restorationplaza.org/brooklyn-community-pride-center/",
        "https://www.restorationplaza.org/bravo-supermarket/",
        "https://www.restorationplaza.org/birdels-tapes-and-records/",
        "https://www.restorationplaza.org/billie-holiday-theatre/",
        "https://www.restorationplaza.org/big-brothers-discount-hardware-appliance/",
        "https://www.restorationplaza.org/bedford-stuyvesant-family-health-center/",
        "https://www.restorationplaza.org/bed-stuy-pharmacy/",
        "https://www.restorationplaza.org/bed-stuy-family-health-center-wic-program/",
        "https://www.restorationplaza.org/bed-stuy-community-legal-services/",
        "https://www.restorationplaza.org/bed-stuy-gateway-bid/",
        "https://www.restorationplaza.org/asterix-party-place/",
        "https://www.restorationplaza.org/aphrika-hair/",
        "https://www.restorationplaza.org/alis-trinidad-roti/",
        "https://www.restorationplaza.org/apple-bees-bed-stuy/",
        "https://www.restorationplaza.org/2020-art/",
        "https://www.restorationplaza.org/bk-reader-restoration-hosts-open-house-to-highlight-importance-of-fulton-streets-preservation/",
        "https://www.restorationplaza.org/mentalhealthawarenessday/",
        "https://www.restorationplaza.org/citi-bike-reaches-crown-heights/",
        "https://www.restorationplaza.org/talib-kweli-and-les-nubians-to-headline-restorationrocks-during-bed-stuy-alive/",
        "https://www.restorationplaza.org/preservetheplaza/",
        "https://www.restorationplaza.org/brooklyncommunitypridecenter/",
        "https://www.restorationplaza.org/36thannual10kcommunityrun/",
        "https://www.restorationplaza.org/genevieve-and-alicia/",
        "https://www.restorationplaza.org/mayoral-debates-2017/",
        "https://www.restorationplaza.org/restoration-connection-october-2017/",
        "https://www.restorationplaza.org/cumbe-center-comes-home-to-bed-stuy/",
        "https://www.restorationplaza.org/broadway-world-billie-holiday-theatres-autumn-sweeps-2017-audelco-awards/",
        "https://www.restorationplaza.org/restorations-50th-anniversary-gala-is-a-smashing-success/",
        "https://www.restorationplaza.org/jobs-plus-cuts-the-ribbon-on-a-new-site-in-east-new-york/",
        "https://www.restorationplaza.org/the-old-settler-spins-a-web-of-charm-and-laughter-at-billie-holiday-theatre/",
        "https://www.restorationplaza.org/robert-f-kennedy-memorial-holiday-party-2017/",
        "https://www.restorationplaza.org/usda-aims-to-improve-food-and-nutrition-education-for-low-income-communities/",
        "https://www.restorationplaza.org/citibike-tumblr-a-brooklynites-journey-to-biking/",
        "https://www.restorationplaza.org/nycbetterbikeshare/",
        "https://www.restorationplaza.org/broadway-world-the-billie-holiday-theatre-and-restorationart-present-the-new-york-premiere-of-a-small-oak-tree-runs-red/",
        "https://www.restorationplaza.org/rfkholidaypartywintercheer/",
        "https://www.restorationplaza.org/event/idnyc-enrollment-esc-central/2018-09-05/",
        "https://www.restorationplaza.org/bk-reader-50-years-of-restoration-building-assets-and-community-wealth/",
        "https://www.restorationplaza.org/next-city-nyc-aims-to-reduce-the-disease-of-financial-distress/",
        "https://www.restorationplaza.org/dance-garment-of-destiny/",
        "https://www.restorationplaza.org/brooklyn-paper-proud-moment-local-pride-center-gets-120000-to-start-internship-program-for-lgbtq-youths/",
        "https://www.restorationplaza.org/restoration-selected-as-semi-finalist-in-age-smart-employer-awards/",
        "https://www.restorationplaza.org/elsie-e-richardson-scholarship-awards-3-students-for-2017-2018-academic-year/",
        "https://www.restorationplaza.org/adam-clayton-powell-jr-comes-to-life-in-one-man-show/",
        "https://www.restorationplaza.org/wadiyah-latif-retires-from-restoration-after-47-years-of-service/",
        "https://www.restorationplaza.org/enterprise-awards-550000-in-hud-section-4-grants-in-new-york/",
        "https://www.restorationplaza.org/department-of-consumer-affairs-releases-report-on-the-ways-neighborhoods-influence-residents-financial-health/",
        "https://www.restorationplaza.org/billie-holiday-theatre-presents-a-small-oak-tree-runs-red/",
        "https://www.restorationplaza.org/sonia-sanchez-recalls-herstory-and-poetry-in-stella-adler-actors-conversation/",
        "https://www.restorationplaza.org/restoration-hosts-mixer-to-promote-tourism-and-business-in-bed-stuy/",
        "https://www.restorationplaza.org/kings-county-politics-cornegy-gives-bed-stuy-seniors-new-tools-to-fight-tickets/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-to-offer-free-tax-prep-service-for-low-income-new-yorkers/",
        "https://www.restorationplaza.org/kings-county-politics-jeffries-wright-confront-bed-stuy-gentrification/",
        "https://www.restorationplaza.org/an-interview-with-dr-indira-etwaroo/",
        "https://www.restorationplaza.org/restoration-dance-youth-ensemble-takes-los-angeles/",
        "https://www.restorationplaza.org/bk-reader-brooklyn-historical-society-honored-with-ny-museum-associations-award-of-merit/",
        "https://www.restorationplaza.org/restorationisawardedbrooklyncenturayawards/",
        "https://www.restorationplaza.org/bk-reader-bed-stuy-crown-heights-listed-among-the-nations-most-gentrified-areas/",
        "https://www.restorationplaza.org/hugh-price-conversation-and-book-signing/",
        "https://www.restorationplaza.org/broadway-world-50in50-womans-voices-initiative-curated-by-playwright-dominique-morriseau-comes-to-the-billie-holiday-theatre/",
        "https://www.restorationplaza.org/ny-daily-news-the-black-panther-moment-to-seize-to-promote-arts-diversity/",
        "https://www.restorationplaza.org/brooklyn-business-center-helps-pave-the-way-to-success-for-local-business/",
        "https://www.restorationplaza.org/basing-bike-share-in-community-bbspchat/",
        "https://www.restorationplaza.org/bk-reader-what-happens-when-history-becomes-his-story-and-not-ours/",
        "https://www.restorationplaza.org/bk-reader-the-high-price-of-knowledge/",
        "https://www.restorationplaza.org/tax-season-update/",
        "https://www.restorationplaza.org/shopsmall-fulton-street-prince-deli-smoke-shop/",
        "https://www.restorationplaza.org/stress-management-support-group/",
        "https://www.eventbrite.com/e/promoting-bike-share-through-ride-leadership-and-community-events-tickets-42434322200#new_tab",
        "https://www.restorationplaza.org/bk-reader-photos-bed-stuys-madam-c-j-walker-awards-celebrates-beauty-entrepreneurs-of-color/",
        "https://www.restorationplaza.org/local-resident-kweli-campbell-leads-first-community-ride-of-the-season/",
        "https://www.tockify.com/cac1368/detail/264/1527721200000#new_tab",
        "https://www.restorationplaza.org/forbes-the-story-behind-moms-of-black-boys-united/",
        "https://www.restorationplaza.org/brooklyn-business-center-hosts-seoul-credit-guarantee-foundation/",
        "https://www.restorationplaza.org/volunteer-spotlight-anthony-f/",
        "https://www.restorationplaza.org/shopsmall-fulton-street-noel-pointer-foundation/",
        "https://www.restorationplaza.org/amny-brooklyn-and-gentrification-will-bed-stuy-feel-the-starbucks-effect-from-new-coffee-shop/",
        "https://www.restorationplaza.org/bk-reader-the-billie-holiday-theatre-closes-its-2017-18-season-with-pulitzer-prize-finalist-yellowman/",
        "https://www.restorationplaza.org/ny-times-review-a-living-archive-of-contemporary-black-dance/",
        "https://www.restorationplaza.org/free-wealth-building-wednesday-series-aims-to-build-financial-confidence/",
        "https://www.restorationplaza.org/broadway-world-brooklyn-jazz-hall-of-fame-awards-and-induction-ceremony-set-for-wednesday/",
        "https://www.restorationplaza.org/bed-stuy-patch-3300-affordable-apartments-coming-to-central-brooklyn-governor/",
        "https://www.restorationplaza.org/amny-vital-brooklyn-funding-initiative-outlined-by-cuomo-in-bed-stuy/",
        "https://www.restorationplaza.org/restoration-rides-into-national-bike-month/",
        "https://www.restorationplaza.org/11th-annual-urban-arts-festival-taking-place-at-bed-stuy-restoration-may-22nd-in-partnership-with-honeybaked-ham-and-restoration-art/",
        "https://www.restorationplaza.org/almost-2000-nycha-residents-and-lower-income-new-yorkers-received-2-8-million-in-refunds-through-nychas-free-tax-prep-program/",
        "https://www.restorationplaza.org/restoration-joins-campaign-to-increase-workforce-development-funding/",
        "https://www.restorationplaza.org/brooklyn-daily-eagle-spike-lees-party-honoring-prince-set-june-9th/",
        "https://www.restorationplaza.org/restoration-receives-best-of-new-york-award/",
        "https://www.restorationplaza.org/adapt-community-network-flushing-avenue-day-program-hosts-open-house/",
        "https://www.restorationplaza.org/ny-times-betty-davis-was-a-raw-funk-pioneer-her-decades-of-silence-are-over/",
        "https://www.eventbrite.com/e/the-racial-wealth-gap-series-shemeka-brathwaite-on-your-distinctive-factor-tickets-46191085781#new_tab",
        "https://www.restorationplaza.org/restoration-and-citi-bike-to-host-june-23rd-birthday-bike-bash/",
        "https://www.restorationplaza.org/nyc-office-of-the-mayor-mayor-de-blasio-announces-fifth-anniversary-of-citibike-in-new-york-city/",
        "https://www.restorationplaza.org/ny-daily-news-thank-you-bobby-kennedy-for-caring-when-others-didnt/",
        "https://www.restorationplaza.org/economic-solutions-center-highlights-community-members-at-success-celebrations/",
        "https://www.restorationplaza.org/report-a-shared-future-inclusion-through-homeownership/",
        "https://www.restorationplaza.org/birthday-bike-bash/",
        "https://www.restorationplaza.org/bk-reader-omari-hardwick-explores-relationships-black-fatherhood-and-gun-violence-in-one-man-show-at-billie/",
        "https://www.restorationplaza.org/citi-bike-profile-becoming-a-bike-share-believer/",
        "https://www.restorationplaza.org/event/wealth-building-wednesdays-10-top-businesses-to-start-now/#new_tab",
        "https://www.restorationplaza.org/brooklyn-daily-eagle-spike-in-rat-complaints-prompts-bp-adams-to-hold-rat-summit/",
        "https://www.restorationplaza.org/woodhull-farmers-market-returns-to-north-bed-stuy/",
        "https://www.restorationplaza.org/restoration-becomes-polling-site-and-training-location/",
        "https://www.restorationplaza.org/nyc-office-of-the-mayor-mayor-announces-low-cost-citi-bike-memberships-will-be-available-to-all-snap-recipients/",
        "https://www.restorationplaza.org/new-york-times-citi-bike-expands-discount-memberships-to-reach-more-low-income-new-yorkers/",
        "https://www.restorationplaza.org/citi-bike-multi-modal/",
        "https://www.slate.com/business/2018/05/citi-bike-at-five-years-is-a-huge-success-for-new-yorkers.html#new_tab",
        "https://www.restorationplaza.org/job-fair-2018-employee-form/",
        "https://www.restorationplaza.org/job-fair-2018-employer-form/",
        "https://www.restorationplaza.org/the-more-things-change-the-more-things-stay-the-same-art-exhibition-debuts-at-restoration/",
        "https://www.bedstuyrestoration1.tumblr.com/#new_tab",
        "https://www.medium.com/@gioncarlovalentine/teresa-shares-her-story-of-leaving-america-and-returning-to-rebuild-her-life-and-her-community-b516bea2fee#new_tab",
        "https://www.bedstuyrestoration1.tumblr.com/private/171909789579/tumblr_p5njpf8Eoc1x8sj5t#new_tab",
        "https://www.restorationplaza.org/brownstoner-producer-charles-hobson-remembers-inside-bedford-stuyvesant-on-its-50th-anniversary/",
        "https://www.restorationplaza.org/report-bedford-stuyvesant-restoration-corporations-farm-to-early-care-program-a-foundation-for-healthier-stronger-central-brooklyn-families-and-communities/",
        "https://www.restorationplaza.org/brownstoner-producer-charles-hobson-remembers-inside-bedford-stuyvesant-on-its-50th-anniversary-2/",
        "https://www.restorationplaza.org/where-are-they-now-elsie-e-richardson-scholarship-winners-2017/",
        "https://www.restorationplaza.org/citi-bike-profile-biking-for-the-culture/",
        "https://www.restorationplaza.org/fresh-food-fresh-faces-north-bed-stuy-farmers-market/",
        "https://www.restorationplaza.org/new-york-state-governor-cuomo-announces-next-step-in-1-4-billion-vital-brooklyn-initiative/",
        "https://www.restorationplaza.org/nyc-cool-roofs-wants-to-keep-bed-stuy-cool/",
        "https://www.restorationplaza.org/working-together-to-improve-community-level-health-the-evolution-of-the-new-york-city-food-fitness-partnership/",
        "https://www.tockify.com/cac1368/detail/299/1536359400000#new_tab",
        "https://www.restorationplaza.org/event/paraderide-2018-09-08/#new_tab",
        "http://cwww.enterforblackliterature.org/rediscovering-zora-neale-hurston/#new_tab",
        "https://www.restorationplaza.org/broadway-world-def-poetry-jam-reunion-coming-to-the-billie-holiday-theatre/",
        "https://www.tockify.com/cac1368/detail/297/1537038000000#new_tab",
        "https://www.wibo.works/wibo-fall-registration/#new_tab",
        "https://www.restorationplaza.org/stress-management-workshop/",
        "https://www.restorationplaza.org/tony-award-winner-kenny-leon-to-direct-colman-domingos-dot-at-the-billie-holiday-theatre/",
        "https://www.restorationplaza.org/skylight-open/",
        "https://www.restorationplaza.org/wibo-brings-16-week-workshop-to-restoration/",
        "https://www.tockify.com/cac1368/detail/304/1537574400000#new_tab",
        "https://www.restorationplaza.org/event/cbr-fraternities/#new_tab",
        "https://www.restorationplaza.org/restoration-partners-with-a-list-preparing-students-for-college-success/",
        "https://www.restorationplaza.org/associated-press-report-one-third-of-households-struggle-to-pay-energy-bills/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-to-host-37th-annual-10k-run-and-fun-walk/",
        "https://www.restorationplaza.org/john-hope-bryant-wbw/",
        "https://www.restorationplaza.org/glimpses-from-the-inaugural-bedford-stuyvesant-crown-heights-film-festival/",
        "http://wwww.ww.bed-stuyalive.org/home-.html#new_tab",
        "https://www.restorationplaza.org/bk-reader-legalizing-marijuana-what-it-could-mean-for-new-yorkers/",
        "https://www.nyc.electiondayworker.com/r/rA_4yzXNYkmL7ahEXYi0dw#new_tab",
        "https://www.restorationplaza.org/event/sat-prep-class/#new_tab",
        "https://www.restorationplaza.org/bedford-stuyvesant-corporation-celebrates-annual-gala/",
        "https://www.restorationplaza.org/transportation-alternatives-tracey-capers-is-a-convert/",
        "https://www.restorationplaza.org/event/money-talks-are-you-listening/#new_tab",
        "https://www.restorationplaza.org/event/job-fair-workforce-hiring-event-2/2019-05-15/#new_tab",
        "https://www.restorationplaza.org/finalists-spark-prize/",
        "https://www.restorationplaza.org/a-guide-to-growing-good-food-jobs-in-new-york-city/",
        "https://www.restorationplaza.org/skylight-open-2/",
        "https://www.restorationplaza.org/wibo-event/",
        "https://www.restorationplaza.org/nycs-better-bike-share-partnership/",
        "https://www.restorationplaza.org/womenysk-depelsha-mcgruder-named-cheif-operating-officer-of-new-york-public-radio/",
        "https://www.restorationplaza.org/event/screeninghirirng6-18/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-to-host-annual-robert-f-kennedy-memorial-party/",
        "https://www.restorationplaza.org/build-bike-share-for-equity-first/",
        "https://www.restorationplaza.org/member-profile-krystal-brown/",
        "https://www.brooklyngives.org/multigive?org_id=460234&campaign_id=30786&user_campaign_id=#new_tab",
        "https://www.restorationplaza.org/atiyas-story/",
        "https://www.facebook.com/events/524603028008392/#new_tab",
        "https://www.restorationplaza.org/event/workforce-development-orientation/",
        "https://www.restorationplaza.org/rfk_2018/#new_tab",
        "https://www.restorationplaza.org/b-j-handal-receives-microfinance-award/",
        "https://www.restorationplaza.org/dance-magazine-congratulations-to-dance-magazine-award-honoree-ronald-k-brown/",
        "https://www.restorationplaza.org/14364-2/",
        "https://www.restorationplaza.org/happy-new-year/",
        "https://www.restorationplaza.org/donate/",
        "https://www.restorationplaza.org/brooklyn-daily-eagle-brooklyns-first-black-administrative-judge-dies-at-94/",
        "https://www.restorationplaza.org/smart-cities-dive-as-bike-share-expands-neighborhood-perception-is-key/",
        "https://www.restorationplaza.org/tax-season-volunteer2020/",
        "https://www.restorationplaza.org/50in50-letters-to-our-daughters-global-call-for-women-writers-with-curatorial-statement/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-offers-free-tax-prep-service-for-all-new-yorkers/",
        "https://www.restorationplaza.org/skylight-open-3/",
        "https://www.restorationplaza.org/event/stress-management-support-group-sessions/2019-03-18/#new_tab",
        "https://www.restorationplaza.org/tax-prep-2019/#new_tab",
        "https://www.restorationplaza.org/tax-prep-faq-2020/",
        "https://www.restorationplaza.org/restoration-jobs-plus-sties-provide-free-tax-prep-to-nycha-residents/",
        "https://www.restorationplaza.org/black-history-month/",
        "https://www.restorationplaza.org/event/orientation-for-youth-adult-services/2019-04-10/#new_tab",
        "https://www.restorationplaza.org/event/screening-hriring-event-2019-09-26/",
        "https://www.restorationplaza.org/bed-stuys-trinidadian-bakery-wins-james-beard-award/",
        "https://www.restorationplaza.org/screening-hiring-event/",
        "https://www.restorationplaza.org/better-bike-share-volunteer/",
        "https://www.restorationplaza.org/all-for-one-survey-administrator/",
        "https://www.restorationplaza.org/volunteer-tax-preparer/",
        "https://www.restorationplaza.org/event/entrepreneurial-empowerment-social-media-presentation/#new_tab",
        "https://www.restorationplaza.org/workforce-development/#new_tab",
        "https://www.restorationplaza.org/bk-reader-billie-holiday-theatre-wins-100k-grant-to-fund-discounted-tickets/",
        "https://www.restorationplaza.org/its-tea-time-in-bed-stuy/",
        "https://www.restorationplaza.org/social-justice-in-central-brooklyn/",
        "https://www.restorationplaza.org/workforce-development/",
        "https://www.restorationplaza.org/cash-bail-strips-wealth-from-low-income-communities/",
        "https://www.restorationplaza.org/a-talk-with-dr-john-flateau/",
        "https://www.restorationplaza.org/a-salute-to-indira-pauletta-and-toni-powerhouses-in-brooklyns-arts-and-culture-scene-share-their-agency-with-50-women-writers/",
        "https://www.restorationplaza.org/economic-solutions-center-expands-2-financial-empowerment-centers-in-central-brooklyn/",
        "https://www.restorationplaza.org/jerrell-gray-super-saver-a-profile/",
        "https://www.restorationplaza.org/content-coordinator/",
        "https://www.restorationplaza.org/graphic-design-assistant/",
        "https://www.restorationplaza.org/event/access-to-capital-panel-discussion-and-meetings/#new_tab",
        "https://www.restorationplaza.org/glenn-edward-sharpe-in-memoriam/",
        "https://www.restorationplaza.org/bww-review-50-in-50-letters-to-our-daughters-at-kumble-theater/",
        "https://www.restorationplaza.org/stress-management-support-group-2/",
        "https://www.restorationplaza.org/event/nyc-comptroller-scott-stringer-at-bedford-stuyvesant-march-26/",
        "https://www.restorationplaza.org/event/prep-for-prep-open-house/",
        "https://www.restorationplaza.org/restoration-kicks-off-community-planning-process-to-re-envision-restoration-plaza/",
        "https://www.restorationplaza.org/event/entrepreneurial-empowerment-access-to-capital/#new_tab",
        "https://www.restorationplaza.org/restoration-president-colvin-grannum-speaks-on-the-redesign-of-the-cultural-hub-in-bed-stuy/",
        "https://www.restorationplaza.org/administrative-assistant/",
        "https://www.restorationplaza.org/volapp/",
        "https://www.restorationplaza.org/restoration-launches-corporate-wide-volunteer-program/",
        "https://www.restorationplaza.org/economic-solutions-center-pilot-brooklyn-savers-club-program-help-residents-save-over-34000/",
        "https://www.restorationplaza.org/inclusiv-announces-partnership-between-bedford-stuyvesant-restoration-and-concord-federal-credit-union/",
        "https://www.restorationplaza.org/food-bank-for-nyc-offers-free-tax-prep-to-low-income-brooklynites/",
        "https://www.restorationplaza.org/the-billie-holiday-theatre-presents-the-world-premiere-of-a-walk-into-slavery/",
        "https://www.restorationplaza.org/event/volunteer-meeting-2019-09-25/2019-09-25/#new_tab",
        "https://www.restorationplaza.org/event/yaa-open-house/2019-09-14/",
        "https://www.restorationplaza.org/volunteer-interview-johnathan-liang/",
        "https://www.restorationplaza.org/holiday/",
        "https://www.restorationplaza.org/celebrate-national-bike-month-with-kick-off-event-and-community-bike-rides/",
        "https://www.restorationplaza.org/youth-services-at-restoration/",
        "https://www.restorationplaza.org/financial-counseling/",
        "https://www.restorationplaza.org/community-bike-rides/",
        "https://www.restorationplaza.org/broadway-world-brooklyn-jazz-hall-of-fame-juneteenth-ceremony-celebrate-african-american-freedom-day/",
        "https://www.restorationplaza.org/doctors-advice-take-two-bike-rides-and-call-me-in-the-morning/",
        "https://www.restorationplaza.org/the-real-deal-nycha-taps-development-team-to-perform-350m-renovations-takeover-managing-properties/",
        "https://www.restorationplaza.org/why-black-credit-unions-matter-for-financial-empowerment/",
        "https://www.restorationplaza.org/broadway-world-hadestown-be-more-chill-and-more-set-for-broadway-in-the-boros/",
        "https://www.restorationplaza.org/nyc-partnership-connects-bike-share-and-public-benefits/",
        "https://www.restorationplaza.org/restoration-credit-club/",
        "https://www.restorationplaza.org/restoration-saver-club/",
        "https://www.restorationplaza.org/annual-financial-reports/",
        "https://www.restorationplaza.org/home-aquisition/",
        "https://www.restorationplaza.org/home-preservation/",
        "https://www.restorationplaza.org/home-events/",
        "https://www.restorationplaza.org/street-team-member/",
        "https://www.restorationplaza.org/central-brooklyn-jazz-consortiums-20th-anniversary/",
        "https://www.restorationplaza.org/crown-heights-restaurant-opens-second-location-thanks-to-community-financial-institution-collaboration-led-by-brooklyn-business-center/",
        "https://www.eventbrite.com/e/ypoc-young-professionals-of-color-brooklyn-tickets-64563608491#new_tab",
        "https://www.restorationplaza.org/grow-your-business-skills-with-google/",
        "https://www.restorationplaza.org/moshood-creations-sets-up-shop-in-restoration-plaza/",
        "https://www.restorationplaza.org/place-based-grants-will-investigate-how-to-diversify-nyc-tech/",
        "https://www.restorationplaza.org/interview-with-moshood-rj/",
        "https://www.restorationplaza.org/spudz-opens-first-specialty-hand-cut-french-fries-restaurant-in-brooklyn/",
        "https://www.restorationplaza.org/the-citi-bike-expansion-plan-is-great-heres-how-to-do-it-right/",
        "https://www.restorationplaza.org/mercy-college-prepares-to-welcome-the-college-of-new-rochelle-students-this-fall/",
        "https://www.restorationplaza.org/restorationart-commemorates-arrival-of-first-slave-ship-with-speak-freedom-artist-gathering/",
        "https://www.restorationplaza.org/mercy-college-to-continue-to-serve-students-at-restoration-plaza-location/",
        "https://www.restorationplaza.org/summers-at-the-woodhull-youthmarket/",
        "https://www.restorationplaza.org/all-4-one-ashlynn/",
        "https://www.restorationplaza.org/cfp_event_form/",
        "https://www.restorationplaza.org/10k2019vol/",
        "https://www.restorationplaza.org/cafe-mocha-radio-shows-9th-annual-salute-her-awards-to-honor-black-women-of-broadway-and-new-york-theatre/",
        "https://www.restorationplaza.org/38th-annual-10k-run-and-5k-walk-wellness-is-wealth/",
        "https://www.restorationplaza.org/the-off-broadway-billie-holiday-theatre-announces-major-highlight-of-the-billies-2019-2020-season-with-new-windows-festival/",
        "https://www.restorationplaza.org/event/national-census-day-instagram-dance-party-to-celebrate-census-completion/",
        "https://www.restorationplaza.org/jrt/",
        "https://www.restorationplaza.org/esc-orientation/",
        "https://www.restorationplaza.org/temp-post/",
        "https://www.restorationplaza.org/a-coop-grows-in-brooklyn/",
        "https://www.restorationplaza.org/rfk_2019/",
        "https://www.restorationplaza.org/new-year-for-reinvision/",
        "https://www.restorationplaza.org/its-hard-to-believe-it-but-its-true-they-give-money-to-those-who-save/",
        "https://www.restorationplaza.org/sock-away-400-for-rainy-day-under-new-brooklyn-program/",
        "https://www.restorationplaza.org/bp-adams-and-restoration-announce-new-free-matching-program-to-help-residents-build-a-financial-savings/",
        "https://www.restorationplaza.org/dcwp-announces-expansion-of-financial-empowerment-centers/",
        "https://www.restorationplaza.org/dcwp-continue-expansion-of-nyc-free-tax-prep/",
        "https://www.restorationplaza.org/wp-content/uploads/Restoration-COVID-19-Final.pdf",
        "https://www.secureservercdn.net/198.71.233.35/qkc.722.myftpupload.com/event/managing-a-full-time-gig-and-side-hustle-how-to-recession-proof-your-career/",
        "https://www.restorationplaza.org/checking-in-with-you/",
        "https://www.restorationplaza.org/the-most-important-thing-you-can-do-at-home/",
        "https://www.restorationplaza.org/department-of-consumer-and-worker-protection-launches-remote-nyc-free-tax-prep-services/",
        "https://www.restorationplaza.org/register-for-our-free-self-prep-taxes/",
        "https://www.restorationplaza.org/economic_solutions/",
        "https://www.restorationplaza.org/financial-guidance-for-difficult-times-empowerment-webinars-from-bed-stuy-restoration-corp/",
        "https://www.restorationplaza.org/disrupting-the-racial-wealth-gap-2/",
        "https://www.restorationplaza.org/restoration-president-colvin-grannums-opinion-editorial-in-crains-on-how-black-and-latinx-populations-need-help-recovering-economically-from-the-pandemic/",
        "https://www.restorationplaza.org/smart-financial-management-during-covid-19/",
        "https://www.restorationplaza.org/addressing-debt-in-black-communities/",
        "https://www.restorationplaza.org/lets-make-this-time-truly-different/",
        "https://www.restorationplaza.org/financial-guidance-for-difficult-times-empowerment-webinars-from-bed-stuy-restoration-corp-2/",
        "https://www.businessinsider.com/new-business-owners-ppp-loans-how-to-raise-capital-cash-2020-6#new_tab",
        "https://www.restorationplaza.org/restoration-covid-19-emergency-fund/",
        "https://www.restorationplaza.org/diaper-giveaway/",
        "https://www.restorationplaza.org/watch-1-min-happy-reaction-during-diaper-giveaway/",
        "https://www.restorationplaza.org/watch/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-launches-emergency-covid-19-relief-fund/",
        "https://www.restorationplaza.org/new-york-city-launches-first-ever-tenant-resource-portal-to-help-renters-avoid-eviction/",
        "https://www.restorationplaza.org/restorations-covid-response/",
        "https://www.restorationplaza.org/at-restoration-your-success-is-our-success/",
        "https://www.restorationplaza.org/how-and-why-to-start-investing/",
        "https://www.restorationplaza.org/restoration-offers-fresh-food-and-fresh-recipes-and-tips/",
        "https://www.restorationplaza.org/make-sure-you-receive-your-stimulus-check/",
        "https://www.restorationplaza.org/solar-one-training-10-26/",
        "https://www.restorationplaza.org/institute-of-nonprofit-practice-classes/",
        "https://www.restorationplaza.org/expanding-the-nycha-to-tech-pipeline/",
        "https://www.restorationplaza.org/black-bean-kale-and-butternut-chili/",
        "https://www.restorationplaza.org/covid-19-emergency-fund/",
        "https://www.restorationplaza.org/the-bikeshare-systems-offering-free-rides-on-election-day/",
        "https://www.restorationplaza.org/brooklyn-navy-yard-equity-incubator/",
        "https://www.restorationplaza.org/grant-recipient-kharisa/",
        "https://www.restorationplaza.org/breakthrough-technology-fellowship/",
        "https://www.restorationplaza.org/vote-2020/",
        "https://www.restorationplaza.org/a-video-message-from-award-winning-global-architect-sir-david-adjaye/",
        "https://www.restorationplaza.org/bsr-virtual-gala/",
        "https://www.restorationplaza.org/neighborhoods-now/",
        "https://www.restorationplaza.org/success-stories/",
        "https://www.restorationplaza.org/resume-support/",
        "https://www.restorationplaza.org/restoration-gala-2020-thank-you/",
        "https://www.restorationplaza.org/passing-of-honorable-david-n-dinkins/",
        "https://www.restorationplaza.org/prepping-for-thanksgiving-nycha-residents/",
        "https://www.restorationplaza.org/celebrating-thanksgiving-with-restoration/",
        "https://www.restorationplaza.org/young-adults-in-tech-thrive-during-covid-19/",
        "https://www.restorationplaza.org/winter-wonderland-opens-in-bed-stuys-marcy-plaza-for-holidays-2020/",
        "https://www.restorationplaza.org/covid-financial-literacy-2020-nyc/",
        "http://wwww.ww.icontact-archive.com/archive?c=1039795&f=11986&s=12497&m=606296&t=7ac7a1e8b6d946baae310a39687337f186f851b5e16bfa6fcd1ac70c2f74639a",
        "https://www.restorationplaza.org/brooklyn-business-center-shop-local-support-our-merchants/",
        "https://www.restorationplaza.org/thank-you-restoration-gala-attendees/",
        "https://www.restorationplaza.org/bed-stuy-restoration-guides-working-class-families-toward-success/",
        "https://www.restorationplaza.org/foundation-for-financial-planning-announces-communities-of-color-initiative-sponsored-by-bny-mellons-pershing/",
        "https://www.restorationplaza.org/restoration-tapped-for-microsofts-community-skills-program-grant/",
        "https://www.restorationplaza.org/robert-bob-annibale-board-member/",
        "https://www.restorationplaza.org/financial-emp-center/",
        "https://www.restorationplaza.org/mental-health-esc/",
        "https://www.restorationplaza.org/social-supports/",
        "https://www.restorationplaza.org/now-is-the-time-to-disrupt-and-close-the-racial-wealth-gap/",
        "https://www.restorationplaza.org/understanding-and-addressing-the-racial-gap/",
        "https://www.restorationplaza.org/cameron-arrington-board-member/",
        "https://www.restorationplaza.org/erika-irish-brown-board-member/",
        "https://www.restorationplaza.org/benjamin-a-glascoe-board-member/",
        "https://www.restorationplaza.org/antonia-yuille-williams-board-member/",
        "https://www.restorationplaza.org/joseph-g-sponholz-board-member/",
        "https://www.restorationplaza.org/peter-m-williams-board-member/",
        "https://www.restorationplaza.org/wayne-c-winborne-board-member/",
        "https://www.restorationplaza.org/food-access-and-equity/",
        "https://www.restorationplaza.org/mental-health-chn/",
        "https://www.restorationplaza.org/physical-activity/",
        "https://www.restorationplaza.org/keeping-dreams-alive-breaking-barriers-through-tech-careers-by-yemisi-onayemi/",
        "https://www.restorationplaza.org/nyc-black-educators-coalition-mayoral-forum/",
        "https://www.restorationplaza.org/restoration-covid-19-emergency-fund/",
        "https://www.restorationplaza.org/at-restoration-young-leaders-are-prepared-to-lead-empowered-for-success/",
        "https://www.restorationplaza.org/congratulations-to-our-board-member-depelsha-mcgruder-for-joining-the-board-of-oaktree-capital-group-llc/",
        "https://www.restorationplaza.org/restoration-was-just-recognized-by-economic-mobility-pathways-empath-for-all-of-our-efforts/",
        "https://www.restorationplaza.org/as-nyc-charts-recovery-from-covid-19-brooklyn-borough-president-adams-puts-forward-series-of-recommendations-to-boost-urban-agriculture-in-the-city/",
        "https://www.restorationplaza.org/food-forward-nyc-city-releases-10-year-food-policy-plan/",
        "https://www.restorationplaza.org/give-us-your-feedback-on-our-restoration-website/",
        "https://www.restorationplaza.org/join-the-emergency-savings-challenge-by-wednesday-march-10-2021/",
        "https://www.restorationplaza.org/coalition-calls-on-nyc-leaders-to-kick-cars-off-25-percent-of-city-streets/",
        "https://www.slgr.typeform.com/to/LIFMDcms",
        "https://www.restorationplaza.org/in-pursuit-of-racial-economic-equity-sunday-supper-2021/",
        "https://www.restorationplaza.org/sunday-supper-march-2021-book-lists/",
        "https://www.restorationplaza.org/new-covid-19-vaccination-site-opens-in-bed-stuy/",
        "https://www.slgr.typeform.com/to/hnNiCn0N",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-opens-covid-19-vaccination-site/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-tax-site-irs-extends-tax-deadline-to-may-17-2021/",
        "https://www.restorationplaza.org/environmental-leadership-program-acceptance-congratulations-to-restoration-staffer/",
        "https://www.restorationplaza.org/damage-from-virus-utility-bills-overwhelm-some-households/",
        "https://www.restorationplaza.org/new-lafayette-gardens-community-center-advisory-board-member-congratulations-to-restoration-staffer/",
        "https://www.restorationplaza.org/darrells-story-restoration-client-story-foundation-for-financial-planning/",
        "https://www.restorationplaza.org/at-restoration-resilience-is/",
        "https://www.restorationplaza.org/restoration-partners-with-st-nicks-alliance-and-wins-buildings-of-excellence-competition-awards-from-nyserda/",
        "https://www.restorationplaza.org/2021-breakthrough-technology-fellowship-demo-day/",
        "https://www.restorationplaza.org/resilience-is-remaining-steadfast-even-in-the-face-of-a-pandemic/",
        "https://www.restorationplaza.org/hussains-story-restoration-volunteer-financial-planner-foundation-for-financial-planning/",
        "https://www.restorationplaza.org/congratulations-to-all-the-2021-breakthrough-technology-fellows/",
        "https://www.restorationplaza.org/resilience-is-brittneys-next-step/",
        "https://www.restorationplaza.org/resilience-is-nurturing-entrepreneurship-to-keep-our-neighborhoods-safe/",
        "https://www.meet.google.com/hfs-jhrz-pmq",
        "https://www.restorationplaza.org/broadband-discount-for-eligible-households/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-releases-report-on-advancing-equity-and-opportunity-within-micromobility/",
        "https://www.restorationplaza.org/introducing-restorations-new-mental-health-provider/",
        "https://www.restorationplaza.org/2021-restore-brooklyn-annual-benefit-gala/",
        "https://www.restorationplaza.org/restoration-2021-gala-save-the-date-monday-november-15-2021/",
        "https://www.restorationplaza.org/bedford-stuyvesant-restoration-corporation-study-calls-for-new-york-state-to-develop-central-brooklyn-food-hub-that-will-support-equitable-locally-controlled-food-system/",
        "https://www.restorationplaza.org/central-bk-food-hub-would-create-community-wealth-support-vulnerable-residents-study-finds/",
        "https://www.restorationplaza.org/change-capital-fund-report-essential-yet-invisible-community-organizations-in-the-time-of-covid/",
        "https://www.restorationplaza.org/making-affordable-housing-greener-using-financing-from-new-york-city-energy-efficiency-corporation-nyceec/",
        "https://www.restorationplaza.org/bed-stuy-neighborhood-career-fair-july-15-2021/",
        "https://www.zoom.us/meeting/register/tJ0qfuGspzItGdNR4VywvAOYq6t3Zu-PiHj9",
    ];

    // Onload check
    function checkUrlOnload() {
        const currentUrl = window.location.href;
        const found = redirectArray.find(element => element == currentUrl);
        const isEvent = window.location.pathname.split('/')[1] == 'event';
        console.log('isEvent: ', isEvent);
        if (found) {
            window.location.replace('https://www.restorationplaza.org/community/news/');
        }
        if (isEvent) {
            window.location.replace('https://www.restorationplaza.org/events/');
        }
    }

    checkUrlOnload();

    //        Events
    const links = document.querySelectorAll('a[href*="/event/"]');
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.replace('https://www.restorationplaza.org/events/');
        });
    })

    // News
    document.addEventListener('click', function (e) {
        const target = e.target;
        if (target.tagName != 'A') return;

        const targetLink = target.getAttribute('href');
        const found = redirectArray.find(element => element == targetLink);

        if (found) {
            e.preventDefault();
            window.location.replace('https://www.restorationplaza.org/community/news/');
        }
    });

});