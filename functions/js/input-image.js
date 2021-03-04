jQuery(document).ready( function( $ ) {
    let inputWrapper = jQuery('.METGS-inputtype-image');
    if(inputWrapper.length>0){
        inputWrapper.find('.image').click(function (e) {
            e.preventDefault();

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: jQuery(this).data('uploader_title'),
                button: {
                    text: jQuery(this).data('uploader_button_text'),
                },
                multiple: false // Set to true to allow multiple files to be selected
            });

            // When a file is selected, run a callback.
            file_frame.on('select', function(){
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                let url = attachment.url;
                let id = attachment.id;
                let title = attachment.title;

                inputWrapper.find('.image').css('background-image','url("'+url+'")');
                inputWrapper.find('input').val(id);
                inputWrapper.find('.image').removeClass('empty');
            });

            // Finally, open the modal
            file_frame.open();

        });

        inputWrapper.find('.close').click(function (e) {
            let field = jQuery(this).parent();
            field.find('input').val('');
            field.find('.image').addClass('empty');
        });

    }

});