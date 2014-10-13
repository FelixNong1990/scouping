/**
 * Chimpy Plugin Frontend JavaScript
 */
jQuery(document).ready(function() {

    /**
     * Maybe resize and center modal (prevent from being impossible to close on extra small screens)
     */
    function chimpy_adjust_modal_size() {
        jQuery('.chimpy_signup_form.sky-form-modal').each(function() {
            var width = jQuery(window).width();
            var height = jQuery(window).height();
            var modal_width = jQuery(this).outerWidth();
            var modal_height = jQuery(this).outerHeight();

            var max_allowed_width = typeof chimpy_max_form_width !== 'undefined' ? chimpy_max_form_width : 400;

            // Resize modal
            if (width < modal_width) {
                jQuery(this).css('width', width);
                modal_width = width;
            }
            else if (width > modal_width) {
                var new_modal_width = (width < max_allowed_width) ? width : max_allowed_width;
                jQuery(this).css('width', new_modal_width);
                modal_width = new_modal_width;
            }

            // Center modal
            jQuery(this).css('left', Math.max(0, ((width - modal_width) / 2) + jQuery(window).scrollLeft()) + "px");
            jQuery(this).css('top', Math.max(0, ((height - modal_height) / 2) + jQuery(window).scrollTop()) + "px");
        });
    }
    jQuery(window).resize(chimpy_adjust_modal_size);
    chimpy_adjust_modal_size();

    /**
     * Display hidden interest groups (if any)
     */
    jQuery('.chimpy_signup_form').click(function() {
        jQuery(this).find('.chimpy_interest_groups_hidden').show();
        chimpy_adjust_modal_size();
    });

    /**
     * Handle form submit
     */
    jQuery('.chimpy_signup_form').each(function() {

        var chimpy_button = jQuery(this).find('button');
        var chimpy_context = jQuery(this).find('#chimpy_form_context').val();

        chimpy_button.click(function() {
            chimpy_process_mailchimp_signup(jQuery(this), chimpy_context);
        });

        jQuery(this).find('input[type="text"], input[type="email"]').each(function() {
            jQuery(this).keydown(function(e) {
                if (e.keyCode === 13) {
                    chimpy_process_mailchimp_signup(chimpy_button, chimpy_context);
                }
            });
        });
    });

    /**
     * MailChimp signup
     */
    function chimpy_process_mailchimp_signup(button, context)
    {
        if (button.parent().parent().valid()) {

            button.parent().parent().find('fieldset').fadeOut(function() {

                var  this_form = jQuery(this).parent();
                this_form.find('#chimpy_signup_'+context+'_processing').fadeIn();
                button.prop('disabled', true);

                jQuery.post(
                    chimpy_ajaxurl,
                    {
                        'action': 'chimpy_subscribe',
                        'data': button.parent().parent().serialize()
                    },
                    function(response) {
                        var result = jQuery.parseJSON(response);

                        // Remove progress scene
                        this_form.find('#chimpy_signup_'+context+'_processing').fadeOut(function() {
                            if (result['error'] == 1) {
                                this_form.find('#chimpy_signup_'+context+'_error').children().html(result['message']);
                                this_form.find('#chimpy_signup_'+context+'_error').fadeIn();
                            }
                            else {
                                this_form.find('#chimpy_signup_'+context+'_success').children().html(result['message']);
                                this_form.find('#chimpy_signup_'+context+'_success').fadeIn();

                                var date = new Date();
                                date.setTime(date.getTime() + (5 * 365 * 24 * 60 * 60 * 1000));
                                jQuery.cookie('chimpy_s', '1', { expires: date, path: '/' });

                                if (context == 'lock') {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                }
                                else if (typeof result['redirect_url'] !== 'undefined' && result['redirect_url']) {
                                    setTimeout(function() {
                                        location.replace(result['redirect_url']);
                                    }, 1000);
                                }
                            }
                        });
                    }
                );
            });
        }
    }

    /**
     * Subscribe from widget
     */
    jQuery('#chimpy_widget_submit').click(function() {

        // Hide fields
        /*jQuery(this).parent().parent().parent().children().each(function() {
            if (jQuery(this).find('#chimpy_widget_subscription_submit').length == 0) {
                jQuery(this).fadeOut(500);
            }
        });*/

        // Show progress
        /*if (jQuery('#chimpy_widget_subscription_loading').length == 0) {
            jQuery(this).parent().parent().parent().prepend('<div id="chimpy_widget_subscription_loading" class="chimpy_widget_loading"></div>');
            jQuery('#chimpy_widget_subscription_loading').delay(400).fadeIn(500);
        }*/

        // Send data to server
        /*jQuery.post(
            chimpy_ajaxurl,
            {
                'action': 'chimpy_subscribe_widget',
                'data': jQuery('#chimpy_registration_form_widget').serialize()
            },
            function(response) {
                var result = jQuery.parseJSON(response);

                // Remove loader
                jQuery('#chimpy_widget_subscription_loading').remove();

                if (result['error'] == 1) {

                    // Display warning
                    if (jQuery('#chimpy_widget_error').length == 0) {
                        jQuery('#chimpy_widget_subscription_submit').parent().parent().before('<tr id="chimpy_widget_error"><td>'+result['message']+'</td></tr>');
                        jQuery('#chimpy_widget_error').delay(500).fadeIn(500);
                    }

                    // Show fields again
                    jQuery('#chimpy_widget_subscription_submit').parent().parent().parent().children().each(function() {
                        if (jQuery(this).find('#chimpy_widget_subscription_submit').length == 0) {
                            jQuery(this).fadeIn(500);
                        }
                    });

                }
                else {

                    // Display success message
                    if (jQuery('#chimpy_widget_success').length == 0) {
                        jQuery('#chimpy_widget_subscription_submit').parent().parent().before('<tr id="chimpy_widget_success"><td>'+result['message']+'</td></tr>');
                        jQuery('#chimpy_widget_success').delay(500).fadeIn(500);
                    }

                    // Make button innactive
                    jQuery('#chimpy_widget_subscription_submit').attr('disabled', 'disabled');

                }

            }
        );*/
    });

});
