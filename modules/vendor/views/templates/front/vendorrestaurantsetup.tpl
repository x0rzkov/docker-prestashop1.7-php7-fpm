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
<script src='{$ps_base_url|escape:"htmlall":"UTF-8"}/modules/vendor/views/js/jscolor.js'></script>
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
                  Updated Store Information Successfully. </div>
              </div>
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Store Setup</h3>
                </div>
                <div class="box-body">
                  <div class="tabbable-panel">
                    <div class="tabbable-line">
                      <ul class="nav nav-tabs add_product_tab">
                        <li class="active"><a data-toggle="tab" href="#menu1">Information</a></li>
                        <li><a data-toggle="tab" href="#menu2">Images</a></li>
                        <li><a data-toggle="tab" href="#menu3">Popup Schedule</a></li>
                        <li><a data-toggle="tab" href="#menu4">Social</a></li>
                        <li><a data-toggle="tab" href="#menu5">Store Content</a></li>
                        <li><a data-toggle="tab" href="#menu6">Grid Content</a></li>
                        <li><a data-toggle="tab" href="#menu7">Store Color</a></li>
                      </ul>
                      <div class="tab-content">
                        <div id="menu4" class="tab-pane fade">
                          <div class="form-group">
                            <label>Facebook Link:</label>
                            <input type="text" value="{$facebook_link|escape:'htmlall':'UTF-8'}" name="txtFacebookLink" class="form-control">
                          </div>
                          <div class="form-group">
                            <label>Twitter Link:</label>
                            <input type="text" value="{$twitter_link|escape:'htmlall':'UTF-8'}" name="txtTwitterLink" class="form-control">
                          </div>
                          <div class="form-group">
                            <label>Google Plus Link:</label>
                            <input type="text" value="{$google_plus_link|escape:'htmlall':'UTF-8'}" name="txtGooglePlus" class="form-control">
                          </div>
                        </div>
                        <div id="menu1" class="tab-pane fade in active">
                          <div class="form-group">
                            <label for="txtStoreName">Store Name:</label>
                            <input type="text" value="{$store_name|escape:'htmlall':'UTF-8'}" name="txtStoreName" class="form-control">
                          </div>
                          <div class="form-group">
                            <label for="txtStoreAddress">Store Address:</label>
                            <textarea type="text" name="txtStoreAddress" class="form-control">{$store_address|escape:'htmlall':'UTF-8'}</textarea>
                          </div>
                          <div class="form-group">
                            <label for="txtZipCode">Zip Code:</label>
                            <input type="text" value="{$store_zipcode|escape:'htmlall':'UTF-8'}" name="txtZipCode" class="form-control">
                          </div>
                          <div class="form-group">
                            <label for="txtStoreEmail">Store Email:</label>
                            <input type="text" value="{$store_email|escape:'htmlall':'UTF-8'}" name="txtStoreEmail" class="form-control">
                          </div>
                          <div class="form-group">
                            <label for="txtPaypalEmail">Paypal Email:</label>
                            <input type="text" value="{$paypal_email|escape:'htmlall':'UTF-8'}" name="txtPaypalEmail" class="form-control">
                          </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                          <div class="form-group" style="border:solid 5px #ccc;padding:10px;"> {if $grid_image neq ''}
                            <div style="margin-bottom:20px;"><img width="150" height="150" src="{$grid_image|escape:'htmlall':'UTF-8'}" id="grid_image" /></div>
                            {/if}
                            <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="gridimage" type="file" style="display:none;">
                            Upload Store Grid Image (485 X 485) </label>
                            <input type="hidden" value="{$grid_image|escape:'htmlall':'UTF-8'}" name="hdnGridImage" />
                            <a href="javascript:;" onclick="removeImageAlert('grid');" class="btn btn-danger">Remove Grid Image</a> </div>
                          <div class="form-group" style="border:solid 5px #ccc;padding:10px;"> {if $banner_image neq ''}
                            <div style="margin-bottom:20px;"><img style="width:50%" src="{$banner_image|escape:'htmlall':'UTF-8'}" id="grid_image" /></div>
                            {/if}
                            <label class="btn btn-success" for="my-file-selector-2">
                            <input id="my-file-selector-2" name="bannerimage" type="file" style="display:none;">
                            Upload Store Banner Image (1140 X 221)</label>
                            <a href="javascript:;" onclick="removeImageAlert('banner');" class="btn btn-danger">Remove Banner Image</a>
                            <input type="hidden" value="{$banner_image|escape:'htmlall':'UTF-8'}" name="hdnBannerImage" />
                          </div>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                          <div class="form-group">
                            <label>Design Your Open/Close Schedule For Popup:</label>
                            <textarea class="txtSchedule" name="txtSchedule" rows="15" cols="80">{$store_schedule|escape:'htmlall':'UTF-8'}</textarea>
                          </div>
                        </div>
                        <div id="menu5" class="tab-pane fade">
                          <div class="form-group">
                           <h4><label>Store Page Profile Content:</label></h4>
                            <textarea class="txtSchedule" name="txtStoreContent" rows="15" cols="80">{$store_content|escape:'htmlall':'UTF-8'}</textarea>
                          </div>
						  <div class="form-group">
                            <h4><label>Store Page About US Content:</label></h4>
                            <textarea class="txtSchedule" name="txtStoreContentAboutUs" rows="15" cols="80">{$store_content_about_us|escape:'htmlall':'UTF-8'}</textarea>
                          </div>
                        </div>
                        <div id="menu6" class="tab-pane fade">
                          <div class="form-group">
                            <label>Grid Content (MAX 160 Char):</label>
                            <textarea class="txtSchedule" name="txtGridContent" rows="15" cols="80">{$grid_content|escape:'htmlall':'UTF-8'}</textarea>
                          </div>
                        </div>
                        <div id="menu7" class="tab-pane fade">
                          <div class="form-group">
                            <label for="txtTopHeaderTextColor">Store Top Header Text Color:</label>
                            <input type="text" value="{$vendor_color_1|escape:'htmlall':'UTF-8'}" name="txtTopHeaderTextColor" class="form-control color">
                          </div>
                          <div class="form-group">
                            <label for="txtStorePhoneTextColor">Store Phone no text color:</label>
                            <input type="text" value="{$vendor_color_2|escape:'htmlall':'UTF-8'}" name="txtStorePhoneTextColor" class="form-control color">
                          </div>
                          <div class="form-group">
                            <label for="txtStoreEmailColor">Store Email Text Color:</label>
                            <input type="text" value="{$vendor_color_3|escape:'htmlall':'UTF-8'}" name="txtStoreEmailColor" class="form-control color">
                          </div>
                          <div class="form-group">
                            <label for="txtTopOtherTextColor">Top Other text Color:</label>
                            <input type="text" value="{$vendor_color_4|escape:'htmlall':'UTF-8'}" name="txtTopOtherTextColor" class="form-control color">
                          </div>
                          <div class="form-group">
                            <label for="txtLeftMenuTextColor">Store Left Menu Color:</label>
                            <input type="text" value="{$vendor_color_5|escape:'htmlall':'UTF-8'}" name="txtLeftMenuTextColor" class="form-control color">
                          </div>
                          <div class="form-group">
                            <label for="txtLeftMenuSelectedTextColor">Store Left Menu Selected Color:</label>
                            <input type="text" value="{$vendor_color_6|escape:'htmlall':'UTF-8'}" name="txtLeftMenuSelectedTextColor" class="form-control color">
                          </div>
						  <div class="form-group">
                            <label for="txtVendorTopBarBGColor">Store Top Bar Background Color:</label>
                            <input type="text" value="{$vendor_color_7|escape:'htmlall':'UTF-8'}" name="txtVendorTopBarBGColor" class="form-control color">
                          </div>
						  <div class="form-group">
                            <label for="txtVendorTopBorderBGColor">Store Top Bar Border Color:</label>
                            <input type="text" value="{$vendor_color_8|escape:'htmlall':'UTF-8'}" name="txtVendorTopBorderBGColor" class="form-control color">
                          </div>
						  <div class="form-group">
                            <label for="txtVendorStoreLeftMenuBGColor">Store Menu Background Color:</label>
                            <input type="text" value="{$vendor_color_9|escape:'htmlall':'UTF-8'}" name="txtVendorStoreLeftMenuBGColor" class="form-control color">
                          </div>
						  <div class="form-group">
                            <label for="txtVendorStoreLeftMenuSelectedColor">Store Menu Selected Color:</label>
                            <input type="text" value="{$vendor_color_10|escape:'htmlall':'UTF-8'}" name="txtVendorStoreLeftMenuSelectedColor" class="form-control color">
                          </div>
						  <div class="form-group">
                            <label for="txtVendorStoreContentBoxBGColor">Store Content Box Background Color:</label>
                            <input type="text" value="{$vendor_color_11|escape:'htmlall':'UTF-8'}" name="txtVendorStoreContentBoxBGColor" class="form-control color">
                          </div>
                        </div>
                      </div>
                    </div>
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
	plugins: "image",
	height : "100"
  });
});
function removeImageAlert(token){
 var iAnswer = confirm('Are you sure you want to delete this ?');
 if(iAnswer){
 	window.location.href = 'index.php?fc=module&module=vendor&controller=VendorRestaurantSetup&imgdel=' + token;
 }
}
</script>
