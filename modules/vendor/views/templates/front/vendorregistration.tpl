{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}
{extends file=$layout}
{block name='content'}
<h1 class="page-heading">Create a Vendor Store or Login</h1>
<div class="bootstrap" style="display:{$msgx|escape:'htmlall':'UTF-8'};">
  <div class="alert alert-{$msg_class|escape:'htmlall':'UTF-8'}">
    <button data-dismiss="alert" class="close" type="button">x</button>
    {$msgs|escape:'htmlall':'UTF-8'} </div>
</div>
<div class="card card-block">
  <div class="row">
    <div class="col-xs-12 col-sm-6">
      <div>
        <form  onsubmit="return validateRegistrationForm();" id="account-creation_form" class="std box" method="post">
          <div class="account_creation">
            <h3 class="page-subheading">Your personal information</h3>
            <div class="required form-group">
              <label for="customer_firstname"> First name <sup>*</sup> </label>
              <input id="customer_firstname" class="is_required validate form-control" type="text" value="" name="customer_firstname" data-validate="isName" onkeyup="$('#firstname').val(this.value);">
            </div>
            <div class="required form-group">
              <label for="customer_lastname">Last name <sup>*</sup></label>
              <input type="text" value="" name="customer_lastname" id="customer_lastname" data-validate="isName" class="is_required validate form-control" onkeyup="$('#lastname').val(this.value);">
            </div>
            <div class="required form-group">
              <label for="email">Email <sup>*</sup></label>
              <input type="email" value="" name="email" id="email" data-validate="isEmail" class="is_required validate account_input form-control">
            </div>
            <div class="required form-group">
              <label for="email">Telephone <sup>*</sup></label>
              <input type="text" name="telephone" id="telephone" class="is_required validate form-control">
            </div>
            <div class="required form-group">
              <label for="email">Country <sup>*</sup></label>
              <select name="country" id="country" onchange="getState(this.value);" class="form-control">
                <option value="">Select Country</option>
			  {foreach from=$country_list item=country}
                <option value="{$country.id_country|escape:'htmlall':'UTF-8'}">{$country.name|escape:'htmlall':'UTF-8'}</option>
			  {/foreach}
              </select>
            </div>
            <div class="required form-group">
              <label for="state">State <sup>*</sup></label>
              <select name="state" id="state" class="form-control">
                <option value="">Select State</option>
              </select>
            </div>
            <div class="required form-group">
              <label for="email">Password <sup>*</sup></label>
              <input type="password" value="" name="passwd" id="passwd" data-validate="isPasswd" class="is_required validate account_input form-control">
            </div>
            <br/>
            <div class="submit">
              <button name="SubmitCreate" id="SubmitCreate" type="submit" class="btn btn-primary button button-medium exclusive"> <span> <i class="icon-user left"></i> Create an account </span> </button>
              <input type="hidden" value="Create an account" name="SubmitCreate" class="hidden">
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-xs-12 col-sm-6">
      <form class="box" id="login_form" method="post">
        <h3 class="page-subheading">Already registered?</h3>
        <div class="form_content clearfix">
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" value="" name="email" id="email" data-validate="isEmail" class="is_required validate account_input form-control">
          </div>
          <div class="form-group">
            <label for="passwd">Password</label>
            <input type="password" value="" name="passwd" id="passwd" data-validate="isPasswd" class="is_required validate account_input form-control">
          </div>
          <p class="lost_password form-group"><a rel="nofollow" title="Recover your forgotten password" href="index.php?fc=module&module=vendor&controller=vendorforgotpassword">Forgot your password?</a></p>
          <p class="submit">
            <button class="button btn btn-primary button-medium" name="SubmitLogin" id="SubmitLogin" type="submit"> <span> <i class="icon-lock left"></i> Sign in </span> </button>
            <input type="hidden" value="Create an account" name="SubmitLogin" class="hidden">
          </p>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function validateRegistrationForm(){
	var proof = true;
	if($("#customer_firstname").val() == ''){
		alert('First Name Required !');
		$("#customer_firstname").focus();
		proof = false;
	}
	else if($("#customer_lastname").val() == ''){
		alert('Last Name Required !');
		$("#customer_lastname").focus();
		proof = false;
	}
	else if($("#email").val() == ''){
		alert('Valid Email Required !');
		$("#email").focus();
		proof = false;
	}
	else if($("#telephone").val() == ''){
		alert('Telephone Required !');
		$("#telephone").focus();
		proof = false;
	}
	else if($("#country").val() == ''){
		alert('Country Required !');
		$("#country").focus();
		proof = false;
	}
	else if($("#passwd").val() == ''){
		alert('Password Required !');
		$("#passwd").focus();
		proof = false;
	}
	return proof;
}
function getState(obj){
	var xhtml = '<option value="">Select State</option>';
	if(obj != ''){
		$.ajax({
		   type: "POST",
		   url: "{$ps_base_url|escape:'htmlall':'UTF-8'}modules/vendor/ajax.php",
		   data: 'method=loadStateByCountry&country_id='+obj,
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
				$("#state").html(xhtml);
			}
		});
	}
}
</script>
{/block} 