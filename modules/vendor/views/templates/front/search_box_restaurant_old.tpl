{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}

<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />

<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Montserrat+Alternates);
</style>
<br/>
<div class="row">
    <form onsubmit="return validateMeFilter();" method="post" action="index.php?fc=module&module=vendor&controller=VendorRestaurantList">
        <div class="box res_filter_box" style="clear:both;">
            <div class="field" id="searchform">
              <input type="text" id="txtRestaurantSearch" name="txtRestaurantSearch" placeholder="Find Vendor By Zipcode or First or Last Name" />
              <button type="submit">Find!</button>
            </div>
        </div>
        <input type="hidden" value="restaurant_filter" name="SubmitFilterBox" class="hidden">
    </form>
</div>

<script type="text/javascript">
function validateMeFilter(){
	if(jQuery("#txtRestaurantSearch").val() == ''){
		alert('Please enter your zipcode or state !!');
		jQuery("#txtRestaurantSearch").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>