/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @category  PrestaShop Module
 * @author    knowband.com <support@knowband.com>
 * @copyright 2015 Knowband
 */

var primaryColor = '#496CAD';
var dangerColor = '#bd362f';
var successColor = '#609450';
var warningColor = '#ab7a4b';
var inverseColor = '#45484d';

var themerPrimaryColor = primaryColor;
var previousPoint = null;
var previousLabel = null;


$(document).ready(function() {
    
    $('#supercheckout_configuration_form table tr td').each(function () {
        var data = $(this).find('.icon-question-sign').prop('attributes');
        $(this).find('.icon-question-sign').remove();
        var this_cus = this;
        for (let ab in data) {
            if (data[ab].name == 'data-toggle' || data[ab].name == 'data-placement' || data[ab].name == 'data-original-title') {
                $(this_cus).find('span').attr(data[ab].name, data[ab].value);
            }
        }
        $(this_cus).find('span').tooltip();
    });
    
    
    /* Code added by Anshul to add comma on pressing the enter */
    
    $('input[name="velocity_supercheckout[ip_addresses]"]').keypress(function (e) {
        if (e.keyCode == 13) {
            $('input[name="velocity_supercheckout[ip_addresses]"]').val($('input[name="velocity_supercheckout[ip_addresses]"]').val() + ', ');
        }
    });
    /* Code added by Anshul to add comma on pressing the enter */
    
    getMailChimpList();
    getSendinBlueList();
    getklaviyoList();
    $('#velsof_tab_login').on('click',function(){
       $("#google_acc").hide();
        $("#facebook_acc").hide();
        $("#loginizer_link").show();
    });
    $('#slide1_controls').on('click', function(){
        $('.velsof-adv-panel').removeAttr('style');
        $('#slide1_controls').hide();
        $('#slide2_controls').show();
    });
    $('#slide2_controls').on('click', function(){
        $('.velsof-adv-panel').attr('style','right:-18px;');
        $('#slide1_controls').show();
        $('#slide2_controls').hide();
    });

    $('.alternate').each(function() {
        $('tr:odd',  this).addClass('odd').removeClass('even');
        $('tr:even', this).addClass('even').removeClass('odd');
    });

    $('input.input-checkbox-option').click(function(){
        if($(this).is(':checked')){
            $(this).attr('value', 1);
        }else{
            $(this).attr('value', 0);
        }
    });

    $('.display_address_field').click(function(event){
        var id = $(this).attr('id').split('_');
        id.pop();
        id = id.join('_');
		if(!$(this).is(':checked')){
			if($('#'+id+'_require').is(':checked')){
				$('#'+id+'_require').parent().removeClass('checked');
                $('#'+id+'_require').removeAttr('checked');
                $('#'+id+'_require').attr('value', 0);
			}

		}
		//$(this).parent().removeClass('checked');
//        if($('#'+id+'_require').is(':checked')){
//            if($(this).is(':checked')){
//                alert(uncheckAddressFieldMsg)
//                $(this).parent().addClass('checked');
//                event.preventDefault();
//            }else{
//                $(this).parent().addClass('checked');
//            }
//        }
    });

    $('.require_address_field').click(function(event){
        if($(this).is(':checked')){
            var id = $(this).attr('id').split('_');
            id.pop();
            id = id.join('_');
            if(!$('#'+id+'_display').is(':checked')){
                $('#'+id+'_display').parent().addClass('checked');
                $('#'+id+'_display').attr('checked', 'checked');
                $('#'+id+'_display').attr('value', 1);
            }
        }
        //event.preventDefault();

        //alert(id);
    });

    $('.sortable > tr').tsort({attr:'sort-data'});

    $( ".sortable" ).sortable({
        revert: true,
        cursor: "move",
        items: "> .sort-item",
        containment: "document",
        distance: 5 ,
        opacity: 0.8,
        stop: function( event, ui ) {
            $(this).find("tr").each(function(i, el){
                //$(this).find("input.sort").val($(el).index());
                $(this).find("input.sort").attr('value',$(el).index()+1);
                $('.alternate').each(function() {
                    $('tr:odd',  this).addClass('odd').removeClass('even');
                    $('tr:even', this).addClass('even').removeClass('odd');
                });
            });
        }
    });

    $('.bootbox-design-edit-html').click(function(){
        var id = $(this).attr('id')+'_value';
        var splitId = id.split('_');
        if($("#"+id).length){
            var stored_value = $("#"+id).val();
        }else{
            var hidden_field = '<input type="hidden" id="'+id+'" name="velocity_supercheckout[design][html]['+splitId[splitId.length - 1]+']" value="">';
            $('#tab_design').append(hidden_field);
            var stored_value="";
        }

        bootbox.confirm('<h4>' + $("#modals_bootbox_prompt_header_html").val() + '</h4><textarea id="text_area_html_' + splitId[splitId.length - 1] + '" class="supercheckout_textarea_html" >' + stored_value + '</textarea><h4 style="color:red;">' + double_quotes_warning + '</h4>',
        function(result) {
            if(result){
                html_string=$('#text_area_html_'+splitId[splitId.length - 1]).val().replace(/(\r\n|\n|\r)/gm, "<br/>");
                $("#"+id).val(html_string);
            }
        });
    });

    /*$('.bootbox-design-extra-html').click(function(){alert('helo');
        var temp = $(this).attr('id');
        var splitId = temp.split('-');
        var id = "modals_bootbox_prompt_"+splitId[splitId.length - 1];
        if($("#"+id).length){
            var stored_value = $("#"+id).val();
        }else{
            var hidden_field = '<input type="hidden" id="'+id+'" name="velocity_supercheckout[design][html]['+splitId[splitId.length - 1]+'][value]" value="">';
            $('#tab_design').append(hidden_field);
            var stored_value="";
        }

        bootbox.confirm('<h4>'+$("#modals_bootbox_prompt_header_html").val()+'</h4><textarea id="text_area_html" class="supercheckout_textarea_html" >'+ stored_value +'</textarea>',
        function(result) {
            if(result){
                html_string=$('#text_area_html').val().replace(/(\r\n|\n|\r)/gm, "<br/>");
                $("#"+id).val(html_string);
            }
        });
    });*/

    //2-Column Layout
    $('input[name="velocity_supercheckout[column_width][2_column][1]"]').css('width', $('input[name="velocity_supercheckout[column_width][2_column][1]"]').val()+'%');
    $('input[name="velocity_supercheckout[column_width][2_column][2]"]').css('width', $('input[name="velocity_supercheckout[column_width][2_column][2]"]').val()-1+'%');

    //3-Column Layout
    $('input[name="velocity_supercheckout[column_width][3_column][1]"]').css('width', $('input[name="velocity_supercheckout[column_width][3_column][1]"]').val()+'%');
    $('input[name="velocity_supercheckout[column_width][3_column][2]"]').css('width', $('input[name="velocity_supercheckout[column_width][3_column][2]"]').val()+'%');
    $('input[name="velocity_supercheckout[column_width][3_column][3]"]').css('width', $('input[name="velocity_supercheckout[column_width][3_column][3]"]').val()-1+'%');

	$("#payment-accordian").accordion({
      animated: 'bounceslide',
      autoHeight: false,
      collapsible: true,
      event: 'click',
      active: false,
      animate: 100
    });
	$("#delivery-accordian").accordion({
      animated: 'bounceslide',
      autoHeight: false,
      collapsible: true,
      event: 'click',
      active: false,
      animate: 100
    });


});

function dialogExtraHtml(e){
    var temp = $(e).attr('id');
    var splitId = temp.split('-');
    var id = "modals_bootbox_prompt_"+splitId[splitId.length - 1];
    if($("#"+id).length){
        var stored_value = $("#"+id).val();
    }else{
        var hidden_field = '<input type="hidden" id="'+id+'" name="velocity_supercheckout[design][html]['+splitId[splitId.length - 1]+'][value]" value="">';
        $('#tab_design').append(hidden_field);
        var stored_value="";
    }

    bootbox.confirm('<h4>' + $("#modals_bootbox_prompt_header_html").val() + '</h4><textarea id="text_area_html" class="supercheckout_textarea_html" >' + stored_value + '</textarea><h4 style="color:red;">' + double_quotes_warning + '</h4>',
    function(result) {
        if(result){
            html_string=$('#text_area_html').val().replace(/(\r\n|\n|\r)/gm, "<br/>");
            $("#"+id).val(html_string);
        }
    });
}

function remove_html(e){
    var data = $(e).attr('data');
    $('#portlet_'+ data).remove();
    $('#modals_bootbox_prompt_'+ data).remove();
    $('#3_col_h_'+data).remove();
    $('#3_row_h_'+data).remove();
    $('#3_col_ins_h_'+data).remove();
    $('#2_col_h_'+data).remove();
    $('#2_row_h_'+data).remove();
    $('#2_col_ins_h_'+data).remove();
    $('#1_col_h_'+data).remove();
    $('#1_row_h_'+data).remove();
    $('#1_col_ins_h_'+data).remove();
}

function isNormalInteger(str) {
    return /^\+?(0|[1-9]\d*)$/.test(str);
}


function validate_data(){
    $('span.error').html('');

    $('#fb_app_id_error').html('');
    $('#fb_app_secret_error').html('');
    $('#gl_client_id_error').html('');
    $('#gl_app_secret_error').html('');
    $('#mailchimp_api_key_error').html('');

    $("#velsof_tab_mailchimp").css('color', '#7c7c7c');
    $("#velsof_tab_login").css('color', '#7c7c7c');
    $("#velsof_tab_payment_method").css('color', '#7c7c7c');
    $("#velsof_tab_delivery_method").css('color', '#7c7c7c');
    $("#velsof_tab_cart").css('color', '#7c7c7c');

    var messgeObj = $('#content').find('.bootstrap').find('.alert');
    $(messgeObj).parent().remove();
    var login_error = false;
    var mailchimp_error = false;
    var payment_method_error = false;
    var delivery_method_error = false;
    var cart_error = false;
    var ip_addresses_error = false;
    var SendinBlue_error = false;
    var klaviyo_error = false;
    var general_settings_error = false;

    if($("#supercheckout_fb_login").is(":checked")){
        if($("#velocity_supercheckout_fb_app_id").val().trim() == ''){
            login_error = true;
            $('#fb_app_id_error').html(fb_app_id_error);
        }
        if($("#velocity_supercheckout_fb_app_secret").val().trim() == ''){
            login_error = true;
            $('#fb_app_secret_error').html(fb_app_secret_error);
        }

    }
    
    /* CODE ADDED BY ANSHUL FOR VALIDATE THE IPs */
    var testip = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    var ip_values = $('input[name="velocity_supercheckout[ip_addresses]"]').val().trim();
    if (ip_values != '') {
        if (ip_values.indexOf(',') >= 0) {
            var ip_array = ip_values.split(',');
            var i;
            var all_ip_correct = true;
            for (i = 0; i < ip_array.length; i++) {
                var ip = ip_array[i].trim();
                if (!ip.match(testip)) {
                all_ip_correct = false;
            }
            }
            if (!all_ip_correct) {
                ip_addresses_error = true;
                general_settings_error= true;
                $('#ip_addresses_error').html(ip_address_error);

            }
        } else {
            if (!ip_values.match(testip)) {
                ip_addresses_error = true;
                 general_settings_error= true;
                $('#ip_addresses_error').html(ip_address_error);
            }
        }
    }
    
    var check_free_shipping_value = velovalidation.checkAmount($('input[name="velocity_supercheckout[free_shipping_amount]"]'));
    if (check_free_shipping_value != true) {
        general_settings_error = true;
        $('#free_shipping_amount_error').html(check_free_shipping_value);
    } else if ($('input[name="velocity_supercheckout[free_shipping_amount]"]').val().length > 10) {
        general_settings_error = true;
        $('#free_shipping_amount_error').html(big_amount_error);
    }
    
    
    /*CODE ADDED BY ANSHUL FOR VALIDATE THE IPS*/

    if($("#supercheckout_google_login").is(":checked")){

        if($("#velocity_supercheckout_ggl_client_id").val().trim() == ''){
            login_error = true;
            $('#gl_client_id_error').html(ggl_client_id_error);
        }
        if($("#velocity_supercheckout_ggl_app_secret").val().trim() == ''){
            login_error = true;
            $('#gl_app_secret_error').html(ggl_app_secret_error);
        }

    }
    // Start code for GoogleAutoComplete Added by Harish
    var google_api_error = false;
    if($("#supercheckout_google_auto_address").is(":checked")){
        if($("#velocity_supercheckout_google_api_key").val().trim() == ''){
            google_api_error = true;
            $('#google_api_key_error').html(google_api_key_error);
    }
    }
    // End code for GoogleAutoComplete
    if($("#supercheckout_mailchimp_enable").is(":checked")){

        if ($('#mailchimp_selectlist').length == 0) {
            mailchimp_error = true;
            $('#mailchimp_api_key_error').html(mailchimp_api_key_error);
        }
    }
    
    if ($("#supercheckout_SendinBlue_enable").is(":checked")) {

        if ($('#SendinBlue_selectlist').length == 0) {
            SendinBlue_error = true;
            $('#SendinBlue_api_key_error').html(SendinBlue_api_key_error);
        }
    }
    
    if ($("#supercheckout_klaviyo_enable").is(":checked")) {

        if ($('#klaviyo_selectlist').length == 0) {
            klaviyo_error = true;
            $('#klaviyo_api_key_error').html(klaviyo_api_key_error);
        }
    }

    if ($("input[name='velocity_supercheckout[payment_method][display_style]']:checked").val() == 1 || $("input[name='velocity_supercheckout[payment_method][display_style]']:checked").val() == 2) {
        $("input[id^=velocity_supercheckout_payment_method_logo_width_]").each(function(){
           field_id = $(this).attr('id');
           field_id_arr = field_id.split('_');
           if ($(this).val().trim() == '') {
               payment_method_error = true;
               $("#payment_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html(empty_width_error);
           } else if (!isNormalInteger($(this).val().trim()) && $(this).val().trim() != 'auto') {
               payment_method_error = true;
               $("#payment_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html(valid_width_error);
           } else {
               $("#payment_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html('');
           }
        });

        $("input[id^=velocity_supercheckout_payment_method_logo_height_]").each(function(){
           field_id = $(this).attr('id');
           field_id_arr = field_id.split('_');
           if ($(this).val().trim() == '') {
               payment_method_error = true;
               $("#payment_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html(empty_height_error);
           } else if (!isNormalInteger($(this).val().trim()) && $(this).val().trim() != 'auto') {
               payment_method_error = true;
               $("#payment_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html(valid_height_error);
           } else {
               $("#payment_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html('');
           }
        });

    } else {
        $("input[id^=velocity_supercheckout_payment_method_logo_width_]").each(function(){
            field_id = $(this).attr('id');
            field_id_arr = field_id.split('_');
            $("#payment_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html('');
        });

        $("input[id^=velocity_supercheckout_payment_method_logo_height_]").each(function(){
            field_id = $(this).attr('id');
            field_id_arr = field_id.split('_');
            $("#payment_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html('');
        });
    }

    if ($("input[name='velocity_supercheckout[shipping_method][display_style]']:checked").val() == 1 || $("input[name='velocity_supercheckout[shipping_method][display_style]']:checked").val() == 2) {
        $("input[id^=velocity_supercheckout_delivery_method_logo_width_]").each(function(){
           field_id = $(this).attr('id');
           field_id_arr = field_id.split('_');
           if ($(this).val().trim() == '') {
               delivery_method_error = true;
               $("#delivery_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html(empty_width_error);
           } else if (!isNormalInteger($(this).val().trim()) && $(this).val().trim() != 'auto') {
               delivery_method_error = true;
               $("#delivery_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html(valid_width_error);
           } else {
               $("#delivery_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html('');
           }
        });

        $("input[id^=velocity_supercheckout_delivery_method_logo_height_]").each(function(){
           field_id = $(this).attr('id');
           field_id_arr = field_id.split('_');
           if ($(this).val().trim() == '') {
               delivery_method_error = true;
               $("#delivery_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html(empty_height_error);
           } else if (!isNormalInteger($(this).val().trim()) && $(this).val().trim() != 'auto') {
               delivery_method_error = true;
               $("#delivery_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html(valid_height_error);
           } else {
               $("#delivery_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html('');
           }
        });

    } else {
        $("input[id^=velocity_supercheckout_delivery_method_logo_width_]").each(function(){
            field_id = $(this).attr('id');
            field_id_arr = field_id.split('_');
            $("#delivery_method_logo_width_error_" + field_id_arr[field_id_arr.length - 1]).html('');
        });

        $("input[id^=velocity_supercheckout_delivery_method_logo_height_]").each(function(){
            field_id = $(this).attr('id');
            field_id_arr = field_id.split('_');
            $("#delivery_method_logo_height_error_" + field_id_arr[field_id_arr.length - 1]).html('');
        });
    }

    if ($("input[name='velocity_supercheckout[cart_image_size][width]']").val().trim() == '') {
        cart_error = true;
        $("#cart_product_image_size_width_error").html(empty_width_error);
    } else if (!isNormalInteger($("input[name='velocity_supercheckout[cart_image_size][width]']").val().trim())) {
        cart_error = true;
        $("#cart_product_image_size_width_error").html(valid_width_error_product_image);
    } else {
        $("#cart_product_image_size_width_error").html('');
    }

    if ($("input[name='velocity_supercheckout[cart_image_size][height]']").val().trim() == '') {
        cart_error = true;
        $("#cart_product_image_size_height_error").html(empty_height_error);
    } else if (!isNormalInteger($("input[name='velocity_supercheckout[cart_image_size][height]']").val().trim())) {
        cart_error = true;
        $("#cart_product_image_size_height_error").html(valid_height_error_product_image);
    } else {
        $("#cart_product_image_size_height_error").html('');
    }

    if (login_error == true || mailchimp_error == true || payment_method_error == true || delivery_method_error == true || cart_error == true || google_api_error==true || ip_addresses_error == true
            || SendinBlue_error == true || klaviyo_error == true || general_settings_error == true) {
        $('#velsof_supercheckout_container').find('li').removeClass('active');
        if (cart_error) {
            $("#velsof_tab_cart").css('background', 'red');
            $("#velsof_tab_cart").trigger('click');
        }
        if (delivery_method_error) {
            $("#velsof_tab_delivery_method").css('background', 'red');
            $("#velsof_tab_delivery_method").trigger('click');
        }
        if (payment_method_error) {
            $("#velsof_tab_payment_method").css('background', 'red');
            $("#velsof_tab_payment_method").trigger('click');
        }
        if (mailchimp_error || SendinBlue_error || klaviyo_error) {
            $("#velsof_tab_mailchimp").css('background', 'red');
            $("#velsof_tab_mailchimp").trigger('click');
        }
        if (google_api_error) {
            $("#velsof_tab_addresses").css('background', 'red');
            $("#velsof_tab_addresses").trigger('click');
        }
        if (login_error) {
            $("#velsof_tab_login").css('background', 'red');
            $("#velsof_tab_login").trigger('click');
        }
        
        if (general_settings_error) {
            $("#velsof_general_tab").css('background', 'red');
            $("#velsof_general_tab").trigger('click');
        }

        var errorHtml = '<div class="bootstrap supercheckout-message"><div class="alert alert-danger">';
        errorHtml += '<button type="button" class="close" data-dismiss="alert">?</button>';
        errorHtml += request_error;
        errorHtml += '</div></div>';
        $('#velsof_supercheckout_container').before(errorHtml);
        setTimeout(function(){$('#velsof_supercheckout_container .supercheckout-message').remove();}, 5000);

        return false;
    }
    else {
        $('#supercheckout_configuration_form').submit();
    }
}
//function validate_data(){
//    $('span.error').html('');
//    var messgeObj = $('#content').find('.bootstrap').find('.alert');
//    $(messgeObj).parent().remove();
//    var success = '';
//    var errorMsg = '';
//    $.ajax( {
//        type: "POST",
//        url: scp_ajax_action,
//        data: $('#supercheckout_configuration_form').serialize()+'&ajax=true&method=validation',
//        async: false,
//        dataType: 'json',
//        beforeSend: function() {
//            $('#supercheckout_configuration_form').fadeTo('slow', 0.4);
//        },
//        success: function( json ) {
//            if(json['success'] != undefined && json['success'] != null){
//                 $('#supercheckout_configuration_form').submit();
//            }else if(json['error'] != undefined){
//                $('#supercheckout_configuration_form').fadeTo('slow', 1);
//                errorMsg = json['error']['request_error'];
//
//                if(json['error']['fb_login_app_id'] != undefined){
//                   $('#fb_app_id_error').html(json['error']['fb_login_app_id']);
//                }
//                if(json['error']['fb_login_app_secret'] != undefined){
//                   $('#fb_app_secret_error').html(json['error']['fb_login_app_secret']);
//                }
//
//                if(json['error']['gl_login_app_id'] != undefined){
//                   $('#gl_app_id_error').html(json['error']['gl_login_app_id']);
//                }
//                if(json['error']['gl_login_client_id'] != undefined){
//                   $('#gl_client_id_error').html(json['error']['gl_login_client_id']);
//                }
//                if(json['error']['gl_login_app_secret'] != undefined){
//                   $('#gl_app_secret_error').html(json['error']['gl_login_app_secret']);
//                }
//
//                $('#velsof_supercheckout_container').find('li').removeClass('active');
//                $("#velsof_tab_login").trigger('click');
//
//                var errorHtml = '<div class="bootstrap supercheckout-message"><div class="alert alert-danger">';
//                errorHtml += '<button type="button" class="close" data-dismiss="alert">?</button>';
//                errorHtml += errorMsg;
//                errorHtml += '</div></div>';
//                $('#velsof_supercheckout_container').before(errorHtml);
//                setTimeout(function(){$('#velsof_supercheckout_container .supercheckout-message').remove();}, 5000);
//            }
//        }
//    } );
//
//    return success;
//}

function setChangedLanguage(url, e){
    location.href= url+'&velsof_translate_lang='+$(e).val();
}

function getMailChimpList()
{
	var key = $("#supercheckout_mailchimp_key").val();
	var listid = $("#supercheckout_mailchimp_list").val();
	$.ajax({
		type: "POST",
		url: scp_ajax_action,
		data: 'ajax=true&method=getMailChimpList&key='+key,
		dataType: 'json',
		beforeSend: function() {
			$("#supercheckout_list").html('');
			$("#mailchimp_loading").show();
		},
		success: function(json) {
			var html = '';

			if (json == 'false')
				html = "<font color='red'>"+ no_list_msg +"</font>"; 
			else
			{
				html += '<select name="velocity_supercheckout[mailchimp][list]"';
				if (ps_ver == 15)
					html += 'class="selectpicker vss_sc_ver15"';
				html += 'id="mailchimp_selectlist">';

				for (i in json)
				{
					if (listid == json[i]['id'])
						html += '<option value="' + json[i]['id'] + '" selected>' + json[i]['name'] + '</option>';
					else
						html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
				}
				html += '</select>';
			}
			$("#mailchimp_loading").hide();
			$("#supercheckout_list").html(html);
			$('select.vss_sc_ver15#mailchimp_selectlist').selectpicker();
		}
	});
}

function getSendinBlueList()
{
    var key = $("#supercheckout_SendinBlue_key").val().trim();
    var listid = $("#supercheckout_SendinBlue_list").val();
    if (key != '') {
    $.ajax({
        type: "POST",
        url: scp_ajax_action,
        data: 'ajax=true&method=getSendinBlueList&key=' + key,
        dataType: 'json',
        beforeSend: function () {
            $("#supercheckout_list").html('');
            $("#SendinBlue_loading").show();
        },
        success: function (json) {
            var html = '';

            if (json == 'false')
                html = "<font color='red'>" + no_list_msg + "</font>";
            else
            {
                html += '<select name="velocity_supercheckout[SendinBlue][list]"';
                if (ps_ver == 15)
                    html += 'class="selectpicker vss_sc_ver15"';
                html += 'id="SendinBlue_selectlist">';

                for (i in json)
                {
                    if (listid == json[i]['id'])
                        html += '<option value="' + json[i]['id'] + '" selected>' + json[i]['name'] + '</option>';
                    else
                        html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
                }
                html += '</select>';
            }
            $("#SendinBlue_loading").hide();
            $("#SendinBlue_supercheckout_list").html(html);
            $('select.vss_sc_ver15#SendinBlue_selectlist').selectpicker();
        }
    });
   } else {
       var html = '';
       html = "<font color='red'>" + no_list_msg + "</font>";
       $("#SendinBlue_supercheckout_list").html(html);
   }
}

function getklaviyoList()
{
    var key = $("#supercheckout_klaviyo_key").val().trim();
    var listid = $("#supercheckout_klaviyo_list").val();
    if (key != '') {
    $.ajax({
        type: "POST",
        url: scp_ajax_action,
        data: 'ajax=true&method=getklaviyoList&key=' + key,
        dataType: 'json',
        beforeSend: function () {
            $("#supercheckout_list").html('');
            $("#klaviyo_loading").show();
        },
        success: function (json) {
            var html = '';

            if (json == 'false')
                html = "<font color='red'>" + no_list_msg + "</font>";
            else
            {
                html += '<select name="velocity_supercheckout[klaviyo][list]"';
                if (ps_ver == 15)
                    html += 'class="selectpicker vss_sc_ver15"';
                html += 'id="klaviyo_selectlist">';

                for (i in json)
                {
                    if (listid == json[i]['value'])
                        html += '<option value="' + json[i]['value'] + '" selected>' + json[i]['label'] + '</option>';
                    else
                        html += '<option value="' + json[i]['value'] + '">' + json[i]['label'] + '</option>';
                }
                html += '</select>';
            }
            $("#klaviyo_loading").hide();
            $("#klaviyo_supercheckout_list").html(html);
            $('select.vss_sc_ver15#klaviyo_selectlist').selectpicker();
        }
    });
    } else {
       var html = '';
       html = "<font color='red'>" + no_list_msg + "</font>";
       $("#klaviyo_supercheckout_list").html(html);
   }
}

function configurationAccordian(id)
{
    if (id == 'facebook')
    {
        $("#facebook_acc").show();
        $("#google_acc").hide();
        $("#loginizer_link").hide();
	$(window).scrollTop($('#facebook_acc').offset().top);
    }
    else if (id == 'google')
    {
        $("#google_acc").show();
        $("#facebook_acc").hide();
        $("#loginizer_link").hide();
	$(window).scrollTop($('#google_acc').offset().top);
    }
    else if(id== 'google_auto_address'){
        $("#google_auto_address_acc").show();
	$(window).scrollTop($('#google_auto_address_acc').offset().top);
    }
$("#"+id+"_accordian").accordion({
      animated: 'bounceslide',
      autoHeight: false,
      collapsible: true,
      event: 'click',
      active: false,
      animate: 100
    });
}
function bg_changer(col)
    {
        color = "#"+col;

 document.getElementById("button_preview").style.backgroundColor= color;
    }

   function border_changer(col)
    {
        color = "#"+col;

 document.getElementById("button_preview").style.borderTopColor= color;
 document.getElementById("button_preview").style.borderRightColor= color;
 document.getElementById("button_preview").style.borderLeftColor= color;
    }
    function border_bottom_changer(col)
    {
        color = "#"+col;

 document.getElementById("button_preview").style.borderBottomColor= color;
    }
       function text_changer(col)
    {
        color = "#"+col;
 document.getElementById("button_preview").style.color= color;
    }

function readPaymentURL(id, imageid){
         $("#"+imageid+"_msg").hide();
		var imgPath = $("#"+imageid+"_file")[0].value;
		var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

		if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
		if (typeof (FileReader) != "undefined") {

            var image_holder = $("#"+imageid);

            image_holder.empty();

            var reader = new FileReader();
            reader.onload = function (e) {

                $('#'+imageid).attr('src', e.target.result);
            }
            image_holder.show();
            reader.readAsDataURL($("#"+imageid+"_file")[0].files[0]);
			$("#payment_image_title_"+id).val("paymethod"+id+extn);
        }
		}
		else
		{	$("#"+imageid+"_msg").css("color", "red");
			$("#"+imageid+"_msg").show();
		}

        }

function removeFile(id)
{
	if (confirm(remove_cnfrm_msg) == true)
	{
	$.ajax({
		type: "POST",
		url: scp_ajax_action,
		data: 'ajax=true&method=removeFile&id=paymethod'+id,
		dataType: 'json',
		beforeSend: function() {
		},
		success: function(json) {
			$("#payment_image_title_"+id).val("");
			$('#payment-img-'+id).attr('src', module_path+'views/img/admin/no-image.jpg');
		}

	});
	}
}

function readDeliveryURL(id, imageid){

         $("#"+imageid+"_msg").hide();
		var imgPath = $("#"+imageid+"_file")[0].value;

		var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

		if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
		if (typeof (FileReader) != "undefined") {

            var image_holder = $("#"+imageid);

            image_holder.empty();

            var reader = new FileReader();
            reader.onload = function (e) {

                $('#'+imageid).attr('src', e.target.result);
            }
            image_holder.show();
            reader.readAsDataURL($("#"+imageid+"_file")[0].files[0]);
			$("#delivery_image_title_"+id).val("deliverymethod"+id+extn);
        }
		}
		else
		{	$("#"+imageid+"_msg").css("color", "red");
			$("#"+imageid+"_msg").show();
		}

        }


function removeDeliveryFile(id)
{
	if (confirm(remove_cnfrm_msg) == true)
	{
	$.ajax({
		type: "POST",
		url: scp_ajax_action,
		data: 'ajax=true&method=removeFile&id=deliverymethod' + id,
		dataType: 'json',
		beforeSend: function() {
		},
		success: function(json) {
			$("#delivery_image_title_" + id).val("");
			$('#delivery-img-' + id).attr('src', module_path + 'views/img/admin/no-image.jpg');
		}

	});
	}
}

function addNewCustomFieldPopup()
{
    $('#modal_add_new_custom_field_form :input[type="text"]').val('');
    $('#modal_add_new_custom_field_form textarea').val('');
    $("#modal_add_new_custom_field_form select option:selected").prop("selected", false);
    $("#modal_add_new_custom_field_form select option:first").prop("selected", "selected");
    $('#modal_add_new_custom_field_form').modal({'show': true, 'backdrop': 'static'});
}

function closeModalPopup(modal)
{
    $('#' + modal + '_load').hide();
    $('#' + modal).modal('hide');
    $('#' + modal + '_progress').hide();
}

function changeLanguageBox(objE, elementToChange)
{
    var idLanguage = objE.value;
    $(".supercheckout_" + elementToChange).addClass("hidden_custom");
    $("#" + elementToChange + "_language_" + idLanguage).removeClass("hidden_custom");
}

function checkFieldType(objE)
{
    var boxValue = objE.value;
    // If options are required
    if(boxValue == "selectbox" || boxValue == "radio" || boxValue == "checkbox")
    {
        // Display textarea to accept option values and labels
        $("#field_options").removeClass("hidden_custom");
        $('select[name="custom_fields[validation_type]"]').prop('disabled', 'disabled');
    }
    else
    {
        $("#field_options").addClass("hidden_custom");
        $('select[name="custom_fields[validation_type]"]').prop('disabled', false);
    }
}

// Edit form
function checkFieldTypeEdit(objE)
{
    var boxValue = objE.value;
    // If options are required
    if(boxValue == "selectbox" || boxValue == "radio" || boxValue == "checkbox")
    {
        // Display textarea to accept option values and labels
        $("#edit_field_options").removeClass("hidden_custom");
        $('select[name="edit_custom_fields[validation_type]"]').prop('disabled', 'disabled');
    }
    else
    {
        $("#edit_field_options").addClass("hidden_custom");
        $('select[name="edit_custom_fields[validation_type]"]').prop('disabled', false);
    }
}

function displayEditCustomFieldPopup(idCustomField)
{
//    $('#modal_edit_custom_field_form').html("");
    // Send ajax request to save the data
    $.ajax( {
        type: "POST",
        url: scp_ajax_action,
        data: $('#supercheckout_configuration_form').serialize()+'&ajax=true&custom_fields_action=displayEditCustomFieldForm&id=' + idCustomField,
        async: false,
        dataType: 'json',
        success: function(json) {
            $('#modal_edit_custom_field_form').html(json.response);
            $('#modal_edit_custom_field_form').modal({'show': true, 'backdrop': 'static'});
        }
    });
}

function submitEditForm()
{
    $("#loader_edit_form").removeClass("hidden_custom");
    var errorOccured = validateEditForm();
    if(errorOccured != 0)
    {
        $("#loader_edit_form").addClass("hidden_custom");
        return false;
    }
    else
    {
        // Showing the ajax loader
        
        //Send ajax request to save the data
        $.ajax( {
            type: "POST",
            url: scp_ajax_action,
            data: $('#supercheckout_configuration_form').serialize()+'&ajax=true&custom_fields_action=editCustomFieldForm',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                closeModalPopup("modal_edit_custom_field_form");
            },
            success: function(json) {
                // Adding a row in table
                var requiredText = '', activeText = '';
                /* Start - Modified by Raghu on 18-Aug-2017 for fixing Translations issues while editing a custom field */
                if(json.response.required == '1')
                {
                    requiredText = yes_text;
                }
                else
                {
                    requiredText = no_text;
                }
                if(json.response.active == '1')
                {
                    activeText = yes_text;
                }
                else
                {
                    activeText = no_text;
                }
                /* End - Modified by Raghu on 18-Aug-2017 for fixing Translations issues while editing a custom field */
                var findRowCount = $("#tr_pure_table_"+json.response.id_velsof_supercheckout_custom_fields).children("td:first").text();
                var tableRow = '<tr class="row_changed" id="tr_pure_table_'+json.response.id_velsof_supercheckout_custom_fields+'">';
                tableRow += '<td>' + findRowCount + '</td>';
                tableRow += '<td class="width_25"><div class="div_250px_ellipsis">' + json.response.field_label + '</div></td>';
                /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue */
                tableRow += '<td>' + getCustomFieldsTypeTranslatedText(json.response.type) + '</td>';
//                tableRow += '<td>' + json.response.type + '</td>';
                /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue */
                /* Start - Code Modified by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
                tableRow += '<td>' + getCustomFieldsBlockTranslatedText(json.response.position) + '</td>';
//                tableRow += '<td class="transform_capitalize">' + json.response.position.split('_').join(' ') + '</td>';
                /* End - Code Modified by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
                tableRow += '<td>' + requiredText + '</td>';
                tableRow += '<td>' + activeText + '</td>';
                tableRow += '<td class="center" style="padding: 12px;">';
                tableRow += '<a style="margin-top: -26px;" href="javascript://" onclick="displayEditCustomFieldPopup(' + json.response.id_velsof_supercheckout_custom_fields + ')" type="11" class="velsof-glyphicons2 glyphicons pencil"><i data-toggle="tooltip" data-placement="top" data-original-title="Edit this custom field"></i></a>';
                tableRow += '<a style="margin-top: -26px;" href="javascript://" onclick="deleteCustomFieldRow(' + json.response.id_velsof_supercheckout_custom_fields + ')" type="11" class="velsof-glyphicons2 glyphicons bin" onclick=""><i data-toggle="tooltip" data-placement="top" data-original-title="Delete this custom field."></i></a>';
                tableRow += '</td>';
                tableRow += '</tr>';

                // Removing the success green color from all the previous edited/added rows
                $("#tbody_custom_fields_data").children().removeClass("row_changed");
                $("#tr_pure_table_"+json.response.id_velsof_supercheckout_custom_fields).replaceWith(tableRow);
                $("#div_custom_fields_success").removeClass("hidden_custom");
                $("#loader_edit_form").addClass("hidden_custom");
                setTimeout(function(){
                    $("#tbody_custom_fields_data").children().removeClass("row_changed", 1000);
                }, 5000);
            }
        });
        // Resetting all the data of element having id as `modal_edit_custom_field_form`
//        $('#modal_edit_custom_field_form').html("");
    }
}

/*
 * Function Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field'
 * @param {type} block_name
 * @returns {String|cart_block_txt}
 */
function getCustomFieldsBlockTranslatedText(block_name)
{
    var final_txt = '';
    switch(block_name) {
        case 'customer_registration_block':
            final_txt = customer_registration_block_txt;
            break;
        case 'shipping_address_form':
            final_txt = shipping_address_form_txt;
            break;
        case 'payment_address_form':
            final_txt = payment_address_form_txt;
            break;
        case 'cart_block':
            final_txt = cart_block_txt;
            break;
    }
    return final_txt;
}

/*
 * Function Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue
 * @param {type} block_name
 * @returns {String|cart_block_txt}
 */
function getCustomFieldsTypeTranslatedText(type_value)
{
    var final_txt = '';
    switch(type_value) {
        case 'textbox':
            final_txt = text_box_txt;
            break;
        case 'selectbox':
            final_txt = select_box_txt;
            break;
        case 'textarea':
            final_txt = text_area_txt;
            break;
        case 'radio':
            final_txt = radio_button_txt;
            break;
        case 'checkbox':
            final_txt = check_boxes_txt;
            break;
        /* Start: Code added by Anshul for adding date and file type */
        case 'date':
            final_txt = date;
            break;
        case 'file':
            final_txt = file;
            break;
        /* End: Code added by Anshul for adding date and file type */
    }
    return final_txt;
}

/**
 * This functin submits the form if all the values are valid
 * @returns {undefined}
 */
function submitForm()
{
    $("#loader_add_form").removeClass("hidden_custom");
    var errorOccured = validateForm();

    if(errorOccured != 0)
    {
        $("#loader_add_form").addClass("hidden_custom");
        return false;
    }
    else
    {
        // Showing the ajax loader
        
        // Send ajax request to save the data
        $.ajax( {
            type: "POST",
            url: scp_ajax_action,
            data: $('#supercheckout_configuration_form').serialize()+'&ajax=true&custom_fields_action=addCustomFieldForm',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                closeModalPopup("modal_add_new_custom_field_form");
                
            },
            success: function(json) {
                // Adding a row in table
                var requiredText = '', activeText = '';
                /* Start - Modified by Raghu on 18-Aug-2017 for fixing Translations issues while adding a custom field */
                if(json.response.required == '1')
                {
                    requiredText = yes_text;
                }
                else
                {
                    requiredText = no_text;
                }
                if(json.response.active == '1')
                {
                    activeText = yes_text;
                }
                else
                {
                    activeText = no_text;
                }
                /* End - Modified by Raghu on 18-Aug-2017 for fixing Translations issues while adding a custom field */
                var rowCount = $('#table_custom_fields_data > tbody > tr').length;

                var tableRow = '<tr class="pure-table-striped row_changed" id="tr_pure_table_'+json.response.id_velsof_supercheckout_custom_fields+'">';
                tableRow += '<td>' + rowCount + '</td>';
                tableRow += '<td class="width_25"><div class="div_250px_ellipsis">' + json.response.field_label + '</div></td>';
                /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue */
                tableRow += '<td>' + getCustomFieldsTypeTranslatedText(json.response.type) + '</td>';
//                tableRow += '<td>' + json.response.type + '</td>';
                /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue */
                /* Start - Code Modified by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
                tableRow += '<td>' + getCustomFieldsBlockTranslatedText(json.response.position) + '</td>';
//                tableRow += '<td class="transform_capitalize">' + json.response.position.split('_').join(' ') + '</td>';
                /* End - Code Modified by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
                tableRow += '<td>' + requiredText + '</td>';
                tableRow += '<td>' + activeText + '</td>';
                tableRow += '<td class="center" style="padding: 12px;">';
                tableRow += '<a style="margin-top: -26px;" href="javascript://" onclick="displayEditCustomFieldPopup(' + json.response.id_velsof_supercheckout_custom_fields + ')" type="11" class="velsof-glyphicons2 glyphicons pencil"><i data-toggle="tooltip" data-placement="top" data-original-title="Edit this custom field"></i></a>';
                tableRow += '<a style="margin-top: -26px;" href="javascript://" onclick="deleteCustomFieldRow(' + json.response.id_velsof_supercheckout_custom_fields + ')" type="11" class="velsof-glyphicons2 glyphicons bin" onclick=""><i data-toggle="tooltip" data-placement="top" data-original-title="Delete this custom field."></i></a>';
                tableRow += '</td>';
                tableRow += '</tr>';

                // Removing the success green color from all the previous edited/added rows
                $("#tbody_custom_fields_data").children().removeClass("row_changed");
                $("#tr_custom_fields_add_new").before(tableRow);
                $("#div_custom_fields_success").removeClass("hidden_custom");
                $("#loader_add_form").addClass("hidden_custom");
                setTimeout(function(){
                    $("#tbody_custom_fields_data").children().removeClass("row_changed", 1000);
                }, 5000);
            }
        });
    }
}

/**
 * This function is used to validate the values
 * @returns {undefined}
 */
function validateForm()
{
    var error = 0, errorFieldOptions = 0;
    var errorMessageFieldOptions;
    var elemType = $("#supercheckout_custom_field_type");
    var optionBoxes = $(".supercheckout_field_options");

    var elemLabelBoxes = $(".supercheckout_field_label");
    var boxCheckerLabels = 0;
    elemLabelBoxes.each(function(index)
    {
        if($(this).val() != "")
        {
            boxCheckerLabels = 1;
        }
    });

    // If nothing provided
    if(boxCheckerLabels == 0)
    {
        error = 1;
        errorMessageFieldOptions = canNotLeaveAllBoxesEmpty;
        $("#error_message_field_label").html(errorMessageFieldOptions);
        $("#error_message_field_label").removeClass("hidden_custom");
    }
    else
    {
        $("#error_message_field_label").addClass("hidden_custom");
    }

    // Checking if selectbox or radio or checkbox is selected
    if(elemType.val() == 'selectbox' || elemType.val() == "radio" || elemType.val() == "checkbox")
    {
        // Loopiong through each value
        var boxChecker = 0;
        optionBoxes.each(function(index) {
            if($(this).val() != "")
            {
                boxChecker = 1;
                // Splitting on \n
                var lines = $(this).val().split('\n');
                for(var i = 0; i < lines.length; i++){
                    var alphanumeric = lines[i].split('|');
                    if(lines[i] == '')
                    {
                        continue;
                    }
                    // If there are more than one | present in a line
                    if(alphanumeric.length != 2)
                    {
                        error = 1;
                        errorFieldOptions = 1;
                    }
                    else
                    {
                        for(var j = 0; j < alphanumeric.length; j++)
                        {
                            if(j == 0)
                            {
                                // Not allowing the space in value side
                                var expression = /^[a-zA-Z0-9]+$/;
                            }
                            else
                            {
                                var expression = /^[a-zA-Z0-9 -_/]+$/;
                            }
                            if(!expression.test(alphanumeric[j]))
                            {
                                error = 1;
                                errorFieldOptions = 1;
                            }
                        }
                    }
                }
            }
        });

        // If nothing provided
        if(boxChecker == 0)
        {
            error = 1;
            errorMessageFieldOptions = canNotLeaveAllBoxesEmpty;
            $("#error_message_field_options").html(errorMessageFieldOptions);
            $("#error_message_field_options").removeClass("hidden_custom");
        }
        else
        {
            if(errorFieldOptions == 1)
            {
                errorMessageFieldOptions = pleaseProvideInValidFormat;
                $("#error_message_field_options").html(errorMessageFieldOptions);
                $("#error_message_field_options").removeClass("hidden_custom");
            }
            else
            {
                $("#error_message_field_options").addClass("hidden_custom");
            }
        }
    }

    return error;
}

/**
 * This function is used to validate the values
 * @returns {undefined}
 */
function validateEditForm()
{
    var error = 0, errorFieldOptions = 0;
    var errorMessageFieldOptions;
    var elemType = $("#supercheckout_edit_custom_field_type");
    var optionBoxes = $(".supercheckout_edit_field_options");

    var elemLabelBoxes = $(".supercheckout_edit_field_label");
    var boxCheckerLabels = 0;
    elemLabelBoxes.each(function(index)
    {
        if($(this).val() != "")
        {
            boxCheckerLabels = 1;
        }
    });

    // If nothing provided
    if(boxCheckerLabels == 0)
    {
        error = 1;
        errorMessageFieldOptions = canNotLeaveAllBoxesEmpty;
        $("#error_message_edit_field_label").html(errorMessageFieldOptions);
        $("#error_message_edit_field_label").removeClass("hidden_custom");
    }
    else
    {
        $("#error_message_edit_field_label").addClass("hidden_custom");
    }

    // Checking if selectbox or radio or checkbox is selected
    if(elemType.val() == 'selectbox' || elemType.val() == "radio" || elemType.val() == "checkbox")
    {
        // Loopiong through each value
        var boxChecker = 0;
        optionBoxes.each(function(index) {
            if($(this).val() != "")
            {
                boxChecker = 1;
                // Splitting on \n
                var lines = $(this).val().split('\n');
                for(var i = 0; i < lines.length; i++){
                    var alphanumeric = lines[i].split('|');
                    if(lines[i] == '')
                    {
                        continue;
                    }
                    // If there are more than one | present in a line
                    if(alphanumeric.length != 2)
                    {
                        error = 1;
                        errorFieldOptions = 1;
                    }
                    else
                    {
                        for(var j = 0; j < alphanumeric.length; j++)
                        {
                            if(j == 0)
                            {
                                var expression = /^[a-zA-Z0-9]+$/;
                            }
                            else
                            {
                                var expression = /^[a-zA-Z0-9 -_/]+$/;
                            }
                            if(!expression.test(alphanumeric[j]))
                            {
                                error = 1;
                                errorFieldOptions = 1;
                            }
                        }
                    }
                }
            }
        });

        // If nothing provided
        if(boxChecker == 0)
        {
            error = 1;
            errorMessageFieldOptions = canNotLeaveAllBoxesEmpty;
            $("#error_message_edit_field_options").html(errorMessageFieldOptions);
            $("#error_message_edit_field_options").removeClass("hidden_custom");
        }
        else
        {
            if(errorFieldOptions == 1)
            {
                errorMessageFieldOptions = pleaseProvideInValidFormat;
                $("#error_message_edit_field_options").html(errorMessageFieldOptions);
                $("#error_message_edit_field_options").removeClass("hidden_custom");
            }
            else
            {
                $("#error_message_edit_field_options").addClass("hidden_custom");
            }
        }
    }

    return error;
}

function deleteCustomFieldRow(idCustomField)
{
    var canDelete = confirm(areYouSureToDelete);
    if(canDelete == true)
    {
        // Send ajax request to save the data
        $.ajax( {
            type: "POST",
            url: scp_ajax_action,
            data: '&ajax=true&custom_fields_action=deleteCustomFieldRow&id_velsof_supercheckout_custom_fields='+idCustomField,
            async: false,
            dataType: 'json',
            success: function(json) {
                $("#tr_pure_table_"+idCustomField).addClass("hidden_custom");
                $("#div_custom_fields_success").removeClass("hidden_custom");
            }
        });
    }
}

//<editor-fold defaultstate="collapsed" desc="GDPR Changes">
function addNewGDPRPrivacyPopup()
{
    $('#modal_add_new_gdpr_privacy_form').modal({'show': true, 'backdrop': 'static'});
    $('#modal_add_new_gdpr_privacy_form input:not(:radio)').val('');
    $('select[name="languages').val($('select[name="languages"] option:first').val());
    $('#custom_fields[required]_off').attr('checked', 'checked');
}

function submitGDPRSettingForm()
{
    var errorOccured = validateGDPRSettingForm();
    if (errorOccured != 0)
    {
        return false;
    }
    else
    {
        // Showing the ajax loader
        $("#loader_add_policy_form").removeClass("hidden_custom");
        $("body").addClass("loading");
        // Send ajax request to save the data
        $.ajax({
            type: "POST",
            url: scp_ajax_action,
            data: $('#modal_add_new_gdpr_privacy_form :input').serialize() + '&ajax=true&gdpr_privacy_action=addNewPrivacyPolicy',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                closeModalPopup("modal_add_new_gdpr_privacy_form");
            },
            success: function (json) {
                // Adding a row in table
                var requiredText = '', activeText = '';
                if (json.response.is_manadatory == '1')
                {
                    requiredText = yes_text;
                }
                else
                {
                    requiredText = no_text;
                }
                if (json.response.status == '1')
                {
                    activeText = yes_text;
                }
                else
                {
                    activeText = no_text;
                }

                var rowCount = $('#table_gdpr_policy_data > tbody > tr').length;

                var tableRow = '<tr class="pure-table-striped row_changed" id="tr_policy_table_' + json.response.policy_id + '">';
                tableRow += '<td>' + rowCount + '</td>';
                tableRow += '<td class="width_25"><div class="div_250px_ellipsis">' + json.response.description + '</div></td>';
                tableRow += '<td>' + requiredText + '</td>';
                tableRow += '<td>' + activeText + '</td>';
                tableRow += '<td class="center" style="padding: 12px;">';
                tableRow += '<div style="display:inline-block;float:left;"><a style="padding:5px" href="javascript://" onclick="displayEditGDPRPolicyPopup(' + json.response.policy_id + ')" type="11" class="velsof-glyphicons2 glyphicons pencil"><i data-toggle="tooltip" data-placement="top" data-original-title="Edit this policy"></i></a></div>';
                tableRow += '<div style="display:inline-block;"><a style="padding:5px" href="javascript://" onclick="deleteGDPRPolicyRow(' + json.response.policy_id + ')" type="11" class="velsof-glyphicons2 glyphicons bin" onclick=""><i data-toggle="tooltip" data-placement="top" data-original-title="Delete this policy."></i></a></div>';
                tableRow += '</td>';
                tableRow += '</tr>';

                // Removing the success green color from all the previous edited/added rows
                $("#tbody_gdpr_policy_data").children().removeClass("row_changed");
                $("#tr_gdpr_policy_add_new").before(tableRow);
                $("#loader_add_policy_form").addClass("hidden_custom");
                setTimeout(function () {
                    $("#tbody_gdpr_policy_data").children().removeClass("row_changed", 1000);
                }, 5000);
                $("body").removeClass("loading");
                $("#div_supercheckout_success").show();
                setTimeout(function () {
                    $("#div_supercheckout_success").hide();
                }, 10000);
            }
        });
    }
}

function validateGDPRSettingForm()
{
    var error = 0;
    var errorMessageFieldOptions;
    var elemLabelBoxes = $(".supercheckout_gdpr_policy_label");
    var boxCheckerLabels = 0;
    elemLabelBoxes.each(function (index)
    {
        if ($(this).val() == "")
        {
            boxCheckerLabels = 1;
        }
    });

    // If nothing provided
    if (boxCheckerLabels == 1)
    {
        error = 1;
        errorMessageFieldOptions = canNotLeaveBoxesEmpty;
        $("#error_message_gdpr_policy_label").html(errorMessageFieldOptions);
        $("#error_message_gdpr_policy_label").removeClass("hidden_custom");
    }
    else
    {
        $("#error_message_gdpr_policy_label").addClass("hidden_custom");
    }

    return error;
}

function validateEditGDPRSettingForm()
{
    var error = 0;
    var errorMessageFieldOptions;
    var elemLabelBoxes = $(".supercheckout_edit_gdpr_policy_label");
    var boxCheckerLabels = 0;
    elemLabelBoxes.each(function (index)
    {
        if ($(this).val() == "")
        {
            boxCheckerLabels = 1;
        }
    });

    // If nothing provided
    if (boxCheckerLabels == 1)
    {
        error = 1;
        errorMessageFieldOptions = canNotLeaveBoxesEmpty;
        $("#error_message_gdpr_policy_label_edit").html(errorMessageFieldOptions);
        $("#error_message_gdpr_policy_label_edit").removeClass("hidden_custom");
    }
    else
    {
        $("#error_message_gdpr_policy_label_edit").addClass("hidden_custom");
    }

    return error;
}

function deleteGDPRPolicyRow(policy_id)
{
    var canDelete = confirm(areYouSureToDelete);
    if (canDelete == true)
    {
        $("body").addClass("loading");
        $.ajax({
            type: "POST",
            url: scp_ajax_action,
            data: '&ajax=true&gdpr_privacy_action=deletePrivacyPolicyRow&policy_id=' + policy_id,
            async: true,
            dataType: 'json',
            success: function (json) {
                $("#tr_policy_table_" + policy_id).addClass("hidden_custom");
                $("#div_custom_fields_success").removeClass("hidden_custom");
                $("body").removeClass("loading");
                $("#div_supercheckout_success").show();
                setTimeout(function () {
                    $("#div_supercheckout_success").hide();
                }, 10000);
            }
        });
    }
}

function displayEditGDPRPolicyPopup(policy_id)
{
    $("body").addClass("loading");
    $.ajax({
        type: "POST",
        url: scp_ajax_action,
        data: 'ajax=true&gdpr_privacy_action=displayEditGDPRPolicyForm&id=' + policy_id,
        async: true,
        dataType: 'json',
        success: function (json) {
            $('#modal_edit_gdpr_policy_form').html(json.response);
            $('#modal_edit_gdpr_policy_form').modal({'show': true, 'backdrop': 'static'});
            $("body").removeClass("loading");
        }
    });
}

function submitEditGDPRSettingForm()
{
    var errorOccured = validateEditGDPRSettingForm();
    if (errorOccured != 0)
    {
        return false;
    }
    else
    {
        // Showing the ajax loader
        $("#loader_edit_policy_form").removeClass("hidden_custom");
        //Send ajax request to save the data
        $.ajax({
            type: "POST",
            url: scp_ajax_action,
            data: $('#modal_edit_gdpr_policy_form :input').serialize() + '&ajax=true&gdpr_privacy_action=editPrivacyPolicyForm',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                $("body").addClass("loading");
                closeModalPopup("modal_edit_gdpr_policy_form");
            },
            success: function (json) {
                // Adding a row in table
                var requiredText = '', activeText = '';
                if (json.response.is_manadatory == '1')
                {
                    requiredText = yes_text;
                }
                else
                {
                    requiredText = no_text;
                }
                if (json.response.status == '1')
                {
                    activeText = yes_text;
                }
                else
                {
                    activeText = no_text;
                }
                var findRowCount = $("#tr_policy_table_" + json.response.policy_id).children("td:first").text();
                var tableRow = '<tr class="row_changed" id="tr_policy_table_' + json.response.policy_id + '">';
                tableRow += '<td>' + findRowCount + '</td>';
                tableRow += '<td class="width_25"><div class="div_250px_ellipsis">' + json.response.description + '</div></td>';
                tableRow += '<td>' + requiredText + '</td>';
                tableRow += '<td>' + activeText + '</td>';
                tableRow += '<td class="center" style="padding: 12px;">';
                tableRow += '<div style="display:inline-block;float:left;"><a style="padding:5px" href="javascript://" onclick="displayEditGDPRPolicyPopup(' + json.response.policy_id + ')" type="11" class="velsof-glyphicons2 glyphicons pencil"><i data-toggle="tooltip" data-placement="top" data-original-title="Edit this policy"></i></a></div>';
                tableRow += '<div style="display:inline-block;"><a style="padding:5px" href="javascript://" onclick="deleteGDPRPolicyRow(' + json.response.policy_id + ')" type="11" class="velsof-glyphicons2 glyphicons bin" onclick=""><i data-toggle="tooltip" data-placement="top" data-original-title="Delete this policy."></i></a></div>';
                tableRow += '</td>';
                tableRow += '</tr>';

                // Removing the success green color from all the previous edited/added rows
                $("#tbody_gdpr_policy_data").children().removeClass("row_changed");
                $("#tr_policy_table_" + json.response.policy_id).replaceWith(tableRow);
                $("#div_custom_fields_success").removeClass("hidden_custom");
                $("#loader_edit_policy_form").addClass("hidden_custom");
                setTimeout(function () {
                    $("#tbody_gdpr_policy_data").children().removeClass("row_changed", 1000);
                }, 5000);
                $("body").removeClass("loading");
                $("#div_supercheckout_success").show();
                setTimeout(function () {
                    $("#div_supercheckout_success").hide();
                }, 10000);
            }
        });
    }
}


function getAcceptedCustomerPolicy()
{
    var searchData = $('input[name="supercheckout_gdpr_filter"]').val();
    if (searchData != '') {
        $("#error_message_supercheckout_gdpr_filter").removeClass("hidden_custom").addClass('hidden_custom');
        $("body").addClass("loading");
        $('#rm_loader').show();
        $.ajax({
            type: "POST",
            url: scp_ajax_action,
            data: {"ajax": true, "gdpr_privacy_action": "displayFilteredGDPRCustomerData", "searchData": searchData},
            async: true,
            dataType: 'json',
            success: function (json) {
                $('#supercheckout_customer_consent').html(json.response);
                $('#rm_loader').hide();
                $("body").removeClass("loading");
            }
        });
    } else {
        $("#error_message_supercheckout_gdpr_filter").removeClass("hidden_custom");
        return false;
    }
}

function resetCustomerPolicy() {
    $("#error_message_supercheckout_gdpr_filter").removeClass("hidden_custom").addClass('hidden_custom');
    var empty_html = '<div class="rm_no_data"><span>' + noDataFound + '</span></div>';
    $('input[name="supercheckout_gdpr_filter"]').val('');
    $('#supercheckout_customer_consent').html(empty_html);
}