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
  <div class="panel-heading"><i class="icon-AdminParentStats"></i> &nbsp;Vendor Report Details</div>
  <div class="table-responsive-row clearfix">
    <div class="form-group">
      <label class="control-label col-lg-3"> <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Select Vendor then select report type">Select Vendor</span> </label>
      <div class="col-lg-9">
        <form method="post">
          <div class="row">
            <div class="col-lg-3">
              <div class="input-group">
                <select name="ddlVendorList" id="ddlVendorList">
                  <option value="0">--Select Vendor--</option>
                  
                  
				  {foreach from=$vendor_list item=vlist}
				  	{if $selected_store eq $vlist.rid}
                  
                  
                  <option selected="selected" value="{$vlist.rid}">{$vlist.firstname} {$vlist.lastname}</option>
                  
                  
					{else}
                  
                  
                  <option value="{$vlist.rid}">{$vlist.firstname} {$vlist.lastname}</option>
                  
                  
					{/if}
				  {/foreach}
                
                
                </select>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="input-group">
                <select name="ddlVendorReportType" id="ddlVendorReportType">
                  <option {if $selected_report_type eq 0} selected {/if} value="0">--Report Type--</option>
                  <option {if $selected_report_type eq 1} selected {/if} value="1">Store Details</option>
                  <option {if $selected_report_type eq 2} selected {/if} value="2">Product Details</option>
                  <option {if $selected_report_type eq 3} selected {/if} value="3">Order Details</option>
                </select>
              </div>
            </div>
            <div class="col-lg-3">
              <button type="submit" name="btnSubmit" class="btn btn-primary"><i class="icon-search"></i> Get Report</button>
            </div>
          </div>
          <input type="hidden" value="report_1" name="hdnreportoption" />
        </form>
      </div>
    </div>
    <div class="row"> {if $vendor_store_info|@count gt 0}
      <div style="margin-top:50px;">
        <h3 align="center">Store Information</h3>
        {foreach from=$vendor_store_info item=foo}
        <div align="center"><img style="width:100px;height:100px;border-radius:100px;" src="{$foo.grid_image}" /></div>
        <div style="font-size:15px;font-weight:bold;text-transform:uppercase;text-align:center">{$foo.firstname} {$foo.lastname}</div>
        <div class="col-lg-6">
          <div style="font-size:14px;margin-left:100px;">
            <div style="margin-top:20px;">Store First Name: {$foo.firstname}</div>
            <div>Store Last Name: {$foo.lastname}</div>
            <div>Store Email Address: {$foo.email}</div>
            <div>Store Telephone: {$foo.telephone}</div>
            <div>Store Commission: {$foo.commission}</div>
            <div>Store Country: {$foo.country}</div>
          </div>
        </div>
        <div class="col-lg-6">
          <div style="font-size:14px;margin-left:100px;">
            <div style="margin-top:20px;">Store Total Product: {$foo.total_product}</div>
            <div>Active Product: {$foo.total_active}</div>
            <div>Inactive Product: {$foo.total_inactive}</div>
            <div>Total Sale: {$foo.total_sale}</div>
            <div>Sold Amount: {$foo.total_amount}</div>
            <div>Admin Profit Amount: {$foo.admin_amount}</div>
          </div>
        </div>
		<div class="row clearfix">
			<div align="center" style="margin-top:20px;"><a class="btn btn-success" target="_blank" href="{$base_url}index.php?fc=module&module=vendor&controller=vendorrestaurantdetails&rid={$foo.rid}">Visit Store</a></div>
		</div>
        {/foreach} </div>
      {/if}
      
      
      {if $vendor_product_details|@count gt 0}
      <div style="margin-top:50px;">
        <h3 align="center">Store product Information (Total: {count($vendor_product_details)} Products)</h3>
        <div class="table-responsive-row clearfix">
          <table class="table">
            <thead>
              <tr class="nodrag nodrop">
                <th><b>Image</b></th>
                <th><b>Name</b></th>
                <th><b>Quantity</b></th>
                <th><b>Price</b></th>
                <th><b>Price off (%)</b></th>
                <th><b>Category</b></th>
                <th><b>Status</b></th>
              </tr>
            </thead>
            <tbody>
            
            {foreach from=$vendor_product_details item=product}
            <tr>
              <td><img class="vendor_image_round" src="{$product.img|escape:'htmlall':'UTF-8'}" /></td>
              <td>{$product.name|escape:'htmlall':'UTF-8'}</td>
              <td>{$product.stock|escape:'htmlall':'UTF-8'}</td>
              <td>{$product.price|escape:'htmlall':'UTF-8'}</td>
              <td>{$product.discount|escape:'htmlall':'UTF-8'}</td>
              <td>{$product.category_name|escape:'htmlall':'UTF-8'}</td>
              <td>{if $product.status eq 1}<i style="color:green;" class="icon-thumbs-up-alt"></i>{else}<i style="color:red;" class="icon-thumbs-down-alt"></i>{/if}</td>
            </tr>
            {/foreach}
            </tbody>
            
          </table>
        </div>
      </div>
      {/if}
      
      {if $vendor_order_details|@count gt 0}
      <div style="margin-top:50px;">
        <h3 align="center">Store Order Information (Total: {count($vendor_order_details)} Order)</h3>
        <div class="table-responsive-row clearfix">
          <table class="table">
            <thead>
              <tr class="nodrag nodrop">
                <th><b>Order No.</b></th>
                <th><b>Reference.</b></th>
                <th><b>Customer</b></th>
                <th><b>Carrier</b></th>
                <th><b>Products</b></th>
                <th><b>Date Sold</b></th>
              </tr>
            </thead>
            <tbody>
            
            {foreach from=$vendor_order_details item=order}
            <tr>
              <td>{$order.order_id|escape:'htmlall':'UTF-8'}</td>
              <td>{$order.reference|escape:'htmlall':'UTF-8'}</td>
              <td>{$order.name|escape:'htmlall':'UTF-8'}</td>
              <td>{$order.carrier_name|escape:'htmlall':'UTF-8'}</td>
              <td>{$order.total_product|escape:'htmlall':'UTF-8'}</td>
              <td>{$order.order_date|escape:'htmlall':'UTF-8'}</td>
            </tr>
            {/foreach}
            </tbody>
            
          </table>
        </div>
      </div>
      {/if} </div>
  </div>
</div>
<style>
.vendor_image_round{
	height:55px;
	width:55px;
	border-radius:55px;
	border:solid 2px #ccc;
}
</style>
