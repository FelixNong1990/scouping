(function($){
	$(document).ready(function() {

        $('.datepicker').datetimepicker({dateFormat : 'M d, yy', timeFormat: "hh:mm tt"});

        $('.codigo-color-field').wpColorPicker();

        // clears out the sibling text inputs
        $('.clear-btn').on('click', function(){
            var el = $(this);
            el.siblings('input[type="text"]').val('');
            el.siblings('.custom_preview_image').attr('src', '').addClass('hidden');
        });

        $('.custom_upload_image_button, .custom_clear_image_button').live('click', function( e ){

            e.preventDefault();
            var button = $(this);
            var container = button.closest('td');
            var urlinput = $('.image-url-input', container);
            var previewimage = $('.custom_preview_image', container);
            var imageoptions = $('.image-css-options', container)
            var file_frame;

            /***********************************
            Remove Button
            ***********************************/
            if( button.hasClass('custom_clear_image_button') ){
                previewimage.removeAttr('src').addClass('hidden');
                imageoptions.addClass('hidden');
                urlinput.removeAttr('value');
                return;
            }

            /***********************************
            Upload Button
            ***********************************/
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
              file_frame.open();
              return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
              title: $( this ).data( 'uploader_title' ),
              button: { text: $( this ).data( 'uploader_button_text' ) },
              multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                var selection = file_frame.state().get('selection');
                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();

                    var imageurl = attachment.url;
                    var thumbnailurl = attachment.url;

                    // if(typeof attachment.sizes.thumbnail !== 'undefined' )
                    //     thumbnailurl = attachment.sizes.thumbnail.url;

                    previewimage.attr('src', thumbnailurl).removeClass('hidden');
                    imageoptions.removeClass('hidden');
                    urlinput.attr('value', imageurl);

                });
            });

            // Finally, open the modal
            file_frame.open();
        });
    })
})(jQuery);