jQuery(document).ready(function($){
    // Uploading files
    var file_frame;
     
      jQuery('.frmw_wpmu_button').on('click', function( event ){
     
        event.preventDefault();
     
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          file_frame.open();
          return;
        }
     
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: jQuery( this ).data( 'uploader_title' ),
          button: {
            text: jQuery( this ).data( 'uploader_button_text' ),
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });
     
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
          // We set multiple to false so only get one image from the uploader
          attachment = file_frame.state().get('selection').first().toJSON();
     
          // Do something with attachment.id and/or attachment.url here
          // write the selected image url to the value of the #frmw_meta text field
          jQuery('#frmw_meta').val('');
          jQuery('#frmw_upload_meta').val(attachment.url);
          jQuery('#frmw_upload_edit_meta').val('/wp-admin/post.php?post='+attachment.id+'&action=edit&image-editor');
          jQuery('.cupp-current-img').attr('src', attachment.url).removeClass('placeholder');
        });
     
        // Finally, open the modal
        file_frame.open();
      });
    
    // Toggle Image Type
      jQuery('input[name=img_option]').on('click', function( event ){
        var imgOption = jQuery(this).val();
    
        if (imgOption == 'external'){
          jQuery('#frmw_upload').hide();
          jQuery('#frmw_external').show();
        } else if (imgOption == 'upload'){
          jQuery('#frmw_external').hide();
          jQuery('#frmw_upload').show();
        }
    
      });
      
      if ( '' !== jQuery('#frmw_meta').val() ) {
        jQuery('#external_option').attr('checked', 'checked');
        jQuery('#frmw_external').show();
        jQuery('#frmw_upload').hide();
      } else {
        jQuery('#upload_option').attr('checked', 'checked');
      }
    
      // Update hidden field meta when external option url is entered
      jQuery('#frmw_meta').blur(function(event) {
        if( '' !== $(this).val() ) {
          jQuery('#frmw_upload_meta').val('');
          jQuery('.cupp-current-img').attr('src', $(this).val()).removeClass('placeholder');
        }
      });
    
    // Remove Image Function
      jQuery('.edit_options').hover(function(){
        jQuery(this).stop(true, true).animate({opacity: 1}, 100);
      }, function(){
        jQuery(this).stop(true, true).animate({opacity: 0}, 100);
      });
    
      jQuery('.remove_img').on('click', function( event ){
        var placeholder = jQuery('#frmw_placeholder_meta').val();
    
        jQuery(this).parent().fadeOut('fast', function(){
          jQuery(this).remove();
          jQuery('.cupp-current-img').addClass('placeholder').attr('src', placeholder);
        });
        jQuery('#frmw_upload_meta, #frmw_upload_edit_meta, #frmw_meta').val('');
      });
    
    });