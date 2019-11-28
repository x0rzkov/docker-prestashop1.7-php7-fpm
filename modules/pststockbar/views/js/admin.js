/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2018 Presta.Site
 * @license   LICENSE.txt
 */

$(function () {
    psb_initColorPicker();
    psb_initColorPickerLevels();
    psb_updateDisplayLevelsOptions(0);

    if (typeof tabs_manager !== 'undefined') {
        tabs_manager.onLoad('ModulePststockbar', function () {
            setTimeout(function () {
                psb_initColorPickerLevels();
            }, 500);
        });
    }

    $('#link-ModulePststockbar').on('click', function () {
        setTimeout(function () {
            psb_initColorPickerLevels();
        }, 100);
    });

    if (psb_psv === 1.7) {
        $('.modules-list-select').on('change', function () {
            if ($(this).val() === 'module-pststockbar') {
                setTimeout(function () {
                    psb_initColorPickerLevels();
                }, 100);
            }
        });
    }

    $('#psb_show_more_options').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).hide();
        $('#psb_hide_more_options').show(200);
        $('.psb_more_options_row').slideDown(200)
    });
    $('#psb_hide_more_options').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).hide();
        $('#psb_show_more_options').show(200);
        $('.psb_more_options_row').slideUp(200)
    });

    $('.psb-ins-toggle').on('click', function (e) {
        e.preventDefault();

        $('#psb-ins-wrp').slideToggle(200);
        var text = $(this).text();
        var alt_text = $(this).data('alt');

        // switch text "show/hide"
        $(this).text(alt_text);
        $(this).data('alt', text);
    });

    $(document).on('click', '.psb-lvl-del', function (e) {
        e.preventDefault();

        if (confirm(psb_basic_confirm_txt)) {
            $(this).parents('tr:first').fadeOut(100).find('.psb-lvl-todel').val('1');
        }
    });

    $(document).on('click', '.psb-lvl-add', function (e) {
        e.preventDefault();

        var id = psb_genID(15);
        var tpl = $('#psb_lvl_tpl')[0].outerHTML;
        tpl = tpl.replace(new RegExp('__id__', 'g'), id);
        if (psb_psv === 1.5) {
            tpl = tpl.replace('translatable_delay', 'translatable');
        }
        var $tpl = $(tpl);
        $tpl.removeAttr('id').show();
        $tpl.appendTo('#psb_lvl_table tbody');
        psb_initColorPickerLevels();
        if (psb_psv === 1.5) {
            displayFlags(languages, id_language);
        }
        $tpl.find('.psb-lvl-qty').focus();
    });

    $(document).on('keyup', '.psb-lvl-qty', function (e) {
        psb_sortLvlTable();
        $(this).focus();
    });

    $('[name=CUSTOM_STOCK_LEVELS]').on('change', function () {
        psb_updateDisplayLevelsOptions(300);
    });

    $(document).on('click', '#psb_csl_btn_save', function () {
        var data = {ajax: true, action: 'saveProductLevels'};
        var data_string = $.param(data) + '&' + $('#psb_product_data_wrp').find(':input').serialize();

        $('#psb_csl_success').hide();
        $('#psb_csl_error').hide();

        $.ajax({
            url: psb_ajax_url,
            data: data_string,
            method: 'post',
            success: function (result) {
                if (result === '1') {
                    $('#psb_csl_success').fadeIn(300);
                    setTimeout(function () {
                        $('#psb_csl_success').fadeOut(300);
                    }, 3000);
                    psb_reloadProductLevels();
                } else {
                    $('#psb_csl_error').html(result).fadeIn(300);
                }
            }
        });
    });
});

function psb_sortLvlTable() {
    var table = document.getElementById('psb_lvl_table');
    var col = 0;
    var reverse = false;
    var tb = table.tBodies[0], // use `<tbody>` to ignore `<thead>` and `<tfoot>` rows
        tr = Array.prototype.slice.call(tb.rows, 0), // put rows into array
        i;

    reverse = -((+reverse) || -1);
    tr = tr.sort(function (a, b) { // sort rows
        var qty_a = parseInt($(a.cells[col]).find('.psb-lvl-qty').val());
        var qty_b = parseInt($(b.cells[col]).find('.psb-lvl-qty').val());
        return reverse // `-1 *` if want opposite order
            * (qty_a - qty_b);
    });
    for(i = 0; i < tr.length; ++i) {
        tb.appendChild(tr[i]); // append each row in order
    }
}

function psb_initColorPicker() {
    $('.psbColorPickerInput').spectrum({
        preferredFormat: "rgb",
        showAlpha: true,
        allowEmpty:true,
        showInput: true
    });
}

function psb_initColorPickerLevels() {
    $('.psbColorPickerLevel:visible').spectrum({
        preferredFormat: "rgb",
        showAlpha: true,
        allowEmpty:true,
        showInput: true
    });
}

function psb_updateDisplayLevelsOptions(speed) {
    var use_custom_levels = $('[name=CUSTOM_STOCK_LEVELS]:checked').val();

    if (!use_custom_levels || use_custom_levels === '0') {
        $('.psb_custom_levels_row').hide();
        $('.psb_regular_levels_row').fadeIn(speed);
    } else {
        $('.psb_regular_levels_row').hide();
        $('.psb_custom_levels_row').fadeIn(speed);
    }
}

function psb_genID(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function psb_reloadProductLevels() {
    $('#psb_lvl_table').addClass('psb_loading');

    var id_product = $('#psb_product_data_wrp').data('id-product');
    $.ajax({
        url: psb_ajax_url,
        data: {ajax: 1, action: 'renderProductLevels', id_product: id_product},
        method: 'post',
        success: function (html) {
            $('#psb_csl_table_wrp').html(html);
            psb_initColorPickerLevels();
        }
    });
}