(function ($) {
        
      $(document).on('click', '.rj_ot_upload_media', function() {
        var field_id            = $(this).parent('.option-tree-ui-upload-parent').find('input').attr('id'),
            post_id             = $(this).attr('rel'),
            save_attachment_id  = $('#'+field_id).hasClass('ot-upload-attachment-id'),
            btnContent          = '';
          var uploadelem = $(this).parent('.option-tree-ui-upload-parent').find('input');
          var field_id =  uploadelem.attr('id');
              field_id = field_id.replace(/\[/g,'_');
              field_id = field_id.replace(/\]/g,'_');
              uploadelem.attr('id',field_id);
          
          
        if ( window.wp && wp.media ) {
          window.ot_media_frame = window.ot_media_frame || new wp.media.view.MediaFrame.Select({
            title: $(this).attr('title'),
            button: {
              text: option_tree.upload_text
            }, 
            multiple: false
          });
          window.ot_media_frame.on('select', function() {
            var attachment = window.ot_media_frame.state().get('selection').first(), 
                href = attachment.attributes.url,
                attachment_id = attachment.attributes.id,
                mime = attachment.attributes.mime,
                regex = /^image\/(?:jpe?g|png|gif|x-icon)$/i;
            if ( mime.match(regex) ) {
              btnContent += '<div class="option-tree-ui-image-wrap"><img src="'+href+'" alt="" /></div>';
            }
            btnContent += '<a href="javascript:(void);" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="'+option_tree.remove_media_text+'"><span class="icon ot-icon-minus-circle"></span>'+option_tree.remove_media_text+'</a>';
            $('#'+field_id).val( ( save_attachment_id ? attachment_id : attachment_id ) );
            $('#'+field_id+'_media').remove();
            $('#'+field_id).parent().parent('div').append('<div class="option-tree-ui-media-wrap" id="'+field_id+'_media" />');
            $('#'+field_id+'_media').append(btnContent).slideDown();
            window.ot_media_frame.off('select');
              
            
          }).open();
        } else {
          var backup = window.send_to_editor,
              intval = window.setInterval( 
                function() {
                  if ( $('#TB_iframeContent').length > 0 && $('#TB_iframeContent').attr('src').indexOf( "&field_id=" ) !== -1 ) {
                    $('#TB_iframeContent').contents().find('#tab-type_url').hide();
                  }
                  $('#TB_iframeContent').contents().find('.savesend .button').val(option_tree.upload_text); 
                }, 50);
          tb_show('', 'media-upload.php?post_id='+post_id+'&field_id='+field_id+'&type=image&TB_iframe=1');
          window.send_to_editor = function(html) {
			  console.log(html);
            var href = $(html).find('img').attr('src');
            if ( typeof href == 'undefined') {
              href = $(html).attr('src');
            } 
            if ( typeof href == 'undefined') {
              href = $(html).attr('href');
            }
            var image = /\.(?:jpe?g|png|gif|ico)$/i;
            if (href.match(image) && OT_UI.url_exists(href)) {
              btnContent += '<div class="option-tree-ui-image-wrap"><img src="'+href+'" alt="" /></div>';
            }
            btnContent += '<a href="javascript:(void);" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="'+option_tree.remove_media_text+'"><span class="icon ot-icon-minus-circle"></span>'+option_tree.remove_media_text+'</a>';
            $('#'+field_id).val(href);
            $('#'+field_id+'_media').remove();
            $('#'+field_id).parent().parent('div').append('<div class="option-tree-ui-media-wrap" id="'+field_id+'_media" />');
            $('#'+field_id+'_media').append(btnContent).slideDown();
            OT_UI.fix_upload_parent();
            tb_remove();
            window.clearInterval(intval);
            window.send_to_editor = backup;
          };
        }
        return false;
      });
 })(jQuery);