let paypalButtons = null;
let amountValue;
let formPay = jQuery('#payment-form');

jQuery(function(){

    let amountButton = jQuery('.donation__amount-item ');
    let amountEnter = jQuery('.donation__enter-amount input');
    amountValue = jQuery('.donation__amount-item.active').data('price');

    amountEnter.on('input', function(){
        amountValue = jQuery(this).val();
    });

    amountButton.click(function(){
        amountValue = jQuery(this).data('price');
    });

    /*let dedicationName = form.find('[name="dedication-name"]');
    form.find('[name="dedication"]').on('change', function(){
        if (jQuery(this).val() !== 'none'){
            dedicationName.attr('required', 'required')
        } else {
            dedicationName.removeAttr('required').val('');
        }
    });*/

    paypalInit();
});

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function paypalInit() {

    let recurring = jQuery('#recurring:checked');
    if(recurring.length) { /* recurring checkbox */

        /*if ( jQuery( "#paypal-buttons" ).length ) {
            paypalButtons = paypal.Buttons({
                onClick: function (data, actions) { /!*валідація*!/

                    //let form = jQuery('#payment-form');
                    let valid = true;
                    let error_message = '';

                    jQuery(form).find('.donation__input-group [required]').each(function () {

                        if (jQuery(this).val() === '') {
                            jQuery(this).addClass("et_checkout_error");
                            valid = false;
                            error_message += '<li>' + jQuery(this).data("placeholder") + '</li>';
                        }
                    });

                    jQuery(form).find('.donation__input-group [type="email"]').each(function () {
                        if (jQuery(this).val() !== '' && !validateEmail(jQuery(this).val())) {
                            jQuery(this).addClass("et_checkout_error");
                            valid = false;
                            error_message += '<li>Email address not valid</li>';
                        }
                    });

                    if (!valid) {
                        jQuery('#card-errors').html('<p>Please, fill in the following fields:</p><ul>' + error_message + '</ul>');
                        jQuery('html, body').animate({
                            scrollTop: jQuery("#card-errors").offset().top - 150
                        }, 500);

                        return actions.reject();
                    } else {
                        jQuery('#card-errors').html('');
                        return actions.resolve();
                    }
                },
                createSubscription: function (data, actions) { /!* до цього момента створити підписку на бекенді *!/
                    var data = new FormData();
                    //var form = jQuery('#payment-form').serialize();
                    var form_serialize = form.serialize();

                    data.append('action', 'paypal_payment_process');
                    data.append('form', form_serialize + '&amount=' + amountValue);

                    return fetch(ajax_object.ajaxurl, {
                        method: 'post',
                        body: data,
                        credentials: 'same-origin'
                    }).then((response) => response.json())
                        .then((data) => {
                            return actions.subscription.create({
                                'plan_id': data.id
                            });
                        })
                        .catch((error) => {
                            console.log('Payment Error:');
                            console.dir(error);
                        });

                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        var data = new FormData();
                        //var form = jQuery('#payment-form');
                        var form_serialize = form.serialize();

                        data.append('action', 'paypal_order_create');
                        data.append('form', form_serialize + '&amount=' + amountValue);
                        data.append('success', true);

                        return fetch(ajax_actions.url, {
                            method: 'post',
                            body: data,
                            credentials: 'same-origin'
                        }).then((response) => response.json()).then((data) => {
                            console.log(data);
                        }).catch((error) => {
                            console.log('Payment Error:');
                            console.dir(error);
                        });
                    });
                },
                onError: function (err) {
                    var data = new FormData();
                    //var form = jQuery('#payment-form');
                    var form_serialize = form.serialize();

                    data.append('action', 'paypal_order_create');
                    data.append('form', form_serialize + '&amount=' + amountValue);
                    data.append('success', false);

                    return fetch(ajax_actions.url, {
                        method: 'post',
                        body: data,
                        credentials: 'same-origin'
                    }).then((response) => response.json()).then((data) => {
                        console.log(data);
                    }).catch((error) => {
                        console.log('Payment Error:');
                        console.dir(error);
                    });
                }
            });

            paypalButtons.render('#paypal-buttons'); /!* місе для кнопки *!/

        }*/

    } else {

        if ( jQuery( "#paypal-buttons" ).length ) {

            paypalButtons = paypal.Buttons({
                onClick: function (data, actions) {

                    //let form = jQuery('#payment-form');
                    let valid = true;
                    let error_message = '';

                    jQuery('#payment-form').find('.donation__input-group [required]').each(function () {

                        if (jQuery(this).val() === '') {
                            jQuery(this).addClass("et_checkout_error");
                            valid = false;
                            error_message += '<li>' + jQuery(this).data("placeholder") + '</li>';
                        } else jQuery(this).removeClass("et_checkout_error");
                    });

                    jQuery('#payment-form').find('.donation__input-group [type="email"]').each(function () {
                        if (jQuery(this).val() !== '' && !validateEmail(jQuery(this).val())) {
                            jQuery(this).addClass("et_checkout_error");
                            valid = false;
                            error_message += '<li>Email address not valid</li>';
                        } //else jQuery(this).removeClass("et_checkout_error");
                    });

                    if (!valid) {
                        jQuery('#card-errors').html('<p>Please, fill in the following fields:</p><ul>' + error_message + '</ul>');
                        jQuery('html, body').animate({
                            scrollTop: jQuery('#payment-form').offset().top - 150
                        }, 500);

                        return actions.reject();
                    } else {
                        jQuery('#card-errors').html('');
                        return actions.resolve();
                    }
                },
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: amountValue,
                                currency_code: 'USD'
                            },
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    /*return actions.order.capture().then(function (details) {
                        var data = new FormData();
                        var form_serialize = form.serialize();

                        data.append('action', 'paypal_order_create');
                        data.append('form', form_serialize + '&amount=' + amountValue);
                        data.append('success', true);

                        return fetch(ajax_actions.url, {
                            method: 'post',
                            body: data,
                            credentials: 'same-origin'
                        }).then((response) => response.json()).then((data) => {
                            console.log(data);
                            form.trigger('reset');
                        }).catch((error) => {
                            console.log('Payment Error:');
                            console.dir(error);
                        });
                    });*/
                    paypalDestroy();
                    jQuery('#payment-form').find('[type="submit"]').show();
                },
                onError: function (err) {
                    /*var data = new FormData();
                    var form_serialize = form.serialize();

                    data.append('action', 'paypal_order_create');
                    data.append('form', form_serialize + '&amount=' + amountValue);
                    data.append('success', false);

                    return fetch(ajax_actions.url, {
                        method: 'post',
                        body: data,
                        credentials: 'same-origin'
                    }).then((response) => response.json()).then((data) => {
                        console.log(data);
                    }).catch((error) => {
                        console.log('Payment Error:');
                        console.dir(error);
                    });*/
                    return;
                }
            });

            paypalButtons.render('#paypal-buttons');

        }

    }
}

function paypalDestroy() {
    if(paypalButtons !== null) {
        paypalButtons.close();
    }else {
        console.log("Not found");
    }
}



