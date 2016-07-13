jQuery(function ($) {
    // Set all variables to be used in scope

    $("#post").validate({
       
    });
    
    $(".remove-gallery-image").bind( "click", function(event) {
        event.preventDefault();
        $(this).parent('.gallery-input').remove();
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
                imgContainer.append('<img src="' + attachment.sizes.medium.url + '" alt="" style="max-width:100%;"/>');

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
                addImgLink = metaBox.find('.add-galery-image'),
                //delImgLink = metaBox.find('.remove-gallery-image'),
                //imgContainer = metaBox.find('.custom-img-container'),
                inputContainer = addImgLink.parent('.custom-gallery-container');
                //imgIdInput = inputContainer.find('.custom-gallery-container');

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

                var attachment = frame.state().get('selection').first().toJSON();
                //console.log(attachment);
                // Send the attachment URL to our custom image input field.
                addImgLink.before('<div class="gallery-input"><input class="custom-gallery-input" type="hidden" name="apt_gallery[]" value="' + attachment.id + '"/><img src="' + attachment.sizes.thumbnail.url + '" alt=""/><br/><a class="remove-gallery-image" href="#">Remove Image</a><br/><br/></div>');
           
                // Unhide the remove image link
                $(".remove-gallery-image").bind( "click", function(event) {
                    event.preventDefault();
                    $(this).parent('.gallery-input').remove();
                });
            });

            // Finally, open the modal on click
            frame.open();
        });

    }

});