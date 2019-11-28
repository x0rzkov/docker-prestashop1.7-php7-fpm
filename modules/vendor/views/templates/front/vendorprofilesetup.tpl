{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/global.css">

<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
<h1 class="page-heading">Welcome To Vendor Dashboard</h1>
<div class="row">
<div class="col-xs-12 col-sm-12 rest_common_panel">
  <div style="width:100%;margin:0 auto;padding:0;">
    <div id="main_content_area">
      <div align="center" id="top_text">Vendor Dashboard</div>
      <br/>
      {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
      <form method="post" enctype="multipart/form-data" >
        <div class="vendorMiddleContentHolder">
          <div class="row">
            <div class="col-md-12">
              <div class="bootstrap" style="display:{$msgx|escape:'htmlall':'UTF-8'};">
                <div class="alert alert-success">
                  <button data-dismiss="alert" class="close" type="button">x</button>
                  Updated Profile Information Successfully. </div>
              </div>
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Profile Setup</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" value="{$firstname|escape:'htmlall':'UTF-8'}" name="txtFirstName" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" value="{$lastname|escape:'htmlall':'UTF-8'}" name="txtLastName" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Email:</label>
                    <input type="text" value="{$email|escape:'htmlall':'UTF-8'}" name="txtEmail" class="form-control">
                  </div>
                  <div class="form-group" style="width:200px;">
                    <label for="ddlcountry">Country:</label>
                    <select name="ddlcountry" onchange="getStateByCountryId(this.value);" id="ddlcountry" class="form-control">
                      <option value="-1">--Select Country--</option>
                      
						{foreach from=$country_list item=country}
							{if $country.id_country eq $country_id}
								
                      <option value="{$country.id_country|escape:'htmlall':'UTF-8'}" selected="selected">{$country.name|escape:'htmlall':'UTF-8'}</option>
                      
							{else}
								
                      <option value="{$country.id_country|escape:'htmlall':'UTF-8'}">{$country.name|escape:'htmlall':'UTF-8'}</option>
                      
							{/if}
						{/foreach}
					
                    </select>
                  </div>
                  <div class="required form-group" style="width:200px;">
                    <label for="state">State <sup>*</sup></label>
                    <select name="ddlstate" id="ddlstate" class="form-control">
                      <option value="">Select State</option>
					  {foreach from=$states item=state}
						  {if $state.id_state eq $state_id}
							<option value="{$state.id_state|escape:'htmlall':'UTF-8'}" selected="selected">{$state.name|escape:'htmlall':'UTF-8'}</option>
						  {else}	
							<option value="{$state.id_state|escape:'htmlall':'UTF-8'}">{$state.name|escape:'htmlall':'UTF-8'}</option>
						  {/if}
					  {/foreach}
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Telephone:</label>
                    <input type="text" value="{$telephone|escape:'htmlall':'UTF-8'}" name="txtTelephone" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Password:</label>
                    <input type="text" value="{$password|escape:'htmlall':'UTF-8'}" name="txtPassword" class="form-control">
                  </div>
                </div>
                <div align="right">
                  <button class="btn btn-primary" type="submit">Save Information</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" value="Create an account" name="SubmitCreate" class="hidden">
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  tinymce.init({
    selector: '.txtSchedule',
	height : "100"
  });
});
function removeImageAlert(token){
 var iAnswer = confirm('Are you sure you want to delete this ?');
 if(iAnswer){
 	window.location.href = 'index.php?controller=VendorRestaurantSetup&imgdel=' + token;
 }
}
function getStateByCountryId(country_id){
	var xhtml = '<option value="">Select State</option>';
	if(country_id != '' && parseInt(country_id) > 0){
		$.ajax({
		   type: "POST",
		   url: "{$ps_base_url|escape:'htmlall':'UTF-8'}modules/vendor/ajax.php",
		   data: 'method=loadStateByCountry&country_id='+country_id,
		   dataType: 'json',
		   success: function(json){
				if(json.isError){
					alert(json.error);
				}
				else{
					for(var i = 0; i < json.data.length; i++) {
						var obj = json.data[i];
						xhtml += "<option value='"+obj.id_state+"'>"+obj.name+"</option>";
					}
				}
				$("#ddlstate").html(xhtml);
			}
		});
	}
}
</script>
