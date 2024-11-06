jQuery(document).on('click', '#sendInvoice', function(event) {
    event.preventDefault();

    const email = jQuery('#email').val();
    const message = jQuery('#message').val();

    let flag = 1;
    if(email === '') {
        flag = 0;
        jQuery('#email').addClass('is-invalid');
    } else {
        jQuery('#email').removeClass('is-invalid');
    }

    if(message === '') {
        flag = 0;
        jQuery('#message').addClass('is-invalid');
    } else {
        jQuery('#message').removeClass('is-invalid');
    }

    if(flag) {
        jQuery.ajax({
            type:'POST',
            url: invoice_share_url,
            data:{'email': email, 'message': message},
            success:function(data) {
                window.location.reload();
            }
        });
    }
});