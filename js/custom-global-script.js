// Get member product price by member type and duration
jQuery(document).ready(function($){
    $("#mtype").change(function(){
        get_member_product_price();
    });

    $("#mduration").change(function(){
        get_member_product_price();
    });

    function get_member_product_price() {
        var mtype = $('#mtype').val();
        var mduration = $('#mduration').val();

        alert(mtype, mduration);
    }
});