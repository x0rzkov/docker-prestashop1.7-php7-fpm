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
  <div class="panel-heading"><i class="icon-list"></i> &nbsp;All Vendors List</div>
  <div style="color:red;font-weight:bold;margin-bottom:10px;">** After approve store set admin commission also.</div>
  <div class="table-responsive-row clearfix">
    <table class="table">
      <thead>
		  <tr class="nodrag nodrop">
			<th><b>Firstname</b></th>
			<th><b>Lastname</b></th>
			<th><b>Email</b></th>
			<th><b>Country</b></th>
			<th><b>Telephone</b></th>
			<th><b>Created Date</b></th>
			<th><b>Approved</b></th>
			<th><b>Commission</b></th>
			<th><b>Status</b></th>
			<th><b>Action</b></th>
		  </tr>
	  </thead>
      {foreach from=$restrurent_list item=foo}
      <tbody>
		  <tr>
			<td style="text-align:center;">{$foo.firstname|escape:'htmlall':'UTF-8'}</td>
			<td style="text-align:center;">{$foo.lastname|escape:'htmlall':'UTF-8'}</td>
			<td style="text-align:center;">{$foo.email|escape:'htmlall':'UTF-8'}</td>
			<td style="text-align:center;">{$foo.country|escape:'htmlall':'UTF-8'}</td>
			<td style="text-align:center;">{$foo.telephone|escape:'htmlall':'UTF-8'}</td>
			<td style="text-align:center;">{$foo.created_date|escape:'htmlall':'UTF-8'}</td>
			<td style="text-align:center;">
			{if $foo.approved eq 1}
			<i style="color:green;" class="icon-thumbs-up-alt"></i>
			{else}
			<i style="color:red;" class="icon-thumbs-down-alt"></i>
			{/if}
			</td>
			<td style="text-align:center;">{$foo.commission|escape:'htmlall':'UTF-8'}&nbsp;%</td>
			{if $foo.status eq 1}
			<td style="text-align:center;"><input data-toggle="toggle" onchange="setStoreStatus({$foo.rid|escape:'htmlall':'UTF-8'},this);" checked="checked" data-on="ON" data-off="OFF" type="checkbox"></td>
			{else}
			<td style="text-align:center;"><input data-toggle="toggle" onchange="setStoreStatus({$foo.rid|escape:'htmlall':'UTF-8'},this);" data-on="ON" data-off="OFF" type="checkbox"></td>
			{/if}
			<td style="text-align:center;"><a data-toggle="tooltip" data-original-title="Details" class="btn btn-warning btn-xs label-tooltip" onclick="viewStoreDetails({$foo.rid|escape:'htmlall':'UTF-8'});" href="javascript:;"><i class="icon-eye"></i></a>&nbsp;<a data-toggle="tooltip" data-original-title="View Store" class="btn btn-info btn-xs label-tooltip" target="_blank" href="{$base_url|escape:'htmlall':'UTF-8'}index.php?fc=module&module=vendor&controller=VendorRestaurantDetails&rid={$foo.rid|escape:'htmlall':'UTF-8'}"><i class="icon-link"></i></a>&nbsp;<a data-toggle="tooltip" data-original-title="Approve" class="btn btn-success btn-xs label-tooltip" onclick="approvedStore({$foo.rid|escape:'htmlall':'UTF-8'});" href="javascript:;"><i class="icon-thumbs-up-alt"></i></a>&nbsp;<a data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger btn-xs label-tooltip" href="javascript:;" onclick="deleteStore({$foo.rid|escape:'htmlall':'UTF-8'});"><i class="icon-trash"></i></a></td>
		  </tr>
	  </tbody>
      <div class="modal fade" tabindex="-1" role="dialog" id="store_{$foo.rid|escape:'htmlall':'UTF-8'}">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h2 class="modal-title">{$foo.firstname|escape:'htmlall':'UTF-8'}&nbsp;{$foo.lastname|escape:'htmlall':'UTF-8'} Details</h2>
            </div>
            <div class="modal-body">
              <div align="center"><img style="border-radius:150px;height:150px;width:150px;" src="{$foo.grid_image|escape:'htmlall':'UTF-8'}" /></div>
              <p></p>
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href=".menu0">Product</a></li>
                <li><a data-toggle="tab" href=".menu1">Sale</a></li>
                <li><a data-toggle="tab" href=".menu4">Profit</a></li>
                <li><a data-toggle="tab" href=".menu2">Commission</a></li>
              </ul>
              <div class="tab-content" style="margin:10px;background:#EFF1F2;">
                <div class="tab-pane fade in active menu0">
                  <p>
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="fa fa-tags"></i></span>
                        <div class="info-box-content"> <span class="info-box-text">Total Product</span> <span class="info-box-number">{$foo.total_product|escape:'htmlall':'UTF-8'}</span> </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="info-box"> <span class="info-box-icon bg-green"><i class="fa fa-tags"></i></span>
                        <div class="info-box-content"> <span class="info-box-text">Active Product</span> <span class="info-box-number">{$foo.total_active|escape:'htmlall':'UTF-8'}</span> </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                      <div class="info-box"> <span class="info-box-icon bg-red"><i class="fa fa-tags"></i></span>
                        <div class="info-box-content"> <span class="info-box-text">In-Active Product</span> <span class="info-box-number">{$foo.total_inactive|escape:'htmlall':'UTF-8'}</span> </div>
                      </div>
                    </div>
                  </div>
                  </p>
                  
                </div>
                <div class="tab-pane fade menu1">
                  <p>
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="fa icon-usd"></i></span>
                        <div class="info-box-content"> <span class="info-box-text">Store Total Sale</span> <span class="info-box-number">{$foo.total_amount|escape:'htmlall':'UTF-8'}</span> </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="info-box"> <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
                        <div class="info-box-content"> <span class="info-box-text">Total Order</span> <span class="info-box-number">{$foo.total_sale|escape:'htmlall':'UTF-8'}</span> </div>
                      </div>
                    </div>
                  </div>
                  </p>
                  
                </div>
                <div class="tab-pane fade menu2">
                  <p>
                  <form name="frmcommission" method="post" id="frmcommission_{$foo.rid|escape:'htmlall':'UTF-8'}">
                    <label for="txtSetAdminCommission_{$foo.rid|escape:'htmlall':'UTF-8'}">Admin Per Product Commission (%)</label>
                    <input type="text" id="txtSetAdminCommission_{$foo.rid|escape:'htmlall':'UTF-8'}" class="from-control" name="txtSetAdminCommission" value="{$foo.commission|escape:'htmlall':'UTF-8'}" />
                    <div class="pull-right">
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                    <input type="hidden" name="SubmitCreate" />
                    <input type="hidden" name="vendorId" value="{$foo.rid|escape:'htmlall':'UTF-8'}" />
                  </form>
                  <div class="clearfix"></div>
                  </p>
                  
                </div>
                <div class="tab-pane fade in menu4">
                  <p>
                  <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                      <div class="info-box"> <span class="info-box-icon bg-green"><i class="fa icon-usd"></i></span>
                        <div class="info-box-content"> <span class="info-box-text">Admin Total Profit</span> <span class="info-box-number">{$foo.admin_amount|escape:'htmlall':'UTF-8'}</span> </div>
                      </div>
                    </div>
                  </div>
                  </p>
                  
                </div>
              </div>
            </div>
            <div class="modal-footer"></div>
          </div>
        </div>
      </div>
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
function approvedStore(rid){
	var iAnswer = confirm('Are you sure you want to approved this store ?');
	if(iAnswer){
		$.ajax({
		   type: "POST",
		   url: "{$ps__base_url|escape:'htmlall':'UTF-8'}ajax.php",
		   data: 'rid='+rid+'&method=resapprove&rand=' + new Date().getTime(),
		   dataType: 'json',
		   success: function(json) {
				if(json.isError){
					alert(json.error);
				}
				else{
					alert('Store Approved Successfully');
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
<style type="text/css"> 
.textbox {
    height: 25px;
    width: 275px;
    text-shadow: 0 1px 0 #FFF;
    outline: none;
    background: -webkit-gradient(linear, left top, left bottom, from(#BCBCBE), to(#FFF));
    background: -moz-linear-gradient(top, #BCBCBE, #FFF);
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    border: 1px solid #717171;
    -webkit-box-shadow: 1px 1px 0 #EFEFEF;
    -moz-box-shadow: 1px 1px 0 #efefef;
    box-shadow: 1px 1px 0 #EFEFEF;
}
.textarea {
    height: 150px;
    width: 275px;
    text-shadow: 0 1px 0 #FFF;
    outline: none;
    background: -webkit-gradient(linear, left top, left bottom, from(#BCBCBE), to(#FFF));
    background: -moz-linear-gradient(top, #BCBCBE, #FFF);
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    border: 1px solid #717171;
    -webkit-box-shadow: 1px 1px 0 #EFEFEF;
    -moz-box-shadow: 1px 1px 0 #efefef;
    box-shadow: 1px 1px 0 #EFEFEF;
}
.button_style {
    background: -moz-linear-gradient(center top , #3d94f6 5%, #1e62d0 100%) repeat scroll 0 0 #3d94f6;
    border: 1px solid #3b7edb;
    border-radius: 6px;
    box-shadow: 0 1px 0 0 #97c4fe inset;
    color: #ffffff;
    display: inline-block;
    font-family: Arial;
    font-size: 15px;
    font-style: normal;
    font-weight: bold;
    height: 37px;
    line-height: 37px;
    text-align: center;
    text-decoration: none;
    text-indent: 0;
    text-shadow: 1px 1px 0 #1570cd;
    width: 140px;
}
.button_style:hover {
    background: -moz-linear-gradient(center top , #1e62d0 5%, #3d94f6 100%) repeat scroll 0 0 #1e62d0;
}
.button_style:active {
    position: relative;
    top: 1px;
}
</style>
