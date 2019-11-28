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
    if (psb_psv == 1.5) {
        var $sales_number = $('.psb-sales-number');
        var html = $sales_number.html();
        if (html) {
            html = html.replace(/\[1\]/g, '<b>').replace(/\[\/1\]/g, '</b>');
            $sales_number.html(html);
        }
    }

    // Fix for PS1.7 when the product page refreshes incorrectly
    if (typeof prestashop === 'object' && typeof prestashop.on === 'function') {
        prestashop.on('updatedProduct', function (event) {
            psb_manualRefresh();
        });
    }
});

if (typeof updateDisplay === 'function') {
    var updateDisplay_pbc = updateDisplay;
    updateDisplay = function () {
        updateDisplay_pbc();
        psb_refreshBar();
    }
}

function psb_refreshBar() {
    var id_pa = $('#idCombination').val();
    $('.psb-combi-wrp').hide();
    $('.psb-cw-' + id_pa).fadeIn(100);
}

function psb_manualRefresh() {
    var data = {ajax: 1, action: 'renderHook', token: psb_token};
    var data_string = $.param(data) + '&' + $('#add-to-cart-or-refresh').serialize();

    $.ajax({
        url: psb_ajax_url,
        data: data_string,
        method: 'post',
        type: 'post',
        cache: false,
        success: function (html) {
            $('.pstStockBar').replaceWith(html);
        }
    });
}