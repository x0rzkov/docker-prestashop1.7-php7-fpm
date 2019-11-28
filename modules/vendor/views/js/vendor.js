/**
 * vendor.js
 *
 * @version 1.1.1
 * @license Licensed under the MIT license.
 * @author  solver circle
 * @created 2016-06-15
 */
 
function vendorDeleteInformation(url,confriminfo)
{
	if(confirm("Are you sure you want to delete this "+confriminfo)){
		window.location.href = url;
	}
}

function vendorChangeTab(obj) {
	if(obj.value == 0) {
		$("#variation_tab").show();
		$("#virtual_tab").hide();
	}
	else {
		$("#virtual_tab").show();
		$("#variation_tab").hide();
	}
}