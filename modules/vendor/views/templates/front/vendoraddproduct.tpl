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

<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/js/jquery/plugins/alerts/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<script type="text/javascript" src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/js/vendor.js"></script>
<script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
<script type="text/javascript" src="{$ps_base_url|escape:'htmlall':'UTF-8'}/js/jquery/plugins/alerts/jquery.alerts.js"></script>
<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/datatables.min.css"/>
<script type="text/javascript" src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/js/datatables.min.js"></script>
<h1 class="page-heading">Welcome To Vendor Dashboard</h1>
<div class="row">
<div class="col-xs-12 col-sm-12 rest_common_panel">
  <div style="width:100%;margin:0 auto;padding:0;">
    <div id="main_content_area">
      <div align="center" id="top_text">Vendor Dashboard</div>
      <br/>
      {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
      <form method="post" onsubmit="return validateProductForm();" enctype="multipart/form-data" >
        <div class="vendorMiddleContentHolder">
          <div>
            <div class="col-md-12">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h2 class="box-title">Add New Product</h2>
                </div>
                <div class="box-body">
                  <div class="tabbable-panel">
                    <div class="tabbable-line">
                      <ul class="nav nav-tabs add_product_tab">
                        <li class="active"><a data-toggle="tab" href="#home">Information</a></li>
                        <li><a data-toggle="tab" href="#menu0">Category</a></li>
                        <li><a data-toggle="tab" href="#menu6">Price</a></li>
                        <li><a data-toggle="tab" href="#menu5">SEO</a></li>
                        <li><a data-toggle="tab" href="#menu1">Quantities</a></li>
                        {if $product_type eq 0}
                        <li id="variation_tab"><a data-toggle="tab" href="#menu9">Combination</a></li>
                        {else}
                        <li id="virtual_tab"><a data-toggle="tab" href="#menu99">Virtual Product</a></li>
                        {/if}
                        <li><a data-toggle="tab" href="#menu2">Image</a></li>
                        <li><a data-toggle="tab" href="#menu3">Gallery</a></li>
                        <li><a data-toggle="tab" href="#menu4">Special</a></li>
                      </ul>
                      <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                          <p>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Type:</label>
                            <div class="radio">
                              <label for="simple_product">
                              <input name="type_product" {if $product_type eq 0} checked {/if} onchange="vendorChangeTab(this);"  class="form-group" id="simple_product" value="0" type="radio">
                              Standard product</label>
                            </div>
                            <div class="radio">
                              <label for="virtual_product">
                              <input name="type_product" {if $product_type eq 1} checked {/if} onchange="vendorChangeTab(this);" class="form-group" id="virtual_product" value="1" type="radio">
                              Virtual product (services, booking, downloadable products, etc.)</label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Name:</label>
                            <input type="text" id="txtItemName" name="txtItemName" value="{if isset($p_name)}{$p_name|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                          </div>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Long Description:</label>
                            <textarea class="mytextarea" name="txtLongDesc" rows="15" cols="80">{if isset($p_long)}{$p_long|escape:'htmlall':'UTF-8'}{/if}</textarea>
                          </div>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Short Description:</label>
                            <textarea class="mytextarea" name="txtShortDesc" rows="15" cols="80">{if isset($p_long)}{$p_long|escape:'htmlall':'UTF-8'}{/if}</textarea>
                          </div>
                          <div class="form-group">
                            <label>Condition:
                            <select name="condition" id="condition" class="form-control">
                              <option value="new" {if $p_con eq "new"} selected {/if}>New</option>
                              <option {if $p_con eq "used"} selected {/if} value="used">Used</option>
                              <option {if $p_con eq "refurbished"} selected {/if} value="refurbished">Refurbished</option>
                            </select>
                            </label>
                          </div>
                          {if $active eq 0}
                          {if $admin_approve eq 0}
                          <div class="form-group">
                            <label>Status:
                            <select name="txtItemStatus" style="width:100px;" id="txtItemStatus" class="form-control">
                              <option value="1" {if $active eq 1} selected {/if}>Enable</option>
                              <option value="0" {if $active eq 0} selected {/if}>Disable</option>
                            </select>
                            </label>
                          </div>
                          {/if}
                          {else}
                          <div class="form-group">
                            <label>Status:
                            <select name="txtItemStatus" style="width:100px;" id="txtItemStatus" class="form-control">
                              <option value="1" {if $active eq 1} selected {/if}>Enable</option>
                              <option value="0" {if $active eq 0} selected {/if}>Disable</option>
                            </select>
                            </label>
                          </div>
                          {/if}
                          </p>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                          <p>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Item Quantity:</label>
                            <input type="text" name="txtItemQuantity" id="txtItemQuantity" value="{if isset($quantity)}{$quantity|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                          </div>
                          </p>
                        </div>
                        <div id="menu0" class="tab-pane fade">
                          <p>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Select Category:</label>
                            <select id="ddlcategoryId" class="form-control" style="width:150px;" name="ddlcategoryId">
                              <option value="">--Select Category--</option>
                              
							{foreach from=$cat_collection item=foo}
                          
                              <option value="{$foo.id_category|escape:'htmlall':'UTF-8'}" {if $category eq $foo.id_category} selected {/if}>{$foo.name|escape:'htmlall':'UTF-8'}</option>
                              
							{/foreach}
                        
                            </select>
                          </div>
                          </p>
                        </div>
                        <div id="menu99" class="tab-pane fade">
                          <p>
                          <h4>Virtual Product (services, booking or downloadable products)</h4>
                          <br/>
                          <div class="form-group">
                            <label>Display Filename:</label>
                            <input type="text" name="txtDisplayFileName" value="{$download_filename}" class="form-control">
                          </div>
                          <div class="form-group">
                            <label>No of Download:</label>
                            <input type="text" name="txtNoDownload" value="{$no_download}" class="form-control">
                          </div>
                          <div class="form-group">
                            <label class="btn btn-success" for="my-file-selector">
                            <input type="file" id="uploadDownloadFile" name="uploadDownloadFile">
                            Upload Virtual Product File (Allow Zip File only) </label>
                            <input type="hidden" name="downloadFileId" value="" />
                          </div>
                          </p>
                        </div>
                        <div id="menu9" class="tab-pane fade">
                          <p> {if $product_id eq 0}
                          <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            There is 1 warning.
                            <ul style="display:block;" id="seeMore">
                              <li>You must save this product before adding combinations.</li>
                            </ul>
                          </div>
                          {else}
                          <div style="border:solid 1px #ccc;padding:7px;" id="comb_panel_list">
                            <div align="left"><u>
                              <h4>Product Combination List</h4>
                              </u></div>
                            <div align="right">
                              <button class="btn btn-success" type="button" onclick="switchPanel();"><i class="fa fa-plus"></i> Add New</button>
                            </div>
                            <br/>
                            <table id="product_attr_list_vendor" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                  <td><b>Attribute - value pair</b></td>
                                  <td><b>Impact on price</b></td>
                                  <td><b>Reference </b></td>
                                  <td><b>EAN-13</b></td>
                                  <td><b>UPC </b></td>
                                  <td><b>Action</b></td>
                                </tr>
                              </thead>
                              <tbody>
                              
                              {foreach from=$comArrays item=comArray}
                              <tr>
                                <td>{$comArray.attributes|escape:'htmlall':'UTF-8'}</td>
                                <td>{$comArray.price|escape:'htmlall':'UTF-8'}</td>
                                <td>{$comArray.reference|escape:'htmlall':'UTF-8'}</td>
                                <td>{$comArray.ean13|escape:'htmlall':'UTF-8'}</td>
                                <td>{$comArray.upc|escape:'htmlall':'UTF-8'}</td>
                                <td><a href="javascript:;" onclick="getCombInfo({$comArray.id_product_attribute|escape:'htmlall':'UTF-8'});" class="btn btn-success round_st"><i class="fa icon-edit fa-lg"></i></a>&nbsp;<a href="javascript:;" onclick="deleteComData({$comArray.id_product_attribute|escape:'htmlall':'UTF-8'});" class="btn btn-danger round_st"><i class="fa icon-trash fa-lg"></i></a></td>
                              </tr>
                              {/foreach}
                              </tbody>
                              
                            </table>
                          </div>
                          <br/>
                          <div style="border:solid 1px #ccc;padding:7px;display:none;" id="add_new_comb_panel">
                            <input type="hidden" name="id_product_attribute" id="id_product_attribute" value="0" />
                            <div align="left"><u>
                              <h4>Add New Product Combination</h4>
                              </u></div>
                            <div align="right">
                              <button class="btn btn-success" type="button" onclick="switchPanel();"><i class="fa fa-arrow-left"></i> Back</button>
                            </div>
                            <br/>
                            <div class="form-group">
                              <label>Attribute:</label>
                              <select id="attribute_group" onchange="getAttributeValue(this);" class="form-control" style="width:275px;" name="attribute_group">
                                <option value="">--Select Attribute--</option>
                                
									{foreach from=$attr_groups item=attr_group}
									
                                <option value="{$attr_group.id_attribute_group|escape:'htmlall':'UTF-8'}">{$attr_group.name|escape:'htmlall':'UTF-8'}</option>
                                
									{/foreach}
								
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Value:</label>
                              <select id="attribute" class="form-control" style="width:275px;" name="attribute">
                                <option value="">--Select Value--</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <select id="product_att_list" class="attr_select" name="attribute_combination_list[]" class="form-control" multiple="multiple">
                              </select>
                            </div>
                            <div class="form-group">
                              <button class="btn btn-success" type="button" onclick="add_attr();"><i class="icon-plus-sign-alt"></i> Add</button>
                                   
                              <button class="btn btn-danger" type="button" onclick="del_attr();"><i class="icon-minus-sign-alt"></i> Delete</button>
                            </div>
                            <div class="form-group">
                              <label>Reference code:</label>
                              <input id="attribute_reference" name="attribute_reference" class="form-control" value="" type="text">
                            </div>
                            <div class="form-group">
                              <label>EAN-13 or JAN barcode:</label>
                              <input maxlength="13" id="attribute_ean13" class="form-control" name="attribute_ean13" value="" type="text">
                            </div>
                            <div class="form-group">
                              <label>EUPC barcode:</label>
                              <input maxlength="12" id="attribute_upc" class="form-control" name="attribute_upc" value="" type="text">
                            </div>
                            <div class="form-group">
                              <label>Impact on price:</label>
                              <select name="attribute_price_impact" class="form-control" id="attribute_price_impact">
                                <option value="0">None</option>
                                <option value="1">Increase</option>
                                <option value="-1">Decrease</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Price:</label>
                              <input maxlength="12" id="attribute_price" class="form-control" name="attribute_price" value="" type="text">
                            </div>
                            <div class="form-group">
                              <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Quantity:</label>
                              <input class="form-control" class="form-control" id="attribute_stock_available" name="attribute_stock_available" value="0" type="text">
                            </div>
                            <div class="form-group">
                              <p class="checkbox">
                                <label for="attribute_default">
                                <input name="attribute_default" id="attribute_default" value="1" type="checkbox">
                                Make this combination the default combination for this product. </label>
                              </p>
                            </div>
                          </div>
                          {/if}
                          </p>
                        </div>
                        <div id="menu2" class="tab-pane fade"> <span style="color:red;font-weight:bold;font-size:15px;">* add product image</span>
                          <p> {if $image neq ''}
                          <div style="margin-bottom:20px;" id="vendor_product_img"> <img id="product_image" src="{$image|escape:'htmlall':'UTF-8'}" /> </div>
                          {else}
                          <div style="margin-bottom:20px;" id="vendor_product_img"> <img id="product_image" src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/img/no_image.png" /> </div>
                          {/if}
                          <label class="btn btn-success" for="my-file-selector">
                          <input onchange=readURL(this); id="my-file-selector" name="productimage" type="file" style="display:none;">
                          Upload Item Image </label>
                          <button type="button" class="btn btn-danger" onclick="removeItemImage();">Remove Image</button>
                          <input type="hidden" name="hdnItemImage" id="hdnItemImage" value="{$image|escape:'htmlall':'UTF-8'}" />
                          </p>
                        </div>
                        <div id="menu6" class="tab-pane fade">
                          <p>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Base Price:</label>
                            <input type="text" name="txtItemPrice" id="txtItemPrice" value="{if isset($price)}{$price|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                          </div>
                          </p>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                          <p>
                            <label class="btn btn-success" for="my-file-selector">
                            <input type="file" multiple="" id="filesToUpload" name="filesToUpload[]">
                            Upload Gallery Image </label>
                          </p>
                          <br/>
                          <p> {foreach from=$gallerys item=gallery}
                          <div class="gallery_delete_icon" id="xx_{$gallery['coverId']|escape:'htmlall':'UTF-8'}"><img style="width:100px;height:100%" src="{$gallery['href']|escape:'htmlall':'UTF-8'}" /><span onclick="deleteProductGalleryImage({$gallery['coverId']|escape:'htmlall':'UTF-8'});" class="gallery_span_btn" title="Delete Me"><img src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/img/delete.png" /></span></div>
                          <input type="hidden" name="hdnGallery[]" id="hdn_{$gallery['coverId']|escape:'htmlall':'UTF-8'}" value="{$gallery['href']|escape:'htmlall':'UTF-8'}">
                          {/foreach}
                          </p>
                          <div style="clear:both;"></div>
                        </div>
                        <div id="menu4" class="tab-pane fade">
                          <p>
                          <div class="form-group">
                            <label>Item Special Price (%):</label>
                            <input type="text" name="txtItemDiscount" class="form-control" value="{if isset($special_price)}{$special_price|escape:'htmlall':'UTF-8'}{/if}">
                          </div>
                          </p>
                        </div>
                        <div id="menu5" class="tab-pane fade">
                          <p>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Meta Title:</label>
                            <input type="text" name="txtMetaTitle" id="txtMetaTitle" value="{if isset($seo_title)}{$seo_title|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                          </div>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Meta Description:</label>
                            <input type="text" name="txtMetaDesc" id="txtMetaDesc" value="{if isset($seo_desc)}{$seo_desc|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                          </div>
                          <div class="form-group">
                            <label><span style="color:red;font-weight:bold;font-size:15px;">*</span> Seo Friendly Url:</label>
                            <input type="text" name="txtSeoUrl" id="txtSeoUrl" value="{if isset($seo_friendly_url)}{$seo_friendly_url|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                          </div>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div align="right"> {if $tokenx eq 'add'}
                    <button class="btn btn-primary" type="submit">Save Information</button>
                    {else}
                    <button class="btn btn-primary" type="submit">Update Information</button>
                    {/if} </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" value="{$tokenx|escape:'htmlall':'UTF-8'}" name="SubmitCreate" class="hidden">
        <input type="hidden" value="{$product_id|escape:'htmlall':'UTF-8'}" name="product_id" class="hidden">
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  tinymce.init({
    selector: '.mytextarea',
	height : "100"
  });
});
function deleteProductGalleryImage(obj){
	if(confirm('Are you sure you want to delete this')){
		$("#xx_"+obj).remove();
		$("#hdn_"+obj).remove();
	}
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#product_image').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function removeItemImage(){
	if(confirm('Are you sure you want to delete product image')){
		$("#vendor_product_img img").removeAttr("src");
		$("#hdnItemImage").remove();
	}
}
function getAttributeValue(obj) {
	var post_url = "{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/ajax.php";
	$.ajax({
		type: "POST",
		url: post_url,
		data: 'agid='+obj.value+'&method=attributeValue',
		dataType: 'json',
		success: function(response) {
			$html = '<option value="">--Select Value--</option>';
			if(response != ''){
				if(response.length > 0){
					for (var i = 0; i < response.length; i++){
						var obj = response[i];
						var name = obj['name'];
						var attr_id = obj['id_attribute'];
						$html += '<option value="'+attr_id+'">'+name+'</option>';
					}
				}
			}
			else{
				alert('No Attribute Value found')
			}
			$("#attribute").html($html);
		},
	});
}

/**
 * deafult messages
 */
var msg_combination_1 = '{l s='Please choose an attribute.'}';
var msg_combination_2 = '{l s='Please choose a value.'}';
var msg_combination_3 = '{l s='You can only add one combination per attribute type.'}';
var msg_new_combination = '{l s='New combination'}';
var msg_cancel_combination = '{l s='Cancel combination'}';

/**
 * Add an attribute from a group in the declination multilist
 */		
var storeUsedGroups = {};
function add_attr()
{
	var attr_group = $('#attribute_group option:selected');
	if (attr_group.val() == 0)
		return jAlert(msg_combination_1);

	var attr_name = $('#attribute option:selected');
	if (attr_name.val() == 0)
		return jAlert(msg_combination_2);

	if (attr_group.val() in storeUsedGroups)
		return jAlert(msg_combination_3);

	storeUsedGroups[attr_group.val()] = true;
	$('<option></option>')
		.attr('value', attr_name.val())
		.attr('groupid', attr_group.val())
		.text(attr_group.text() + ' : ' + attr_name.text())
		.appendTo('#product_att_list');
	$('#product_att_list option').prop('selected', true);
}

/**
 * Delete one or several attributes from the declination multilist
 */
function del_attr()
{
	$('#product_att_list option:selected').each(function()
	{
		delete storeUsedGroups[$(this).attr('groupid')];
		$(this).remove();
	});
}

function switchPanel(){
	if($('#comb_panel_list').is(':visible') ){
		$("#comb_panel_list").hide();
		$("#add_new_comb_panel").show();
	}
	else{
		$("#comb_panel_list").show();
		$("#add_new_comb_panel").hide();
	}
	setAttrDefault();
}

function getCombInfo(id_product_attribute){
	if(id_product_attribute != '' && parseInt(id_product_attribute) > 0){
		$("#id_product_attribute").val(id_product_attribute);
		var post_url = "index.php?fc=module&module=vendor&controller=VendorAddProduct";
		$.ajax({
			type: "POST",
			url: post_url,
			data: 'method=getComInformation&id_product=' + {$product_id} + '&id_product_attribute=' + id_product_attribute,
			dataType: 'json',
			success: function(data) {
				switchPanel();
				$('#product_att_list').html('');
				var price = data[0]['price'];
				var weight = data[0]['weight'];
				var unit_impact = data[0]['unit_price_impact'];
				var reference = data[0]['reference'];
				var ean = data[0]['ean13'];
				var quantity = data[0]['quantity'];
				var image = false;
				var product_att_list = new Array();
				for(i=0;i<data.length;i++)
				{
					product_att_list.push(data[i]['group_name']+' : '+data[i]['attribute_name']);
					product_att_list.push(data[i]['id_attribute']);
				}
				var id_product_attribute = data[0]['id_product_attribute'];
				var default_attribute = data[0]['default_on'];
				var eco_tax = data[0]['ecotax'];
				var upc = data[0]['upc'];
				var minimal_quantity = data[0]['minimal_quantity'];
				var available_date = data[0]['available_date'];
				
				var id_product_attribute = data[0]['id_product_attribute'];
				var default_attribute = data[0]['default_on'];
				var eco_tax = data[0]['ecotax'];
				var upc = data[0]['upc'];
				var minimal_quantity = data[0]['minimal_quantity'];
				var available_date = data[0]['available_date'];
				
				if (default_attribute == 1) {
					document.getElementById('attribute_default').checked = true;
					$("#uniform-attribute_default span").addClass('checked');
				}
				else {
					document.getElementById('attribute_default').checked = false;
					$("#uniform-attribute_default span").removeClass('checked');
				}
				
				$("#attribute_reference").val(reference);
				$("#attribute_ean13").val(ean);
				$("#attribute_upc").val(upc);
				$("#attribute_price").val(price);
				$("#attribute_stock_available").val(quantity);
				
				if (price < 0)
				{
					$("#attribute_price_impact").val(-1);
				}
				else if (!price)
				{
					//$("#attribute_price_impact").val(-1);
				}
				else if (price > 0)
				{
					$("#attribute_price_impact").val(1);
				}
				
				var elem = document.getElementById('product_att_list');
				for (var i = 0; i < product_att_list.length; i++)
				{
					var opt = document.createElement('option');
					opt.text = product_att_list[i++];
					opt.value = product_att_list[i];
					try {
						elem.add(opt, null);
					}
					catch(ex) {
						elem.add(opt);
					}
				}
				$('#product_att_list option').prop('selected', true);
				
			},
		});
	}
	else{
		$("#id_product_attribute").val(0);
	}
}

function setAttrDefault(){
	$("#attribute_group").val('');
	$("#attribute").find('option').not(':first').remove();
	$('#product_att_list').html('');
	$("#attribute_reference").val('');
	$("#attribute_ean13").val('');
	$("#attribute_upc").val('');
	$("#attribute_price_impact").val(1);
	$("#attribute_price").val('');
	$("#attribute_stock_available").val('');
	document.getElementById('attribute_default').checked = false;
	$("#uniform-attribute_default span").removeClass('checked');
}

function deleteComData(id_product_attribute) {
	if(id_product_attribute != '' && parseInt(id_product_attribute) > 0){
		if(confirm("Are you sure you want to delete this combination ?")) {
			//here yes
			var post_url = "index.php?fc=module&module=vendor&controller=VendorAddProduct";
			$.ajax({
				type: "POST",
				url: post_url,
				data: 'method=removeCombination&id_product=' + {$product_id} + '&id_product_attribute=' + id_product_attribute,
				dataType: 'json',
				success: function(data) {
					alert(data.message);
					window.location.reload();
				},
			});
		}
	}
}


function validateProductForm() {
	if(jQuery("#txtItemName").val() == '') {
		alert("Product name required");
		jQuery("#txtItemName").focus();
		return false;
	} else if($.trim(tinymce.get('txtLongDesc').getContent()) == '') {
		alert("Product long description required");
		jQuery("#txtLongDesc").focus();
		return false;
	} else if($.trim(tinymce.get('txtShortDesc').getContent()) == '') {
		alert("Product short description required");
		jQuery("#txtShortDesc").focus();
		return false;
	} else if(jQuery("#ddlcategoryId").val() == '') {
		alert("Product category required");
		jQuery("#ddlcategoryId").focus();
		return false;
	} else if(jQuery("#txtItemPrice").val() == '0.00' || jQuery("#txtItemPrice").val() == '') {
		alert("Product price required");
		jQuery("#txtItemPrice").focus();
		return false;
	} else if(jQuery("#txtMetaTitle").val() == '') {
		alert("Product meta title required");
		jQuery("#txtMetaTitle").focus();
		return false;
	} else if(jQuery("#txtMetaDesc").val() == '') {
		alert("Product meta description required");
		jQuery("#txtMetaDesc").focus();
		return false;
	} else if(jQuery("#txtSeoUrl").val() == '') {
		alert("Product seo friendly required");
		jQuery("#txtSeoUrl").focus();
		return false;
	/*} else if(jQuery("#txtItemQuantity").val() == '0' || jQuery("#txtItemQuantity").val() == '') {
		alert("Product quantity required");
		jQuery("#txtItemQuantity").focus();
		return false;*/
	} else if(jQuery("#product_image").attr('src').replace( /^.*?([^\/]+)\..+?$/, '$1' )== 'no_image') {
		alert("Product image required");
		return false;
	} else {
		return true;
	}
}
</script>
<script type="text/javascript"> jQuery(document).ready(function() { jQuery('#product_attr_list_vendor').DataTable(); } ); </script>