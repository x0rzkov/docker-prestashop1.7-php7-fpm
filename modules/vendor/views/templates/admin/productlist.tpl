{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
{if $xMsg neq ''}
<div class="bootstrap">
  <div class="alert alert-success mymsg">
    <button data-dismiss="alert" class="close" type="button">x</button>
    {$xMsg|escape:'htmlall':'UTF-8'} </div>
</div>
{/if}
<div class="panel">
  <div class="panel-heading"><i class="icon-list"></i> &nbsp;All Requested Product List</div>
  <div class="table-responsive-row clearfix">
    <table class="table">
      <thead>
        <tr class="nodrag nodrop">
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Store Name</th>
          <th>Product Category</th>
          <th>Added date</th>
          <th style="text-align:center;">Approved</th>
          <th style="text-align:center;">Action</th>
        </tr>
      </thead>
      {foreach from=$requested_product_list item=foo}
      <tbody>
        <tr>
          <td><img style="border:solid 1px #ccc" width="50" height="50" src="{$foo.img|escape:'htmlall':'UTF-8'}" /></td>
          <td>{$foo.name|escape:'htmlall':'UTF-8'}</td>
          <td>{$foo.price|escape:'htmlall':'UTF-8'}</td>
          <td>{$foo.s_name|escape:'htmlall':'UTF-8'}</td>
          <td>{$foo.category_name|escape:'htmlall':'UTF-8'}</td>
          <td>{$foo.date_add|escape:'htmlall':'UTF-8'}</td>
          <td align="center">{if $foo.status eq 1}<i style="color:green;" class="icon-thumbs-up-alt"></i>{else}<i style="color:red;" class="icon-thumbs-down-alt"></i>{/if}</td>
          <td align="center"><a data-toggle="tooltip" data-original-title="Approve Product" class="btn btn-success btn-xs label-tooltip" onclick="approvedStoreProduct({$foo.id_product});" href="javascript:;"><i class="icon-thumbs-up-alt"></i></a> &nbsp; <a data-toggle="modal" data-original-title="Product Action" class="btn btn-primary btn-xs label-tooltip" data-target="#store_{$foo.id_product}"><i class="icon-edit"></i></a>
            
			<div class="modal fade" tabindex="-1" role="dialog" id="store_{$foo.id_product}">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h2 class="modal-title">Delete Product and Send Email To Vendor</h2>
                  </div>
                  <div class="modal-body">
				  	<div align="left">
						<form method="post">
							<div class="form-group">
								<label for="txtVendorEmail">Vendor Email</label>
								<input type="text" class="form-control" name="txtVendorEmail" readonly="true" value="{$foo.s_email}" id="txtVendorEmail" />
							</div>
							<div class="form-group">
								<label for="txtEmailSubject">Subject</label>
								<input type="text" class="form-control" name="txtEmailSubject" id="txtEmailSubject" />
							</div>
							<div class="form-group">
								<label for="txtEmailBody">Message</label>
								<textarea id="txtEmailBody" class="form-control" name="txtEmailBody"></textarea>
							</div>
							<div class="form-group">
								<label for="chkDeleteProduct">Delete Product</label>
								<br/>
								<input type="checkbox" name="chkDeleteProduct" id="chkDeleteProduct" />
							</div>
							<input type="hidden" name="hdnVendorEmail" value="{$foo.s_email}" />
							<input type="hidden" name="hdnVendorProduct" value="{$foo.id_product}" />
							<div class="form-group">
								<div align="right"><button type="submit" class="btn btn-success"><i class="icon-send"></i> Send</button></div>
							</div>
						</form>
					</div>
				  </div>
                  <div class="modal-footer"></div>
                </div>
              </div>
            </div></td>
        </tr>
      </tbody>
      {/foreach}
    </table>
  </div>
</div>
<script type="text/javascript">
function viewStoreDetails(rid){
	$('#store_'+rid).modal('toggle');
}
function deleteStore(rid){
	var iAnswer = confirm('Are you sure you want to delete this store ?');
	if(iAnswer){
		$.ajax({
		   type: "POST",
		   url: "{$ps__base_url|escape:'htmlall':'UTF-8'}ajax.php",
		   data: 'rid='+rid+'&method=deleteres&rand=' + new Date().getTime(),
		   dataType: 'json',
		   success: function(json) {
				if(json.isError){
					alert(json.error);
				}
				else{
					alert('Store Remove Successfully');
					window.location.reload();
				}
			}
		});
	}
}
function setStoreStatus(rid,obj){
	var iAnswer = confirm('Are you sure you want to change store status?');
	if(iAnswer){
		var x=0;
		if(obj.checked){
			x=1;
		}
		$.ajax({
		   type: "POST",
		   url: "{$ps__base_url|escape:'htmlall':'UTF-8'}ajax.php",
		   data: 'rid='+rid+'&method=storeStatus&status='+x+'&rand=' + new Date().getTime(),
		   dataType: 'json',
		   success: function(json) {
				if(json.isError){
					alert(json.error);
				}
				else{
					alert('Store Status Change Successfully');
					window.location.reload();
				}
			}
		});
	}
}

function deleteStoreProduct(pid,email,modal_id){
	$("#txtVendorEmail").val(email);
	$("#"+modal_id).modal('show');
}

function actionProductEmailAndDelete() {
	var validate = true;
	
	if ($("#txtEmailSubject").val() != '') {
		alert('Email subject requred !!!');
		validate = false;
	}
	else if ($("#txtEmailBody").val() != '') {
		alert('Email message requred !!!');
		validate = false;
	}
	
	return validate;
}

function approvedStoreProduct(pid){
	var iAnswer = confirm('Are you sure you want to approved this product ?');
	if(iAnswer){
		$.ajax({
		   type: "POST",
		   url: "{$ps__base_url|escape:'htmlall':'UTF-8'}ajax.php",
		   data: 'pid='+pid+'&method=proapprove&rand=' + new Date().getTime(),
		   dataType: 'json',
		   success: function(json) {
				if(json.hasError){
					alert(json.error);
				}
				else{
					alert('Product Approved Successfully');
					window.location.reload();
				}
			}
		});
	}
}
</script>

<style type="text/css">
.info-box {
    background: #fff none repeat scroll 0 0;
    border-radius: 2px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    display: block;
    margin-bottom: 15px;
    min-height: 90px;
    width: 100%;
}
.bg-aqua, .callout.callout-info, .alert-info, .label-info, .modal-info .modal-body {
    background-color: #00c0ef !important;
	color:#fff;
}
.info-box-content {
    margin-left: 90px;
    padding: 5px 10px;
}
.info-box-icon {
    background: rgba(0, 0, 0, 0.2) none repeat scroll 0 0;
    border-radius: 2px 0 0 2px;
    display: block;
    float: left;
    font-size: 30px;
    height: 90px;
    line-height: 90px;
    text-align: center;
    width: 90px;
}
.info-box-text {
    display: block;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.info-box-number {
    display: block;
    font-size: 18px;
    font-weight: bold;
}
.bg-red, .callout.callout-danger, .alert-danger, .alert-error, .label-danger, .modal-danger .modal-body {
    background-color: #dd4b39 !important;
	color:#fff;
}
.bg-green, .callout.callout-success, .alert-success, .label-success, .modal-success .modal-body {
    background-color: #00a65a !important;
	color:#fff;
}
.errorlist{
	color:red;
	font-weight:bold;
	margin-left:5px;
	display:none;
}
#my_quote_cart td{
	border:solid 1px #000000;
	font-weight:bold;
}
#my_quote_cart th{
	border:solid 1px #000000;
	font-weight:bold;
}
.my_quote_cart_sub td{
	border:solid 1px #000000;
	font-weight:bold;
}
.my_quote_cart_sub th{
	border:solid 1px #000000;
	font-weight:bold;
}
.modal-title{
	 color:red !important;
}
</style>