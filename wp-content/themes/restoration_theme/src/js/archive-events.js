jQuery(document).ready(function ($) {

    let form = $('form#events-filter');
    let picker = document.querySelector('input#date');
    if(picker) {
        const date_picker = datepicker('input#date', {
            formatter: (input, date, instance) => {
                const value = moment(date.toLocaleDateString(), "DD.MM.YYYY").format("YYYY-MM-DD");
                input.value = value // => '1/1/2099'
            },
        });
    }
    form.find('select').niceSelect();
    let filters = {
        date : '',
        category : '',
        location : '',
        keyword : '',
        nonce : form.find('[name="events_nonce_field"]').val(),
    };

    form.on('submit', function (e) {
        e.preventDefault();

        filters.date = $(this).find('input#date').val();
        filters.category = $(this).find( '#category option:selected' ).val();
        filters.location = $(this).find('#location option:selected').val();
        filters.keyword = $(this).find('input#keyword').val();



        $.ajax({
            method: 'POST',
            url: ajax_actions.url,
            data: {
                filters: filters,
                action: 'filter_events',
            }, beforeSend: function () {
                /*loader = jQuery('<div/>', {
                    "class": 'redesign--ajax-loader-container',
                });
                $('<img />', {
                    src: '/wp-content/themes/keenethics/assets/img/ajax-loader.gif',
                    alt: 'ajax-loader'
                }).appendTo(loader);

                $('.redesign-archive-container').prepend(loader);*/

            }, success: function (response) {
                window.history.replaceState(null, null, response.archiveUrl + response.filterString);
                $('.other-events:not(.default), .no-events-result').remove();
                if (!response.clear) {
                    $('.events-filter').after(response.html);
                    $('.other-events.default').hide();
                } else {
                    $('.other-events.default').show();
                }
            }
        });
    });
});