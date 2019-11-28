/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL DREAM ME UP
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL DREAM ME UP is strictly forbidden.
 *
 *   .--.
 *   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
 *   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
 *   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
 *        w w w . d r e a m - m e - u p . f r       '
 *
 * @author    Dream me up <prestashop@dream-me-up.fr>
 * @copyright 2007 - 2016 Dream me up
 * @license   All Rights Reserved
 */
var url_global = currentIndex+'&token='+token;
var field_edit = '';

function init_listener()
{
    $('a.product_img').off().hover(function(){
        $('.zoom_photo').hide();
        id = $(this).attr('id').split('_');
        id_product = id[2];
        position = $('#product_img_'+id_product).position();
        $('#zoom_photo_'+id_product).css({'top':position.top,'left':(position.left+60)}).show();
    });
    $('.zoom_photo').off().on('mouseleave',function(){
        $(this).hide();
    });

    // champ dmu_text
    field_edit = '';
    $('td.show_dmu_text').off().click(function(){
        id_span = $(this).children().attr('id');
        if (id_span == undefined) {
            return;
        }
        dmu_text = id_span.split('_txt_');
        field = dmu_text[0];
        ids = dmu_text[1].split('_');
        id_product = ids[0];
        id_product_attribute = ids[1];
        if (field_edit == field + id_product + id_product_attribute) {
            return;
        }
        hide_all();
        if ($(this).children().next().hasClass('input_dmu_text')) {
            $(this).children().hide().next().show().focus().select();
            field_edit = field + id_product + id_product_attribute;
        } else if ($(this).children().next().hasClass('translatable-field')) {
            $(this).children().hide();
            hideOtherLanguageDmu(field, id_product, id_product_attribute, defaultFormLanguage);
        } else {
            $(this).children().trigger('click');
        }
    }).each(function() {
        elem = $(this);
        if (elem.children().attr('onclick')) {
            elem.off().attr('onclick', elem.children().attr('onclick'));
            elem.children().removeAttr('onclick');
        } else if (elem.attr('onclick')) {
            elem.off();
        }
    });

    $('.input_dmu_text').off().each(function(){
        $(this).keyup(function(e){
            if (e.which === 13) {
                var input_dmu_text = $(this);
                id = input_dmu_text.attr('id').split('_value_');
                var field = id[0];
                id = id[1].split('_');
                var id_product = id[0];
                var id_product_attribute = id[1];
                id_lang = id[2];
                value = input_dmu_text.val();
                var badge = false;
                $.post(url_global + '&ajax=1&action=change_' + field, {id_product: id_product, id_product_attribute: id_product_attribute, value: value, id_lang: id_lang}, function(data){
                    if (input_dmu_text.prev().data('badge') !== undefined) {
                        badge = true;
                        if (data == '' || data <= 0) {
                            input_dmu_text.prev().addClass('badge badge-danger');
                        } else {
                            input_dmu_text.prev().removeClass('badge badge-danger');
                        }
                    }
                    $('.translatable-field').hide();
                    $('#' + field + '_txt_' + id_product + '_' + id_product_attribute).html(data).show();
                    input_dmu_text.hide();
                    field_edit = '';
                    $.ajax({
                        type: 'POST',
                        url: url_global +'&ajax=1&action=getFields',
                        async: true,
                        cache: false,
                        data: {id_product: id_product, id_product_attribute: id_product_attribute, field: field},
                        dataType: 'json',
                        success: function(json) {
                            for (i in json.confirmations[0]) {
                                if (json.confirmations[0][i].col != 'price_final') {
                                    id_product_attribute = 0;
                                }
                                id_update = $('#' + json.confirmations[0][i].col + '_txt_' + id_product + '_' + id_product_attribute);
                                val = json.confirmations[0][i].value;
                                id_update.html(val);
                                if (json.confirmations[0][i].input_value !== undefined) {
                                    $('#' + json.confirmations[0][i].col + '_value_' + id_product + '_0_0').val(json.confirmations[0][i].input_value);
                                }
                                if (badge) {
                                    if (val == '' || val <= 0) {
                                        id_update.addClass('badge badge-danger');
                                    } else {
                                        id_update.removeClass('badge badge-danger');
                                    }
                                }
                            }
                        }
                    });
                });
            }
        });
    });

    // champ dmu_list
    $('td.show_dmu_list').off().click(function(){
        span = $(this).children('span');
        id = span.attr('id').split('_id_');
        field = id[0];
        id_product = id[1];
        if (field_edit == field + id_product + '0') {
            return;
        }
        field_edit = field + id_product + '0';
        hide_all();
        span.hide();
        select = span.data('select');
        list = $('#select_' + field + '_' + id_product);
        if (!list.length) {
            $('#' + field).clone().insertAfter('#' + field + '_id_' + id_product);
            $('#form-dmuadminrecherche #' + field + ', #dmuadminrecherche #' + field).attr({'id': 'select_' + field + '_' + id_product, 'class': 'select_dmu_list', 'data-field': field, 'data-id': id_product}).removeAttr('onchange').val(select).show();
            if (span.data('required') !== undefined) {
                $('#select_' + field + '_' + id_product).children().first().remove();
            } else {
                $('#select_' + field + '_' + id_product).children().first().html($('#trad_list').html());
            }

            $('.select_dmu_list').off().change(function(){
                var select_dmu_list = $(this);
                id_product = select_dmu_list.data('id');
                field = select_dmu_list.data('field');
                value = select_dmu_list.val();
                $.post(url_global +'&ajax=1&action=change_' + field, {id_product: id_product,value: value}, function(data){
                    select_dmu_list.hide().prev().html(data).show();
                    field_edit = '';
                });
            });
        } else {
            list.show();
        }
    });

    // champ active
    $('.list-action-enable').off().click(function(){
        var icon_active = $(this);
        id_product = icon_active.attr('id').split('_')[1];
        active = (icon_active.hasClass('action-enabled')) ? 0 : 1;

        $.post(url_global +'&ajax=1&action=change_active', {id_product: id_product,active: active}, function(data){
            if (data == '0') {
                icon_active.removeClass('action-enabled').addClass('action-disabled').children('.icon-check').addClass('hidden').next('.icon-remove').removeClass('hidden');
            } else {
                icon_active.removeClass('action-disabled').addClass('action-enabled').children('.icon-check').removeClass('hidden').next('.icon-remove').addClass('hidden');
            }
        });
    });

    $('.list-toolbar-btn').off().each(function(){
        if ($(this).attr('href') == 'javascript:location.reload();') {
            $(this).attr('href', 'javascript:ajax_reload();');
        }
    });

    $('.pagination-link').off().on('click',function(e){
        initLoader();
        page = $(this).data('page');
        nb_result = $('#dmuadminrecherche_pagination').val();
        $.get(url_global + '&ajax=1&action=change_pagination', {submitFilterdmuadminrecherche:page, dmuadminrecherche_pagination_ajax:nb_result}, function(html){
            refresh_result(html);
        });
    });

    $('#dmuadminrecherche_pagination').off().typeWatch({
        highlight: true, wait: 600, captureLength: 0, callback: function(nb_result) {
            initLoader();
            $.get(url_global + '&ajax=1&action=change_pagination', {dmuadminrecherche_pagination_ajax:nb_result}, function(html){
                refresh_result(html);
            });
        }
    });
}

function hideOtherLanguageDmu(field, id_product, id_product_attribute, id_lang)
{
    $('.show_dmu_text .translatable-field').hide();
    $('#lang-' + field + '-' + id_product + '-' + id_lang).show();

    var id_old_language = id_language;
    id_language = id_lang;

    if (id_old_language != id_lang)
        changeEmployeeLanguage();

    input = $('#' + field + '_value_' + id_product + '_'  + id_product_attribute + '_' + id_lang);
    if (id_lang != defaultFormLanguage && !input.val()) {
        $.get(url_global + '&ajax=1&action=get_translation', {field: field, id_lang: id_lang, id_product: id_product}, function(data){
            input.val(data).show().focus().select();
        });
    } else {
        input.show().focus().select();
    }
    field_edit = field + id_product + id_product_attribute;
}

function ajax_update(name, value)
{
    initLoader();
    $.ajax({
        type: 'GET',
        url: url_global +'&ajax=1&action=change_filter',
        async: true,
        cache: false,
        dataType : 'html',
        data: 'name=' + name + '&value=' + value,
        success: function(html)
        {
            refresh_result(html);
        }
    });
}

function ajax_reload()
{
    initLoader();
    page = $('#select_pagination').val();
    nb_result = $('#dmuadminrecherche_pagination').val();
    $.ajax({
        type: 'GET',
        url: url_global +'&ajax=1&action=reload&submitFilterdmuadminrecherche=' + page + '&dmuadminrecherche_pagination_ajax=' + nb_result,
        async: true,
        cache: false,
        dataType : 'html',
        success: function(html) {
            refresh_result(html);
        }
    });
}

function initLoader()
{
    $('#refresh_result').show();
}

function initLoaderPopin()
{
    $('#refresh_result_comb').show();
}

function refresh_result(html) {
    $('#dmuadminrecherche-empty-filters-alert').remove();
    $('#sql_form_dmuadminrecherche').remove();
    $('#form-dmuadminrecherche, #dmuadminrecherche').remove();
    $('#tri_criteres').after(html);
    init_listener();
}

$(document).ready(function () {
    id_shop = getE('id_shop');
    if (id_shop !== null)
    {
        id_shop = id_shop.value;
        url_global = url_global+ '&id_shop='+id_shop;
    }

    $.ajaxSetup({
        cache: false
    });

    $('input#search_field_input').keypress( function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code === 13)
            ajax_update('query', $('#search_field_input').val());
    });

    $('.critere').change(function(){
            initLoader();
            page = $('#select_pagination').val();
            nb_result = $('#dmuadminrecherche_pagination').val();
            columns = [];
            $('.critere').each(function() {
                    if ($(this).is(':checked')) {
                        columns.push($(this).val());
                    }
                }
            );
            var list_columns = columns.join(",");
            $.ajax({
                type: 'POST',
                url: url_global +'&ajax=1&action=critere',
                async: true,
                cache: false,
                dataType : 'html',
                data: {list_columns: list_columns, submitFilterdmuadminrecherche:page, dmuadminrecherche_pagination_ajax:nb_result},
                success: function(html)
                {
                    refresh_result(html);
                }
            });
        }
    );

    $('#show_criteres').click(function(){
        criteres_liste = $('#criteres_liste');
        if (criteres_liste.css('display') == 'block') {
            $('.hide_crit').hide();
            $('.show_crit').show();
            criteres_liste.slideToggle();
        } else {
            $('.show_crit').hide();
            $('.hide_crit').show();
            criteres_liste.slideToggle();
        }
    });

    init_listener();
});

function hide_all() {
    $('td.show_dmu_text').children().show();
    $('td.show_dmu_text .translatable-field, .input_dmu_text').hide();
    $('td.show_dmu_list').children().show();
    $('.select_dmu_list').hide();
    field_edit = '';
}

function sendBulkActionAjax(select)
{
    products = [];
    $('input[name="dmuadminrechercheBox[]"]').each(function(){
        if ($(this).is(':checked')) {
            products.push($(this).val());
        }
    });
    list_products = products.join(',');
    if (list_products.length === 0) {
        alert(msg_error_select_product);
        return false;
    }
    initLoader();
    page = $('#select_pagination').val();
    nb_result = $('#dmuadminrecherche_pagination').val();
    $.ajax({
        type: 'POST',
        url: url_global + '&ajax=1&action=bulk',
        async: true,
        cache: false,
        dataType: 'html',
        data: {list_products: list_products, select: select, submitFilterdmuadminrecherche:page, dmuadminrecherche_pagination_ajax:nb_result},
        success: function (html) {
            refresh_result(html);
        }
    });
}

function sendBulkCombActionAjax(select, id)
{
    combinations = [];
    $('input[name="combinationsBox[]"]').each(function(){
        if ($(this).is(':checked')) {
            combinations.push($(this).val());
        }
    });
    list_combinations = combinations.join(',');
    if (list_combinations.length === 0) {
        alert(msg_error_select_combination);
        return false;
    }
    initLoaderPopin('#popin_combinations_' + id);
    $.ajax({
        type: 'POST',
        url: url_global + '&ajax=1&action=bulkComb',
        async: true,
        cache: false,
        dataType: 'html',
        data: {list_combinations: list_combinations, select: select, id_product: id},
        success: function (data) {
            if(data === 'ok') {
                $('.fancybox-close').trigger('click');
                show_combinations(id);
                $.ajax({
                    type: 'POST',
                    url: url_global +'&ajax=1&action=getFields',
                    async: true,
                    cache: false,
                    data: {id_product: id, field: 'quantity_comb'},
                    dataType: 'json',
                    success: function(json) {
                        for (i in json.confirmations[0]) {
                            id_update = $('#' + json.confirmations[0][i].col + '_txt_' + id + '_0');
                            val = json.confirmations[0][i].value;
                            id_update.html(val);
                            if (json.confirmations[0][i].input_value !== undefined) {
                                $('#' + json.confirmations[0][i].col + '_value_' + id + '_0_0').val(json.confirmations[0][i].input_value);
                            }
                            if (val == '' || val <= 0) {
                                id_update.addClass('badge badge-danger');
                            } else {
                                id_update.removeClass('badge badge-danger');
                            }
                        }
                    }
                });
            } else if (data) {
                $('#refresh_result_comb').hide();
                alert(data);
            }
        }
    });
}

function popin_refresh(show)
{
    if (show) {
        $('.popin_refresh').show();
        $('.bulk_ok').hide();
    } else {
        $('.popin_refresh').hide();
        $('.bulk_ok').show();
    }
}

function show_price_impact(id)
{
    products = [];
    $('input[name="dmuadminrechercheBox[]"]').each(function () {
        if ($(this).is(':checked')) {
            products.push($(this).val());
        }
    });
    if (products.length == 0) {
        alert(msg_error_select_product);
        return false;
    }
    initLoader();
    hide_all();
    $('#' + id + ' .alert').hide();
    $.fancybox($('#' + id),
        {
            autoSize: false,
            width: 450,
            height: 200,
            afterLoad: function() {
                $('#refresh_result').hide();
            },
            helpers: {
                overlay: {
                    locked: false
                }
            }
        }
    );
}

function price_impact(increase)
{
    if (increase) {
        var change = 'increase';
    } else {
        var change = 'reduce';
    }
    tax = $('#' + change + '_price_tax').val();
    type = $('#' + change + '_price_type').val();
    value = $('#' + change + '_price_value').val();
    popin_refresh(true);
    products = [];
    $('input[name="dmuadminrechercheBox[]"]').each(function(){
        if ($(this).is(':checked')) {
            products.push($(this).val());
        }
    });
    list_products = products.join(',');
    page = $('#select_pagination').val();
    nb_result = $('#dmuadminrecherche_pagination').val();

    $.post(url_global +'&ajax=1&action=price_impact', {tax:tax,type:type,value:value,list_products:list_products,increase:increase, submitFilterdmuadminrecherche:page, dmuadminrecherche_pagination_ajax:nb_result}, function(json){
        if (json.status == 'error') {
            show_error('error_' + change + '_price', 'bulk_' + change + '_price', json.error[0]);
        } else {
            refresh_result(json.content);
            $('.fancybox-close').trigger('click');
            $('#' + change + '_price_value').val('');
        }
        popin_refresh(false);
    },'json');
}

function show_prices(id)
{
    initLoader();
    hide_all();
    popin_refresh(false);
    $.get(url_global +  '&ajax=1&action=show_prices', {id_product: id}, function(data){
        popin = $('#popin_action');
        popin.html(data);
        calcPriceTI(id);
        unitPriceWithTax(id);
        $.fancybox(popin,
            {
                autoSize: false,
                autoHeight :true,
                width: 800,
                afterLoad: function() {
                    $('#refresh_result').hide();
                },
                afterClose: function() {
                    popin.html('');
                },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            });
    });
}

function change_prices(id)
{
    wholesale_price = $('#wholesale_price_price_'+id).val();
    price = $('#priceTE_price_'+id).val();
    id_tax_rules_group = $('#id_tax_rules_group_price_'+id).val();
    ecotax = $('#ecotax_price_'+id).val();
    unit_price = $('#unit_price_price_'+id).val();
    unity = $('#unity_price_'+id).val();
    on_sale = 0;

    if ($('#on_sale_price_'+id).is(':checked')){
        on_sale = 1;
    }

    popin_refresh(true);
    $('#error_prices_'+id).hide();
    $.ajax({
        type: 'POST',
        url: url_global +'&ajax=1&action=change_prices',
        async: false,
        cache: false,
        dataType : 'json',
        data: ({id_product:id,wholesale_price:wholesale_price,price:price,id_tax_rules_group:id_tax_rules_group,ecotax:ecotax,unit_price:unit_price,unity:unity,on_sale:on_sale}),
        success: function(json)
        {
            if (json.status == 'error') {
                show_error('error_prices_' + id, 'popin_prices_' + id, json.error[0]);
                popin_refresh(false);
            } else {
                for (i in json.confirmations[0]) {
                    $('#' + json.confirmations[0][i].col + '_txt_' + id + '_0').html(json.confirmations[0][i].value);
                    if (json.confirmations[0][i].input_value !== undefined) {
                        $('#' + json.confirmations[0][i].col + '_value_' + id + '_0_0').val(json.confirmations[0][i].input_value);
                    }
                }
                $.fancybox.close();
            }
        }
    });
}

function show_details(id)
{
    initLoader();
    hide_all();
    popin_refresh(false);
    $.get(url_global +  '&ajax=1&action=show_details', {id_product: id}, function(data){
        popin = $('#popin_action');
        popin.html(data);
        $.fancybox(popin,
            {
                autoSize: false,
                autoHeight :true,
                width: 800,
                afterLoad: function() {
                    $('#refresh_result').hide();
                    $('.label-tooltip').tooltip();
                },
                afterClose: function() {
                    popin.html('');
                },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        );
    });
}

function change_details(id)
{
    ean13 = $('#ean13_detail_'+id).val();
    upc = $('#upc_detail_'+id).val();
    location_warehouse = $('#location_detail_'+id).val();
    width = $('#width_detail_'+id).val();
    height = $('#height_detail_'+id).val();
    depth = $('#depth_detail_'+id).val();
    weight = $('#weight_detail_'+id).val();

    popin_refresh(true);
    $('#error_details_'+id).hide();
    $.post(url_global +'&ajax=1&action=change_details', {id_product: id,ean13: ean13,upc: upc,location: location_warehouse,width: width,height: height,depth: depth,weight: weight}, function(json){
        if (json.status == 'error') {
            show_error('error_details_' + id, 'popin_details_' + id, json.error[0]);
            popin_refresh(false);
        } else {
            for (i in json.confirmations[0]) {
                $('#' + json.confirmations[0][i].col + '_txt_' + id + '_0').html(json.confirmations[0][i].value);
                if (json.confirmations[0][i].input_value !== undefined) {
                    $('#' + json.confirmations[0][i].col + '_value_' + id + '_0_0').val(json.confirmations[0][i].input_value);
                }
            }
            $.fancybox.close();
        }
    }, 'json');
}

function show_error(id_error, id_popin, error)
{
    $('#' + id_error).html(error).show();
    popin = $('#' + id_popin);
    popin_height = popin.parent().height();
    popin.parent().height(popin_height);
}

function show_status(id)
{
    popin_refresh(false);
    $.get(url_global +  '&ajax=1&action=show_status', {id_product: id}, function(data){
        $('#div_status_' + id).html(data);
        $.fancybox($('#popin_status_' + id), {
            helpers: {
                overlay: {
                    locked: false
                }
            }
        });
    });
}

function change_status(id)
{
    id_status = $('#id_status_'+id).val();
    comment_status = $('#comment_status_'+id).val();

    popin_refresh(true);
    $.post(url_global +'&ajax=1&action=change_status', {id_product: id,status: id_status,comment_status: comment_status}, function(data){
        if(data !== '')
        {
            if (id_status == 1) {
                $('#pastille_status_' + id).html('<i class="icon-circle text-muted"></i>');
            } else if (id_status == 2) {
                $('#pastille_status_' + id).html('<i class="icon-circle green_status"></i>');
            } else if (id_status == 3) {
                $('#pastille_status_' + id).html('<i class="icon-circle red_status"></i>');
            } else {
                $('#pastille_status_' + id).html('--');
            }
            $('#pastille_status_' + id).attr('title', comment_status);
            $.fancybox.close();
        }
    });
}

function show_seo(id)
{
    initLoader();
    hide_all();
    popin_refresh(false);
    $.get(url_global +  '&ajax=1&action=show_seo', {id_product: id}, function(data){
        popin = $('#popin_action');
        popin.html(data);
        $.fancybox(popin,
            {
                autoSize: false,
                autoHeight :true,
                width: 800,
                afterShow: function(){
                    $('#refresh_result').hide();
                },
                afterClose: function() {
                    popin.html('');
                },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        );
    });
}

function change_seo(id)
{
    form_seo = $('#form_seo_' + id).serialize();

    popin_refresh(true);
    $('#error_seo_'+id).hide();
    $.post(url_global +'&ajax=1&action=change_seo', form_seo, function(json){
        if (json.status == 'error') {
            show_error('error_seo_' + id, 'popin_seo_' + id, json.error[0]);
            popin_refresh(false);
        } else {
            for (i in json.confirmations[0]) {
                $('#' + json.confirmations[0][i].col + '_txt_' + id + '_0').html(json.confirmations[0][i].value);
                if (json.confirmations[0][i].input_value !== undefined) {
                    $('#' + json.confirmations[0][i].col + '_value_' + id + '_0_0').val(json.confirmations[0][i].input_value);
                }
            }
            $.fancybox.close();
        }
    }, 'json');
}

function show_descriptions(id)
{
    initLoader();
    hide_all();
    popin_refresh(false);
    $.get(url_global + '&ajax=1&action=show_descriptions', {id_product: id}, function(data){
        popin = $('#popin_action');
        popin.html(data);
        $.fancybox(popin,
            {
                afterShow: function(){
                    $('#refresh_result').hide();
                    $('.label-tooltip').tooltip();
                    tinySetup({
                        editor_selector :'autoload_rte'
                    });
                },
                beforeClose: function(){
                    tinyMCE.remove();
                },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        );
    });
}

function change_descriptions(id)
{
    tinyMCE.triggerSave();
    $('#popin_descriptions_' + id + ' .tagify').each(function(){
        $(this).val($(this).tagify('serialize'));
    });
    form_description = $('#form_description_' + id).serialize();

    popin_refresh(true);
    $('#error_descriptions_'+id).hide();
    $.post(url_global +'&ajax=1&action=change_descriptions', form_description, function(data){
        if(data !== '')
        {
            if(data != 1) {
                show_error('error_descriptions_' + id, 'popin_descriptions_' + id, data);
                popin_refresh(false);
            } else {
                $.fancybox.close();
            }
        }
    });
}

function show_features(id)
{
    initLoader();
    hide_all();
    popin_refresh(false);
    $.get(url_global + '&ajax=1&action=show_features', {id_product: id}, function(data){
        popin = $('#popin_action');
        popin.html(data);
        $.fancybox(popin,
            {
                autoSize: false,
                autoHeight :true,
                width: 900,
                afterShow: function(){
                    $('#refresh_result').hide();
                },
                afterClose: function() {
                    popin.html('');
                },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        );
    });
}

function show_combinations(id)
{
    initLoader();
    hide_all();
    //popin_refresh(false);
    $.get(url_global +  '&ajax=1&action=show_combinations', {id_product: id}, function(data){
        popin = $('#popin_action');
        popin.html(data);
        $.fancybox(popin,
            {
                afterLoad: function() {
                    $('#refresh_result').hide();
                    init_listener();
                },
                afterClose: function() {
                    popin.html('');
                },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            });
    });
}

function delete_product_combination(id, id_attribute)
{
    if (confirm(msg_conf_assoc_img))
    {
        initLoaderPopin('#popin_combinations_' + id);
        $.ajax({
            type: 'POST',
            url: url_global + '&ajax=1&action=delete_product_combination',
            async: false,
            cache: false,
            dataType: 'html',
            data: ({id_product: id, id_product_attribute: id_attribute}),
            success: function (data) {
                if(data === 'ok') {
                    $('.fancybox-close').trigger('click');
                    show_combinations(id);
                    $.ajax({
                        type: 'POST',
                        url: url_global +'&ajax=1&action=getFields',
                        async: true,
                        cache: false,
                        data: {id_product: id, field: 'quantity_comb'},
                        dataType: 'json',
                        success: function(json) {
                            for (i in json.confirmations[0]) {
                                id_update = $('#' + json.confirmations[0][i].col + '_txt_' + id + '_0');
                                val = json.confirmations[0][i].value;
                                id_update.html(val);
                                if (json.confirmations[0][i].input_value !== undefined) {
                                    $('#' + json.confirmations[0][i].col + '_value_' + id + '_0_0').val(json.confirmations[0][i].input_value);
                                }
                                if (val == '' || val <= 0) {
                                    id_update.addClass('badge badge-danger');
                                } else {
                                    id_update.removeClass('badge badge-danger');
                                }
                            }
                        }
                    });
                } else if (data === 'error') {
                    $('#refresh_result_comb').hide();
                    alert(msg_error_delete_combination);
                }
            }
        });
    }
}

function duplicate(id_product, copy_image)
{
    if (copy_image) {
        copy_image = 1;
    } else {
        copy_image = 0;
    }
    initLoader();
    hide_all();
    $.get(url_global +  '&ajax=1&action=duplicate', {id_product: id_product, copy_image: copy_image}, function(json){
        if (json.status == 'error') {
            alert(json.error[0]);
            $('#refresh_result').hide();
        } else {
            refresh_result(json.content);
        }
    }, 'json');
}

function default_product_combination(id, id_attribute)
{
    initLoaderPopin('#popin_combinations_' + id);
    $.ajax({
        type: 'POST',
        url: url_global +'&ajax=1&action=default_product_combination',
        async: false,
        cache: false,
        dataType : 'html',
        data: ({id_product: id,id_product_attribute: id_attribute}),
        success: function(data)
        {
            if (data === 'ok') {
                $('.fancybox-close').trigger('click');
                show_combinations(id);
            }
        }
    });
}

function change_features(id)
{
    form_features = $('#form_features_' + id).serialize();

    popin_refresh(true);
    $('#error_features_'+id).hide();
    $.post(url_global +'&ajax=1&action=change_features', form_features, function(json){
        if (json.status == 'error') {
            show_error('error_features_' + id, 'popin_features_' + id, json.error[0]);
            popin_refresh(false);
        } else {
            $.fancybox.close();
        }
    }, 'json');
}

function change_order()
{
    initLoader();
    order_by = $('#order_by').val();
    page = $('#select_pagination').val();
    nb_result = $('#dmuadminrecherche_pagination').val();
    $.get(url_global +'&ajax=1&action=change_order', {order_by:order_by, submitFilterdmuadminrecherche:page, dmuadminrecherche_pagination_ajax:nb_result}, function(html){
        refresh_result(html);
    });
}

function position_column(position, critere)
{
    initLoader();
    page = $('#select_pagination').val();
    nb_result = $('#dmuadminrecherche_pagination').val();
    $.get(url_global +'&ajax=1&action=set_position', {position:position, critere:critere, submitFilterdmuadminrecherche:page, dmuadminrecherche_pagination_ajax:nb_result}, function(html){
        refresh_result(html);
    });
}

function ajax_delete_images_combinations(id_product)
{
    if (confirm(msg_conf_delete_img))
    {
        combinations = [];
        $('input[name="combinationsBox[]"]').each(function(){
            if ($(this).is(':checked')) {
                combinations.push($(this).val());
            }
        });
        list_combinations = combinations.join(',');
        if (list_combinations.length === 0)
        {
            alert(msg_error_select_combination);
            return false;
        }
        initLoaderPopin('#popin_combinations_' + id_product);

        $.ajax({
            type: 'POST',
            url: url_global +'&ajax=1&action=delete_combinations_images',
            async: false,
            cache: false,
            dataType : 'html',
            data: 'list_combinations=' + list_combinations,
            success: function(data)
            {
                $('.fancybox-close').trigger('click');
                show_combinations(id_product);
            }
        });
    }
}

function ajax_assoc_images_combinations(id_product)
{
    combinations = [];
    $('input[name="combinationsBox[]"]').each(function(){
        if ($(this).is(':checked')) {
            combinations.push($(this).val());
        }
    });
    list_combinations = combinations.join(',');

    images = [];
    $('input[name="id_image_attr[]"]').each(function(){
        if ($(this).is(':checked')) {
            images.push($(this).val());
        }
    });
    list_images = images.join(',');

    if (list_combinations.length === 0 || list_images.length === 0)
    {
        $('#refresh_result_comb').hide();
        alert(msg_error_assoc_img);
        return false;
    }
    initLoaderPopin('#popin_combinations_' + id_product);

    $.ajax({
        type: 'POST',
        url: url_global +'&ajax=1&action=Assoc_combinations_images',
        async: false,
        cache: false,
        dataType : 'html',
        data: 'list_images=' + list_images + '&list_combinations=' + list_combinations,
        success: function(data)
        {
            $('.fancybox-close').trigger('click');
            show_combinations(id_product);
        }
    });
}

function reset_filter() {
    jQuery.ajax(
    {
        type : 'POST',
        url  :  url_global+'&ajax=1&action=clearCookie',
        success : function(html)
        {
            $('#div_recherche input[type=radio]').attr('checked', 'checked');
            $('#div_recherche select').val('0');
            window.location.reload();
        }
    });
}

function display_filter() {
    var show_filter;
    $('#div_recherche').slideToggle(400, function() {
        filter = $('#img_filter');
        if (filter.length)
        {
            if ($(this).css('display') === 'none')
            {
                filter.attr('class', filter.attr('class').replace('collapse', 'expand'));
                show_filter = 0;
            }
            else
            {
                filter.attr('class', filter.attr('class').replace('expand', 'collapse'));
                show_filter = 1;
            }
        }
        $.ajax({
            type: 'GET',
            url: url_global + '&ajax=1&action=show_filter&show_filter=' + show_filter,
            async: true,
            cache: false
        });
    });
}