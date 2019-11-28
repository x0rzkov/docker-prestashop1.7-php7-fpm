/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @author    PresTeamShop.com <support@presteamshop.com>
 * @copyright 2011-2019 PresTeamShop
 * @license   see file: LICENSE.txt
 * @category  PrestaShop
 * @category  Module
 */

$(function(){
    AppOPC.init();
    OPC_External_Validation.init();
    OPC_Compatibilities.init();

    function toggleIcon(e) {
        $(e.target)
            .parent()
            .prev('.card-header')
            .find(".more-less")
            .toggleClass('fa-pts-angle-down fa-pts-angle-up');
    }
    $('#panel_addresses_customer').on('hidden.bs.collapse', toggleIcon);
    $('#panel_addresses_customer').on('shown.bs.collapse', toggleIcon);

    //$('#checkbox_create_account_guest').trigger('click');
});

var AppOPC = {
    $opc: false,
    $opc_step_one: false,
    $opc_step_two: false,
    $opc_step_three: false,
    $opc_step_review: false,
    initialized: false,
    load_offer: true,
    is_valid_opc: false,
    is_valid_form_login: false,
    is_valid_form_customer: false,
    is_valid_form_address_delivery: false,
    is_valid_form_address_invoice: false,
    jqOPC: typeof $jqOPC === typeof undefined ? $ : $jqOPC,
    m4gpdr: false,
    init: function(){
        $(document).on('click', 'button#btn-copy_invoice_address_to_delivery', AppOPC.copyInvoiceAddressToDelivery);

        prestashop.on('updatedDeliveryForm', function(event) {
            $('.delivery-option.selected .carrier-extra-content').show()
        });

        AppOPC.initialized = true;
        AppOPC.$opc = $('#onepagecheckoutps');
        AppOPC.$opc_step_one = $('#onepagecheckoutps div#onepagecheckoutps_step_one');
        AppOPC.$opc_step_two = $('#onepagecheckoutps div#onepagecheckoutps_step_two');
        AppOPC.$opc_step_three = $('#onepagecheckoutps div#onepagecheckoutps_step_three');
        AppOPC.$opc_step_review = $('#onepagecheckoutps div#onepagecheckoutps_step_review');

        if (typeof OnePageCheckoutPS !== typeof undefined)
        {
            if (typeof jeoquery !== typeof undefined) {
                jeoquery.defaultCountryCode = OnePageCheckoutPS.iso_code_country_delivery_default;
                jeoquery.defaultLanguage = prestashop.language.iso_code;
                jeoquery.defaultData.lang = prestashop.language.iso_code;
            }

            //Se anade funcionalidad para eliminar espacios vacios de principio y fin de la cadena
            AppOPC.$opc_step_one.find('input:text, input[type="email"]').on('blur', function(e) {
                var value       = $(e.currentTarget).val();
                var new_value   = value.trim();

                $(e.currentTarget).val(new_value);
            });

            //launch validate fields
            if (typeof $.formUtils !== typeof undefined && typeof $.validate !== typeof undefined){
                $.formUtils.loadModules('prestashop.js, security.js, brazil.js', OnePageCheckoutPS.ONEPAGECHECKOUTPS_DIR + 'views/js/lib/form-validator/');
                $.validate({
                    form: 'div#onepagecheckoutps #form_login, div#onepagecheckoutps #form_customer, div#onepagecheckoutps #form_address_delivery, div#onepagecheckoutps #form_address_invoice',
                    validateHiddenInputs: true,
                    language : messageValidate,
                    onError: function ($form) {
                        if ($form.attr('id') == 'form_login') {
                            AppOPC.is_valid_form_login = false;
                        } else if ($form.attr('id') == 'form_customer') {
                            AppOPC.is_valid_form_customer = false;
                        } else if ($form.attr('id') == 'form_address_delivery') {
                            AppOPC.is_valid_form_address_delivery = false;
                        } else if ($form.attr('id') == 'form_address_invoice') {
                            AppOPC.is_valid_form_address_invoice = false;
                        }
                    },
                    onSuccess: function ($form) {
                        if ($form.attr('id') == 'form_login') {
                            AppOPC.is_valid_form_login = true;
                        } else if ($form.attr('id') == 'form_customer') {
                            AppOPC.is_valid_form_customer = true;
                        } else if ($form.attr('id') == 'form_address_delivery') {
                            AppOPC.is_valid_form_address_delivery = true;
                        } else if ($form.attr('id') == 'form_address_invoice') {
                            AppOPC.is_valid_form_address_invoice = true;
                        }

                        return false;
                    }
                });
            }

            $(OnePageCheckoutPS.CONFIGS.OPC_ID_CONTENT_PAGE)
                .css({
                    margin: 0
                })
                .addClass('opc_center_column')
                .removeClass('col-sm-push-3');

            Address.launch();
            Fronted.launch();
            if (!register_customer)
            {
                $(OnePageCheckoutPS.CONFIGS.OPC_ID_CONTENT_PAGE).css({width: '100%'});

                Carrier.launch();
                PaymentOPC.launch();
                Review.launch();
            }

            if (typeof $.fn.datepicker !== typeof undefined) {
                AppOPC.$opc_step_one.find('input[data-validation*="isBirthDate"], input[data-validation*="isDate"]').datepicker({
                    dateFormat: OnePageCheckoutPS.date_format_language,
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    yearRange: '-100:+0',
                    isRTL: parseInt(prestashop.language.is_rtl)
                });
            }
        }
    },
    copyInvoiceAddressToDelivery: function() {
        var $invoice_fields = $('#panel_address_invoice #form_address_invoice').find('.invoice');
        var $form_delivery = $('#panel_address_delivery #form_address_delivery');

        $.each($invoice_fields, function(i, elem) {
            var field_name  = $(elem).data('field-name');
            var value       = $(elem).val();

            if (field_name != 'id' && $form_delivery.find('[data-field-name="'+field_name+'"]').length > 0) {
                $form_delivery.find('[data-field-name="'+field_name+'"]').val(value);
            }
        });
    }
}

var Fronted = {
    launch: function(){
        $('div#onepagecheckoutps #opc_show_login').click(function(){
            Fronted.showModal({type:'normal', title:$('#opc_login').attr('title'), title_icon:'fa-pts-user', content:$('#opc_login')});
        });

        AppOPC.$opc.find('#opc_login').on('click', '#btn_login', Fronted.loginCustomer);

        AppOPC.$opc.on('click', '#btn_continue_shopping', function(){
            var link = $('div#onepagecheckoutps #btn_continue_shopping').data('link');
            if (typeof link === typeof undefined) {
                link = prestashop.urls.pages.index;
            }
            window.location = link;
        });

        AppOPC.$opc.on('click', '#btn-logout', function(e){
            $.totalStorageOPC.deleteItem('create_invoice_address_'+OnePageCheckoutPS.id_shop);

            window.location = $(e.currentTarget).data('link');
        });

        AppOPC.$opc.find('#opc_login #txt_login_password').keypress(function(e){
            var code = (e.keyCode ? e.keyCode : e.which);

            if (code == 13)
                Fronted.loginCustomer();
       });

        //evita el guest checkout cuando solo quiere registrarse o iniciar sesion "rc=1".
        if (AppOPC.$opc_step_review.length <= 0) {
            var $create_account_guest = AppOPC.$opc_step_one.find('#field_customer_checkbox_create_account_guest');
            if ($create_account_guest.length > 0) {
                $create_account_guest.hide();

                if (!$create_account_guest.find('#checkbox_create_account_guest').is(':checked')) {
                    $create_account_guest.find('#checkbox_create_account_guest').trigger('click');
                }
            }
        }

       //evita copiar, pegar y cortar en los campos de confirmacion.
        /*AppOPC.$opc_step_one.find('#customer_conf_email, #customer_conf_passwd').bind("cut copy paste", function(e) {
            e.preventDefault();
        });*/
    },
    openCMS: function(params){
        var param = $.extend({}, {
            id_cms: ''
        }, params);

        var data = {
            url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
            is_ajax: true,
            dataType: 'html',
            action: 'loadCMS',
            id_cms: param.id_cms
        };

        var _json = {
            data: data,
            beforeSend: function() {
                Fronted.loadingBig(true);
            },
            success: function(html) {
                if (!$.isEmpty(html)){
                    Fronted.showModal({name: 'cms_modal', content: html});
                }
            },
            complete: function(){
                Fronted.loadingBig(false);
            }
        };
        $.makeRequest(_json);
    },
    loading: function(show, selector){
        if (show) {
            Fronted.loadingBig(true);
        }
    },
    loadingBig: function(show){
        if (show && !AppOPC.$opc.find('> .row').hasClass('.opc_overlay')) {
            AppOPC.$opc.find('> .row').addClass('opc_overlay');

            if ($(window).width() >= 1024) {
                AppOPC.$opc.find('.loading_big').show();
            } else {
                $('div#opc_loading').remove();
                $('body').append('<div id="opc_loading">'+OnePageCheckoutPS.Msg.processing_purchase+'<i class="fa-pts fa-pts-spin fa-pts-refresh"></i></div>');
            }
        } else {
            AppOPC.$opc.find('> .row').removeClass('opc_overlay');

            if ($(window).width() >= 1024) {
                AppOPC.$opc.find('.loading_big').hide();
            } else {
                $('div#opc_loading').remove();
            }
        }
    },
    showModal: function(params){
        var param = $.extend({}, {
            name: 'opc_modal',
            type: 'normal',
            title: '',
            title_icon: '',
            message: '',
            content: '',
            close: true,
            button_ok: false,
            button_close: false,
            size: '',
            callback: '',
            callback_ok: '',
            callback_close: ''
        }, params);

        $('#'+param.name).remove();

        //var windows_width = $(window).width();

        var parent_content = '';
        if (typeof param.content === 'object'){
            parent_content = param.content.parent();
        }

        var $modal = $('<div/>').attr({id:param.name, 'class':'modal fade', role:'dialog'});
        var $modal_dialog = $('<div/>').attr({'class':'modal-dialog ' + param.size});
        var $modal_header = $('<div/>').attr({'class':'modal-header'});
        var $modal_content = $('<div/>').attr({'class':'modal-content'});
        var $modal_body = $('<div/>').attr({'class':'modal-body'});
        var $modal_footer = $('<div/>').attr({'class':'modal-footer'});
        var $modal_button_close = $('<button/>')
                .attr({type:'button', 'class':'close'})
                .click(function(){
                    $('#'+param.name).modal('hide');
                })
                .append('<i class="fa-pts fa-pts-close"></i>');
        var $modal_button_ok_footer = $('<button/>')
            .attr({type:'button', 'class':'btn btn-primary'})
            .click(function(){
                if (typeof param.callback_ok !== typeof undefined && typeof param.callback_ok === 'function') {
                    if (!param.callback_ok()) {
                        return false;
                    }
                    $('#'+param.name).modal('hide');
                }
            })
            .append('OK');
        var $modal_button_close_footer = $('<button/>')
            .attr({type:'button', 'class':'btn btn-default'})
            .click(function(){
                $('#'+param.name).modal('hide');
            })
            .append(OnePageCheckoutPS.Msg.close);
        var $modal_title = '';

        if (typeof param.message === 'array'){
            var message_html = '';
            $.each(param.message, function(i, message){
                message_html += '- ' + message + '<br/>';
            });
            param.message =  message_html;
        }

        if (param.type == 'error'){
            $modal_title = $('<span/>')
                .attr({'class':'panel-title'})
                .append(param.close ? $modal_button_close : '')
                .append('<i class="fa-pts fa-pts-times-circle fa-pts-2x" style="color:red"></i>')
                .append(param.message);
        }else if (param.type == 'warning'){
            $modal_title = $('<span/>')
                .attr({'class':'panel-title'})
                .append(param.close ? $modal_button_close : '')
                .append('<i class="fa-pts fa-pts-warning fa-pts-2x" style="color:orange"></i>')
                .append(param.message);
        }
        else{
            $modal_title = $('<span/>')
                .attr({'class':'panel-title'})
                .append(param.close ? $modal_button_close : '')
                .append('<i class="fa-pts '+param.title_icon+' fa-pts-1x"></i>')
                .append(param.title);
        }

        $modal_header.append($modal_title);
        $modal_content.append($modal_header);

        if (param.type == 'normal'){
            if (typeof param.content === 'object'){
                param.content.removeClass('hidden').appendTo($modal_body);
            }else{
                $modal_body.append(param.content);
            }

            $modal_content.append($modal_body);

            if (param.button_close){
                $modal_footer.append($modal_button_close_footer);
                $modal_content.append($modal_footer);
            }
            if (param.button_ok){
                $modal_footer.append($modal_button_ok_footer);
                $modal_content.append($modal_footer);
            }
        }

        $modal_dialog.append($modal_content);
        $modal.append($modal_dialog);

        $modal.on('hide.bs.modal', function(){
            if (!param.close){
                return false;
            } else {
                if (typeof param.callback_close !== typeof undefined && typeof param.callback_close === 'function') {
                    if (!param.callback_close()) {
                        return false;
                    }
                }

                if (!$.isEmpty(parent_content)) {
                    param.content.appendTo(parent_content).addClass('hidden');
                }

                $('body').removeClass('modal-open');
            }
        });

        $('div#onepagecheckoutps').prepend($modal);

        $('#'+param.name).modal('show');

        if (!$('#'+param.name).hasClass('in')) {
            $('#'+param.name).addClass('in').css({display : 'block'});
        }

        var modalResize = function() {
            var paddingTop = 0
            var windows_height = $(window).height();

            if (windows_height > $modal_dialog.height() && (AppOPC.$opc.height() / 2) > $modal_dialog.height()) {
                paddingTop = (windows_height - $modal_dialog.height()) / 2;
            }

            $('#'+param.name).css({
                paddingTop: paddingTop
            });
        };
        modalResize();

        $(window).on('resize', function(){
            modalResize();
        });

        Fronted.loadingBig(false);

        if (typeof param.callback !== typeof undefined && typeof param.callback === 'function')
            param.callback();

        //fix problem with module: pakkelabels_shipping
        $('.pakkelabels_modal-backdrop').remove();

        window.scrollTo(0, $('div#onepagecheckoutps').offset().top);
    },
    loginCustomer: function(){
        var email = $('#opc_login #txt_login_email').val();
        var password = $('#opc_login #txt_login_password').val();
        var login_success = false;

        var data = {
            is_ajax: true,
            action: 'loginCustomer',
            email: email,
            password: password
        };

        Fronted.validateOPC({valid_form_login: true});

        if (AppOPC.is_valid_opc) {
            //no its use makeRequest because dont work.. error weird.
            $.ajax({
                type: 'POST',
                url: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                cache: false,
                dataType: 'json',
                data: data,
                beforeSend: function() {
                    $('#opc_login #btn_login').attr('disabled', 'true');
                    $('#opc_login .alert').empty().addClass('hidden');
                },
                success: function(json) {
                    if(json.success) {
                        if ($('div#onepagecheckoutps #onepagecheckoutps_step_review_container').length > 0) {
                            window.parent.location.reload();
                        } else {
                            if (parseInt($('.shopping_cart .ajax_cart_quantity').text()) > 0){
                                window.parent.location = prestashop.urls.pages.order;
                            } else {
                                window.parent.location = prestashop.urls.base_url;
                            }
                        }

                        login_success = true;
                    } else {
                        if(json.errors){
                            $('#opc_login .alert').html('&bullet; ' + json.errors.join('<br>&bullet; ')).removeClass('hidden');
                        }
                    }
                },
                complete: function(){
                    if (!login_success) {
                        $('#opc_login #btn_login').removeAttr('disabled');
                    }
                }
            });
        }
    },
    openWindow: function (url){
		var LeftPosition = (screen.width) ? (screen.width-700)/2 : 0;
		var TopPosition = (screen.height) ? (screen.height-500)/2 : 0;
		window.open(url,'','height=500,width=600,top='+(TopPosition-10)+',left='+LeftPosition+',toolbar=no,directories=no,status=no,menubar=no,modal=yes,scrollbars=yes');
	},
    validateOPC: function(params){
        var param = $.extend({}, {
            valid_form_login: false,
            valid_form_customer: false,
            valid_form_address_delivery: false,
            valid_form_address_invoice: false,
            valid_carrier: false,
            valid_payment: false,
            valid_condition: false,
            valid_privacy: false,
            valid_gdpr: false
        }, params);

        AppOPC.is_valid_opc = true;

        if (param.valid_form_login) {
            AppOPC.$opc.find('#form_login').submit();
        }
        if (param.valid_form_customer) {
            if (($('div#onepagecheckoutps #field_customer_checkbox_change_passwd input[name="checkbox_change_passwd"]').length > 0
                && !$('div#onepagecheckoutps #field_customer_checkbox_change_passwd input[name="checkbox_change_passwd"]').is(':checked'))
                || ($('div#onepagecheckoutps #field_customer_checkbox_create_account input[name="checkbox_create_account"]').length > 0
                && !$('div#onepagecheckoutps #field_customer_checkbox_create_account input[name="checkbox_create_account"]').is(':checked'))
                || ($('div#onepagecheckoutps #field_customer_checkbox_create_account_guest input[name="checkbox_create_account_guest"]').length > 0
                && !$('div#onepagecheckoutps #field_customer_checkbox_create_account_guest input[name="checkbox_create_account_guest"]').is(':checked'))
            ) {
                $('#onepagecheckoutps input[type="password"]').val('');
            }
            AppOPC.$opc.find('#form_customer').submit();
        }
        if (AppOPC.$opc.find('#form_address_delivery').length > 0 && (!is_virtual_cart || OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL) && param.valid_form_address_delivery) {
            AppOPC.$opc.find('#form_address_delivery').submit();
        }
        if (Address.isSetInvoice() && param.valid_form_address_invoice) {
            AppOPC.$opc.find('#form_address_invoice').submit();
        }

        if (param.valid_form_login && !AppOPC.is_valid_form_login) {
            AppOPC.is_valid_opc = false;
        }
        if (param.valid_form_customer && !AppOPC.is_valid_form_customer) {
            AppOPC.is_valid_opc = false;
        }
        if (AppOPC.$opc.find('#form_address_delivery').length > 0 && (!is_virtual_cart || OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL) && param.valid_form_address_delivery && !AppOPC.is_valid_form_address_delivery) {
            AppOPC.is_valid_opc = false;

            if (OnePageCheckoutPS.IS_LOGGED) {
                AppOPC.$opc_step_one.find('#delivery_address_container #form_address_delivery').show(400);
                AppOPC.$opc_step_one.find('.addresses_customer_container.delivery').hide(400);
            }
        }
        if (Address.isSetInvoice() && param.valid_form_address_invoice && !AppOPC.is_valid_form_address_invoice) {
            AppOPC.is_valid_opc = false;

            if (OnePageCheckoutPS.IS_LOGGED) {
                AppOPC.$opc_step_one.find('#invoice_address_container #form_address_invoice').show(400);
                AppOPC.$opc_step_one.find('.addresses_customer_container.invoice').hide(400);
            }
        }

        if (AppOPC.is_valid_opc) {
            if (param.valid_carrier) {
                AppOPC.$opc_step_two.removeClass('alert alert-warning');

                //validate shipping
                if (AppOPC.$opc_step_two.find('.delivery_options_address').length >= 0 && !is_virtual_cart) {
                    var id_carrier = AppOPC.$opc_step_two.find('.delivery_option_radio:checked').val();

                    if (!$.isEmpty(id_carrier)){
                        Carrier.id_delivery_option_selected = id_carrier;

                        AppOPC.is_valid_opc = true;
                    }else{
                        Carrier.id_delivery_option_selected = null;
                        AppOPC.$opc_step_two.find('#shipping_container').addClass('alert alert-warning');

                        Fronted.showModal({type: 'warning', message: OnePageCheckoutPS.Msg.shipping_method_required});

                        AppOPC.is_valid_opc = false;
                    }
                }
            }
        }

        if (AppOPC.is_valid_opc) {
            if (param.valid_payment) {
                AppOPC.$opc_step_three.removeClass('alert alert-warning');

                //validate payments
                if (AppOPC.$opc_step_three.find('#free_order').length <= 0) {
                    var payment = AppOPC.$opc_step_three.find('input[name="payment-option"]:checked');

                    if (payment.length > 0){
                        PaymentOPC.id_payment_selected = $(payment).attr('id');

                        AppOPC.is_valid_opc = true;
                    }else{
                        PaymentOPC.id_payment_selected = '';

                        AppOPC.$opc_step_three.addClass('alert alert-warning');

                        Fronted.showModal({type: 'warning', message: OnePageCheckoutPS.Msg.payment_method_required});

                        AppOPC.is_valid_opc = false;
                    }
                }
            }
        }

        //terms conditions
        if (AppOPC.is_valid_opc) {
            if (param.valid_condition) {
                AppOPC.$opc.find('#conditions-to-approve label').removeClass('alert alert-warning');
                AppOPC.$opc.find('#onepagecheckoutps_step_review_container #div_privacy_policy').css('padding-left','0px');

                if (OnePageCheckoutPS.CONFIGS.OPC_ENABLE_TERMS_CONDITIONS) {
                    AppOPC.$opc_step_review.find('#conditions-to-approve input').each(function(i, condition) {
                        if (!$(condition).is(':checked')) {
                            $(condition).parent().addClass('alert alert-warning').css('padding-left','15px');

                            AppOPC.is_valid_opc = false;
                        }
                    });

                    if (!AppOPC.is_valid_opc) {
                        AppOPC.$opc.find('#onepagecheckoutps_step_review_container #div_privacy_policy').css('padding-left','15px');

                        Fronted.showModal({type: 'warning', message: OnePageCheckoutPS.Msg.agree_terms_and_conditions});
                    }
                }
            }
        }

        if (AppOPC.is_valid_opc) {
            if (param.valid_privacy) {
                AppOPC.$opc.find('#div_privacy_policy').removeClass('alert alert-warning');
                AppOPC.$opc.find('#onepagecheckoutps_step_review_container #conditions-to-approve label').css('padding-left','0px');
                AppOPC.$opc.find('#checkbox_create_invoice_address').css('margin-left','0px');

                //privacy policy
                if (OnePageCheckoutPS.CONFIGS.OPC_ENABLE_PRIVACY_POLICY
                    && (!OnePageCheckoutPS.IS_LOGGED || (OnePageCheckoutPS.IS_LOGGED && OnePageCheckoutPS.CONFIGS.OPC_REQUIRE_PP_BEFORE_BUY))
                    && (AppOPC.$opc.find('#privacy_policy').length > 0 && !AppOPC.$opc.find('#privacy_policy').is(':checked'))
                ){
                    AppOPC.$opc.find('#div_privacy_policy').addClass('alert alert-warning').css('padding-left','15px');
                    AppOPC.$opc.find('#conditions-to-approve label').css('padding-left','15px');
                    AppOPC.$opc.find('#checkbox_create_invoice_address').css('margin-left','12px');

                    Fronted.showModal({type: 'warning', message: OnePageCheckoutPS.Msg.agree_privacy_policy});

                    AppOPC.is_valid_opc = false;
                }
            }
        }

        if (AppOPC.is_valid_opc) {
            if (param.valid_gdpr) {
                if (typeof message_psgdpr !== typeof undefined) {
                    AppOPC.$opc.find('#gdpr_consent').removeClass('alert alert-warning');

                    //GDPR
                    if (message_psgdpr && !AppOPC.$opc.find('#gdpr_consent_checkbox').is(':checked')){
                        AppOPC.$opc.find('#gdpr_consent').addClass('alert alert-warning');

                        Fronted.showModal({type: 'warning', message: OnePageCheckoutPS.Msg.agree_privacy_policy});

                        AppOPC.is_valid_opc = false;
                    }
                }
            }
        }

        //Compatibility MondialRelay 2.3.3 - PrestaShop
        if (AppOPC.is_valid_opc) {
            if ($('#onepagecheckoutps_step_two .delivery-option.selected input[name="mondialrelay"]').length > 0
                && typeof checkMrSelection !== typeof undefined
            ) {
                if(!checkMrSelection()){
                   AppOPC.is_valid_opc = false;
                }
            }
        }

        if (!AppOPC.is_valid_opc) {
            AppOPC.$opc.find('#btn_place_order').removeAttr('disabled');
        }
    }
}

var Address = {
    id_customer: 0,
    id_address_delivery: 0,
    id_address_invoice: 0,
    delivery_vat_number: false,
    invoice_vat_number: false,
    initEventsChangeCountry: function(object) {
        if ((object === 'delivery' && OnePageCheckoutPS.CONFIGS.OPC_INSERT_ISO_CODE_IN_DELIV_DNI)
            || (object === 'invoice' && OnePageCheckoutPS.CONFIGS.OPC_INSERT_ISO_CODE_IN_INVOI_DNI)
        ) {
            Address.insertCountryISOCode(object);
        }

        Address.isNeedDniByCountryId({object: object});
        Address.isNeedPostCodeByCountryId({object: object});
        Address.updateState({object: object});
        Address.initPostCodeGeonames({object: object});
    },
    launch: function(){
        var rc_page = AppOPC.$opc.find('#rc_page').val();

        if (OnePageCheckoutPS.IS_LOGGED) {
            Address.id_customer = AppOPC.$opc_step_one.find('#customer_id').val();
        }

        AppOPC.$opc_step_one
            .on('click', '.container_card .header_card, .container_card .content_card', function(item){
                if (!$.isEmpty(rc_page) && rc_page != 'order') {
                    return false;
                }

                var $addresses_customer_container = $(item.currentTarget).parents('.addresses_customer_container');
                if ($(item.currentTarget).parents('.container_card').hasClass('selected')) {
                    return;
                }

                var object = $addresses_customer_container.data('object');
                var id_address = $(item.currentTarget).parents('.address_card').data('id-address');

                AppOPC.$opc_step_one.find('#'+object+'_id').val(id_address);

                if (object == 'delivery') {
                    Address.id_address_delivery = id_address;
                } else if (object == 'invoice') {
                    Address.id_address_invoice = id_address;
                }

                var callback = function () {
                    Address.updateAddress({object: object, id_address: id_address, update_cart: true, load_addresses: true, load_carriers: true});
                }
                Address.load({object: object, id_address: id_address, callback: callback});
            })
            .on('click', '.container_card .choose_address', function(item){
                var id_address = $(item.currentTarget).data('id-address');
                AppOPC.$opc_step_one.find('.addresses_customer_container #address_card_'+id_address+' .content_card').trigger('click');
            })
            .on('click', '#address_card_new_content span', function(item){
                var object = $(item.currentTarget).parents('.addresses_customer_container').data('object');

                AppOPC.$opc_step_one.find('#'+object+'_address_container #form_address_'+object).show(400);
                AppOPC.$opc_step_one.find('.addresses_customer_container.'+object).hide(400);

                Address.clearFormByObject(object);
            })
            .on('click', '.address_card .edit_address', function(item){
                var id_address = $(item.currentTarget).data('id-address');
                var object = $(item.currentTarget).parents('.addresses_customer_container').data('object');

                $(item.currentTarget).prop('disabled', true).addClass('disabled');

                AppOPC.$opc_step_one.find('#'+object+'_id').val(id_address);
                AppOPC.$opc_step_one.find('#'+object+'_address_container #form_address_'+object).show(400);
                AppOPC.$opc_step_one.find('.addresses_customer_container.'+object).hide(400);

                Address.load({object: object, id_address: id_address});
            })
            .on('click', '.address_card .delete_address', function(item){
                $(item.currentTarget).prop('disabled', true).addClass('disabled');

                var id_address = $(item.currentTarget).data('id-address');
                var object = $(item.currentTarget).parents('.addresses_customer_container').data('object');

                if (!Address.removeAddress({id_address: id_address, object: object})) {
                    $(item.currentTarget).prop('disabled', false).removeClass('disabled');
                }
            })
            .on('click', '#btn_update_address_delivery', function(){
                var callback = function() {
                    if (!OnePageCheckoutPS.IS_GUEST) {
                        AppOPC.$opc_step_one.find('#delivery_address_container #form_address_delivery').hide(400);
                        AppOPC.$opc_step_one.find('.addresses_customer_container.delivery').show(400);

                        Address.loadAddressesCustomer({object: 'delivery'});
                    }
                }

                if (AppOPC.$opc_step_one.find('#delivery_address_container .addresses_customer_container .address_card:not(#address_card_new)').length <= 0) {
                    Address.updateAddress({object: 'delivery', load_carriers: true, callback: callback, update_cart: true});
                } else {
                    var id_edited_address   = AppOPC.$opc_step_one.find('#panel_address_delivery #delivery_id').val();
                    var load_carriers       = false;

                    if (AppOPC.$opc_step_one.find('#panel_address_delivery .address_card[data-id-address="'+id_edited_address+'"] > .container_card').hasClass('selected')) {
                        load_carriers = true;
                    }

                    Address.updateAddress({object: 'delivery', load_carriers: load_carriers, callback: callback});
                }
            })
            .on('click', '#btn_update_address_invoice', function(){
                var callback = function() {
                    if (!OnePageCheckoutPS.IS_GUEST) {
                        AppOPC.$opc_step_one.find('#invoice_address_container #form_address_invoice').hide(400);
                        AppOPC.$opc_step_one.find('.addresses_customer_container.invoice').show(400);

                        Address.loadAddressesCustomer({object: 'invoice'});
                    }
                }

                if (AppOPC.$opc_step_one.find('#invoice_address_container .addresses_customer_container .address_card:not(#address_card_new)').length <= 0) {
                    Address.updateAddress({object: 'invoice', load_carriers: true, callback: callback, update_cart: true});
                } else {
                    var id_edited_address   = AppOPC.$opc_step_one.find('#panel_address_invoice #invoice_id').val();
                    var load_carriers       = false;

                    if (AppOPC.$opc_step_one.find('#panel_address_invoice .address_card[data-id-address="'+id_edited_address+'"] > .container_card').hasClass('selected')) {
                        load_carriers = true;
                    }

                    Address.updateAddress({object: 'invoice', load_carriers: load_carriers, callback: callback});
                }
            })
            .on('click', '#btn_cancel_address_delivery', function(){
                AppOPC.$opc_step_one.find('#delivery_address_container #form_address_delivery').hide(400);
                AppOPC.$opc_step_one.find('.addresses_customer_container.delivery').show(400);

                Address.clearFormByObject('delivery');
                Address.load({object: 'delivery'});
            })
            .on('click', '#btn_cancel_address_invoice', function(){
                AppOPC.$opc_step_one.find('#invoice_address_container #form_address_invoice').hide(400);
                AppOPC.$opc_step_one.find('.addresses_customer_container.invoice').show(400);

                Address.clearFormByObject('invoice');
                Address.load({object: 'invoice'});
            })
            .on('click', 'input#checkbox_create_account_guest', Address.checkGuestAccount)
            .on('click', 'input#checkbox_create_account', Address.checkGuestAccount)
            .on('click', 'input#checkbox_change_passwd', Address.checkGuestAccount)
            .on('keyup','.search_address',function (event){
                var text = $(event.currentTarget).val();

                if ($.isEmpty(text))
                    AppOPC.$opc_step_one.find('.addresses_customer_container .address_card').show();
                else {
                    AppOPC.$opc_step_one.find('.addresses_customer_container .container_card:ptsContains(' + text + ')').parents('.address_card:not(#address_card_new)').show();
                    AppOPC.$opc_step_one.find('.addresses_customer_container .container_card:not(:ptsContains(' + text + '))').parents('.address_card:not(#address_card_new)').hide();
                }
            })
            .on('blur', 'input#delivery_dni', function() {
                if (OnePageCheckoutPS.CONFIGS.OPC_INSERT_ISO_CODE_IN_DELIV_DNI) {
                    Address.insertCountryISOCode('delivery');
                }
            })
            .on('blur', 'input#invoice_dni', function() {
                if (OnePageCheckoutPS.CONFIGS.OPC_INSERT_ISO_CODE_IN_INVOI_DNI) {
                    Address.insertCountryISOCode('invoice');
                }
            });

        $(document).on('blur', '#onepagecheckoutps input[data-field-name="address1"]', Address.cleanSpecialCharacterAddress);

        AppOPC.$opc
            .on('click', '#btn_save_customer', Address.createCustomer)
            .on('blur', '#customer_email', Address.checkEmailCustomer)
            .on("click", "#div_privacy_policy span.read", function(){
                Fronted.openCMS({id_cms : OnePageCheckoutPS.CONFIGS.OPC_ID_CMS_PRIVACY_POLICY});
            });

        if (!OnePageCheckoutPS.IS_LOGGED) {
            AppOPC.$opc_step_one.find('#delivery_address_container #form_address_delivery').show();
            AppOPC.$opc_step_one.find('#invoice_address_container #form_address_invoice').show();

            AppOPC.$opc_step_one.find('.addresses_customer_container').hide();
        }

        Address.checkGuestAccount();

        $('div#onepagecheckoutps #field_customer_id').addClass('hidden');

        //just allow lang with weird characters
        if ($.inArray(prestashop.language.iso_code, OnePageCheckoutPS.LANG_ISO_ALLOW) == 0) {
            $('#customer_firstname, #customer_lastname').validName();
        }

        //evita espacios al inicio y final en los campos del registro.
        AppOPC.$opc_step_one.find('input.customer, input.delivery, input.invoice, #customer_conf_passwd, #customer_conf_email').on('paste', function(e){
            var $element = $(e.currentTarget);
            setTimeout(function () {
                $element.val($.trim($element.val()));
            }, 100);
        });

        AppOPC.$opc_step_one.find('.container_help_invoice u').click(function(){
            $('#onepagecheckoutps_step_one #li_invoice_address a').trigger('click');
        });

        //support module: rg_chilexpress - Rolige v2.1.0
        if (typeof rg_chilexpress !== typeof undefined) {
            $('input[name="delivery_city"]').flexdatalist({
                data: rg_chilexpress.cities,
                minLength: 1,
                valueProperty: 'name',
                searchIn: 'name',
                selectionRequired: true,
                searchByWord: true,
                noResultsText: rg_chilexpress.texts.no_results + ' "{keyword}"',
                debug: false
            });
            $('#delivery_city.flexdatalist').on('change:flexdatalist', function(event, set, options) {
                if (AppOPC.$opc_step_one.find('select#delivery_id_country').val() == rg_correoschile.id_country
                    && !OnePageCheckoutPS.IS_LOGGED && !OnePageCheckoutPS.IS_GUEST
                ) {
                    Address.updateAddress({object: 'delivery', update_cart: true, load_carriers: true});
                }
            });
        }

        /* Compatibilidad rg_correoschile(Correos chile) - Rolige - V2.0.0 */
        if (typeof rg_correoschile !== typeof undefined) {
            if (!AppOPC.$opc_step_one.find('input#delivery_city').hasClass('flexdatalist-set')) {
                AppOPC.$opc_step_one.find('input#delivery_city').flexdatalist({
                    data: rg_correoschile.cities,
                    minLength: 1,
                    valueProperty: 'name',
                    searchIn: 'name',
                    selectionRequired: true,
                    searchByWord: true,
                    noResultsText: rg_correoschile.texts.no_results + ' "{keyword}"',
                    debug: false
                }).on('change:flexdatalist', function() {
                    if (AppOPC.$opc_step_one.find('select#delivery_id_country').val() == rg_correoschile.id_country
                        && !OnePageCheckoutPS.IS_LOGGED && !OnePageCheckoutPS.IS_GUEST
                    ) {
                        Address.updateAddress({object: 'delivery', update_cart: true, load_carriers: true});
                    }
                });
            }
        }

        Address.load({object: 'customer'});
        Address.loadAutocompleteAddress();

        if (OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL || !is_virtual_cart) {
            $('div#onepagecheckoutps #delivery_postcode').validPostcode();
            if ($.inArray(prestashop.language.iso_code, OnePageCheckoutPS.LANG_ISO_ALLOW) == 0){
                $('div#onepagecheckoutps #delivery_firstname, div#onepagecheckoutps #delivery_lastname').validName();
                $('div#onepagecheckoutps #delivery_address1, div#onepagecheckoutps #delivery_address2, div#onepagecheckoutps #delivery_city').validAddress();
            }

            $('div#onepagecheckoutps #field_delivery_id').addClass('hidden');

            Address.initPostCodeGeonames({object: 'delivery'});

            $('div#onepagecheckoutps')
                .on('change', '#delivery_city', function(){
                    $('#delivery_city_list').val('');
                })
                .on('change', 'select#delivery_id_state', function(event){
                    if (OnePageCheckoutPS.CONFIGS.OPC_SHOW_LIST_CITIES_GEONAMES) {
                        AppOPC.$opc_step_one.find('#delivery_city').val('').trigger('reset');
                    }

                    Address.getCitiesByState({object: 'delivery'});

                    $(event.currentTarget).validate(null, null, messageValidate);

                    if (!OnePageCheckoutPS.IS_LOGGED) {
                        Address.updateAddress({object: 'delivery', load_carriers: true});
                    }
                })
                .on('change', 'select#delivery_id_country', function(event){
                    Address.initEventsChangeCountry('delivery');

                    if (AppOPC.$opc.find('input#delivery_postcode').length > 0) {
                        AppOPC.$opc.find('input#delivery_postcode').validate();
                    }

                    if (!OnePageCheckoutPS.IS_LOGGED) {
                        Address.updateAddress({object: 'delivery', load_carriers: true});
                    }
                });

            if (!OnePageCheckoutPS.IS_LOGGED || OnePageCheckoutPS.IS_GUEST) {
                var callback = function () {
                    Address.updateAddress({object: 'delivery', load_carriers: true});
                }
                Address.load({object: 'delivery', callback: callback});
            } else {
                var callback = function() {
                    Carrier.getByCountry();
                };
                Address.loadAddressesCustomer({object: 'delivery', callback: callback});
            }
        }

        if(OnePageCheckoutPS.CONFIGS.OPC_ENABLE_INVOICE_ADDRESS){
            if (OnePageCheckoutPS.CONFIGS.OPC_REQUIRED_INVOICE_ADDRESS && !OnePageCheckoutPS.IS_LOGGED && !OnePageCheckoutPS.IS_GUEST) {
                Address.updateAddress({object: 'invoice', update_cart: true});
            }

            if (typeof $.totalStorageOPC !== typeof undefined) {
                if ($.totalStorageOPC('create_invoice_address_'+OnePageCheckoutPS.id_shop)) {
                    $('div#onepagecheckoutps #checkbox_create_invoice_address').attr('checked', 'true');
                }
            }

            $('div#onepagecheckoutps #invoice_postcode').validPostcode();
            if ($.inArray(prestashop.language.iso_code, OnePageCheckoutPS.LANG_ISO_ALLOW) == 0){
                $('div#onepagecheckoutps #invoice_firstname, div#onepagecheckoutps #invoice_lastname').validName();
                $('div#onepagecheckoutps #invoice_address1, div#onepagecheckoutps #invoice_address2, div#onepagecheckoutps #invoice_city').validAddress();
            }

            $('div#onepagecheckoutps #field_invoice_id').addClass('hidden');

            $('div#onepagecheckoutps').on('click', 'input#checkbox_create_invoice_address', function(event){
                Address.checkNeedInvoice();

                if ($(event.currentTarget).is(':checked') && !OnePageCheckoutPS.IS_LOGGED) {
                    Address.updateAddress({object: 'invoice', update_cart: true});
                } else {
                    Address.removeAddressInvoice();
                }
            });

			Address.checkNeedInvoice();
            Address.initPostCodeGeonames({object: 'invoice'});

            $('div#onepagecheckoutps')
                .on('change', '#invoice_city', function(){
                    $('#invoice_city_list').val('');
                })
                .on('change', 'select#invoice_id_state', function(event){
                    if (OnePageCheckoutPS.CONFIGS.OPC_SHOW_LIST_CITIES_GEONAMES) {
                        AppOPC.$opc_step_one.find('#invoice_city').val('').trigger('reset');
                    }

                    Address.getCitiesByState({object: 'invoice'});

                    $(event.currentTarget).validate();
                })
                .on('change', 'select#invoice_id_country', function(event){
                    Address.initEventsChangeCountry('invoice');

                    if (AppOPC.$opc.find('input#invoice_postcode').length > 0) {
                        AppOPC.$opc.find('input#invoice_postcode').validate();
                    }

                    if (!OnePageCheckoutPS.IS_LOGGED && OnePageCheckoutPS.PS_TAX_ADDRESS_TYPE == 'id_address_invoice') {
                        Address.updateAddress({object: 'invoice', load_payments: true});
                    }
                });

            if (!OnePageCheckoutPS.IS_LOGGED || OnePageCheckoutPS.IS_GUEST) {
                Address.load({object: 'invoice'});
            }
        }

        if (is_virtual_cart && !OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL) {
            PaymentOPC.getByCountry();
        }
    },
    insertCountryISOCode: function(type) {
        var input   = AppOPC.$opc_step_one.find('input#'+type+'_dni');
        if (input.length === 0) {
            return false;
        }

        var value   = input.val();
        var country_prefix = AppOPC.$opc_step_one.find('select#' + type + '_id_country > option:selected').data('iso-code');

        if (typeof country_prefix === typeof undefined) {
            country_prefix = '';
        }

        if (value.length > 1) {
            var current_country_iso_code    = $.totalStorageOPC(type + '_current_country_iso_code');

            if (!$.isEmpty(current_country_iso_code) && value.indexOf(current_country_iso_code) >= 0) {
                value = value.replace(current_country_iso_code, country_prefix);
            } else {
                value = country_prefix + value;
            }

            $.totalStorageOPC(type + '_current_country_iso_code', country_prefix);
            input.val(value);
        }
    },
    initPostCodeGeonames: function(params){
        var param = $.extend({}, {
            object: 'delivery'
        }, params);

        if (OnePageCheckoutPS.CONFIGS.OPC_AUTO_ADDRESS_GEONAMES && AppOPC.$opc_step_one.find('#'+param.object+'_postcode').length > 0){
            var $id_country = $('#onepagecheckoutps_step_one #'+param.object+'_id_country');
            var iso_code_country = '';

            if ($id_country.length > 0) {
                iso_code_country = $id_country.find('option:selected').data('iso-code');
            } else {
                iso_code_country = OnePageCheckoutPS.iso_code_country_delivery_default;
            }

            $('#onepagecheckoutps_step_one #'+param.object+'_postcode').jeoPostCodeAutoComplete({
                country: iso_code_country,
                callback: function(data){
                    $('#onepagecheckoutps_step_one #'+param.object+'_postcode').val(data.postalCode);
                    $('#onepagecheckoutps_step_one #'+param.object+'_city_list').val(data.name);
                    $('#onepagecheckoutps_step_one #'+param.object+'_city').val(data.name);

                    if ($('#onepagecheckoutps_step_one #'+param.object+'_id_state [data-text="'+data.adminName2+'"]').length <= 0) {
                        $('#onepagecheckoutps_step_one #'+param.object+'_id_state [data-iso-code="'+data.countryCode + '-' + data.adminCode2+'"]').attr('selected', 'true');
                    } else {
                        $('#onepagecheckoutps_step_one #'+param.object+'_id_state [data-text="'+data.adminName2+'"]').attr('selected', 'true');
                    }

                    if (typeof is_necessary_postcode !== typeof undefined && is_necessary_postcode) {
                        $('#onepagecheckoutps_step_one #'+param.object+'_postcode').trigger('blur');
                    } else if(typeof is_necessary_city !== typeof undefined && is_necessary_city) {
                        $('#onepagecheckoutps_step_one #'+param.object+'_city').trigger('blur');
                    }

                    if (typeof is_necessary_postcode !== typeof undefined
                        && !is_necessary_postcode
                        && typeof is_necessary_postcode !== typeof undefined
                        && !is_necessary_postcode)
                    {
                        $('#onepagecheckoutps_step_one #'+param.object+'_id_state [data-text="'+data.adminName2+'"]').trigger('change');
                    }
                }
            });
        }
    },
    getCityByPostCode: function(params){
        var param = $.extend({}, {
            object: 'delivery'
        }, params);

        if (1==2) {
            var $city_list = $('#onepagecheckoutps_step_one #'+param.object+'_city_list');

            if ($city_list.length <= 0 || ($city_list.length > 0 && !$city_list.is(':visible'))) {
                var $id_country = $('#onepagecheckoutps_step_one #'+param.object+'_id_country');
                var $postcode = $('#onepagecheckoutps_step_one #'+param.object+'_postcode');
                var $city = $('#onepagecheckoutps_step_one #'+param.object+'_city');

                if ($postcode.length > 0 && $city.length > 0) {
                    $postcode.jeoPostalCodeLookup({
                        country: $id_country.find('option:selected').data('iso-code'),
                        target: $city
                    });
                }
            }
        }
    },
    getCitiesByState: function(params){
        var param = $.extend({}, {
            object: 'delivery'
        }, params);

        if (OnePageCheckoutPS.CONFIGS.OPC_SHOW_LIST_CITIES_GEONAMES) {
            var $id_country = AppOPC.$opc_step_one.find('#'+param.object+'_id_country');
            var $id_state = AppOPC.$opc_step_one.find('#'+param.object+'_id_state');
            var iso_code_country = '';

            if ($id_country.length > 0) {
                iso_code_country = $id_country.find('option:selected').data('iso-code');
            } else {
                iso_code_country = OnePageCheckoutPS.iso_code_country_delivery_default;
            }

            var name_state = $.trim($id_state.find('option:selected').data('text'));

            if ($id_state.length > 0 && !$.isEmpty(name_state)) {
                var cities = Array();

                jeoquery.getGeoNames(
                  'search',
                  {
                      q: name_state,
                      country: iso_code_country,
                      featureClass: 'P',
                      style: 'full'
                  },
                  function(data){
                    //ordenar array de objetos por una propiedad en especifico
                    function dynamicSort(property) {
                        var sortOrder = 1;
                        if(property[0] === "-") {
                            sortOrder = -1;
                            property = property.substr(1);
                        }
                        return function (a,b) {
                            var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
                            return result * sortOrder;
                        }
                    }

                    $.each(data.geonames, function(i, item){
                        if ($.inArray(item.name, cities) == -1) {
                            cities.push({name: $.trim(item.name), postcode: item.adminCode3});
                        }
                    });
                    cities.sort(dynamicSort('name'));

                    var $city_list = AppOPC.$opc_step_one.find('#'+param.object+'_city_list');
                    if ($city_list.length <= 0) {
                        $city_list = $('<select/>')
                            .attr({
                                id: param.object+'_city_list',
                                class: 'form-control input-sm not_unifrom not_uniform'
                            })
                            .on('change', function(event){
                                var option_selected = $(event.currentTarget).find('option:selected');

                                AppOPC.$opc_step_one.find('#'+param.object+'_city').val($(option_selected).attr('value')).trigger('blur');
                                AppOPC.$opc_step_one.find('#'+param.object+'_postcode').val($(option_selected).attr('data-postcode')).validate();
                            }
                        );
                    } else {
                        $city_list.empty().hide();
                    }

                    var current_city = AppOPC.$opc_step_one.find('#'+param.object+'_city').val();

                    var $option = $('<option/>')
                        .attr({
                            value: ''
                        }).append('--');
                    $option.appendTo($city_list);
                    $.each(cities, function(i, city) {
                        var $option = $('<option/>')
                            .attr({
                                'value': city.name,
                                'data-postcode': city.postcode
                            }).append(city.name);

                        $option.appendTo($city_list);
                    });

                    AppOPC.$opc_step_one.find('#field_'+param.object+'_city').append($city_list);

                    //si existe la ciudad, la seleccionamos.
                    if ($city_list.find('option[value="'+current_city+'"]').length > 0) {
                        $city_list.val(current_city);
                    }

                     $city_list.show();
                });
            } else {
                AppOPC.$opc_step_one.find('#'+param.object+'_city_list').hide();
            }
        }
    },
    loadAddressesCustomer: function(params){
        var param = $.extend({}, {
            object: 'delivery',
            callback: ''
        }, params);

        var valid = true;

        if (OnePageCheckoutPS.IS_LOGGED) {
            if (param.object == 'delivery') {
                if (is_virtual_cart && !OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL) {
                    valid = false;
                }
            }
            if (param.object == 'invoice') {
                if (!Address.isSetInvoice()) {
                    valid = false;
                }
            }

            if (valid) {
                var data = {
                    url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                    is_ajax: true,
                    action: 'loadAddressesCustomer',
                    object: param.object,
                    rc_page: AppOPC.$opc.find('#rc_page').val()
                };
                var _json = {
                    data: data,
                    success: function(json) {
                        var callback = null;

                        if(typeof json.html !== typeof undefined) {
                            AppOPC.$opc_step_one.find('.addresses_customer_container.'+param.object).html(json.html).show(400);
                            AppOPC.$opc_step_one.find('#'+param.object+'_address_container #form_address_'+param.object).hide(400);
                        }

                        if (param.object == 'delivery') {
                            Address.id_address_delivery = json.id_address;
                            AppOPC.$opc_step_one.find('#delivery_id').val(json.id_address);

                            callback = function() {
                                if (json.addresses.length > 0) {
                                    Fronted.validateOPC({valid_form_address_delivery: true});

                                    if (!AppOPC.is_valid_form_address_delivery) {
                                        AppOPC.$opc_step_one.find('#'+param.object+'_address_container #form_address_'+param.object).show(400);
                                        AppOPC.$opc_step_one.find('.addresses_customer_container.'+param.object).hide(400);
                                    }
                                }
                            }
                        } else if (param.object == 'invoice') {
                            Address.id_address_invoice = json.id_address;
                            AppOPC.$opc_step_one.find('#invoice_id').val(json.id_address);

                            callback = function() {
                                if (json.addresses.length > 0) {
                                    Fronted.validateOPC({valid_form_address_invoice: true});

                                    if (!AppOPC.is_valid_form_address_invoice) {
                                        AppOPC.$opc_step_one.find('#'+param.object+'_address_container #form_address_'+param.object).show(400);
                                        AppOPC.$opc_step_one.find('.addresses_customer_container.'+param.object).hide(400);
                                    }
                                }
                            }
                        }

                        if (json.id_address !== 0) {
                            Address.load({object: param.object, id_address: json.id_address/*, callback: callback*/});
                        }
                    },
                    complete: function() {
                        setTimeout(function() {
                            //calcula el alto del contenido de las tarjetas para colocar colocarles un alto igual a todas.
                            //-----------------------------------------------
                            var height_card_max = 0;
                            $.each($('.addresses_customer_container .address_card:not(#address_card_new) .content_card ul'), function(i, card) {
                                var height_card = $(card).innerHeight() + 5;

                                if (height_card_max < height_card) {
                                    height_card_max = height_card;
                                }
                            });
                            $('.addresses_customer_container .address_card:not(#address_card_new) .content_card').css({'height': height_card_max});
                            //-----------------------------------------------
                            //calcula el alto del titulo de las tarjetas para colocar colocarles un alto igual a todas.
                            //-----------------------------------------------
                            var height_header_card_max = 0;
                            $.each($('.addresses_customer_container .address_card:not(#address_card_new) .header_card'), function(i, header_card) {
                                var height_header_card = $(header_card).innerHeight();

                                if (height_header_card_max < height_header_card) {
                                    height_header_card_max = height_header_card;
                                }
                            });
                            $('.addresses_customer_container .address_card:not(#address_card_new) .header_card').css({'height': height_header_card_max});
                            //-----------------------------------------------
                        }, 800);

                        if (typeof param.callback !== typeof undefined && typeof param.callback === 'function') {
                            param.callback();
                        }
                        }
                };
                $.makeRequest(_json);
            }
        } else {
            if (typeof param.callback !== typeof undefined && typeof param.callback === 'function') {
                param.callback();
            }
        }

        return valid;
    },
    createCustomer: function(){
        Fronted.validateOPC({valid_form_customer: true, valid_privacy: true, valid_gdpr: true, valid_form_address_delivery: true, valid_form_address_invoice: true});

        if (AppOPC.is_valid_opc) {
            var fields = Review.getFields();

            var _extra_data = Review.getFieldsExtra({});
            var _data = $.extend({}, _extra_data, {
                'url_call'              : prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                'is_ajax'               : true,
                'dataType'              : 'json',
                'action'                : (OnePageCheckoutPS.IS_LOGGED ? 'placeOrder' : 'createCustomerAjax'),
                'id_customer'           : (!$.isEmpty(AppOPC.$opc_step_one.find('#customer_id').val()) ? AppOPC.$opc_step_one.find('#customer_id').val() : ''),
                'id_address_delivery'   : Address.id_address_delivery,
                'id_address_invoice'    : !$.isEmpty(Address.id_address_invoice) ? Address.id_address_invoice : Address.id_address_delivery,
                'is_new_customer'       : (AppOPC.$opc_step_one.find('#checkbox_create_account_guest').is(':checked') ? 0 : 1),
                'fields_opc'            : JSON.stringify(fields),
                'is_set_invoice'        : Address.isSetInvoice()
            });

            if ($('textarea[name="g-recaptcha-response"]').length > 0) {
                _data['g-recaptcha-response'] = $('textarea[name="g-recaptcha-response"]').val();
            }

            var _json = {
                data: _data,
                beforeSend: function() {
                    Fronted.loading(true, '#onepagecheckoutps_step_one_container');
                },
                success: function(data) {
                    if (data.isSaved && (!OnePageCheckoutPS.PS_GUEST_CHECKOUT_ENABLED || $('#checkbox_create_account_guest').is(':checked'))){
                        AppOPC.$opc_step_one.find('#customer_id').val(data.id_customer);
                        AppOPC.$opc_step_one.find('#customer_email, #customer_conf_email, #customer_passwd, #customer_conf_passwd')
                            .attr({
                                'disabled': 'true',
                                'data-validation-optional' : 'true'
                            })
                            .addClass('disabled')
                            .trigger('reset');

                        $('#div_onepagecheckoutps_login, #field_customer_passwd, #field_customer_conf_passwd, #field_customer_email, #field_customer_conf_email, div#onepagecheckoutps #onepagecheckoutps_step_one_container .account_creation, #field_choice_group_customer').addClass('hidden');
                    }

                    if (data.hasError){
                        Fronted.showModal({type:'error', message : '&bullet; ' + data.errors.join('<br>&bullet; ')});
                    } else if (data.hasWarning){
                        Fronted.showModal({type:'warning', message : '&bullet; ' + data.warnings.join('<br>&bullet; ')});
                    } else {
                        if (!OnePageCheckoutPS.IS_LOGGED || OnePageCheckoutPS.IS_GUEST) {
                            if (typeof data.redirect !== typeof undefined) {
                                window.parent.location = data.redirect;
                            } else {
                                var products_count = 0;

                                if (typeof prestashop.cart !== typeof undefined && prestashop.cart !== null && prestashop.cart.products_count > 0) {
                                    products_count = prestashop.cart.products_count;
                                } else if ($('div#blockcart span.cart-products-count-btn, .blockcart span.cart-products-count').length > 0) {
                                    products_count = $('div#blockcart span.cart-products-count-btn, .blockcart span.cart-products-count').text();
                                } else if ($('#ps-shoppingcart-wrapper .cart-products-count-btn').length > 0) {
                                    products_count = $('#ps-shoppingcart-wrapper .cart-products-count-btn').text();
                                }

                                if (products_count > 0 && !register_customer) {
                                    if (OnePageCheckoutPS.CONFIGS.OPC_REDIRECT_DIRECTLY_TO_OPC) {
                                        window.parent.location = prestashop.urls.current_url;
                                    } else {
                                        window.parent.location = prestashop.urls.pages.order;
                                    }
                                } else {
                                    window.parent.location = prestashop.urls.pages.my_account;
                                }
                            }

                            $('div#onepagecheckoutps #btn_save_customer').attr('disabled', 'true');
                        } else {
                            location.reload();
                        }
                    }
                },
                complete: function(){
                    Fronted.loading(false, '#onepagecheckoutps_step_one_container');
                    Fronted.loadingBig(false);
                }
            };

            var callback = function() {
                $.makeRequest(_json);
            }
            supportModuleGDPR(callback);
        }
    },
    load: function(params){
        var param = $.extend({}, {
            object: '',
            id_address: false,
            callback: ''
        }, params);

        if (param.object == 'customer') {
            if (!OnePageCheckoutPS.IS_LOGGED && !OnePageCheckoutPS.IS_GUEST) {
                return false;
            }
        }
        if (param.object == 'delivery') {
            if (is_virtual_cart && !OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL) {
                return false;
            }
        }
        if (param.object == 'invoice') {
            if (!Address.isSetInvoice()) {
                return false;
            }
        }

        var data = {
            url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
            is_ajax: true,
            action: 'loadAddress',
            object: param.object,
            id_address: param.id_address
        };
        var _json = {
            data: data,
            beforeSend: function() {},
            success: function(json) {
                if(!$.isEmpty(json.customer.id) || !$.isEmpty(json.address.id)) {
                    Address.id_customer = $.isEmpty(json.customer.id) ? 0 : json.customer.id;

                    if (param.object == 'delivery') {
                        Address.id_address_delivery = $.isEmpty(json.address.id) ? 0 : json.address.id;
                    } else if (param.object == 'invoice') {
                        Address.id_address_invoice = $.isEmpty(json.address.id) ? 0 : json.address.id;

                        if (!OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL && is_virtual_cart) {
                            Address.id_address_delivery = Address.id_address_invoice;
                        }
                    }

                    if (OnePageCheckoutPS.IS_LOGGED || OnePageCheckoutPS.IS_GUEST) {
                        var object_load = '.'+param.object+',.customer';

                        //load customer, delivery or invoice data
                        $('div#onepagecheckoutps #onepagecheckoutps_step_one').find(object_load).each(function(i, field){
                            var $field = $(field);
                            var name = $field.data('field-name');
                            var default_value = $field.data('default-value');
                            var object = '';
                            if ($field.hasClass('customer')){
                                var value = json.customer[name];
                                object = 'customer';
                            }else if ($field.hasClass('delivery')){
                                var value = json.address[name];
                                object = 'delivery';
                            }else if ($field.hasClass('invoice')){
                                var value = json.address[name];
                                object = 'invoice';
                            }

                            if (object === 'customer' && name === 'passwd') {
                                return;
                            }

                            if (object == 'invoice' && !OnePageCheckoutPS.CONFIGS.OPC_ENABLE_INVOICE_ADDRESS){
                                AppOPC.$opc_step_one.find('#invoice_id').val('');

                                return;
                            }

                            if (value == '0000-00-00') {
                                value = '';
                            }

                            if ($field.is(':checkbox')){
                                if (parseInt(value)) {
                                    $field.attr('checked', 'true');
                                } else {
                                    $field.removeAttr('checked');
                                }
                            } else if ($field.is(':radio')){
                                if ($field.val() == value) {
                                    $field.attr('checked', 'true');
                                }
                            } else {
                                if (name == 'birthday'){
                                    if (!$.isEmpty(value)) {
                                        var date_value = value.split('-');
                                        var date_string = OnePageCheckoutPS.date_format_language.replace('dd', date_value[2]);
                                        date_string = date_string.replace('mm', date_value[1]);
                                        date_string = date_string.replace('yy', date_value[0]);

                                        $field.val(date_string);
                                    }
                                }else{
                                    if (name != 'email') {
                                        $field.val(value);
                                    }

                                    if (name === 'city' && object === 'delivery' && typeof rg_chilexpress !== typeof undefined) {
                                        $('input[name="delivery_city"]').flexdatalist('value', value);
                                    }
                                }

                                //do not show values by default on input text
                                if ($field.is(':text') && !$field.hasClass('flexdatalist-alias')) {
                                    if (value == default_value) {
                                        $field.val('');
                                    }
                                }
                            }

                            if (name == 'email'){
                                if (OnePageCheckoutPS.IS_LOGGED) {
                                    $field.attr('disabled', 'true').addClass('disabled');
                                } else {
                                    $('div#onepagecheckoutps #onepagecheckoutps_step_one #customer_conf_email').val($field.val());
                                }
                            }
                        });
                    }

                    if (param.object == 'delivery' || param.object == 'invoice') {
                        Address.isNeedDniByCountryId({object: param.object});
                        Address.isNeedPostCodeByCountryId({object: param.object});

                        if (OnePageCheckoutPS.IS_LOGGED || OnePageCheckoutPS.IS_GUEST) {
                            Address.updateState({object: param.object, id_state_default: json.address['id_state']});
                        } else {
                            Address.updateState({object: param.object});
                        }
                    }
                } else {
                    Address.isNeedDniByCountryId({object: param.object});
                    Address.isNeedPostCodeByCountryId({object: param.object});
                    Address.updateState({object: param.object});
                }
            },
            complete: function(){
                if (typeof param.callback !== typeof undefined && typeof param.callback === 'function') {
                    param.callback();
                }
            }
        };
        $.makeRequest(_json);
    },
    loadAutocompleteAddress: function() {
        if (OnePageCheckoutPS.CONFIGS.OPC_AUTOCOMPLETE_GOOGLE_ADDRESS && !$.isEmpty(OnePageCheckoutPS.CONFIGS.OPC_GOOGLE_API_KEY) && typeof google.maps.places !== typeof undefined) {
            if ($('#delivery_address1').length > 0)
            {
                Address.autocomplete_delivery = new google.maps.places.Autocomplete(
                    (document.getElementById('delivery_address1')),
                    {types: ['geocode']}
                );
                google.maps.event.addListener(Address.autocomplete_delivery, 'place_changed', function() {
                    Address.fillInAddress('delivery', Address.autocomplete_delivery);
                });
            }

            if ($('#invoice_address1').length > 0)
            {
                Address.autocomplete_invoice = new google.maps.places.Autocomplete(
                    (document.getElementById('invoice_address1')),
                    {types: ['geocode']}
                );

                google.maps.event.addListener(Address.autocomplete_invoice, 'place_changed', function() {
                    Address.fillInAddress('invoice', Address.autocomplete_invoice);
                });
            }
        }
    },
    fillInAddress: function(address, autocomplete) {
        Address.componentForm = {
            administrative_area_level_1: {index: 0, type: 'select', field: address + '_id_state'},
            administrative_area_level_2: {index: 1, type: 'select', field: address + '_id_state'},
            administrative_area_level_3: {index: 2, type: 'select', field: address + '_id_state'},
            country: {index: 3, type: 'select', field: address + '_id_country'},
            locality: {index: 4, type: 'long_name', field: address + '_city'},
            postal_code: {index: 5, type: 'long_name', field: address + '_postcode'},
            street_number: {index: 6, type: 'short_name', field: address + '_address1'},
            route: {index: 7, type: 'long_name', field: address + '_address1'},
            premise: {index: 8, type: 'short_name', field: address + '_address1'}
        };

        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        //reset
        $.each(Address.componentForm, function(c, component) {
            if (component.type !== 'select' && component.field != (address + '_address1')) {
                $('#' + component.field).val('');
            }
        });

        var components = [];
        var found_address = false;
        var components_state = [];

        $.each(place.address_components, function(a, component) {
            if (typeof Address.componentForm[component.types[0]] !== typeof undefined) {
                var field = Address.componentForm[component.types[0]].field;
                var type = Address.componentForm[component.types[0]].type;
                var index = Address.componentForm[component.types[0]].index;

                if (component.types[0] == 'street_number' || component.types[0] == 'route' || component.types[0] == 'administrative_area_level_3') {
                    found_address = true;
                }

                components[index] = {
                    field: field,
                    type: type,
                    name: component.types[0],
                    short_name: component.short_name,
                    long_name: component.long_name,
                    value: (typeof component[type] !== typeof undefined) ? component[type] : component.long_name
                };
            }
        });

        $.each(components, function(c, component) {
            if (typeof component !== typeof undefined) {
                if (component.type === 'select') {
                    if (component.name === 'country') {
                        $('#' + address + '_id_country option').prop('selected', false);
                        $('#' + address + '_id_country option[data-iso-code="' + component.short_name + '"]').prop('selected', true);
                        $('#' + address + '_id_country').trigger('change');
                    } else if (typeof $('#' + address + '_id_state')[0] !== typeof undefined) {
                        components_state.push(component)

                        Address.callBackState = function() {
                            var id_state = '';

                            $.each(components_state, function(c, component_state) {
                                if ($('#' + address + '_id_state option[data-iso-code="' + component_state.short_name + '"]').length > 0) {
                                    id_state = $('#' + address + '_id_state option[data-iso-code="' + component_state.short_name + '"]').val();

                                    return false;
                                }else if ($('#' + address + '_id_state option[data-text="' + component_state.value + '"]').length > 0) {
                                    id_state = $('#' + address + '_id_state option[data-text="' + component_state.value + '"]').val();

                                    return false;
                                }
                            });

                            if (!$.isEmpty(id_state)) {
                                $('#' + address + '_id_state option').prop('selected', false);
                                $('#' + address + '_id_state').val(id_state);
                            }
                        }
                    }
                } else {
                    var tmp_value = $('#' + component.field).val();

                    if (component.field == (address + '_address1') && !$.isEmpty(tmp_value)) {
                        if (OnePageCheckoutPS.CONFIGS.OPC_SUGGESTED_ADDRESS_GOOGLE) {
                            $('#' + address + '_address1').val(tmp_value);
                        } else {
                            $('#' + address + '_address1').val(place.name);
                        }
                    } else {
                        $('#' + component.field).val(component.value).validate();
                    }
                }
            }
        });

        if (!found_address) {
            $('#' + address + '_address1').val(place.name);
        }

        //dispatch inputs events
        if (typeof is_necessary_postcode !== typeof undefined && is_necessary_postcode) {
            $('#onepagecheckoutps_step_one #'+address+'_postcode').trigger('blur');
        } else if(typeof is_necessary_city !== typeof undefined && is_necessary_city) {
            $('#onepagecheckoutps_step_one #'+address+'_city').trigger('blur');
        }
    },
    geolocate: function(event) {
        $(event.currentTarget).off('focus');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = new google.maps.LatLng(
                position.coords.latitude, position.coords.longitude);
                autocomplete.setBounds(new google.maps.LatLngBounds(geolocation,
                    geolocation));
            });
        }
    },
    updateState: function(params) {
        var param = $.extend({}, {
            object: '',
            id_state_default: '',
            id_country: ''
        }, params);

        var states = null;
        if (!$.isEmpty(param.object)) {
            var $id_country = $('div#onepagecheckoutps select#' + param.object + '_id_country');
            var $id_state = $('div#onepagecheckoutps select#' + param.object + '_id_state');
            var id_country = null;

            if ($id_country.length > 0) {
                id_country = $id_country.val();
            } else {
                if (param.object == 'delivery') {
                    id_country = OnePageCheckoutPS.id_country_delivery_default;
                } else if (param.object == 'invoice') {
                    id_country = OnePageCheckoutPS.id_country_invoice_default;
                }
            }

			var states = countriesJS[id_country];

            //delete states
            $id_state.find('option').remove();

            if (!$.isEmpty(states)) {
                //empty option
                var $option = $('<option/>')
                    .attr({
                        value: '',
                    }).append('--');
                 $option.appendTo($id_state);

                $.each(states, function(i, state) {
                    var $option = $('<option/>')
                        .attr({
                            'data-text': state.name,
                            'data-iso-code': state.iso_code,
                            value: state.id,
                        }).append(state.name);

                    if (param.id_state_default == state.id) {
                        $option.attr('selected', 'true');
                    }

                    $option.appendTo($id_state);
                });

                if (typeof Address.callBackState === 'function') {
                    Address.callBackState();
                } else {
                    //auto select state.
                    if ($.isEmpty($id_state.find('option:selected').val())){
                        var default_value = $id_state.attr('data-default-value');

                        if (default_value === '0' || (!$.isEmpty(default_value) && $id_state.find('option[value='+default_value+']').length <= 0)) {
                            $id_state.find(':eq(1)').attr('selected', 'true');
                        } else if ($.isEmpty(default_value)) {
                            $id_state.find(':eq(0)').attr('selected', 'true');
                        } else {
                            if (typeof forze_select_state !== typeof undefined && forze_select_state) {
                                $id_state.val(default_value);
                            }
                        }
                    }
                }

                if (param.object == 'delivery' || (param.object == 'invoice' && Address.isSetInvoice())){
                    $id_state.attr('data-validation', 'required').addClass('required');
                }
                $('div#onepagecheckoutps #field_' + param.object + '_id_state').find('sup').html('*');
                $('div#onepagecheckoutps #field_' + param.object + '_id_state').show();
            } else {
                $id_state.removeAttr('data-validation').removeClass('required');
                $('div#onepagecheckoutps #field_' + param.object + '_id_state').find('sup').html('');
                $('div#onepagecheckoutps #field_' + param.object + '_id_state').hide();
            }

            Address.getCitiesByState({object: param.object});
        }
    },
    checkNeedInvoice: function(params){
        var param = $.extend({}, {
        }, params);

        if (Address.isSetInvoice()) {
            Address.isNeedDniByCountryId({object: 'invoice'});
            Address.updateState({object: 'invoice'});
            Address.loadAddressesCustomer({object: 'invoice'});

            AppOPC.$opc_step_one.find('#invoice_address_container').addClass('in');

            $('div#onepagecheckoutps #panel_address_invoice').removeClass('hidden');

            $('div#onepagecheckoutps #invoice_address_container .invoice.required').each(function(i, item){
                $(item).removeAttr('data-validation-optional');
            });

            if (typeof $.totalStorageOPC !== typeof undefined) {
                $.totalStorageOPC('create_invoice_address_'+OnePageCheckoutPS.id_shop, true);
            }
        }else{
            $('div#onepagecheckoutps #panel_address_invoice').addClass('hidden');

            $('div#onepagecheckoutps #invoice_address_container .invoice.required').each(function(i, item){
                $(item).attr('data-validation-optional', 'true').trigger('reset');
            });

            if (typeof $.totalStorageOPC !== typeof undefined) {
                $.totalStorageOPC('create_invoice_address_'+OnePageCheckoutPS.id_shop, false);
            }
        }
    },
    togglePasswordRequired: function(toggle_elem) {
        if ($('div#onepagecheckoutps').find(toggle_elem).is(':checked')){
            $('div#onepagecheckoutps #field_customer_passwd, div#onepagecheckoutps #field_customer_conf_passwd, div#onepagecheckoutps #field_customer_current_passwd')
                .fadeIn()
                .addClass('required');
            $('div#onepagecheckoutps #field_customer_passwd sup, div#onepagecheckoutps #field_customer_conf_passwd sup, div#onepagecheckoutps #field_customer_current_passwd sup').html('*');
            $('div#onepagecheckoutps #customer_passwd, div#onepagecheckoutps #customer_conf_passwd, div#onepagecheckoutps #customer_current_passwd').removeAttr('data-validation-optional').val('');
        }else{
            $('div#onepagecheckoutps #field_customer_passwd, div#onepagecheckoutps #field_customer_conf_passwd, div#onepagecheckoutps #field_customer_current_passwd')
                .fadeOut()
                .removeClass('required')
                .trigger('reset');
            $('div#onepagecheckoutps #field_customer_passwd sup, div#onepagecheckoutps #field_customer_conf_passwd sup, div#onepagecheckoutps #field_customer_current_passwd sup').html('');
            $('div#onepagecheckoutps #customer_passwd, div#onepagecheckoutps #customer_conf_passwd, div#onepagecheckoutps #customer_current_passwd').attr('data-validation-optional', 'true');
        }
    },
    checkGuestAccount: function(){
        if ($('#field_customer_current_passwd').length > 0) {
            Address.togglePasswordRequired('#checkbox_change_passwd');
        } else {
            if (OnePageCheckoutPS.PRESTASHOP.CONFIGS.PS_GUEST_CHECKOUT_ENABLED){
                Address.togglePasswordRequired('#checkbox_create_account_guest');
            }else{
                if (OnePageCheckoutPS.CONFIGS.OPC_REQUEST_PASSWORD && OnePageCheckoutPS.CONFIGS.OPC_OPTION_AUTOGENERATE_PASSWORD){
                    Address.togglePasswordRequired('#checkbox_create_account');
                }
            }
        }
    },
//    checkGuestAccount: function(){
//        if (OnePageCheckoutPS.PRESTASHOP.CONFIGS.PS_GUEST_CHECKOUT_ENABLED){
//            if ($('div#onepagecheckoutps #checkbox_create_account_guest').is(':checked')){
//                $('div#onepagecheckoutps #field_customer_passwd, div#onepagecheckoutps #field_customer_conf_passwd')
//                    .fadeIn()
//                    .addClass('required');
//                $('div#onepagecheckoutps #field_customer_passwd sup, div#onepagecheckoutps #field_customer_conf_passwd sup').html('*');
//                $('div#onepagecheckoutps #customer_passwd, div#onepagecheckoutps #customer_conf_passwd').removeAttr('data-validation-optional').val('');
//            }else{
//                $('div#onepagecheckoutps #field_customer_passwd, div#onepagecheckoutps #field_customer_conf_passwd')
//                    .fadeOut()
//                    .removeClass('required')
//                    .trigger('reset');
//                $('div#onepagecheckoutps #field_customer_passwd sup, div#onepagecheckoutps #field_customer_conf_passwd sup').html('');
//                $('div#onepagecheckoutps #customer_passwd, div#onepagecheckoutps #customer_conf_passwd').attr('data-validation-optional', 'true');
//            }
//        }else{
//            if (OnePageCheckoutPS.CONFIGS.OPC_REQUEST_PASSWORD && OnePageCheckoutPS.CONFIGS.OPC_OPTION_AUTOGENERATE_PASSWORD){
//                if ($('div#onepagecheckoutps #checkbox_create_account').is(':checked')){
//                    $('div#onepagecheckoutps #field_customer_passwd, div#onepagecheckoutps #field_customer_conf_passwd')
//                        .fadeIn()
//                        .addClass('required');
//                    $('div#onepagecheckoutps #field_customer_passwd sup, div#onepagecheckoutps #field_customer_conf_passwd sup').html('*');
//                    $('div#onepagecheckoutps #customer_passwd, div#onepagecheckoutps #customer_conf_passwd').removeAttr('data-validation-optional').val('');
//                }else{
//                    $('div#onepagecheckoutps #field_customer_passwd, div#onepagecheckoutps #field_customer_conf_passwd')
//                        .fadeOut()
//                        .removeClass('required')
//                        .trigger('reset');
//                    $('div#onepagecheckoutps #field_customer_passwd sup, div#onepagecheckoutps #field_customer_conf_passwd sup').html('');
//                    $('div#onepagecheckoutps #customer_passwd, div#onepagecheckoutps #customer_conf_passwd').attr('data-validation-optional', 'true');
//                }
//            }
//        }
//    },
    isSetInvoice: function() {
        if (((OnePageCheckoutPS.CONFIGS.OPC_ENABLE_INVOICE_ADDRESS && OnePageCheckoutPS.CONFIGS.OPC_REQUIRED_INVOICE_ADDRESS) || $('div#onepagecheckoutps #checkbox_create_invoice_address').is(':checked'))
            && $('#panel_addresses_customer').length > 0
        ) {
            return true;
        }

        return false;
    },
    isNeedDniByCountryId: function(params){
        var param = $.extend({}, {
            object: ''
        }, params);

        if (!$.isEmpty(param.object)){
            var id_country = null;
            var $id_country = $('#onepagecheckoutps_step_one select#' + param.object + '_id_country');

            if ($id_country.length > 0) {
                id_country = $id_country.val();
            } else {
                if (param.object == 'delivery') {
                    id_country = OnePageCheckoutPS.id_country_delivery_default;
                } else if (param.object == 'invoice') {
                    id_country = OnePageCheckoutPS.id_country_invoice_default;
                }
            }

            if (typeof id_country !== typeof undefined) {
                if (!$.isEmpty(id_country) && typeof countriesJS !== typeof undefined && $('#field_' + param.object + '_dni').length > 0){
                    if (countriesNeedIDNumber[id_country]){
                        if ((param.object === 'invoice' && Address.isSetInvoice())
                                || param.object === 'delivery') {
                            $('#field_' + param.object + '_dni').addClass('required').show();
                            $('#field_' + param.object + '_dni sup').html('*');
                            $('#' + param.object + '_dni').removeAttr('data-validation-optional').addClass('required');
                        } else {
                            $('#field_' + param.object + '_dni').removeClass('required').hide();
                            $('#field_' + param.object + '_dni sup').html('');
                            $('#' + param.object + '_dni').attr('data-validation-optional', 'true').removeClass('required');
                        }
                    } else {
                        if ($('#' + param.object + '_dni').attr('data-required') == '0'){
                            $('#field_' + param.object + '_dni').removeClass('required');
                            $('#field_' + param.object + '_dni sup').html('');
                            $('#' + param.object + '_dni').attr('data-validation-optional', 'true').removeClass('required');
                        }
                    }
                }
            }
        }
    },
	isNeedPostCodeByCountryId: function(params){
        var param = $.extend({}, {
            object: ''
        }, params);

        if (!$.isEmpty(param.object)){
            var $id_country = AppOPC.$opc.find('select#' + param.object + '_id_country');
            var $postcode = AppOPC.$opc.find('#' + param.object + '_postcode');

            if ($id_country.length > 0) {
                id_country = $id_country.val();
            } else {
                if (param.object == 'delivery') {
                    id_country = OnePageCheckoutPS.id_country_delivery_default;
                } else if (param.object == 'invoice') {
                    id_country = OnePageCheckoutPS.id_country_invoice_default;
                }
            }

            if (typeof id_country !== typeof undefined) {
                if (!$.isEmpty(id_country) && typeof countriesJS !== typeof undefined && $postcode.length > 0) {
                    $postcode_field = AppOPC.$opc.find('#field_' + param.object + '_postcode');

                    if (!$.isEmpty(countriesNeedZipCode[id_country])){
                        var format = countriesNeedZipCode[id_country];
                        format = format.replace(/N/g, '0');
                        format = format.replace(/L/g, 'A');
                        format = format.replace(/C/g, countriesIsoCode[id_country]);
                        $postcode.attr({'data-default-value': format, 'placeholder': format});
                    }

                    if ($postcode.data('required')) {
                        $postcode_field.addClass('required').show();
                        $postcode_field.find('sup').html('*');

                        if (param.object === 'delivery' || (param.object === 'invoice' && AppOPC.$opc.find('#checkbox_create_invoice_address').is(':checked'))) {
                            $postcode.removeAttr('data-validation-optional').addClass('required');
                        }
                    } else {
                        $postcode_field.removeClass('required');
                        $postcode_field.find('sup').html('');
                        $postcode.attr('data-validation-optional', 'true').removeClass('required');
                    }
                }
            }
        }
    },
    checkEmailCustomer: function(e){
        var email = $(e.currentTarget).val();
        /*Salta validacion cuando el cliente ya esta logueado y el campo email desabilitado*/
        if ($(e.currentTarget).prop('disabled') && OnePageCheckoutPS.IS_LOGGED) {
            return true;
        }
        var data = {
            url_call: prestashop.urls.pages.order + '?checkout=1&rand=' + new Date().getTime(),
            is_ajax: true,
			dataType: 'html',
            action: 'checkRegisteredCustomerEmail',
            email: email
        };

        if (!$.isEmpty(email) && $.isEmail(email)){
            var _json = {
                data: data,
                success: function(id_customer) {
                    var callback = function(){
                        AppOPC.$opc.find('#form_login #txt_login_email').val(AppOPC.$opc.find('#customer_email').val());
                        AppOPC.$opc.find('#email_check_modal .modal-footer').append('<button type="button" class="btn btn-primary" onclick="$(\'div#onepagecheckoutps button.close\').trigger(\'click\');$(\'div#onepagecheckoutps #opc_show_login\').trigger(\'click\')" style="margin-left: 15px;">'+OnePageCheckoutPS.Msg.login_customer+'</button>');
                    }
                    var callback_close = function() {
                        $(e.currentTarget).val('').trigger('reset').focus();
                        AppOPC.$opc.find('#customer_conf_email').val('').trigger('reset');

                        return true;
                    }

                    if (id_customer != 0){
                        Fronted.showModal({name: 'email_check_modal', type:'normal', content : OnePageCheckoutPS.Msg.error_registered_email, button_close: true, callback_close: callback_close, callback: callback});
                    }
                }
            };
            $.makeRequest(_json);
        }
    },
    clearFormByObject: function(object){
        AppOPC.$opc_step_one.find('#form_address_'+object).trigger('reset');
        AppOPC.$opc_step_one.find('.addresses_customer_container .edit_address').removeAttr('disabled').removeClass('disabled');

        Address.initEventsChangeCountry(object);
    },
    updateAddress: function(params){
        var param = $.extend({}, {
            object: '',
            id_address: '',
            load_carriers: false,
            load_payments: false,
            load_review: false,
            load_addresses: false,
            update_cart: false,
            callback: ''
        }, params);

        if (OnePageCheckoutPS.IS_LOGGED) {
            if (param.object == 'delivery') {
                Fronted.validateOPC({valid_form_address_delivery: true});
            } else if (param.object == 'invoice') {
                Fronted.validateOPC({valid_form_address_invoice: true});
            }
        } else {
            AppOPC.is_valid_opc = true;
        }

        if (AppOPC.is_valid_opc) {
            var fields = Review.getFields({object: param.object});
            var rc_page = AppOPC.$opc.find('#rc_page').val();

            var data = {
                url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                is_ajax: true,
                action: 'updateAddress',
                dataType: 'json',
                id_customer: AppOPC.$opc.find('#customer_id').val(),
                id_address: (!$.isEmpty(param.id_address) ? param.id_address : AppOPC.$opc.find('#'+param.object+'_id').val()),
                object: param.object,
                update_cart: param.update_cart,
                is_set_invoice: Address.isSetInvoice(),
                fields: JSON.stringify(fields),
                rc_page: rc_page
            };

            var _json = {
                data: data,
                beforeSend: function() {
                    Fronted.loading(true, '#onepagecheckoutps_step_one_container');
                },
                success: function(json) {
                    if (json.hasError){
                        Fronted.showModal({type:'error', message : json.errors});
                    } else {
                        if (typeof json.id_address_delivery !== typeof undefined) {
                            Address.id_address_delivery = json.id_address_delivery;
                        }
                        if (typeof json.id_address_invoice !== typeof undefined) {
                            Address.id_address_invoice = json.id_address_invoice;
                        }

                        if (param.load_addresses) {
                            Address.loadAddressesCustomer({object: param.object});
                        }
                        if (param.load_carriers && !is_virtual_cart) {
                            Carrier.getByCountry();
                        }
                        if (param.load_payments || (is_virtual_cart && param.load_carriers)) {
                            PaymentOPC.getByCountry();
                        }
                        if (param.load_review && !param.load_payments) {
                            Review.display();
                        }
                    }

                },
                complete: function(){
                    Fronted.loading(false, '#onepagecheckoutps_step_one_container');
                    if (AppOPC.$opc_step_review.length <= 0 || !param.load_carriers) {
                        Fronted.loadingBig(false);
                    }

                    if (typeof param.callback !== typeof undefined && typeof param.callback === 'function') {
                        param.callback();
                    }
                }
            };
            $.makeRequest(_json);
        }
    },
    removeAddress: function(param) {
        var alias_address = AppOPC.$opc_step_one.find('#address_card_'+param.id_address+' .header_card span').text().trim();

        if (confirm(OnePageCheckoutPS.Msg.confirm_remove_address+' "'+alias_address+'"')) {
            var data = {
                url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                is_ajax: true,
                action: 'removeAddress',
                dataType: 'json',
                id_address: param.id_address
            };

            var _json = {
                data: data,
                beforeSend: function() {
                    Fronted.loading(true, '#onepagecheckoutps_step_one_container');
                },
                success: function() {
                    Address.loadAddressesCustomer({object: param.object});
                },
                complete: function(){
                    Fronted.loading(false, '#onepagecheckoutps_step_one_container');
                    Fronted.loadingBig(false);
                }
            };
            $.makeRequest(_json);

            return true;
        }

        return false;
    },
    removeAddressInvoice: function(params){
        var param = $.extend({}, {
            callback: ''
        }, params);

        if (!$('div#onepagecheckoutps #checkbox_create_invoice_address').is(':checked')){
            var data = {
                url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                is_ajax: true,
                action: 'removeAddressInvoice',
                dataType: 'html'
            };

            var _json = {
                data: data,
                beforeSend: function() {
                    Fronted.loading(true, '#onepagecheckoutps_step_one_container');
                },
                success: function() {
                    Carrier.getByCountry();
                },
                complete: function(){
                    Fronted.loading(false, '#onepagecheckoutps_step_one_container');
                    if (AppOPC.$opc_step_review.length <= 0) {
                        Fronted.loadingBig(false);
                    }

                    if (typeof param.callback !== typeof undefined && typeof param.callback === 'function')
                        param.callback();
                }
            };
            $.makeRequest(_json);
        }
    },
    cleanSpecialCharacterAddress: function(e) {
        var data = $(e.currentTarget);
        var value = data.val();
        var reg = '^[^!<>?=+@{}_$%]+$';
        var array_characters = reg.split("");

        $.each(array_characters, function(key, char) {
            for (var i = 0;i < value.length; i++) {
                value = value.replace(char, ' ');
            }

            $(e.currentTarget).val(value);
        });
    }
}

var Carrier = {
    id_delivery_option_selected : 0,
    getIdCarrierSelected: function () {
        var id_carrier = AppOPC.$opc_step_two.find('.delivery_option_radio:checked').val();
        id_carrier = id_carrier.replace(',', '');

        return parseInt(id_carrier);
    },
    launch: function(){
        if (!is_virtual_cart){
            $('div#onepagecheckoutps #gift_message').empty();

            if (!OnePageCheckoutPS.CONFIGS.OPC_SHIPPING_COMPATIBILITY) {
                AppOPC.$opc_step_two
                .on('click', '.delivery-option .delivery_option_logo', function(event){
                    var $option_radio = $(event.currentTarget).parents('.delivery-option').find('.delivery_option_radio');
                    if (!$option_radio.is(':checked')) {
                        $option_radio.attr('checked', true).trigger('change');
                    }
                })
                .on('click', '.delivery-option .carrier_delay', function(event){
                    var $option_radio = $(event.currentTarget).parents('.delivery-option').find('.delivery_option_radio');
                    if (!$option_radio.is(':checked')) {
                        if ($(event.currentTarget).find('#selulozenka, #paczkomatyinpost_selected, .btn.btn-warning').length <= 0) {//support module 'ulozenka'
                            $option_radio.attr('checked', true).trigger('change');
                        }
                    }
                })
                .on('click', '.delivery-option', function (event){
                    if (typeof showWidgetMr !== typeof undefined) {
                        showWidgetMr();
                    }
                })
                .on('change', '.delivery_option_radio', function (event){
                    $('div#onepagecheckoutps #onepagecheckoutps_step_two .delivery-option').removeClass('selected alert alert-info');
                    $(this).parent().parent().parent().addClass('selected alert alert-info');

                    Carrier.update({delivery_option_selected: $(event.currentTarget), load_carriers: true, load_payments: false, load_review: false});
                })
                .on('change', '#recyclable', Carrier.update)
                .on('blur', '#gift_message', Carrier.update)
                .on('blur', '#id_planning_delivery_slot', Carrier.update)//support module planningdeliverycarrier
                .on('click', '#gift', function (event){
                    Carrier.update({load_payments : true});

                    if ($(event.currentTarget).is(':checked'))
                        $('div#onepagecheckoutps #gift_div_opc').removeClass('hidden');
                    else
                        $('div#onepagecheckoutps #gift_div_opc').addClass('hidden');
                });
            }

            if (OnePageCheckoutPS.CONFIGS.OPC_SHIPPING_COMPATIBILITY) {
                AppOPC.$opc_step_two.on('click', '#show_carrier_embed', function() {
                    AppOPC.$opc_step_two.empty();
                    AppOPC.$opc_step_two.html('<div class="row"><div class="col-xs-12 btn-secondary" id="show_carrier_embed"><span><i class="fa-pts fa-pts-check-square-o"></i>&nbsp;'+OnePageCheckoutPS.Msg.choose_carrier_embed+'</span></div></div>');

                    $('#opc_shipping_compability').show(400);
                    window.scrollTo(0, $('#opc_shipping_compability').offset().top - 150);
                    AppOPC.$opc.find('#onepagecheckoutps_header, #onepagecheckoutps_contenedor').hide(400);
                });
                $('#opc_shipping_compability').on('click', '#hide_carrier_embed', function() {
                    Carrier.getByCountry({reset_carrier_embed: false});

                    $('#opc_shipping_compability').hide(400);
                    AppOPC.$opc.find('#onepagecheckoutps_header, #onepagecheckoutps_contenedor').show(400);
                });
            }
        }
    },
    getByCountry: function(params){
        var param = $.extend({}, {
            callback: '',
            reset_carrier_embed: true
        }, params);

        if (register_customer)
            return;

        if (!is_virtual_cart){
            var extra_params = '';
            $.each(document.location.search.substr(1).split('&'),function(c,q){
                if (q != undefined && q != ''){
                    var i = q.split('=');
                    if ($.isArray(i)){
                        extra_params += '&' + i[0].toString();
                        if (typeof i[1] !== "undefined" && i[1].toString() != undefined)
                            extra_params += '=' + i[1].toString();
                    }
                }
            });

            var data = {
                url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime() + extra_params,
                is_ajax: true,
                action: 'loadCarrier',
                dataType: 'html'
            };

            var _json = {
                data: data,
                beforeSend: function() {
                    Fronted.loading(true, '#onepagecheckoutps_step_two_container');
                },
                success: function(html) {
                    if (!$.isEmpty(html)) {

                        if (OnePageCheckoutPS.CONFIGS.OPC_SHIPPING_COMPATIBILITY && param.reset_carrier_embed) {
                            var $content_carrier = $('<div/>').html(html);

                            if ($content_carrier.find('.alert.alert-warning').length <= 0) {
                                AppOPC.$opc_step_two.empty();
                                AppOPC.$opc_step_two.html('<div class="pts-btn-secondary" id="show_carrier_embed"><span><i class="fa-pts fa-pts-check-square-o"></i>&nbsp;'+OnePageCheckoutPS.Msg.choose_carrier_embed+'</span></div>');

                                PaymentOPC.getByCountry();

                                return;
                            }
                        }

                        AppOPC.$opc_step_two.html(html);

                        //support module: pakkelabels_shipping - v1.3.0 - Pakkelabels.dk
                        AppOPC.$opc_step_two.find('.delivery_option_radio:checked').trigger('click');

                        if (AppOPC.$opc_step_two.find('#gift').is(':checked')) {
                            AppOPC.$opc_step_two.find('#gift_div_opc').show();
                        }
                        if (!OnePageCheckoutPS.CONFIGS.OPC_SHIPPING_COMPATIBILITY) {
                            //support module deliverydays
                            if(AppOPC.$opc_step_two.find('#deliverydays_day option').length > 1){
                                AppOPC.$opc_step_two.find('#deliverydays_day option:eq(1)').attr('selected', 'true');
                            }

                            /* support module deliverydate - V1.6.2 de MARICHAL Emmanuel */
                            if (typeof deliveries !== typeof undefined && typeof changeDeliveryDate !== typeof undefined) {
                                if (typeof deliverydate_position !== typeof undefined) {
                                    if (deliverydate_position == 'bottom') {
                                        moveDates();

                                        if (typeof deliverydate_reason !== typeof undefined) {
                                            if (!$.isEmpty(deliverydate_reason)) {
                                                moveReason();
                                            }
                                        }
                                    }
                                }

                                changeDeliveryDate();
                            }

                            if (AppOPC.$opc_step_two.find('.delivery_option_radio').length > 0) {
                                Carrier.update({load_payments: true});
                            } else {
                                PaymentOPC.getByCountry();
                            }
                        } else {
                            PaymentOPC.getByCountry();
                        }
                    }
                },
                complete: function(){
                    Fronted.loading(false, '#onepagecheckoutps_step_two_container');

                    $(document).trigger('opc-load-carrier:completed', {});

                    if (!OnePageCheckoutPS.CONFIGS.OPC_SHIPPING_COMPATIBILITY) {
                        if (typeof frontDeliveryTimeLink !== typeof undefined) {
                            $('div#onepagecheckoutps .delivery_option_radio[value="'+id_carrier_selected+'"]').trigger('click');
                        }

                        //Compatibility Mondial Relay
                        if ($('#onepagecheckoutps_step_two .delivery-option.selected input[name="mondialrelay"]').length > 0
                            && typeof checkMrSelection !== typeof undefined
                        ) {
                            showWidgetMr();
                        }
                    }

                    //support module: seur - v2.0.3 - Línea Grafica
                    if (typeof initSeurCarriers !== typeof undefined) {
                        initSeurCarriers();
                    }

                    /* support module: boxtalconnect - v1.0.5 - de Boxtal */
                    if (typeof bxParcelPoint !== typeof undefined && typeof bxParcelPoint.initCarriers !== typeof undefined) {
                        bxParcelPoint.initCarriers();
                    }

                    if (typeof param.callback !== typeof undefined && typeof param.callback === 'function') {
                        param.callback();
                    }
                }
            };
            $.makeRequest(_json);
        }else{
            PaymentOPC.getByCountry();
            Review.display();
        }
    },
    update: function(params){
        var param = $.extend({}, {
            delivery_option_selected: $('div#onepagecheckoutps .delivery_option_radio:checked'),
            load_carriers: false,
            load_payments: false,
            load_review: true,
            callback: ''
        }, params);

        if (!is_virtual_cart){
            var data = {
                url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                is_ajax: true,
                action: 'updateCarrier',
                dataType: 'html',
                recyclable: ($('#recyclable').is(':checked') ? $('#recyclable').val() : ''),
                gift: ($('#gift').is(':checked') ? $('#gift').val() : ''),
                gift_message: (!$.isEmpty($('#gift_message').val()) ? $('#gift_message').val() : '')
            };

            if ($(param.delivery_option_selected).length > 0)
                data[$(param.delivery_option_selected).attr('name')] = $(param.delivery_option_selected).val();

            $('#onepagecheckoutps_step_two input[type="text"]:not(.customer, .delivery, .invoice),#onepagecheckoutps_step_two input[type="hidden"]:not(.customer, .delivery, .invoice), #onepagecheckoutps_step_two select:not(.customer, .delivery, .invoice)').each(function(i, input){
                var name = $(input).attr('name');
                var value = $(input).val();

                if (!$.isEmpty(name))
                    data[name] = value;
            });

            var _json = {
                data: data,
                beforeSend: function() {
                    Fronted.loading(true, '#onepagecheckoutps_step_two_container');
                },
                success: function(json) {
                    if (json.hasError){
                        Fronted.showModal({type:'error', message : json.errors});
                    } else if (json.hasWarning){
                        Fronted.showModal({type:'warning', message : json.warnings});
                    }
                },
                complete: function(){
                    Fronted.loading(false, '#onepagecheckoutps_step_two_container');

                    if ( typeof mustCheckOffer !== 'undefined' && event_dispatcher !== undefined && event_dispatcher === 'carrier' && AppOPC.load_offer ) {
                        AppOPC.load_offer = false;
                        mustCheckOffer = undefined;
                        checkOffer(function() {
                            //Fronted.closeDialog();
                        });
                    }

                    if(param.load_carriers)
                        Carrier.getByCountry();
                    if(param.load_payments)
                        PaymentOPC.getByCountry();
                    if(param.load_review && !param.load_payments)
                        Review.display();

                    if (typeof param.callback !== typeof undefined && typeof param.callback === 'function')
                        param.callback();

                    $(document).trigger('opc-update-carrier:completed', {});
                }
            };
            $.makeRequest(_json);
        }
    }
}

var PaymentOPC = {
    id_payment_selected: '',
    name_module_selected: '',
    launch: function(){
        $("div#onepagecheckoutps #onepagecheckoutps_step_three")
            .on('click', '.module_payment_container', function(event){
                if (!$(event.target).hasClass('payment_radio')) {
//                    $(event.currentTarget).find('.payment_radio').attr('checked', true).trigger('change');
                    $(event.currentTarget).find('.payment_radio').trigger('click').trigger('change');
                }
            })
            .on("change", "input[name=payment-option]", function(){
                $('div#onepagecheckoutps #onepagecheckoutps_step_review .extra_fee').addClass('hidden');
                $('div#onepagecheckoutps #onepagecheckoutps_step_review .extra_fee_tax').addClass('hidden');

                PaymentOPC.id_payment_selected = $(this).attr('id');
                PaymentOPC.name_module_selected = $(this).val();

                $('div#onepagecheckoutps #onepagecheckoutps_step_three .module_payment_container').removeClass('selected alert alert-info');
                $('div#onepagecheckoutps #onepagecheckoutps_step_three .payment_content_html').addClass('hidden');
                $('div#onepagecheckoutps #onepagecheckoutps_step_three .js-payment-option-form').addClass('hidden').removeClass('ps-hidden');

                $(this).parents('.module_payment_container').addClass('selected alert alert-info').find('.payment_content_html, .js-payment-option-form').show().removeClass('hidden');

                Review.showRowsExtraFee();

                //support module: braintreejs - v3.0.5 - Bellini Services
                if (PaymentOPC.name_module_selected == 'braintreejs') {
                    if (typeof braintreeHostedSetup == 'function') {
                        braintreeHostedSetup();
                    } else if (typeof braintreePaypalSetup == 'function') {
                        braintreePaypalSetup();
                    } else if (typeof braintreeSetup == 'function') {
                        braintreeSetup();
                    }
                }

                /* support module orderfees_payment - V1.8.14 de motionSeed */
                if (orderfees_payment_installed) {
                    Review.display();
                }
                /* Support module orderfees - V1.8.51 de motionSeed */
                if (orderfees_installed) {
                    var time_out = setTimeout(function() {
                        Review.display();
                        clearTimeout(time_out);
                    }, 300);
                }
            });
    },
    getByCountry: function(params){
        var param = $.extend({}, {
            callback: '',
            show_loading: true
        }, params);

        if (register_customer) {
            return;
        }

        if (!is_virtual_cart) {
            if ($('div#onepagecheckoutps #onepagecheckoutps_step_two').find('.delivery_option_radio').length <= 0) {
                $('div#onepagecheckoutps #onepagecheckoutps_step_three').html('<p class="alert alert-warning col-xs-12">'+OnePageCheckoutPS.Msg.shipping_method_required+'</p>');

                Review.display();
                return;
            }
        }

        var extra_params = '';
        $.each(document.location.search.substr(1).split('&'),function(c,q){
            if (q != undefined && q != ''){
                var i = q.split('=');
                if ($.isArray(i)){
                    extra_params += '&' + i[0].toString();
                    if (typeof i[1] !== "undefined" && i[1].toString() != undefined)
                        extra_params += '=' + i[1].toString();
                }
            }
        });

        var data = {
            url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime() + extra_params,
            is_ajax: true,
            dataType: 'html',
            action: 'loadPayment'
        };

        var _json = {
            data: data,
            beforeSend: function() {
                if(param.show_loading) {
                    Fronted.loading(true, '#onepagecheckoutps_step_three_container');
                }
            },
            success: function(html) {
                $('div#onepagecheckoutps #onepagecheckoutps_forms').html('');
                $('div#onepagecheckoutps #onepagecheckoutps_step_three').html(html);

                $('div#onepagecheckoutps #onepagecheckoutps_step_three .module_payment_container').removeClass('selected alert alert-info');
                $('div#onepagecheckoutps #onepagecheckoutps_step_three .payment_content_html').addClass('hidden');
                $('div#onepagecheckoutps #onepagecheckoutps_step_three .js-payment-option-form').addClass('hidden');
                $('div#onepagecheckoutps #onepagecheckoutps_step_three .module_payment_container.selected').find('.payment_content_html').removeClass('hidden');

                if (!$.isEmpty(PaymentOPC.id_payment_selected)){
                    $('div#onepagecheckoutps #onepagecheckoutps_step_three #payment_method_container #' + PaymentOPC.id_payment_selected).parent().parent().trigger('click');
                } else if ($('#onepagecheckoutps_step_three #payment_method_container .module_payment_container').length == 1){
                    $('#onepagecheckoutps_step_three #payment_method_container .module_payment_container').trigger('click');
                } else if (!$.isEmpty(OnePageCheckoutPS.CONFIGS.OPC_DEFAULT_PAYMENT_METHOD)){
                    $('div#onepagecheckoutps #onepagecheckoutps_step_three #payment_method_container [value="'+ OnePageCheckoutPS.CONFIGS.OPC_DEFAULT_PAYMENT_METHOD + '"]').parent().parent().trigger('click');
                }
            },
            complete: function(){
                if (param.show_loading) {
                    Fronted.loading(false, '#onepagecheckoutps_step_three_container');
                }

                $(document).trigger('opc-load-payment:completed', {});

                if (typeof param.callback !== typeof undefined && typeof param.callback === 'function') {
                    param.callback();
                } else {
                    Review.display();
                }

                if (typeof stripe_isInit !== typeof undefined && typeof StripePubKey !== typeof undefined && typeof initStripeOfficial !== typeof undefined) {
                    //if (!stripe_isInit) {
                    if (StripePubKey && typeof stripe_v3 !== 'object') {
                        stripe_v3 = Stripe(StripePubKey);
                    }
                    initStripeOfficial(stripe_v3);

                    if (!OnePageCheckoutPS.IS_GUEST && !OnePageCheckoutPS.IS_LOGGED){
                        $('#stripe-payment-form .stripe-name').val("");
                    }

                    if (typeof initStripeOfficialGiropay === 'function') {
                        initStripeOfficialGiropay();
                    }
                    //}
                }

                if (typeof initBraintreeCard !== typeof undefined) {
                    initBraintreeCard();
                }
                if (typeof initPayPalPlus !== typeof undefined) {
                    initPayPalPlus();
                }
                if (typeof initStripeCheckout !== typeof undefined) {
                    initStripeCheckout();
                }
                if (typeof initStripePrestaShop !== typeof undefined) {
                    initStripePrestaShop();
                }

                OPC_Compatibilities.execute('payment');
            }
        };
        $.makeRequest(_json);
    },
    change: function(){
        if ( !AppOPC.load_offer || typeof mustCheckOffer === 'undefined' || (event_dispatcher !== undefined && event_dispatcher !== 'payment_method') ) {
//            PaymentOPC.validateSelected();
        } else {
            AppOPC.load_offer = false;
            checkOffer(function() {
//                PaymentOPC.validateSelected();
            });
        }
    }
}

var Review = {
    message_order: '',
    launch: function(){
        AppOPC.$opc_step_review.find('.remove-from-cart').off('click');

        AppOPC.$opc
            .on('click', '#conditions-to-approve a', function (e){
                e.preventDefault();
                e.stopPropagation();
//                Fronted.openCMS({id_cms : OnePageCheckoutPS.CONFIGS.OPC_ID_CMS_TEMRS_CONDITIONS});

                var link_conditions = $(e.currentTarget).attr('href');
                $.get(link_conditions+"?content_only=1",function (data){
                    Fronted.showModal({name: 'cms_modal', content: $(data).find('.content-only').html()});
                });
            })
            .on("click", "#btn_place_order", function() {
                Review.placeOrder();
            })
            .on("change", '#cgv', function(e) {
                if ( typeof mustCheckOffer !== 'undefined' && event_dispatcher !== undefined && event_dispatcher === 'terms' && AppOPC.load_offer ) {
                    if ( $(e.target).is(':checked') ) {
                        if ( !offerApplied ) {
                            AppOPC.load_offer = false;
                            checkOffer(function() {
                                $(e.target).unbind('change');
                            });
                        }
                    }
                }
            });

        AppOPC.$opc_step_review
            .on('click', '.bootstrap-touchspin-up, .bootstrap-touchspin-down, .remove-from-cart', function(e){
                e.preventDefault();
                e.stopPropagation();

                var url_call = '';
                var $input = $(e.currentTarget).parents('.bootstrap-touchspin').find('.cart-line-product-quantity');

                if ($(e.currentTarget).hasClass('bootstrap-touchspin-up')) {
                    url_call = $input.data('up-url');
                } else if ($(e.currentTarget).hasClass('bootstrap-touchspin-down')) {
                    url_call = $input.data('down-url');
                } else {
                    url_call = $(e.currentTarget).attr('href');
                }

                var _json = {
                    data: {
                        url_call: url_call,
                        action: 'update',
                        ajax: 1,
                        token: static_token
                    },
                    beforeSend: function() {
                        Fronted.loading(true, '#onepagecheckoutps_step_review_container');
                    },
                    success: function(json) {
                        if (json.success) {
                            Review.updateCartSummary(json);
                        } else if (json.hasError && (json.errors.length > 0 || Object.keys(json.errors).length > 0)) {
                            $(e.currentTarget).val(json.quantity);

                            if (typeof json.errors.length === typeof undefined) {
                                var errors = new Array();
                                $.each(json.errors, function(i, err) {
                                    errors.push(err);
                                });
                            } else {
                                var errors = json.errors;
                            }

                            Fronted.showModal({type:'error', message : '&bullet; ' + errors.join('<br>&bullet; ')});
                            Fronted.loading(false, '#onepagecheckoutps_step_review_container');
                        }
                    }
                };
                $.makeRequest(_json);
            })
            .on("click", "#submitAddDiscount, .cart_discount .cart_quantity_delete", Review.processDiscount)
            .on("click", "#payment_paypal_express_checkout", function(){
                $('#paypal_payment_form').submit();
            })
            .on('blur', '.cart-line-product-quantity', function(e){
                var before_qty = $(e.currentTarget).attr('value');
                var actual_qty = $(e.currentTarget).val();

                if (actual_qty == 0) {
                    $(e.currentTarget).val(before_qty);
                } else {
                    var operation = 'down';
                    var qty = actual_qty - before_qty;

                    if (qty != 0) {
                        var url_call = $(e.currentTarget).data('update-url');

                        if (qty > 0) {
                            operation = 'up';
                        }

                        var _json = {
                            data: {
                                url_call: url_call,
                                action: 'update',
                                ajax: 1,
                                token: static_token,
                                op: operation,
                                qty:  Math.abs(qty)
                            },
                            beforeSend: function() {
                                AppOPC.$opc.find('#btn_place_order').attr('disabled', 'true');
                                Fronted.loading(true, '#onepagecheckoutps_step_review_container');
                            },
                            success: function(json) {
                                if (json.success) {
                                    Review.updateCartSummary(json);
                                } else if (json.hasError && json.errors.length > 0) {
                                    $(e.currentTarget).val(json.quantity);

                                    Fronted.showModal({type:'error', message : '&bullet; ' + json.errors.join('<br>&bullet; ')});

                                    Fronted.loading(false, '#onepagecheckoutps_step_review_container');
                                }
                            }
                        };
                        $.makeRequest(_json);
                    }
                }
            })
            .on("blur", "#div_leave_message #message", function(){
                Review.message_order = $(this).val();
            });
    },
    showRowsExtraFee: function() {
        $.each(payment_modules_fee, function(name_module_fee, payment) {
            if (PaymentOPC.name_module_selected == name_module_fee) {
                $('div#onepagecheckoutps #onepagecheckoutps_step_review .extra_fee').removeClass('hidden');
                $('div#onepagecheckoutps #onepagecheckoutps_step_review #extra_fee_label').text(payment.label_fee);
                $('div#onepagecheckoutps #onepagecheckoutps_step_review #extra_fee_price').text(payment.fee);
                $('div#onepagecheckoutps #onepagecheckoutps_step_review #extra_fee_total_price_label').text(payment.label_total);
                $('div#onepagecheckoutps #onepagecheckoutps_step_review #extra_fee_total_price').text(payment.total_fee);

                if (typeof payment.fee_tax !== typeof undefined && !$.isEmpty(payment.fee_tax)) {
                    $('div#onepagecheckoutps #onepagecheckoutps_step_review .extra_fee_tax').removeClass('hidden');
                    $('div#onepagecheckoutps #onepagecheckoutps_step_review #extra_fee_tax_label').text(payment.label_fee_tax);
                    $('div#onepagecheckoutps #onepagecheckoutps_step_review #extra_fee_tax_price').text(payment.fee_tax);
                }
            }
        });
    },
    updateCartSummary: function (json){
        //update cart
        if ($('.blockcart').length > 0) {
            var refreshURL = $('.blockcart').data('refresh-url');

            $.post(refreshURL, {}).then(function (resp) {
                $('.blockcart').replaceWith($(resp.preview).find('.blockcart'));
            });
        }

        if (typeof json !== typeof undefined) {
            if (typeof json.cart !== typeof undefined && json.cart.is_virtual){
                $('#onepagecheckoutps_step_two_container').remove();
                $('#onepagecheckoutps_step_three_container').removeClass('col-md-6');

                if (!OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL) {
                    AppOPC.$opc_step_one.find('#panel_address_delivery').remove();
                }

                is_virtual_cart = true;

                PaymentOPC.getByCountry();
                Review.display();
            } else {
                if (typeof json.load === typeof undefined){
                    Fronted.loading(true, '#onepagecheckoutps_step_review_container');

                    Carrier.getByCountry();
                }
            }
        }
        //Esta funcionalidad es para actualizar productos en el carrito en la app
        total_productos = prestashop.cart.products_count
        parent.postMessage("cart-" + total_productos,"*");
    },
    display: function(params){
        var param = $.extend({}, {
            callback: ''
        }, params);

        if (register_customer) {
            return;
        }
        if (OnePageCheckoutPS.CONFIGS.OPC_ENABLE_TERMS_CONDITIONS) {
            var privacy_policy = AppOPC.$opc.find('#privacy_policy').is(':checked');
        }
        if (OnePageCheckoutPS.CONFIGS.OPC_ENABLE_PRIVACY_POLICY) {
            var cgv = AppOPC.$opc.find('#cgv').is(':checked');
        }

        var data = {
            url_call: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
            is_ajax: true,
            dataType: 'html',
            action: 'loadReview'
        };

        /* support module orderfees_payment - V1.8.14 de motionSeed */
        if (orderfees_installed) {
            data['orderfees_payment_method'] = $('#onepagecheckoutps input[name="payment-option"]:checked').data('module-name');
        }

        var _json = {
            data: data,
            beforeSend: function() {
                Fronted.loading(true, '#onepagecheckoutps_step_review_container');
            },
            success: function(html) {
                $("div#onepagecheckoutps #onepagecheckoutps_step_review").html(html);

                if (OnePageCheckoutPS.CONFIGS.OPC_COMPATIBILITY_REVIEW) {
                    $('.js-cart-line-product-quantity').TouchSpin({
                        verticalbuttons:!0,
                        verticalupclass:"material-icons touchspin-up",
                        verticaldownclass:"material-icons touchspin-down",
                        buttondown_class:"btn btn-touchspin js-touchspin js-increase-product-quantity",
                        buttonup_class:"btn btn-touchspin js-touchspin js-decrease-product-quantity",
                        min:parseInt($('.js-cart-line-product-quantity').attr("min"),10),
                        max:1e6
                    });
                }
                if (!OnePageCheckoutPS.CONFIGS.OPC_COMPATIBILITY_REVIEW && AppOPC.$opc_step_review.find('#order-detail-content .cart_item').length <= 0 ) {
                    window.parent.location.reload();
                }

                if (OnePageCheckoutPS.CONFIGS.OPC_ENABLE_TERMS_CONDITIONS && cgv) {
                    $('div#onepagecheckoutps #cgv').attr('checked', 'true');
                }

                if (OnePageCheckoutPS.CONFIGS.OPC_ENABLE_PRIVACY_POLICY && privacy_policy) {
                    AppOPC.$opc.find('#privacy_policy').attr('checked', 'true');
                }

                //si el metodo de pago necesita cargar un nuevo valor, ya sea porque cambio de pais u otra cosa.
                $.each(payment_modules_fee, function(name_module_fee, payment) {
                    if (payment.reload) {
                        $.ajax({
                            type: 'POST',
                            url: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                            cache: false,
                            dataType : "json",
                            data: 'is_ajax=true'
                                +'&action=addModulesExtraFee'
                                +'&token='+static_token
                                +'&allow_refresh=1',
                            success: function(jsonData)
                            {
                                payment_modules_fee = jsonData;

                                Review.showRowsExtraFee();
                            }
                        });

                        return false;
                    }

                    Review.showRowsExtraFee();
                });
            },
            complete: function(){
                Fronted.loading(false, '#onepagecheckoutps_step_review_container');
                Fronted.loadingBig(false);

                if (!is_virtual_cart) {
                    if (AppOPC.$opc_step_two.find('.delivery-options .delivery-option').length <= 0) {
                        AppOPC.$opc_step_review.find('#remaining_amount_free_shipping').hide();
                        AppOPC.$opc_step_review.find('.item_total:not(.cart_total_product, .cart_total_voucher, .cart_discount)').hide();
//                        AppOPC.$opc_step_review.find('#list-voucher-allowed').hide();
                    }
                }

                //remove express checkout paypal on review
                $('#container_express_checkout').remove();

                if (OnePageCheckoutPS.CONFIGS.OPC_SHOW_ZOOM_IMAGE_PRODUCT) {
                    //image zoom on product list.
                    $('div#onepagecheckoutps #order-detail-content .cart_item a > img').mouseenter(function(event){
                        $('div#onepagecheckoutps #order-detail-content .image_zoom').hide();
                        $(event.currentTarget).parents('.image_product').find('.image_zoom').show();
                    });
                    $('div#onepagecheckoutps #order-detail-content .image_zoom').click(function(event){
                        $(event.currentTarget).toggle();
                    });
                    $('div#onepagecheckoutps #order-detail-content .image_zoom').hover(function(event){
                        $(event.currentTarget).show();
                    }, function(event){
                        $(event.currentTarget).hide();
                    });
                }

                var intervalLoadJavaScriptReview = setInterval(
                    function() {
                        loadJavaScriptReview();
                        clearInterval(intervalLoadJavaScriptReview);
                    }
                    , (typeof csoc_prefix !== 'undefined' ? 5001 : 0));

                //last minute opc
                if ( typeof mustCheckOffer !== 'undefined' && event_dispatcher !== undefined && event_dispatcher === 'init' && AppOPC.load_offer ) {
                    AppOPC.load_offer = false;
                    mustCheckOffer = undefined;

                    setTimeout(checkOffer, time_load_offer * 1000);
                }

                if (OnePageCheckoutPS.CONFIGS.OPC_CONFIRMATION_BUTTON_FLOAT){
                    var $container_float_review = $("div#onepagecheckoutps div#onepagecheckoutps_step_review #container_float_review");
                    var $container_float_review_point = $("div#onepagecheckoutps div#onepagecheckoutps_step_review #container_float_review_point");

                    $(window).scroll(function() {
                        var time_out = setTimeout(function() {
                            if (AppOPC.$opc.find('.loading_big').is(':visible')) {
                                $container_float_review.removeClass('stick_buttons_footer');
                            } else {
                                if (!$container_float_review_point.visible() && $(window).height() > 640) {
                                    if ($container_float_review_point.offset().top > $(window).scrollTop()){
                                        $container_float_review.addClass('stick_buttons_footer').css({width : $('#onepagecheckoutps_step_review').outerWidth()});
                                    }
                                } else {
                                    $container_float_review.removeClass('stick_buttons_footer').removeAttr('style');
                                }
                            }
                            clearTimeout(time_out);
                        }, 400);
                    });

                    $(window).resize(function(){
                        $(window).trigger('scroll');
                    });
                    $(window).trigger('scroll');
                }

                if (typeof FB !== typeof undefined && typeof FB.XFBML.parse == 'function') {
                    FB.XFBML.parse();
                }

                if (!$.isEmpty(Review.message_order)) {
                    $('div#onepagecheckoutps #onepagecheckoutps_step_review_container #message').val(Review.message_order);
                }

                if (typeof getAppliedOffers !== typeof undefined && typeof getAppliedOffers === 'function') {
                     getAppliedOffers();
                }

                if (typeof DORCORE !== typeof undefined) {
                    DORCORE.init();
                }
                if (typeof SUGGESTION !== typeof undefined) {
                    SUGGESTION.init();
                }

                //$('#btn_continue_shopping').attr('data-link', document.referrer);

                $(document).trigger('opc-load-review:completed', {});

                if (typeof initEventsKbstorelocatorpickup !== typeof undefined) {
                    initEventsKbstorelocatorpickup();
                }

                if (typeof param.callback !== typeof undefined && typeof param.callback === 'function')
                    param.callback();
            }
        };
        $.makeRequest(_json);
    },
    processDiscount: function(e) {
        $element = $(e.currentTarget);

        var _data = {
             url_call: prestashop.urls.pages.cart,
            action: 'update',
            ajax: 1,
            token: static_token
        }

        if ($element.is('i')) {
            _data.deleteDiscount = $element.data('id-cart-rule');
        } else {
            _data.addDiscount = 1;
            _data.discount_name = AppOPC.$opc_step_review.find('#discount_name').val();
        }

        var _json = {
            data: _data,
            beforeSend: function() {
                Fronted.loading(true, '#onepagecheckoutps_step_review_container');
            },
            success: function(json) {
                if (json.hasError){
                    Fronted.loading(false, '#onepagecheckoutps_step_review_container');
                    Fronted.showModal({type:'error', message : '&bullet; ' + json.errors.join('<br>&bullet; ')});
                } else {
                    if ($('#onepagecheckoutps_step_two #input_virtual_carrier').length > 0){
                        PaymentOPC.getByCountry();
                    }else{
                        Carrier.getByCountry();
                    }
                }
            },
            complete: function(){
                $('#onepagecheckoutps_step_review #submitAddDiscount').attr('disabled', false);
            }
        };
        $.makeRequest(_json);
    },
    getFields: function(params){
        var param = $.extend({}, {
            object: ''
        }, params);

        var fields = Array();

        var $selector = $('div#onepagecheckoutps div#onepagecheckoutps_step_one .customer, \n\
            div#onepagecheckoutps div#onepagecheckoutps_step_one .delivery, \n\
            div#onepagecheckoutps div#onepagecheckoutps_step_one .invoice');

        if (param.object == 'customer') {
            $selector = AppOPC.$opc_step_one.find('.customer');
        } else if (param.object == 'delivery') {
            $selector = AppOPC.$opc_step_one.find('.delivery');
        } else if (param.object == 'invoice') {
            $selector = AppOPC.$opc_step_one.find('.invoice');
        }

        $selector.each(function(i, field){
            if ($(field).is('span'))
                return true;

            var name = $(field).attr('data-field-name');
            var value = '';
            var object = '';

            if ($.isEmpty(name))
                return true;

            if ($(field).hasClass('customer')){
                object = 'customer';
            }else if ($(field).hasClass('delivery')){
                object = 'delivery';
            }else if ($(field).hasClass('invoice')){
                object = 'invoice';
            }

            if (object == 'invoice' && !Address.isSetInvoice()) {
                return true;
            }

            if (($('div#onepagecheckoutps #field_customer_checkbox_change_passwd input[name="checkbox_change_passwd"]').length > 0
                && !$('div#onepagecheckoutps #field_customer_checkbox_change_passwd input[name="checkbox_change_passwd"]').is(':checked'))
                || ($('div#onepagecheckoutps #field_customer_checkbox_create_account input[name="checkbox_create_account"]').length > 0
                && !$('div#onepagecheckoutps #field_customer_checkbox_create_account input[name="checkbox_create_account"]').is(':checked'))
                || ($('div#onepagecheckoutps #field_customer_checkbox_create_account_guest input[name="checkbox_create_account_guest"]').length > 0
                && !$('div#onepagecheckoutps #field_customer_checkbox_create_account_guest input[name="checkbox_create_account_guest"]').is(':checked'))
            ) {
                if (name == 'current_passwd' || name == 'passwd_confirmation' || name == 'passwd') {
                    return true;
                }
            }

            if (!$.isEmpty(object)){
                if ($(field).is(':checkbox')){
                    value = $(field).is(':checked') ? 1 : 0;
                }else if ($(field).is(':radio')){
                    var tmp_value = $('input[name="' + name + '"]:checked').val();
                    if (typeof tmp_value !== typeof undefined)
                        value = tmp_value;
                }else{
                    value = $(field).val();

                    if (value === null)
                        value = '';
                }

                if ($.strpos(value, '\\')){
                    value = addslashes(value);
                }

                if ($.strpos(value, '\n')){
                    value = value.replace(/\n/gi, '\\n');
                }

                if (!$.isEmpty(value) && typeof value == 'string'){
                    value = value.replace(/\"/g, '\'');
                }

                value = $.trim(value);

                if ($.isEmpty(value) && $(field).data('required') == 1) {
                    value = $(field).data('default-value');
                }

                fields.push({'object' : object, 'name' : name, 'value' : value});
            }
        });

        return fields;
    },
    getFieldsExtra: function(_data){
        $('div#onepagecheckoutps input[type="text"]:not(.customer, .delivery, .invoice), div#onepagecheckoutps input[type="hidden"]:not(.customer, .delivery, .invoice), div#onepagecheckoutps select:not(.customer, .delivery, .invoice)').each(function(i, input){
            var name = $(input).attr('name');
            var value = $(input).val();

            if (name == 'action' || name === 'controller' || (name === 'module' && value === 'sisow') || (name === 'fc' && value === 'module')) {
                return true;
            }

            //compatibilidad modulo eydatepicker
            if (name == 'shipping_date_raw')
                name = 'shipping_date';

            if (!$.isEmpty(name))
                _data[name] = value;
        });

        $('div#onepagecheckoutps input[type="checkbox"]:not(.customer, .delivery, .invoice)').each(function(i, input){
            var name = $(input).attr('name');
            var value = $(input).is(':checked') ? $(input).val() : '';

            if (!$.isEmpty(name))
                _data[name] = value;
        });

        $('div#onepagecheckoutps input[type="radio"]:not(.customer, .delivery, .invoice):checked').each(function(i, input){
            var name = $(input).attr('name');
            var value = $(input).val();

            if (!$.isEmpty(name))
                _data[name] = value;
        });

        delete _data['id_customer'];
        _data['id_customer'];
        _data['id_customer'];

        return _data;
    },
    placeOrder: function(params){
        var param = $.extend({}, {
            validate_payment: true,
            position_element: null
        }, params);

        if (!OPC_External_Validation.execute('review:placeOrder')){
            return false;
        }

        if (OnePageCheckoutPS.IS_LOGGED) {
            if (AppOPC.$opc.find('#form_address_delivery').is(':visible') || AppOPC.$opc.find('#form_address_invoice').is(':visible')) {
                Fronted.showModal({type:'warning', message : OnePageCheckoutPS.Msg.finalize_address_update});
                return false;
            }

            if (!is_virtual_cart || OnePageCheckoutPS.CONFIGS.OPC_SHOW_DELIVERY_VIRTUAL) {
                if (AppOPC.$opc_step_one.find('#delivery_address_container .address_card').length <= 1) {
                    Fronted.showModal({type:'warning', message : OnePageCheckoutPS.Msg.need_add_delivery_address});
                    return false;
                }
                if (AppOPC.$opc.find('.addresses_customer_container.delivery .container_card.selected').length <= 0) {
                    Fronted.showModal({type:'warning', message : OnePageCheckoutPS.Msg.select_delivery_address});
                    return false;
                }
            }

            if (Address.isSetInvoice() && AppOPC.$opc.find('.addresses_customer_container.invoice .container_card.selected').length <= 0) {
                Fronted.showModal({type:'warning', message : OnePageCheckoutPS.Msg.select_invoice_address});
                return false;
            }
        }

        AppOPC.$opc.find('#btn_place_order').attr('disabled', 'true');

        Fronted.validateOPC({
            valid_form_customer: true,
            valid_form_address_delivery: true,
            valid_form_address_invoice: true,
            valid_carrier: true,
            valid_payment: true,
            valid_privacy: true,
            valid_gdpr: true,
            valid_condition: true
        });

        if (AppOPC.is_valid_opc) {
            var is_customer_logged = OnePageCheckoutPS.IS_LOGGED;

            if (!OnePageCheckoutPS.IS_LOGGED || OnePageCheckoutPS.IS_GUEST) {
                var fields = Review.getFields();
            } else {
                var fields = Review.getFields({object: 'customer'});
            }

            if (fields) {
                var _extra_data = Review.getFieldsExtra({});
                var _data = $.extend({}, _extra_data, {
                    'url_call'				: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
                    'is_ajax'               : true,
                    'action'                : 'placeOrder',
                    'id_customer'           : (!$.isEmpty(AppOPC.$opc_step_one.find('#customer_id').val()) ? AppOPC.$opc_step_one.find('#customer_id').val() : ''),
                    'id_address_delivery'   : Address.id_address_delivery,
                    'id_address_invoice'    : !$.isEmpty(Address.id_address_invoice) ? Address.id_address_invoice : Address.id_address_delivery,
                    'fields_opc'            : JSON.stringify(fields),
                    'message'               : (!$.isEmpty(AppOPC.$opc_step_review.find('#message').val()) ? AppOPC.$opc_step_review.find('#message').val() : ''),
                    'is_new_customer'       : (AppOPC.$opc_step_one.find('#checkbox_create_account_guest').is(':checked') ? 0 : 1),
                    'token'                 : static_token
                });

                if ($('textarea[name="g-recaptcha-response"]').length > 0) {
                    _data['g-recaptcha-response'] = $('textarea[name="g-recaptcha-response"]').val();
                }

                var _json = {
                    data: _data,
                    beforeSend: function() {
                        Fronted.loadingBig(true);
                        window.scrollTo(0, AppOPC.$opc.outerHeight() / 3);
                    },
                    success: function(data) {
                        if (data.isSaved && (!OnePageCheckoutPS.PS_GUEST_CHECKOUT_ENABLED || $('#checkbox_create_account_guest').is(':checked'))){
                            AppOPC.$opc_step_one.find('#customer_email, #customer_conf_email, #customer_passwd, #customer_conf_passwd')
                                .attr({
                                    'disabled': 'true',
                                    'data-validation-optional' : 'true'
                                })
                                .addClass('disabled')
                                .trigger('reset');

                            $('#div_onepagecheckoutps_login, #field_customer_passwd, #field_customer_conf_passwd, div#onepagecheckoutps #onepagecheckoutps_step_one_container .account_creation, #field_choice_group_customer, #field_customer_checkbox_create_account, #field_customer_checkbox_create_account_guest').addClass('hidden');

                            AppOPC.$opc_step_one.find('#div_save_customer').remove();
                            AppOPC.$opc_step_one.find('#div_create_invoide_address').remove();
                            AppOPC.$opc_step_one.find('#opc_show_login').remove();
                            AppOPC.$opc_step_one.find('#label_help_invoice').remove();

                            AppOPC.$opc_step_one.find('#action_address_delivery').removeClass('hidden');
                            AppOPC.$opc_step_one.find('#action_address_delivery').removeClass('hidden');

                            OnePageCheckoutPS.IS_LOGGED = data.isSaved;
                            OnePageCheckoutPS.IS_GUEST = data.isGuest;
                        }

                        if (data.hasError){
                            Fronted.showModal({type:'error', message : '&bullet; ' + data.errors.join('<br>&bullet; ')});
                        } else if (data.hasWarning){
                            Fronted.showModal({type:'warning', message : '&bullet; ' + data.warnings.join('<br>&bullet; ')});
                        } else {
                            Address.id_customer = data.id_customer;
                            Address.id_address_delivery = data.id_address_delivery;
                            Address.id_address_invoice = data.id_address_invoice;

                            AppOPC.$opc_step_one.find('#customer_id').val(Address.id_customer);
                            AppOPC.$opc_step_one.find('#delivery_id').val(Address.id_address_delivery);
                            AppOPC.$opc_step_one.find('#invoice_id').val(Address.id_address_invoice);

                            //plugin last minute offer
                            if ( !AppOPC.load_offer || typeof mustCheckOffer === 'undefined' || (event_dispatcher !== undefined && event_dispatcher !== 'confirm') ) {
                                window['checkOffer'] = function(callback) {
                                    callback();
                                };
                            }

                            if ($('div#onepagecheckoutps #onepagecheckoutps_step_three #free_order').length > 0){
                                document.location.href = prestashop.urls.pages.order_confirmation + '?free_order=1';
                                return;
                            }

                            if (param.validate_payment === true) {
                                var $payment_selected = AppOPC.$opc_step_three.find('#' + PaymentOPC.id_payment_selected + ':checked');
                                var name_payment = $payment_selected.val();
                                var arr_reload_payment_modules = ['sofortbanking', 'redsys', 'eupago_multibanco', 'eupago_payshop', 'eupagomultibanco', 'redsysoficial'];

                                    var callback_placeorder = function() {
                                        var $payment_selected = AppOPC.$opc_step_three.find('#' + PaymentOPC.id_payment_selected + ':checked');
                                        var url_payment = $payment_selected.next().val();
                                        var form_payment_selected = $payment_selected.parents('.module_payment_container.selected').find('form')[0];

                                        $.totalStorageOPC.deleteItem('create_invoice_address_'+OnePageCheckoutPS.id_shop);

                                        if (typeof form_payment_selected !== typeof undefined) {
                                            if (name_payment == 'culqi' && typeof Culqi !== typeof undefined) {
                                                Culqi.createToken();

                                                if (!$.isEmpty(Culqi.token)) {
                                                    form_payment_selected.submit();
                                                } else {
                                                    Fronted.loadingBig(false);
                                                }
                                            } else if (name_payment == 'stripe_official') {
                                                AppOPC.$opc.find('#btn_place_order').prop('disabled', true);
                                                if (form_payment_selected.id === 'stripe-payment-form') {
                                                    $('form#stripe-payment-form button').trigger('click');

                                                    Fronted.loadingBig(false);

                                                    $('html, body').animate({
                                                        scrollTop: $payment_selected.offset().top - 200
                                                    }, 300);

                                                } else {
                                                    $('#payment-confirmation button:submit').trigger('click');
                                                    Fronted.loadingBig(false);
                                                }
                                            } else if (name_payment == 'stripejs') {
                                                if (typeof stripeCheckoutSetup !== typeof undefined) {
                                                    stripeCheckoutSetup();
                                                }

                                                AppOPC.$opc.find('#btn_place_order').prop('disabled', true);
                                                Fronted.loadingBig(false);
                                                $('#payment-confirmation button.btn-primary').trigger('click')
                                            } else if (name_payment == 'paypalbraintree') {
                                                Fronted.loadingBig(false);
                                                $('#payment-confirmation > div.ps-shown-by-js > button').trigger('click')
                                            } else if (name_payment == 'paypalplus' && (typeof _paypalplus !== typeof undefined || typeof ppp_global_approval_url !== typeof undefined)) {
                                                Fronted.loadingBig(false);

                                                if (typeof _paypalplus !== typeof undefined) {
                                                    $('input[name="' + name_payment + '"]').parent('form').submit(function(e) {
                                                        return false;
                                                    });

                                                    _paypalplus.pppPopupPayment($('input[name="paypalplus"]').val());
                                                } else if (typeof isSelectedPPP !== typeof undefined) {
                                                    var disabled = $("#pppContinueButton").prop('disabled');
                                                    if ((typeof disabled != 'undefined') && !disabled) {
                                                        doPaypalPlusCheckout();
                                                    }
                                                }
                                            } else if (name_payment == 'amzpayments' && $('span#payWithAmazonListDiv img').length > 0) {
                                                Fronted.loadingBig(false);
                                                $('span#payWithAmazonListDiv img').trigger('click');
                                            //kf_paypal - (v1.1.0 - de KForge)
                                            } else if (name_payment == 'kf_paypal' && $('section#kfpaypalForm').length > 0) {
                                                Fronted.loadingBig(false);
                                                $('#kfpaypalForm').appendTo('#onepagecheckoutps');
                                                $('#onepagecheckoutps').children(':not(#kfpaypalForm)').remove();
                                                checkSUbmitButton();
                                                $('#kfpaypalForm').show();
                                                $('#kfpaypalForm').removeClass('disabled');
                                                window.scrollTo(0,'#onepagecheckoutps');
                                            } else if (name_payment == 'braintreejs') {
                                                if (dropinInstanceRef.isPaymentMethodRequestable()) {
                                                    $('#braintree-submit-button').click();
                                                } else {
                                                    var error_msg = $('#braintree-translations-dropin #braintree-payment-not-provided').text();
                                                    Fronted.showModal({type:'error', message : '&bullet; ' + error_msg});
                                                }
                                            } else if (name_payment == 'stripepro') { //v5.3.3 - NTS
                                                AppOPC.$opc.find('#btn_place_order').removeAttr('disabled');
                                                Fronted.loadingBig(false);
                                                $('#payment-confirmation button.btn-primary').trigger('click')
                                            } else if (name_payment === 'zipmoneypayment') {
                                                if (typeof Zip !== typeof undefined) {
                                                    var replaceAll = function(search, replace, subject){
                                                        while(subject.indexOf(search) > -1){
                                                            subject = subject.replace(search, replace);
                                                        }
                                                        return subject;
                                                    }

                                                    redirectUri = replaceAll('&amp;', '&', redirectUri);
                                                    checkoutUri = replaceAll('&amp;', '&', checkoutUri);

                                                    Zip.Checkout.init({
                                                        redirect: zm_in_context != "true",
                                                        checkoutUri: checkoutUri,
                                                        redirectUri: redirectUri,
                                                        onComplete: function(response){
                                                            if(response.state == "approved" || response.state == "referred"){
                                                                var nextStep = redirectUri + (redirectUri.indexOf('?') > -1 ? "&" : "?") + "result=" + response.state + "&checkoutId=" + response.checkoutId;
                                                                window.location.href = nextStep;
                                                            }
                                                        },
                                                        onClose: function() {
                                                            Fronted.loadingBig(false);
                                                            AppOPC.$opc.find('#btn_place_order').removeAttr('disabled');
                                                        },
                                                        onError: function(error) {
                                                            window.console.log('========  ========');
                                                            window.console.log('ERROR :::   ',error.message);
                                                            window.console.log('========  ========');
                                                        }
                                                    });
                                                }
                                            } else {
                                                if ($.strpos(url_payment, 'javascript:') !== false) {
                                                    eval(url_payment);
                                                    return false;
                                                } else {
                                                    form_payment_selected.submit();
                                                }
                                            }
                                        } else {
                                            window.location = url_payment;
                                        }
                                };

                                //recarga de nuevo los metodos de pago para actualizar los formularios que tengan datos del cliente por defecto.
                                if (!is_customer_logged && $.inArray(name_payment, arr_reload_payment_modules) != -1)
                                {
                                    PaymentOPC.getByCountry({show_loading: false, callback: callback_placeorder});
                                } else {
                                    callback_placeorder();
                                }
                            }
                        }
                    },
                    complete: function() {
                        AppOPC.$opc.find('#btn_place_order').removeAttr('disabled');
                    },
                    error: function(data){
                        alert(data);
                        Fronted.loadingBig(false);
                        AppOPC.$opc.find('#btn_place_order').removeAttr('disabled');
                    }
                };

                var callback = function() {
                    $.makeRequest(_json);
                }

                if (OnePageCheckoutPS.CONFIGS.OPC_CONFIRM_ADDRESS && !is_virtual_cart) {
                    var address = AppOPC.$opc_step_one.find('#delivery_address1').length > 0 ? AppOPC.$opc_step_one.find('#delivery_address1').val() : '';
                    var postcode = AppOPC.$opc_step_one.find('#delivery_postcode').length > 0 ? ', ' + AppOPC.$opc_step_one.find('#delivery_postcode').val() : '';
                    var city = AppOPC.$opc_step_one.find('#delivery_city').length > 0 ? ', ' + AppOPC.$opc_step_one.find('#delivery_city').val() : '';
                    var state = AppOPC.$opc_step_one.find('#delivery_id_state option').length > 0 ? ' (' + AppOPC.$opc_step_one.find('#delivery_id_state option:selected').data('text') + ')' : '';
                    var customer_address = '<b>' + address + postcode + city + state + '</b>';
                    customer_address = OnePageCheckoutPS.Msg.message_validate_address.replace('%address%', customer_address);

                    Fronted.showModal({
                        name: 'modal_confirm_address',
                        type:'normal',
                        title: OnePageCheckoutPS.Msg.validate_address,
                        title_icon: 'fa-pts-map-marker ',
                        content : customer_address,
                        button_close: true,
                        button_ok: true,
                        callback_ok: function(){
                            supportModuleGDPR(callback);
                            return true;
                        },
                        callback_close: function() {
                            AppOPC.$opc.find('#btn_place_order').removeAttr('disabled');
                            return true;
                        }
                    });
                } else {
                    supportModuleGDPR(callback);
                }
            }
        }
    }
}

var OPC_Compatibilities = {
    steps: [],
    init: function() {
        OPC_Compatibilities.steps['payment'] = new Array();

        /* Compatibilidad módulo paypalplus - V1.7.13 de terracode*/
        OPC_Compatibilities.steps['payment'].push(function() {
            var is_valid_conf = typeof ppp_global_approval_url !== 'undefined' && typeof ppp_global_mode !== 'undefined' && typeof ppp_global_language !== 'undefined' && typeof ppp_global_country !== 'undefined';
            if (is_valid_conf) {
                $.ajaxSetup({cache: true});
                $.getScript("https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js", function (data, textStatus, jqxhr) {
                    //$(document).ready( function() {
                    var initData = {
                        "approvalUrl": ppp_global_approval_url,
                        "placeholder": "ppplus",
                        "mode": ppp_global_mode,
                        "language": ppp_global_language,
                        "country": ppp_global_country,
                        "buttonLocation": "outside",
                        "showLoadingIndicator": true,
                        "disableContinue": function () {
                            $("#pppContinueButton").prop('disabled', true);
                            if ($('input.payment_radio[value="paypalplus"]').is(':checked')) {
                                $('#btn_place_order').attr('disabled', 'disabled');
                            }
                        },
                        "enableContinue": function () {
                            $("#pppContinueButton").prop('disabled', false);
                            if ($('input.payment_radio[value="paypalplus"]').is(':checked')) {
                                $('#btn_place_order').removeAttr('disabled');
                            }
                            $(document).trigger("pppMethodSelected");
                        },
                        "preselection": "none"
                    };
                    ppp_global = PAYPAL.apps.PPP(initData);
                });
            }
        });
    },
    execute: function(step) {
        if (typeof OPC_Compatibilities.steps[step] !== typeof undefined) {
            $.each(OPC_Compatibilities.steps[step], function(i, compatibility) {
                compatibility();
            });
        }
    }
};

var OPC_External_Validation = {
    validations: [],
    init: function(){
        OPC_External_Validation.validations['review:placeOrder'] = Array();

        // <editor-fold defaultstate="collapsed" desc="validations">
        OPC_External_Validation.validations['review:placeOrder'].push(function() {
            if ($('#onepagecheckoutps_step_two .delivery-option.selected div.extra_info_carrier a.select_pickup_point').length > 0){
                alert(OnePageCheckoutPS.Msg.need_select_pickup_point);

                $('#onepagecheckoutps_step_two .delivery-option.selected div.extra_info_carrier a.select_pickup_point').trigger('click');

                return false;
            }
        });
        OPC_External_Validation.validations['review:placeOrder'].push(function() {
            //support module: packetery v2.0.2 (ZLab Solutions)
            if (AppOPC.$opc_step_two.find('.delivery-option.selected .packetery-widget-status').length > 0) {
                if (AppOPC.$opc_step_two.find('.delivery-option.selected .packetery-widget-status > a').data('status') == '0') {
                    alert(OnePageCheckoutPS.Msg.need_select_pickup_point);

                    return false;
                }
            }
        });
        OPC_External_Validation.validations['review:placeOrder'].push(function() {
            //support module: carrierpickupstore
            if (typeof CarrierPickupStore !== typeof undefined) {
                if (AppOPC.$opc_step_two.find('.delivery-option.selected .delivery_option_radio[value=\'' + CarrierPickupStore.id_carrierpickupstore + ',\']').is(':checked')) {
                    if (AppOPC.$opc_step_two.find('.opt_id_store').val() == '0') {
                        alert(OnePageCheckoutPS.Msg.need_select_pickup_point);

                        return false;
                    }
                }
            }
        });

        OPC_External_Validation.validations['review:placeOrder'].push(function() {
            //support module: kbstorelocatorpickup
            if ($('#delivery_confirmation').val() != 'yes' && typeof add_kb_preferred_time !== typeof undefined) {
                $('#preferred-alert').remove();

                if ($('input[name="kb_pickup_selected_store"]').val() == '') {
                    $('.velo-field-preferred-date').before('<div id="preferred-alert" class="alert alert-danger"><span>' + preferred_store_unselected + '</span></div>');

                } else if ($('#kb_pickup_select_date').val() == '') {
                    $('.velo-field-preferred-date').before('<div id="preferred-alert" class="alert alert-danger"><span>' + preferred_date_empty + '</span></div>');
                }
                $('#hook-display-before-carrier').before('<div id="preferred-alert" class="alert alert-danger"><span>' + add_kb_preferred_time + '</span></div>');
                if ($('#preferred-alert').length) {
                    $('html, body').animate({
                        scrollTop: $("#preferred-alert").offset().top - 200
                    }, 1000);
                }
                return false;
            }
        });
        // </editor-fold>
    },
    execute: function(step) {
        var is_valid = true;

        if (typeof OPC_External_Validation.validations[step] !== typeof undefined) {
            $.each(OPC_External_Validation.validations[step], function(i, external_validation) {
                if (external_validation() === false) {
                    is_valid = false;

                    return false;
                }
            });
        }

        return is_valid;
    }
}
function updateExtraCarrier(id_delivery_option, id_address)
{
	$.ajax({
		type: 'POST',
		url: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
		cache: false,
		dataType : "json",
		data: 'is_ajax=true'
			+'&action=updateExtraCarrier'
			+'&id_address='+id_address
			+'&id_delivery_option='+id_delivery_option
			+'&token='+static_token
			+'&allow_refresh=1',
		success: function(jsonData)
		{
			$('#HOOK_EXTRACARRIER_'+id_address).html(jsonData['content']);
		}
	});
}

function supportModuleGDPR(callback) {
    //support module: m4gdpr - v1.1.2 - PrestaAddons
    if (typeof m4gdprConsent !== typeof undefined && !AppOPC.m4gpdr && (!OnePageCheckoutPS.IS_LOGGED || OnePageCheckoutPS.IS_GUEST)) {
        var isOnepagecheckoutpsConsent = false;

        $.each(m4gdprConsent.elements, function (i, element) {
            if (element.element == 'onepagecheckoutps') {
                isOnepagecheckoutpsConsent = true;

                function disableSubmitButton() {
                    $('.vex-dialog-buttons button[type="submit"]').prop('disabled', true);
                }
                function enableSubmitButton() {
                    $('.vex-dialog-buttons button[type="submit"]').prop('disabled', false);
                }
                function isAllChecked(id) {
                    var allChecked = true;
                    $('#m4gdpr-dialog-' + id + ' input[type="checkbox"]').each(function() {
                        if ($(this).hasClass('required') && false == $(this).prop('checked')) {
                            allChecked = false;
                        }
                    });

                    return allChecked;
                }

                var buttons = [
                    jQuery.extend({}, vex.dialog.buttons.YES, {
                        text: element.count ? m4gdprConsent.buttonText.submit : m4gdprConsent.buttonText.accept
                    })
                ];

                if (element.count) {
                    buttons.push(
                        jQuery.extend({}, vex.dialog.buttons.NO, {
                            text: m4gdprConsent.buttonText.cancel, click: function() {
                                this.value = false;
                                this.close();
                                return false;
                            }
                        })
                    );
                }

                var vexDialog = vex.dialog.open({
                    unsafeMessage: m4gdprConsent.message,
                    input: (element && element.input) ? element.input.join('') : '',
                    buttons: buttons,
                    className: m4gdprConsent.className,
                    afterOpen: function() {
                        $('#m4gdpr-dialog-' + element.id).on('change', 'input[type="checkbox"]', function() {
                            if (isAllChecked(element.id)) {
                                enableSubmitButton();
                            } else {
                                disableSubmitButton();
                            }
                        });
                    },
                    onSubmit: function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        if (!isAllChecked(element.id)) {
                            disableSubmitButton();
                            return false;
                        } else {
                            var params = $('.vex-dialog-form').serializeArray();

                            params.push({'name': 'from', 'value': AppOPC.$opc_step_one.find('#customer_email').val()});

                            $.post(m4gdprBaseUri + 'index.php', params);

                            AppOPC.m4gpdr = true;

                            vexDialog.close();
                            callback();
                        }
                    }
                });

                if (isAllChecked(element.id)) {
                    enableSubmitButton();
                } else {
                    disableSubmitButton();
                }
            }
        });

        if (!isOnepagecheckoutpsConsent) {
            callback();
        }

        return false;
    }

    callback();
}

function updateCarrierSelectionAndGift(params) {
    var param = $.extend({}, {
        load_carrier: false
    }, params);

    var $delivery_selected = $('#opc_payment_methods input.delivery_option_radio:checked');

    if ($delivery_selected.length > 0) {
        var data = {
            ajax: true,
            method: 'updateCarrierAndGetPayments',
            token: static_token,
            recyclable: '',
            gift_message: '',
            gift: ''
        };

        data[$delivery_selected.attr('name')] = $delivery_selected.val();

        $.ajax({
            type: 'POST',
            headers: { "cache-control": "no-cache" },
            url: orderOpcUrl + '?rand=' + new Date().getTime(),
            dataType : "json",
            data: data,
            beforeSend: function() {
                Fronted.loadingBig(true);
            },
            success: function(json) {
                $('#opc_payment_methods #carrier_area').replaceWith(json.carrier_data.carrier_block);
                $('#opc_payment_methods #HOOK_BEFORECARRIER').html(json.HOOK_BEFORECARRIER);

                setTimeout(function(){
                    Fronted.removeUniform({parent_control: '#opc_payment_methods'});
                }, 3000);
            },
            complete: function() {
                if (param.load_carrier) {
                    Carrier.getByCountry({reset_carrier_embed: false});
                }

                Fronted.loadingBig(false);
            }
        });
    }
}

//compatibilidad modulo crosselling
function loadJavaScriptReview(){
    $(function(){
        /* Compatibilidad ps_featuredproducts - V2.0.0 de PrestaShop (at_nova theme) */
        if(typeof products_list_functions !== typeof undefined && products_list_functions.length > 0){
            $.each(products_list_functions, function(i, fnction) {
                if (typeof fnction === 'function') {
                    fnction();
                }
            });
        }

//        if($('#crossselling_list').length > 0)
//        {
//        	//init the serialScroll for thumbs
//        	cs_serialScrollNbImages = $('#crossselling_list li').length;
//        	cs_serialScrollNbImagesDisplayed = 5;
//        	cs_serialScrollActualImagesIndex = 0;
//        	$('#crossselling_list').serialScroll({
//        		items:'li',
//        		prev:'a#crossselling_scroll_left',
//        		next:'a#crossselling_scroll_right',
//        		axis:'x',
//        		offset:0,
//        		stop:true,
//        		onBefore:cs_serialScrollFixLock,
//        		duration:300,
//        		step: 1,
//        		lazy:true,
//        		lock: false,
//        		force:false,
//        		cycle:false
//        	});
//        	$('#crossselling_list').trigger( 'goto', [ (typeof cs_middle !== 'undefined' ? cs_middle : middle)-3] );
//        }

//        $('#onepagecheckoutps_step_review #gift-products_block .ajax_add_to_cart_button').die('click');

            if ($('.slick-default-carousel').length > 0) {
            let $carousels = $('.slick-default-carousel');
            let defaultOptions = {
                dots: true,
                accessibility: false,
                speed: 300,
                autoplay: iqitTheme.pl_crsl_autoplay,
                autoplaySpeed: 4500,
                slidesToShow: iqitTheme.pl_slider_ld,
                slidesToScroll: iqitTheme.pl_slider_ld,
                infinite: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: iqitTheme.pl_slider_d,
                            slidesToScroll: iqitTheme.pl_slider_d
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: iqitTheme.pl_slider_t,
                            slidesToScroll: iqitTheme.pl_slider_t
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: iqitTheme.pl_slider_p,
                            slidesToScroll: iqitTheme.pl_slider_p
                        }
                    },
                ]
            };

            $carousels.each(function() {
                let $carousel = $(this);
                let slickOptions = $.extend({}, defaultOptions, $carousel.data('slider_options'));
                $carousel.slick( slickOptions );
            });
        }
            $('#onepagecheckoutps_step_review .ajax_add_to_cart_button').unbind('click').click(function(event){
                var idProduct = 0;

                if (!$.isEmpty($(event.currentTarget).attr('data-id-product')))
                    idProduct = $(event.currentTarget).attr('data-id-product');
                else
                    idProduct =  $(this).attr('rel').replace('ajax_id_product_', '');

                if ($('#onepagecheckoutps_step_review #gift-products_block').length > 0){
                    event.preventDefault();
                    window.location = $(event.currentTarget).attr('href');

                    return false;
                }

                if (!$.isEmpty(idProduct)){
                    ajaxCart.add(idProduct, null, false, this);
                    Carrier.getByCountry();

                    return false;
                }
            });
//        }

        $('#onepagecheckoutps_step_review .ajax_add_to_cart_button').css({visibility: 'visible'});

        //compatibilidad con modulo CheckoutFields
        if (typeof checkoutfields !== 'undefined')
            checkoutfields.bindAjaxSave();

        //compatibilidad con modulo paragonfaktura
        $('#pfform input').click(function(){
            var value = $('#pfform input:checked').val();
            var id_cart = $('#pfform #pf_id').val();
            $.ajax({
              type: "POST",
              url: "modules/paragonfaktura/save.php",
              data: { value: value, id_cart: id_cart }
            }).done(function( msg ) {

            });
		});

        //Compatibilidad con modulo pm_crosssellingoncart v2.4.3 by Presta-Module
        if ($('#csoc-container').length > 0) {
            $csocjqPm(pm_crosssellingoncart.prefix).pmCSOCOwlCarousel({
                items : parseInt(pm_crosssellingoncart.nbItems),
                itemsCustom : false,
                itemsDesktop : false,
                itemsDesktopSmall : false,
                itemsTablet : [768,parseInt(pm_crosssellingoncart.products_quantity_tablet)],
                itemsTabletSmall : false,
                itemsMobile : [479,parseInt(pm_crosssellingoncart.products_quantity_mobile)],
                slideSpeed : 200,
                paginationSpeed : 800,
                autoPlay : true,
                stopOnHover : true,
                goToFirstSpeed : 1000,
                navigation : false,
                navigationText : ["prev","next"],
                scrollPerPage : true,
                pagination : true,
                baseClass : "pm-csoc-owl-carousel",
                theme : "pm-csoc-owl-theme",
                mouseDraggable : false,
                responsiveBaseWidth: pm_crosssellingoncart.prefix == '#PM_CSOC' ?  window : $csocjqPm('.nyroModalCont, .mfp-content')
            });

            if (typeof(modalAjaxCart) == 'undefined' && typeof(ajaxCart) != 'undefined' && typeof(pm_reloadCartOnAdd) != 'undefined' && typeof(pm_csocLoopInterval) == 'undefined') {
                pm_csocLoopInterval = setInterval(function() {
                    pm_reloadCartOnAdd(pm_crosssellingoncart.order_page_link);
                }, 500);
            }

            if ($csocjqPm('body#product').size() > 0) {
                // Remove product on CSOC
                $csocjqPm(document).on('click', '#PM_CSOC a.ajax_add_to_cart_button', function(e){
                    e.preventDefault();
                    var owl = $csocjqPm(pm_crosssellingoncart.prefix).data('pm-csoc-owlCarousel');
                    owl.removeItem(owl.currentItem);
                    owl.reinit();

                    if ($csocjqPm('#PM_CSOC .product-box').length <= 0) {
                        $csocjqPm('#csoc-container').remove();
                    }
                });
            }
        }
    });
}

function opc_callback_error_payment(name_module, params) {
    if (name_module == 'braintree') {
        $("div#onepagecheckoutps .loading_big").hide();

        if (typeof params.errorMsg !== typeof undefined && params.errorMsg) {
            Fronted.showModal({type: 'warning', message: params.msg});
        }
    }
}
function addslashes(str) {
  //  discuss at: http://phpjs.org/functions/addslashes/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Ates Goral (http://magnetiq.com)
  // improved by: marrtins
  // improved by: Nate
  // improved by: Onno Marsman
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Oskar Larsson (http://oskar-lh.name/)
  //    input by: Denny Wardhana
  //   example 1: addslashes("kevin's birthday");
  //   returns 1: "kevin\\'s birthday"

  return (str + '')
    .replace(/[\\"']/g, '\\$&')
    .replace(/\u0000/g, '\\0');
}

function version_compare(v1, v2, operator) { // eslint-disable-line camelcase
  //       discuss at: http://locutus.io/php/version_compare/
  //      original by: Philippe Jausions (http://pear.php.net/user/jausions)
  //      original by: Aidan Lister (http://aidanlister.com/)
  // reimplemented by: Kankrelune (http://www.webfaktory.info/)
  //      improved by: Brett Zamir (http://brett-zamir.me)
  //      improved by: Scott Baker
  //      improved by: Theriault (https://github.com/Theriault)
  //        example 1: version_compare('8.2.5rc', '8.2.5a')
  //        returns 1: 1
  //        example 2: version_compare('8.2.50', '8.2.52', '<')
  //        returns 2: true
  //        example 3: version_compare('5.3.0-dev', '5.3.0')
  //        returns 3: -1
  //        example 4: version_compare('4.1.0.52','4.01.0.51')
  //        returns 4: 1

  // Important: compare must be initialized at 0.
  var i
  var x
  var compare = 0

  var vm = {
    'dev': -6,
    'alpha': -5,
    'a': -5,
    'beta': -4,
    'b': -4,
    'RC': -3,
    'rc': -3,
    '#': -2,
    'p': 1,
    'pl': 1
  }

  var _prepVersion = function (v) {
    v = ('' + v).replace(/[_\-+]/g, '.')
    v = v.replace(/([^.\d]+)/g, '.$1.').replace(/\.{2,}/g, '.')
    return (!v.length ? [-8] : v.split('.'))
  }

  var _numVersion = function (v) {
    return !v ? 0 : (isNaN(v) ? vm[v] || -7 : parseInt(v, 10))
  }

  v1 = _prepVersion(v1)
  v2 = _prepVersion(v2)
  x = Math.max(v1.length, v2.length)
  for (i = 0; i < x; i++) {
    if (v1[i] === v2[i]) {
      continue
    }
    v1[i] = _numVersion(v1[i])
    v2[i] = _numVersion(v2[i])
    if (v1[i] < v2[i]) {
      compare = -1
      break
    } else if (v1[i] > v2[i]) {
      compare = 1
      break
    }
  }
  if (!operator) {
    return compare
  }

  switch (operator) {
    case '>':
    case 'gt':
      return (compare > 0)
    case '>=':
    case 'ge':
      return (compare >= 0)
    case '<=':
    case 'le':
      return (compare <= 0)
    case '===':
    case '=':
    case 'eq':
      return (compare === 0)
    case '<>':
    case '!==':
    case 'ne':
      return (compare !== 0)
    case '':
    case '<':
    case 'lt':
      return (compare < 0)
    default:
      return null
  }
}

jQuery.expr[':'].ptsContains = function(a, i, m) { return jQuery(a).text().toUpperCase() .indexOf(m[3].toUpperCase()) >= 0; };

var reload_init_opc = setInterval(function(){
    if (typeof AppOPC !== typeof undefined){
        if(!AppOPC.initialized)
            AppOPC.init();
        else
            clearInterval(reload_init_opc)
    }
}, 2000);

//una alternativa al interval.
//window.onload = function() {
//    AppOPC.init();
//};