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

$(function() {
    setTimeout(removeUniform, 1000);

    $('.delivery_options').on('change', 'input.delivery_option_radio', function(e){
        var $delivery_selected = $(e.currentTarget);

        var data = {
            url_call: orderOpcCarrier + '?rand=' + new Date().getTime(),
            is_ajax: true,
            action: 'updateCarrier',
            token: static_token
        };

        data[$delivery_selected.attr('name')] = $delivery_selected.val();

        var _json = {
            data: data
        };
        $.makeRequest(_json);
    });
});

function removeUniform() {
    var parent_control = 'div#onepagecheckoutps';

    if (typeof $.uniform !== 'undefined' && typeof $.uniform.restore !== 'undefined') {
        $.uniform.restore(parent_control + ' select');
        $.uniform.restore(parent_control + ' input');
        $.uniform.restore(parent_control + ' a.button');
        $.uniform.restore(parent_control + ' button');
        $.uniform.restore(parent_control + ' textarea');
    }

    if (typeof $(parent_control + ' select').select_unstyle !== 'undefined') {
        $(parent_control + ' select').select_unstyle();
    }

    if (typeof $(parent_control + ' select').selectBox !== 'undefined') {
        $(parent_control + ' select').selectBox('destroy');
    }

    if (typeof $(parent_control + ' select').selectBox !== 'undefined') {
        $(parent_control + ' select').selectBox('destroy');
    }
}
