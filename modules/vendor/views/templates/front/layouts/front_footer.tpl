{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}

<script type="text/javascript">
jQuery(function(jQuery) {	
	if(jQuery("#cart_summary").length == 1){
		jQuery('#cart_summary > tbody  > tr').each(function() {
			var design_code = '';
			jQuery(this).find('.cart_description small a').each(function () {
        		var atag_html = this.innerHTML;  
        		var custom_val = atag_html.split('|~|');
				var new_data = '';
				if(custom_val.length > 0){
					for (var i=0;i<custom_val.length;i++) {
						new_data += custom_val[i] + '<br/>';
					}
				}
				jQuery(this).before(new_data);
				jQuery(this).remove();
			});
		});
	}
});
</script>