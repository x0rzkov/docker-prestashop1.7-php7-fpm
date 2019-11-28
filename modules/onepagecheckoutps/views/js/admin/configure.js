/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @category  PrestaShop
 * @category  Module
 * @author    PresTeamShop.com <support@presteamshop.com>
 * @copyright 2011-2019 PresTeamShop
 * @license   see file: LICENSE.txt
 */

var AppOPC = {
    initialized: false,
    init: function() {
        AppOPC.initialized = true;

        Fields.init();
        ShipToPay.init();
        ImagePayment.init();

        $.ptsInitPopOver();
        $.ptsInitColorPicker();
        $.ptsInitTabDrop();
        $.ptsInitChangeLog();

        AppOPC.registerEvents();
        AppOPC.fillRequiredFieldList();
        AppOPC.initStatistics();

        $('#pts_content > .pts-overlay').remove();
    },
    registerEvents: function(){
        $('.switch').ptsToggleDepend();
        //Ship To Pay
        $('#update_ship_to_pay').click(ShipToPay.update);
        //show option form
        $('#lst_type_control').change(AppOPC.changeTypeControl);
        //save social login
        $('.btn-save-social_login').on('click', AppOPC.saveSocialLogin);
        //modal social login info
        $('.handler-modal-social-login').on('click', AppOPC.showModalSocialLogin);
        $('div#tab-register button.btn-delete-address').on('click', function(e){AppOPC.deleteEmptyAddressesOPC(e);});

        $('button#btn-expand-all').on('click', function() {
            $('#content_translations .panel-title .accordion-toggle.collapsed').trigger('click');
        });
        $('button#btn-collapse-all').on('click', function() {
            $('#content_translations .panel-title .accordion-toggle:not(.collapsed)').trigger('click');
        });

        /* debug mode */
        var $div_container_ip_debug = $('div.tab-content div#tab-general form div#container-enable_debug div#container-ip_debug');

        var $sub_div_add_ip = $('<div>')
            .addClass('col-xs-12 col-sm-3 input-group-md pull-right')
            .appendTo($div_container_ip_debug);

        var $button = $('<button>')
            .attr('type', 'button')
            .addClass('btn btn-primary btn-sm')
            .html('&nbsp;'+Msg.add_IP)
            .appendTo($sub_div_add_ip)
            .on('click', AppOPC.addNewIP);

        $('<i>').addClass('fa-pts fa-pts-plus').prependTo($button);

        setTimeout(function(){ $('#message_configuration_saved').hide(500); }, 6000);

        $('#form_required_fields #txt-field_name').on('blur', function(e){
            var _json = {
                data: {
                    dataType: 'html',
                    action: 'toolsLinkRewrite',
                    value: $(e.currentTarget).val()
                },
                success: function(new_value) {
                    $(e.currentTarget).val($.trim(new_value));
                }
            };
            $.makeRequest(_json);
        });
    },
    addNewIP: function() {
        var $txt_ip_debug = $('div.tab-content div#tab-general form div#container-ip_debug #txt-ip_debug');
        var ip_debug = $txt_ip_debug.val();

        var default_ip = (ip_debug.length > 0) ? ','+remote_addr : remote_addr;
        $txt_ip_debug.val(ip_debug+default_ip);
    },
    deleteEmptyAddressesOPC: function(event) {
        event.preventDefault();

        var data = {
            action: 'deleteEmptyAddressesOPC',
            dataType: 'json'
        };

        var _json = {
            data: data
        };
        $.makeRequest(_json);
    },
    fillRequiredFieldList: function() {
        $.getList('table-required-fields', 'getRequiredFieldList');
    },
    initStatistics: function() {
        if ($('div#tab-statistics #statistics').length === 0 || Object.keys(OnePageCheckoutPS.SOCIAL_DATA).length === 0) {
            return false;
        }

        var ctxP = $('div#tab-statistics #statistics')[0].getContext('2d');
        new Chart(ctxP, {
            type: 'pie',
            data: {
                labels:  Object.values(OnePageCheckoutPS.SOCIAL_DATA.labels),
                datasets: [{
                    data:  Object.values(OnePageCheckoutPS.SOCIAL_DATA.data),
                    backgroundColor:  Object.values(OnePageCheckoutPS.SOCIAL_DATA.backgroundColor),
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: Msg.chart_title
                }
            }
        });
    },
    saveSocialLogin: function(event) {
        event.preventDefault();
        var $parent = $(event.currentTarget).parents('[id*="social_login_"]');
        var name_social_network = $parent.attr('id').replace('tab-social_login_', '').replace('-container', '');
        var values = {};
        $parent.find('input.form-control').each(function(i, input) {
            var name = 'client' + $(input).attr('id').replace('txt-social_login', '');
            values[name] = $(input).val();
        });
        $parent.find('input.switch-input').each(function(i, input) {
            var name = $(input).attr('id').replace('chk-social_login_', '');
            values[name] = $(input).is(':checked') ? 1 : 0;
        });

        var data = {
            social_network: name_social_network,
            values: values
        };

        var _json = {
            data: {
                action: 'saveSocialLogin',
                data: data
            }
        };
        $.makeRequest(_json);
    },
    showModalSocialLogin: function(event) {
        var modal = $(event.currentTarget).data('social-modal');
        var icon = $(event.currentTarget).parents('.pts-panel').find('.main-head i.fa').attr('class');
        Fields.showModal({button_close: true, type:'normal', title: $(event.currentTarget).text(), title_icon:icon, content:$('#'+modal)});
    }
};

var ImagePayment = {
    init: function() {
        ImagePayment.clear();
    },
    registerEvents: function() {
        $('#payment-images-container .pts-remove-image-handler').on('click', ImagePayment.removeImagePayment);
        $('#payment-images-container .pts-change-image-handler').on('click', ImagePayment.loadImagePayment);
        $('#payment-images-container .save-image-payment').off('click');
        $('#payment-images-container .save-image-payment').on('click', ImagePayment.uploadImagePayment);
        $('#payment-images-container input[name="test_mode"]').on('change', ImagePayment.changePaymentTestMode);
        $('#payment-images-container div.btn-payment-add-ip').on('click', ImagePayment.addPaymentIp);
    },
    clear: function() {
        ImagePayment.registerEvents();
    },
    addPaymentIp: function(e) {
        var input_ip    = $(e.currentTarget).siblings('input[name="test_ip"]');
        var ip      = input_ip.val();
        var new_ip  = ((ip.length > 0) ? ',' : '') + remote_addr;

        input_ip.val(ip + new_ip);
    },
    changePaymentTestMode: function(e) {
        var element = $(e.currentTarget);
        var button_add_ip   = element.parents('.input-group').find('.btn-payment-add-ip');
        var input_ip        = element.parents('.input-group').find('input[name="test_ip"]');
        var test_mode       = element.is(':checked') ? 1 : 0;

        if (test_mode) {
            button_add_ip.removeClass('disabled');
            input_ip.prop('disabled', false);
        } else {
            button_add_ip.addClass('disabled');
            input_ip.prop('disabled', true);
        }
    },
    removeImagePayment: function (event) {
        event.stopPropagation();
        event.preventDefault();

        var id_module = $(event.currentTarget).data('id-module');
        var name_module = $(event.currentTarget).data('name-module');

        var data = {
            action: 'removeImagePayment',
            id_module: id_module,
            name_module: name_module
        };
        var _json = {
            data: data,
            success: function(json) {
                if (json.message_code === SUCCESS_CODE) {
                    var date_today = new Date();
                    var src = module_img + 'payments/no-image.png?' + date_today.getTime();

                    $('#image_payment_' + id_module).attr('src', src);

                    $('#payment-images-container #remove-image-handler-' + id_module).addClass('hidden');
                }
            }
        };
        $.makeRequest(_json);
    },
    loadImagePayment: function(event) {
        event.stopPropagation();
        event.preventDefault();

        var id_module = $(event.currentTarget).data('id-module');
        var $file = $('#payment-images-container #file-image_payment-'+id_module);

        $file.on('change', ImagePayment.prepareUpload).trigger('click');
    },
    prepareUpload: function(event) {
        var name_payment = $(event.currentTarget).data('name-module');
        var files = event.target.files;

        $('#btn-save_image_payment-' + name_payment).off('click');
        $('#btn-save_image_payment-' + name_payment).on('click', {files: files}, ImagePayment.uploadImagePayment);

        if (typeof files[0] !== typeof undefined) {
            $(event.currentTarget).parent().find('.pts-change-image-name').text('"' + files[0].name + '"');
        }
    },
    uploadImagePayment: function(event) {
        event.stopPropagation();
        event.preventDefault();

        var param = $.extend({}, {
            files: undefined
        }, event.data);

        var form_data = new FormData();
        var name_payment = $(event.currentTarget).data('name-module');
        var id_module = $(event.currentTarget).data('id-module');
        //var force_display = $('#chk-force_display-' + name_payment).is(':checked') ? 1 : 0;
        var force_display = 0;
        var test_mode   = $('#chk-test_mode-' + name_payment).is(':checked') ? 1 : 0;
        var test_ip     = $('#txt-test_ip-' + name_payment).val();

        //title and description
        var payment_data = new Array();
        for ( var i = 0; i < languages.length; i++ ) {
            var id_lang = languages[i];
            var $title = $('#txt-image_payment_title-' + name_payment + '_' + id_lang);
            var description = $('#ta-image_payment_description-' + name_payment + '_' + id_lang).val();

            if ($.strpos(description, '\\')){
                description = addslashes(description);
            }
            if ($.strpos(description, '\n')){
                description = description.replace(/\n/gi, '\\n');
            }
            if (!$.isEmpty(description) && typeof description == 'string'){
                description = description.replace(/\"/g, '\'');
            }

            payment_data.push({
                id_lang: id_lang,
                title: $title.val(),
                description: description
            });
        }

        form_data.append('id_module', id_module);
        form_data.append('force_display', force_display);
        form_data.append('name', name_payment);
        form_data.append('payment_data', JSON.stringify(payment_data));
        form_data.append('test_mode', test_mode);
        form_data.append('test_ip', test_ip);

        //image
        if (typeof param.files !== typeof undefined && param.files.length) {
            form_data.append(name_payment, param.files[0]);
        }

        //action - token
        form_data.append('action', 'uploadImage');
        form_data.append('token', pts_static_token);

        //make request
        $.ajax({
            url: actions_controller_url,
            type: 'POST',
            data: form_data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.has-action').addClass('disabled');
            },
            success: function (data)
            {
                if (data.message_code === SUCCESS_CODE) {
                    //refresh image
                    if (typeof data.name_image !== typeof undefined && !$.isEmpty(data.name_image)){
                        var date_today = new Date();
                        var src = module_img + 'payments/' + data.name_image + '?' + date_today.getTime();

                        $('#image_payment_' + id_module).attr('src', src);
                    }

                    $('#payment-images-container #remove-image-handler-' + id_module).removeClass('hidden');
                    $('#payment-images-container #file-image_payment-' + id_module).val('');
                }

                $.showMessage(data.message_code, data.message);
            },
            complete: function() {
                $('.has-action').removeClass('disabled');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $.showMessage(ERROR_CODE, textStatus);
            }
        });
    }
};

var Fields = {
    options_to_remove: [],
    init: function() {
        $('#update_field').click(Fields.update);
        $('#clear_field').click(Fields.clear);
        Fields.initSortable();
        Fields.initFieldOptions();
        Fields.registerEvents();
    },
    initSortable: function() {
        $('ol.nested_fields_position').each(function (i, element) {
            $(element).sortable({
                group: 'nested_' + $(element).data('group'),
                pullPlaceholder: true,
                // animation on drop
                onDrop: function ($item, targetContainer, _super, event) {
                    if ($item.hasClass('li-row') && targetContainer.el.hasClass('ol-row')) {
                        var list = this.lastParent.find('li');
                        $item.insertBefore(list.eq(this.lastIndex));
                    } else if (!$item.hasClass('li-row') && targetContainer.el.hasClass('nested_fields_position')) {
                        var $label = $item.find('label').clone();
                        $item.find('label').remove();
                        var $li_container = $('<li/>').append($label);
                        $li_container.attr({
                            'data-field': $item.attr('data-field'),
                        });
                        var $ol_row = $('<ol/>').addClass('list-group ol-row').append($li_container);
                        $item.addClass('li-row list-group-item').append($ol_row);
                    }
                    _super($item);

                    //remove
                    if (typeof this.lastParent !== typeof undefined && typeof this.lastParent[0] !== typeof undefined) {
                        if (this.lastParent.children().length === 0) {
                            if (this.lastParent.hasClass('ol-row')) {
                                this.lastParent.parent().remove();
                            } else if (this.lastParent.hasClass('li-row')) {
                                this.lastParent.remove();
                            }
                        }
                    }

                    //save
                    var data = {};
                    $('ol.nested_fields_position li.li-row').each(function(r, row) {
                        data[r] = {};
                        $(row).find('ol.ol-row li').each(function(c, col) {
                            data[r][c] = {
                                id_field: $(col).attr('data-field'),
                                group: $(row).parent().attr('data-group')
                            };
                        });
                    });
                    Fields.saveFieldsPosition(data);
                },
                // set item relative to cursor position
                onDragStart: function ($item, container, _super) {
                    this.lastParent = $item.parent();
                    this.lastIndex = $item.index();

                    var offset = $item.position();
                    var pointer = container.rootGroup.pointer;

                    adjustment = {
                        left: pointer.left - offset.left,
                        top: pointer.top - offset.top
                    };

                    _super($item, container);
                },
                onDrag: function ($item, position) {
                    if ($item.hasClass('li-row')) {
                        $('.pts .nested_fields_position > li > ol > li.placeholder').css('display', 'none');
                        $('.pts .nested_fields_position > li.placeholder').css('display', 'block');
                    } else {
                        $('.pts .nested_fields_position > li > ol > li.placeholder').css('display', 'inline-block');
                    }

                    $item.css({
                        left: position.left,//adjustment.left,// + position.left,
                        top: position.top,// - adjustment.top,
                        position: 'fixed'
                    });
                }
            });
        });
    },
    initFieldOptions: function() {
        $('<option/>').attr({value: ''}).html('--').appendTo($('#lst-manage-object'));
        $.each(GLOBALS_JS.object, function(i, object) {
            $('<option/>').attr({value: object}).html(object).appendTo($('#lst-manage-object'));
        });
        $('#table-field-options tbody').empty();
    },
    registerEvents: function() {
        $('#btn-update_field').click(Fields.update);
        //new register
        $('#btn-new_register').click(Fields.add);
        //manage field options
        $('#btn-manage_field_options').click(Fields.manageOptions);
        //add new option
        $('#btn-add_field_option').click(Fields.addOption);
        //save options
        $('#btn-update_field_options').click(Fields.saveOptions);
        //load fields
        $('#lst-manage-object').change(Fields.loadFieldsByObject);
        //load options
        $('#lst-manage-field').change(Fields.loadOptionsByField);
    },
    enableFormOptions: function() {
        $('#btn-add_field_option').removeClass('disabled');
        $('#btn-update_field_options').removeClass('disabled');
    },
    disableFormOptions: function() {
        $('#btn-add_field_option').addClass('disabled');
        $('#btn-update_field_options').addClass('disabled');
    },
    loadOptionsByField: function(event) {
        var id_field = $(event.target).val();
        Fields.disableFormOptions();
        Fields.options_to_remove = [];
        $('#table-field-options tbody').empty();
        if (!$.isEmpty(id_field)) {
            var data = {
                action: 'getOptionsByField',
                id_field: id_field
            };
            var _json = {
                data: data,
                beforeSend: function() {
                    _json.e = event;
                },
                success: function(json) {
                    if (json.message_code === SUCCESS_CODE ) {
                        Fields.enableFormOptions();
                        $.each(json.options, function(f, option) {
                            Fields.addOption(null, option);
                        });
                    }
                }
            };
            $.makeRequest(_json);
        }
    },
    loadFieldsByObject: function(event) {
        var object = $(event.target).val();

        Fields.disableFormOptions();
        Fields.options_to_remove = [];
        $('#lst-manage-field').prop('disabled', true);
        $('#table-field-options tbody').empty();
        $('#lst-manage-field').find('option').remove();
        $('<option/>').attr({value: ''}).html('--').appendTo($('#lst-manage-field'));

        if (!$.isEmpty(object)) {
            var data = {
                action: 'getFieldsByObject',
                object: object
            };
            var _json = {
                data: data,
                beforeSend: function() {
                    _json.e = event;
                },
                success: function(json) {
                    if (json.message_code === SUCCESS_CODE ) {
                        $('#lst-manage-field').prop('disabled', false);
                        $.each(json.fields, function(f, field) {
                            $('<option/>').attr({value: field.id_field}).html(field.description).appendTo($('#lst-manage-field'));
                        });
                    }
                }
            };
            $.makeRequest(_json);
        }
    },
    saveOptions: function(event) {
        var id_field = $('#lst-manage-field').val();
        var options = new Array();

        $('#table-field-options tbody tr').each(function(r, row) {
            var id_option = $(row).find('input:hidden').val();
            var value = $(row).find('input.option-value').val();
            var description = new Array();
            $(row).find('.translatable-field').each(function(t, translatable) {
                var $input = $(translatable).find('input.form-control');
                description.push({
                    id_lang: $input.attr('id').split('_').pop(),
                    value: $input.val()
                });
            });

            options.push({
                id_option: id_option,
                value: value,
                description: description
            });
        });

        if (!$.isEmpty(id_field)) {
            var data = {
                action: 'saveOptionsByField',
                id_field: id_field,
                options: options,
                options_to_remove: Fields.options_to_remove
            };
            var _json = {
                data: data,
                beforeSend: function() {
                    Fields.disableFormOptions();
                },
                success: function(json) {
                    Fields.enableFormOptions();
                    $('#lst-manage-field').trigger('change');
                }
            };
            $.makeRequest(_json);
        }
    },
    addOption: function(event, data) {
        var $tr = $('<tr/>');
        var $td_value = $('<td/>');//.addClass('col-xs-5 nopadding');
        var $td_description = $('<td/>');//.addClass('col-xs-5 nopadding');
        var $td_action = $('<td/>');//.addClass('col-xs-2 nopadding');

        var $input_hidden = $('<input/>').attr('type', 'hidden').appendTo($td_value);

        var $input_value = $('<input/>').addClass('form-control option-value');
        $input_value.appendTo($td_value);

        var $translatable = $('#aux_clone_translatable_input').clone().removeClass('hidden').attr('id', '');
        $translatable.appendTo($td_description);
        $translatable.find('.change-language').click($.changeLanguage);

        var $button = $('<span/>').addClass('btn btn-danger');
        $('<i/>').addClass('fa-pts fa-pts-times nohover').appendTo($button);
        $button.appendTo($td_action);
        $button.click(function() {
            $tr.remove();
            if (typeof data !== typeof undefined) {
                Fields.options_to_remove.push(data.id);
            }
        });

        $tr.append($td_value);
        $tr.append($td_description);
        $tr.append($td_action);

        if (typeof data !== typeof undefined) {
            $input_hidden.val(data.id);
            $input_value.val(data.value);
            $.each(data.description, function(id_lang, description) {
                $translatable.find('input[id=_'+id_lang+']').val(description);
            });
        }

        $('#table-field-options').append($tr);
    },
    manageOptions: function(event) {
        Fields.showModal({type:'normal', title: Msg.manage_field_options, title_icon:'fa-list', content:$('#form_manage_field_options')});
    },
    toggleRequired: function(event, data) {
        var data = {
            action: 'toggleRequiredField',
            id_field: data.id
        };
        var _json = {
            data: data,
            beforeSend: function() {
                _json.e = event;
            },
            success: function(json) {
                if (json.message_code === SUCCESS_CODE ) {
                    $(event.currentTarget).toggleLabelStatus();
                    AppOPC.fillRequiredFieldList();
                }
            }
        };
        $.makeRequest(_json);
    },
    toggleActive: function(event, data){
        var data = {
            action: 'toggleActiveField',
            id_field: data.id
        };
        var _json = {
            data: data,
            beforeSend: function() {
                _json.e = event;
            },
            success: function(json) {
                if (json.message_code === SUCCESS_CODE ) {
                    $(event.currentTarget).toggleLabelStatus();
                    AppOPC.fillRequiredFieldList();
                }
            }
        };
        $.makeRequest(_json);
    },
    update: function(event) {
        event.preventDefault();
        var descriptions = new Array();
        var labels = new Array();

        $.each(languages, function(i, id_lang) {
            var description = $('#field_description_' + id_lang).val();
            var label = $('#field_label_' + id_lang).val();
            descriptions.push({
                id_lang: id_lang,
                description: description
            });
            labels.push({
                id_lang: id_lang,
                description: label
            });
        });

        var default_value = $('#txt-field_default_value').val();
        var name = $('#txt-field_name').val();
        var size = $('#txt-field_size').val();
        var valid = true;
        var re_link = /^[a-zA-Z0-9-_]+$/;

        if (name === 'id_country' && $.isEmpty(default_value)) {
            $.showMessage(-1, Msg.required_default_country);
            valid = false;
        }

        if (re_link.test(name))
            $('#txt-field_name').parent().removeClass('has-error');
        else {
            $('#txt-field_name').parent().addClass('has-error');
            valid = false;
        }

        if (!valid) {
            event.stopPropagation();
            event.preventDefault();
            return false;
        }

        var data = {
            action: 'updateField',
            id_field: $('#hdn-id_field').val(),
            object: $('#lst-field_object').val(),
            name: name,
            description: descriptions,
            label: labels,
            type: $('#lst-field_type').val(),
            size: size,
            type_control: $('#lst-field_type_control').val(),
            default_value: default_value,
            required: $('#chk-field_required').is(':checked'),
            active: $('#chk-field_active').is(':checked')
        };

        var _json = {
            data: data,
            beforeSend: function() {
                _json.e = event;
            },
            success: function(json) {
                if (json.message_code === SUCCESS_CODE ) {
                    //change label
                    $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + '] label')
                        .html($('#field_description_' + id_language_default).val());
                    $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + '] label')
                        .html($('#field_label_' + id_language_default).val());
                    //clear
                    Fields.clear();
                    AppOPC.fillRequiredFieldList();
                    //if new, add for sort
                    if (data.id_field == '0') {
                        Fields.addSortableField(data, json.id_field);
                    } else if (json.refresh_position) {
                        var $parent = $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + ']').parent();
                        $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + '] label').remove();
                        $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + ']').remove();

                        if ($parent.find('li').length == 0 || typeof $parent.find('li')[0] === typeof undefined) {
                            var $grand_parent = $parent.parent();
                            $parent.remove();
                            if ($grand_parent.find('ol').length == 0 || typeof $grand_parent.find('ol')[0] === typeof undefined) {
                                $grand_parent.remove();
                            }
                        }
                        Fields.addSortableField(data, data.id_field);
                    }
                }
            }
        };
        $.makeRequest(_json);
        return false;
    },
    addSortableField: function(data, id_field) {
        var label = '';
        if (data.object == 'customer')
            label = 'primary';
        else if (data.object == 'delivery')
            label = 'success';
        else if (data.object == 'invoice')
            label = 'warning';

        var $ol = $('.nested_fields_position[data-group="'+data.object+'"]');
        var $li = $('<li/>').addClass('list-group-item li-row');
        var $ol_secundary = $('<ol/>').addClass('list-group ol-row');
        var $li_secundary = $('<li/>').attr({'data-field': id_field});
        var $label = $('<label/>').addClass('label label-'+label);

        $label.html(data.description[0].description);
        $label.appendTo($li_secundary.appendTo($ol_secundary.appendTo($li.appendTo($ol))));

    },
    add: function(event) {
        var data = {
            id: 0,
            object: 'customer',
            name: '',
            type: 'isName',
            size: '',
            type_control: 'textbox',
            default_value: '',
            required: 0,
            active: 1,
            is_custom: 1,
            title: Msg.new_field
        };
        Fields.edit(event, data);
    },
    edit: function(event, data) {
        Fields.clear();

        $('#txt-field_name').parent().removeClass('has-error');
        $('#txt-field_size').parent().removeClass('has-error');

        //if is custom
        if (parseInt(data.is_custom)) {
            $('#form_required_fields #lst-field_object').prop('disabled', false).closest('.form-group').show();
            $('#form_required_fields #txt-field_name').prop('disabled', false).closest('.form-group').show();
            $('#form_required_fields #lst-field_type').prop('disabled', false).closest('.form-group').show();
            $('#form_required_fields #txt-field_size').prop('disabled', false).closest('.form-group').show();
            $('#form_required_fields #lst-field_type_control').prop('disabled', false).closest('.form-group').show();
            $('#form_required_fields #txt-field_default_value').prop('disabled', false).closest('.form-group').show();

            $('#form_required_fields #chk-field_required').closest('.form-group').show();
            $('#form_required_fields #chk-field_active').closest('.form-group').show();
        } else {
            $('#form_required_fields #lst-field_object').prop('disabled', true).closest('.form-group').hide();
            $('#form_required_fields #txt-field_name').prop('disabled', true).closest('.form-group').hide();
            $('#form_required_fields #lst-field_type').prop('disabled', true).closest('.form-group').hide();
            $('#form_required_fields #txt-field_size').prop('disabled', true).closest('.form-group').hide();
            $('#form_required_fields #lst-field_type_control').prop('disabled', true).closest('.form-group').hide();

            //evita que los clientes estos valores que no pueden ser tocados
            if (data.name == 'id_country' || data.name == 'id_state' || data.name == 'alias' || data.name == 'newsletter' || data.name == 'optin') {
                $('#form_required_fields #txt-field_default_value').prop('disabled', false).closest('.form-group').show();
            } else if (data.name == 'id' || data.name == 'passwd') {
                $('#form_required_fields #chk-field_required').closest('.form-group').hide();
                $('#form_required_fields #chk-field_active').closest('.form-group').hide();
                $('#form_required_fields #txt-field_default_value').prop('disabled', true).closest('.form-group').hide();
            } else {
                $('#form_required_fields #chk-field_required').closest('.form-group').show();
                $('#form_required_fields #chk-field_active').closest('.form-group').show();
                $('#form_required_fields #txt-field_default_value').prop('disabled', true).closest('.form-group').hide();
            }
        }

        //fill data
        $('#form_required_fields #hdn-id_field').val(data.id);
        $('#form_required_fields #lst-field_object').val(data.object);
        $('#form_required_fields #txt-field_name').val(data.name);
        $('#form_required_fields #lst-field_type').val(data.type);
        $('#form_required_fields #txt-field_size').val(data.size);
        $('#form_required_fields #lst-field_type_control').val(data.type_control);
        $('#form_required_fields #txt-field_default_value').val(data.default_value);

        //multilang
        if (data.description instanceof Object) {
            $.each(data.description, function(id_lang, description) {
                $('#form_required_fields #field_description_' + id_lang).val(description);
            });
        } else {
            $('#form_required_fields input[id^="#field_description_"]').val(data.description);
        }
        if (data.label instanceof Object) {
            $.each(data.label, function(id_lang, label) {
                $('#form_required_fields #field_label_' + id_lang).val(label);
            });
        } else {
            $('#form_required_fields input[id^="#field_label_"]').val(data.label);
        }

        //switch
        $('#chk-field_required').prop('checked', parseInt(data.required));
        $('#chk-field_active').prop('checked', parseInt(data.active));

        //modal
        var title = Msg.edit_field;
        if (typeof data.title !== typeof undefined)
            title = data.title;

        Fields.showModal({type:'normal', title: title, title_icon:'fa-edit', content:$('#form_required_fields')});
    },
    remove: function(event, data) {
        if (parseInt(data.is_custom) == 1) {
            if (confirm(Msg.confirm_remove_field)) {
                 var data = {
                    action: 'removeField',
                    id_field: data.id
                };

                var _json = {
                    data: data,
                    beforeSend: function() {
                        _json.e = event;
                    },
                    success: function(json) {
                        if (json.message_code === SUCCESS_CODE ) {
                            //change label
                            var $parent = $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + ']').parent();
                            $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + '] label').remove();
                            $('.nested_fields_position li.li-row ol.ol-row li[data-field=' + data.id_field + ']').remove();

                            if ($parent.find('li').length == 0 || typeof $parent.find('li')[0] === typeof undefined) {
                                var $grand_parent = $parent.parent();
                                $parent.remove();
                                if ($grand_parent.find('ol').length == 0 || typeof $grand_parent.find('ol')[0] === typeof undefined) {
                                    $grand_parent.remove();
                                }
                            }
                            //clear
                            Fields.clear();
                            AppOPC.fillRequiredFieldList();
                        }
                    }
                };
                $.makeRequest(_json);
            }
        } else {
            alert(Msg.cannot_remove_field);
        }
    },
    clear: function(){
        $('#form_required_fields #hdn-id_field').val('');
        $('#form_required_fields #lst-field_object').val('');
        $('#form_required_fields #txt-field_name').val('');
        $('#form_required_fields #lst-field_type').val('');
        $('#form_required_fields #txt-field_size').val('');
        $('#form_required_fields #lst-field_type_control').val('');
        $('#form_required_fields #txt-field_default_value').val('');
        $('#form_required_fields input[id^="field_description_"]').val('');
        $('#form_required_fields input[id^="field_label_"]').val('');

        //switch
        $('#chk-field_required').prop('checked', false);
        $('#chk-field_active').prop('checked', false);

        //remove disabled property
        $('#form_required_fields #lst-field_object').prop('disabled', false);
        $('#form_required_fields #txt-field_name').prop('disabled', false);
        $('#form_required_fields #lst-field_type').prop('disabled', false);
        $('#form_required_fields #txt-field_size').prop('disabled', false);
        $('#form_required_fields #lst-field_type_control').prop('disabled', false);

        //modal
        $('#opc_modal').modal('hide');
    },
    sortFields: function(event) {
        var fields = new Array();
        $('#table-required-fields tbody tr').each(function(i, element) {
            var id_field = $(element).attr('id').split('_').pop();
            fields.push(id_field);
        });

        var data = {
            action: 'updateFieldsPosition',
            order_fields: fields
        };
        var _json = {
            data: data
        };
        $.makeRequest(_json);
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
            button_close: false,
            size: '',
            callback: '',
            callback_close: ''
        }, params);

        $('#'+param.name).remove();

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

                    if (!$.isEmpty(parent_content))
                        param.content.appendTo(parent_content).addClass('hidden');

                    $('body').removeClass('modal-open');

                    if (typeof param.callback_close !== typeof undefined && typeof param.callback_close === 'function')
                        param.callback_close();
                })
                .append('<i class="fa-pts fa-pts-close"></i>');
        var $modal_button_close_footer = $('<button/>')
            .attr({type:'button', 'class':'btn btn-default'})
            .click(function(){
                $('#'+param.name).modal('hide');

                if (!$.isEmpty(parent_content))
                    param.content.appendTo(parent_content).addClass('hidden');

                $('body').removeClass('modal-open');

                if (typeof param.callback_close !== typeof undefined && typeof param.callback_close === 'function')
                    param.callback_close();
            })
            .append('OK');
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
                .append('<i class="fa-pts '+param.title_icon+' fa-1x"></i>')
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
        }

        $modal_dialog.append($modal_content);
        $modal.append($modal_dialog);

        $modal.on('hide.bs.modal', function(){
            if (!param.close){
                return false;
            } else {
                if (!$.isEmpty(parent_content))
                    param.content.appendTo(parent_content).addClass('hidden');

                if (typeof param.callback_close !== typeof undefined && typeof param.callback_close === 'function')
                    param.callback_close();
            }
        });

        $('div#pts_content').prepend($modal);

        $('#'+param.name)
            .modal('show')
            .css({
                top: 0
            });

        if (!$('#'+param.name).hasClass('in'))
            $('#'+param.name).addClass('in').css({display : 'block'});

        if (typeof param.callback !== typeof undefined && typeof param.callback === 'function')
            param.callback();
    },
    saveFieldsPosition: function(positions) {
        var data = {
            action: 'saveFieldsPosition',
            positions: positions
        };

        var _json = {
            data: data
        };
        $.makeRequest(_json);
    }
};

var ShipToPay = {
    init: function() {
        ShipToPay.registerEvents();
        ShipToPay.getAssociationsShipToPay();
    },
    registerEvents: function() {
        $('#btn-update_ship_pay').click(ShipToPay.update);
    },
    update: function(event){
        var payment_carrier = new Array();
        $('#ship-pay-container .carrier_container').each(function (i, carrier_container){
            var id_reference = $(carrier_container).data('id-reference');
            var payments = new Array();

            $(carrier_container).find('input:checkbox:checked').each(function(i, input){
                payments.push($(input).data('id-module'));
            });

            payment_carrier.push({
                id_reference: id_reference,
                payments: payments
            });
        });

        var data = {
            action: 'updateShipToPay',
            payment_carrier: payment_carrier
        };

        var _json = {
            data: data,
            beforeSend: function() {
                _json.e = event;
            }
        };
        $.makeRequest(_json);
    },
    getAssociationsShipToPay: function() {
        var data = {
            action: 'getAssociationsShipToPay',
        };
        var _json = {
            data: data,
            beforeSend: function() {
                $('#div_loading_ship_to_pay').removeAttr('class').empty();
            },
            success: function(json) {
                if (!$.isEmpty(json)) {
                    if (json.message_code === SUCCESS_CODE ) {
                        $('#ship-pay-container :checkbox').prop('checked', false);

                        $.each(json.carriers, function(i, carrier) {
                            $('#payment_' + carrier.id_module + '_' + carrier.id_reference).prop('checked', true);
                        });
                    }
                }
            }
        };
        $.makeRequest(_json);
    }
};

window.onload = function() {
    AppOPC.init();
    globalTabs.ini();
};