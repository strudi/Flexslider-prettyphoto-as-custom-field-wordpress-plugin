(function($) {
    "use strict";
    $(function() {
        var cmbSlider = (function() {
            var _custom_link = false,
                _custom_media = true,
                $itemsPlaceholder = $(".attach_list_gallery"),
                $thiscBoxLink = $(".cmbSliderThickboxLink"),
                $addSliderBtn = $('.cmb_gallery_button'),
                dataItems;

            function setupMediaManager() {
                $addSliderBtn.click(function(e) {
                    var thisButton = this;
                    e.preventDefault();
                    var custom_uploader = wp.media({
                        title:  cmbSliderData.editor_title,
                        button: {
                            text: cmbSliderData.save_button
                        },
                        multiple: true // Set this to true to allow multiple files to be selected
                    }).on('select', function() {
                        var attachments = custom_uploader.state().get('selection').toJSON();
                        $(thisButton).siblings('.attach_list_gallery').trigger("addGalleryFieldItems", {
                            "attachments": attachments
                        });
                    }).open();
                });
            }

            function appendItems(data) {
                $.each(data.attachments, function(i, attachment) {
                    appendItem(attachment);
                });
            }

            function appendItem(attachment) {
                 var url;
                 url = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url :  attachment.sizes.full.url;

                $itemsPlaceholder.prepend('<li  class="ui-state-default medo-gallery-image"> \
                               <a class="cmb_remove_cmb_gallery" href="#"> \
                               Odstrani \
                               </a>  \
                               <img width="150" id="theImg" src="' + url + '" /> \
                               <input class="cmb_gallery" type="hidden" id="prt_port_gal-' + cmbSliderData.fieldID + '" \
                                 name="' + cmbSliderData.fieldID + '[]" value="' + attachment.id + '" /> \
                             <a data-attachment-id="' + attachment.id + '" href="#"   \
                             class="cmbSliderThickboxLink">Caption</a> \
                               </li> \
                   ');
            }

            function WPThickboxForCaption() {
               $itemsPlaceholder.on("click", "a.cmbSliderThickboxLink" , function() {
                    
                 $(':input','#cmb-attachment-edit-form')
                  .not(':button, :submit, :reset')
                  .val('');
               
                  tb_show('Caption', '#TB_inline?width=350&height=300&inlineId=cmb-attachment-edit-form');
                  
                  jQuery(document).find('#TB_window').width("350px").height("300px");
                 
                  var att_id = $(this).data("attachment-id");
                
                  getAttachment(att_id, bindUpdateForm);
              });
            }

            function bindUpdateForm(data) {
               var $form, $input;

                $form =  $("#cmb-attachment-form");
             
                $input = $form.find("#cmb-att-title");
               $input.val(data.title);

               $input = $form.find("#cmb-att-caption");
               $input.val(data.caption);

               $input = $form.find("#cmb-att-alt-text");
               $input.val(data.alt);

               $input = $form.find("#cmb-att-description");
               $input.val(data.description);
               
               $input = $form.find("#cmb-att-id");
               $input.val(data.id);
               
               $input = $form.find("#cmb-att-nonce");
               $input.val(data.nonces.update);
            }
            
            function setSorting() {
                jQuery(".attach_list_gallery").sortable({
                    update: function(event, ui) {
                        //  rebuildGalleryIndex($(this));
                    },
                    placeholder: "ui-state-highlight"
                });
            }

            function updateAttCaption() {
              var  $form =  $("#cmb-attachment-form");

                $.post("admin-ajax.php", {
                    id:  $form.find("#cmb-att-id").val() ,
                    action: "save-attachment",
                    nonce:  $form.find("#cmb-att-nonce").val(),
                    "changes[title]": $form.find("#cmb-att-title").val(),
                    "changes[description]": $form.find("#cmb-att-description").val(),
                    "changes[caption]": $form.find("#cmb-att-caption").val(),
                    "changes[alt]": $form.find("#cmb-att-alt-text").val()
                });
            }

            function getAttachment(attachment_ID, callback) {
                var url = cmbSliderData.ajaxurl;
                $.post(url, {
                    action: 'get-attachment',
                    id: attachment_ID
                }).done(function(data) {
                 
                        callback(data.data);
                  
                });
            }

            function loadAttachments() {
                var url = cmbSliderData.ajaxurl;
                $.post(url, {
                    action: 'query-attachments',
                    "query[post__in]": cmbSliderData.attachments,
                    "query[paged]":0
                }).done(function(data) {
                 
                   $.each(data.data, function(i, attachment) {
                      appendItem(attachment);

                  });
                  
              });

              
            }

            return {
                append: appendItems,
                init: function() {
                    loadAttachments();
                    setSorting();
                    WPThickboxForCaption();
                    setupMediaManager();
                    $itemsPlaceholder.on("addGalleryFieldItems", function(event, data) {
                        cmbSlider.append(data);
                    });
                    $itemsPlaceholder.on('click', 'a#cmb-save-att-button' , function() {
                        tb_remove();
                        updateAttCaption();
                    });
                     $('#cmb-save-att-button').live('click', function() {
                         tb_remove();
                        updateAttCaption();
                    });
                    $('.cmb_remove_cmb_gallery').live('click', function() {
                        $(this).parent().remove();
                        return false;
                    });
                    $('.add_media').on('click', function() {
                        _custom_media = false;
                    });
                }
            };
        })();
        cmbSlider.init();
    });
    /*  function rebuildGalleryIndex(container) {
            var attrId = new Array();

            container.children().each(function() {
                attrId.push($(this).data("attid"));
            });
            container.siblings('.cmb_gallery').val(attrId.join(","));
        }*/
    //var _orig_send_attachment = wp.media.editor.send.attachment;
}(jQuery));