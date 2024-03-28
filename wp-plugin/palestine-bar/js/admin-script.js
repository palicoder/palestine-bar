jQuery(document).ready(function($) {
    $('#palestine_bar_upload_image_button').click(function() {
        var custom_uploader;
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#data_image_link').val(attachment.url);
        });
        custom_uploader.open();
    });
});
