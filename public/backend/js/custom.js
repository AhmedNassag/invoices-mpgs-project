(function ($) {
    "use strict";

    jQuery.noConflict();

    jQuery('[data-toggle="tooltip"]').tooltip();

    jQuery(".upload-file-input").on('change', function () {
        var file = this.files;
        if (file.length > 0) {
            var file = file[0];
            jQuery(this).siblings().eq(0).text(file.name);
        } else {
            jQuery(this).siblings().eq(0).text('Choose file');
        }
    });

    jQuery(".delete").on("submit", function () {
        return confirm("Are you sure?");
    });

    if (jQuery.fn.DataTable) {
        jQuery('#dataTable').DataTable();
    }

    if (jQuery.fn.datepicker) {
        jQuery('.datepicker').datepicker({
            format: 'dd-mm-yyyy', autoclose: true
        });
    }

    if (jQuery.fn.summernote) {
        jQuery('#description').summernote({
            toolbar: [// [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']], ['font', ['strikethrough', 'superscript', 'subscript']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], ['height', ['height']]],
            height: 150
        });
    }

    jQuery('.multiple-file input').on('change', function (e) {
        var files = [];
        for (var i = 0; i < jQuery(this)[0].files.length; i++) {
            files.push(jQuery(this)[0].files[i].name);
        }
        jQuery(this).next('.custom-file-label').html(files.join(', '));
    });

    jQuery('#userrole').on('change', function (e) {
        var roleUrl = jQuery(this).find(':selected').data('url')
        window.location.href = roleUrl;
    });

    jQuery('#permissionrole').on('change', function (e) {
        var permissionUrl = jQuery(this).find(':selected').data('url')
        window.location.href = permissionUrl;
    });


    // Demo Login Purpose
    jQuery('#demoadmin').click(function () {
        jQuery('#demoemail').val('admin@example.com');
        jQuery('#demopassword').val('123456');
        jQuery('#demopassword').attr('type', 'text');
    });

    jQuery('#demomoderator').click(function () {
        jQuery('#demoemail').val('moderator@example.com');
        jQuery('#demopassword').val('123456');
        jQuery('#demopassword').attr('type', 'text');
    });

    jQuery('#democustomer').click(function () {
        jQuery('#demoemail').val('customer@example.com');
        jQuery('#demopassword').val('123456');
        jQuery('#demopassword').attr('type', 'text');
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery('#prevLogo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    jQuery("#uploadLogo").change(function () {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery('#prevProfile').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    jQuery("#uploadProfile").change(function () {
        readURL(this);
    });


    jQuery('.single-theme').on('click', function () {
        jQuery('.single-theme').removeClass('single-theme-active');
        jQuery(this).addClass('single-theme-active');
        jQuery('.theme-active').remove();
        jQuery(this).append('<div class="theme-active"><span class="fa fa-check fa-2x"></span></div>');

        const theme = jQuery(this).data('theme');

        if (theme) {
            jQuery.ajax({
                type: "POST", url: INVOICE_THEME_SETTING, data: {'theme': theme}, success: function (data) {
                    location.reload();
                }
            });
        }
    });

})(jQuery);
