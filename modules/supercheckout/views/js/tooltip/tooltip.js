/**
 * DISCLAIMER.
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer tohttp://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @category  PrestaShop Module
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 *
 * Description
 *
 * tooltip.js file
 */

var lsTooltip = {

	init : function() {

		jQuery(document).on('mouseover', '[data-help]', function() {

			lsTooltip.open(this);
		});

		jQuery(document).on('mouseout', '[data-help]', function() {
			lsTooltip.close();
		});
	},

	open : function(el) {

		// Create tooltip
		jQuery('body').prepend( jQuery('<div>', { 'class' : 'ls-tooltip' })
			.append( jQuery('<div>', { 'class' : 'inner' }))
			.append( jQuery('<span>') )
		);
        
		// Get tooltip
		var tooltip = jQuery('.ls-tooltip');

		// Set tooltip text
		tooltip.find('.inner').text( jQuery(el).attr('data-help'));

		// Get viewport dimensions
		var v_w = jQuery(window).width();

		// Get element dimensions
		var e_w = jQuery(el).width();

		// Get element position
		var e_l = jQuery(el).offset().left;
		var e_t = jQuery(el).offset().top;

		// Get toolip dimensions
		var t_w = tooltip.outerWidth();
		var t_h = tooltip.outerHeight();

		// Position tooltip
		tooltip.css({ top : e_t - t_h - 10, left : e_l - (t_w - e_w) / 2  });
		// Fix right position
		if(tooltip.offset().left + t_w > v_w) {
			tooltip.css({ 'left' : 'auto', 'right' : 10 });
			tooltip.find('span').css({ left : 'auto', right : v_w - jQuery(el).offset().left - jQuery(el).outerWidth() / 2 - 17, marginLeft : 'auto' });
		}

	},

	close : function() {
		jQuery('.ls-tooltip').remove();
	}
};
lsTooltip.init();