jQuery(function ($) {
    // Set all variables to be used in scope

    jQuery("#post").validate({
       
    });
    
    authors_image();
    authors_gallery();
    
    function authors_image(){
    
        var frame,
                metaBox = $('#apt_author_fields.postbox'), // Your meta box id here
                addImgLink = metaBox.find('.upload-custom-img'),
                delImgLink = metaBox.find('.delete-custom-img'),
                imgContainer = metaBox.find('.custom-img-container'),
                imgIdInput = metaBox.find('.custom-img-id');

        // ADD IMAGE LINK
        addImgLink.on('click', function (event) {

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: 'Select or Upload Media',
                button: {
                    text: 'Use this media'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });


            // When an image is selected in the media frame...
            frame.on('select', function () {

                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();

                // Send the attachment URL to our custom image input field.
                imgContainer.append('<img src="' + attachment.url + '" alt="" style="max-width:100%;"/>');

                // Send the attachment id to our hidden input
                imgIdInput.val(attachment.id);

                // Hide the add image link
                addImgLink.addClass('hidden');

                // Unhide the remove image link
                delImgLink.removeClass('hidden');
            });

            // Finally, open the modal on click
            frame.open();
        });


        // DELETE IMAGE LINK
        delImgLink.on('click', function (event) {

            event.preventDefault();

            // Clear out the preview image
            imgContainer.html('');

            // Un-hide the add image link
            addImgLink.removeClass('hidden');

            // Hide the delete image link
            delImgLink.addClass('hidden');

            // Delete the image id from the hidden input
            imgIdInput.val('');

        });
    }
    
    function authors_gallery(){
    
        var frame,
                metaBox = $('#apt_author_fields.postbox'), // Your meta box id here
                addImgLink = metaBox.find('.setup-gallery'),
                delImgLink = metaBox.find('.clear-gallery'),
                //imgContainer = metaBox.find('.custom-img-container'),
                imgIdInput = metaBox.find('.custom-gallery-input');

        // ADD IMAGE LINK
        addImgLink.on('click', function (event) {

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: 'Select or Upload Media',
                button: {
                    text: 'Use this media'
                },
                multiple: true  // Set to true to allow multiple files to be selected
            });


            // When an image is selected in the media frame...
            frame.on('select', function () {
                var input_ids = [];
                // Get media attachment details from the frame state
                var selection = frame.state().get('selection').toJSON();

                $.each(selection, function(index, value){
                    input_ids.push(value.id)
                });

                // Send the attachment id to our hidden input
                imgIdInput.val(input_ids.join(','));

                // Hide the add image link
                addImgLink.addClass('hidden');

                // Unhide the remove image link
                delImgLink.removeClass('hidden');
            });

            // Finally, open the modal on click
            frame.open();
        });


        // DELETE IMAGE LINK
        delImgLink.on('click', function (event) {

            event.preventDefault();

            // Clear out the preview image
            //imgContainer.html('');

            // Un-hide the add image link
            addImgLink.removeClass('hidden');

            // Hide the delete image link
            delImgLink.addClass('hidden');

            // Delete the image id from the hidden input
            imgIdInput.val('');

        });
    }

});