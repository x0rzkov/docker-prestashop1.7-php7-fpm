/**
 * vendor.js
 *
 * @version 1.1.1
 * @license Licensed under the MIT license.
 * @author  solver circle
 * @created 2016-06-15
 */
 
jQuery(document).ready(function() {
	if(jQuery(".helpdesk").length > 0) {
		jQuery(".helpdesk").each(function() {
		  jQuery(this).fancybox({
				'transitionIn': 'elastic',
				'transitionOut': 'elastic',
				'speedIn': 600,
				'speedOut': 200,
				'content': jQuery('#x_'+this.id).html()
		  });
		});
	}
});

(function(jQuery) {
  if(jQuery("#main-tab").length > 0) {
	  jQuery('#main-tab li').on('click', function() {
		tab = $(this).children('a').data('tab');
		jQuery('.mp-list-group li').removeClass('mp-active');
		jQuery(this).addClass('mp-active');
		jQuery('.mp-tabs').removeClass('mp-tab-active');
		jQuery(tab).addClass('mp-tab-active');
		if(tab == '#tab-collection') {
		  jQuery('#category-menu').show();
		} else {
		  jQuery('#category-menu').hide();
		}
	  });
  }
})(jQuery)