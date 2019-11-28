{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}

<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />
<div class="box res_filter_box" align="center" style="clear:both;">
	<form onsubmit="return validateMeFilter();" class="form-wrapper cf" method="post" action="index.php?fc=module&module=vendor&controller=VendorRestaurantList">
		<input type="text" name="txtRestaurantSearch" id="txtRestaurantSearch" placeholder="Find your vendor by zipcode, firstname, lastname or state...">
		<button type="submit">Search</button>
		<input type="hidden" value="restaurant_filter" name="SubmitFilterBox" class="hidden">
	</form>
</div>
<script type="text/javascript">
function validateMeFilter(){
	if($("#txtRestaurantSearch").val() == ''){
		alert('Please enter your zipcode or state !!');
		$("#txtRestaurantSearch").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>