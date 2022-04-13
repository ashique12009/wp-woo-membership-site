jQuery(document).ready(function($) {
    // GET memebership price
    $('#mtype_duration, #renew_mtype_duration').change(function() {
        var id = $(this).val();
        var ajax_url = member_vars.ajax_url;
        var nonce = member_vars.member_fee_nonce;
        var action = 'member_fee_price';

        $.get(ajax_url, {id: id, action: action, nonce: nonce}, function(data) {
            if (data.success === true) {
                $('#mcharge, #mcharge-renew').val(data.data);
            }
        });
    });

    // Re-new membership slide toggle
    setTimeout(() => {
        $('#slideToggleBtn').trigger('click');    
    }, 500);
    
    $('#slideToggleBtn').click(function() {
        $('#collapseTwo').slideToggle();
    });
});