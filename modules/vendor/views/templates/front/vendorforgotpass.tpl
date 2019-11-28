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
{if $msgs neq ''}
<div class="bootstrap">
  <div class="alert alert-{$msg_class|escape:'htmlall':'UTF-8'}">
    <button data-dismiss="alert" class="close" type="button">x</button>
    {$msgs|escape:'htmlall':'UTF-8'} </div>
</div>
{/if}
<div class="card card-block">
  <div class="row">
    <div class="center_column col-xs-12 col-sm-12" id="center_column">
      <div class="box">
        <h1 class="page-subheading">Forgot your password?</h1>
        <p>Please enter the email address you used to register. We will then send you a new password. </p>
        <form onsubmit="return validateForgetPassForm();" method="post">
          <fieldset>
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" class="form-control">
          </div>
          <p class="submit">
            <button class="btn btn-primary button button-medium exclusive" type="submit"><span>Retrieve Password<i class="icon-chevron-right right"></i></span></button>
          </p>
          </fieldset>
        </form>
      </div>
      <ul class="clearfix footer_links">
        <li><a rel="nofollow" title="Back to Login" href="index.php?fc=module&module=vendor&controller=VendorRegistration" class="btn btn-default button button-small"><span><i class="icon-chevron-left"></i><u>Back to Login</u></span></a></li>
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
function validateForgetPassForm(){
	if($("#email").val() == ''){
		alert('Email Address Required !');
		jQuery("#email").focus();
		return false;
	}
	return true;
}
</script>
{/block} 