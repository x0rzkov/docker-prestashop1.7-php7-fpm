{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/global.css">

<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/datatables.min.css"/>
<script type="text/javascript" src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/js/datatables.min.js"></script>
<h1 class="page-heading">Welcome To Restaurant Dashboard</h1>
<div class="row">
<div class="col-xs-12 col-sm-12 rest_common_panel">
  <div style="width:100%;margin:0 auto;padding:0;">
    <div id="main_content_area">
      <div align="center" id="top_text">Order Report</div>
      <br/>
      {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
      <div class="vendorMiddleContentHolder">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Find Your Order</h3>
              </div>
              <div style="border:solid 1px #ccc;padding:20px;">
                <form method="post">
                  <div class="col-md-4">
                    <input type="text" placeholder="Order Id" name="txtOrderId" id="txtOrderId" value="{$orderId|escape:'htmlall':'UTF-8'}" class="form-control" style="height:35px;" />
                  </div>
				  <div class="col-md-1"> OR </div>
                  <div class="col-md-4">
                    <input type="text" name="txtOrderdate" id="txtOrderDate" value="{$xdate|escape:'htmlall':'UTF-8'}" placeholder="dd/mm/yyyy" class="form-control datepicker" style="height:35px;" data-date-format="dd-mm-yyyy" />
                  </div>
				  <div class="col-md-2">
                    <input type="submit" value="Search" name="btnSubmit" class="btn btn-primary" />
                  </div>
				  <input type="hidden" name="submitCreate" value="submitFilter" />
                </form>
                <div style="clear:both;"></div>
              </div>
              <br/>
              <br/>
              <div align="center">
                <h3 class="box-title">Filter Order List</h3>
              </div>
              <div class="box-body">
                <table id="order_list_vendor" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <td><b>Order No.</b></td>
                      <td><b>Reference.</b></td>
                      <td><b>Customer</b></td>
                      <td><b>Carrier</b></td>
                      <td><b>Products</b></td>
                      <td><b>Date Sold</b></td>
                      <td>&nbsp;</td>
                    </tr>
                  </thead>
                  <tbody>
                  {foreach from=$orders item=order}
                  <tr>
                    <td>{$order.order_id|escape:'htmlall':'UTF-8'}</td>
                    <td>{$order.reference|escape:'htmlall':'UTF-8'}</td>
                    <td>{$order.name|escape:'htmlall':'UTF-8'}</td>
                    <td>{$order.carrier_name|escape:'htmlall':'UTF-8'}</td>
                    <td>{$order.total_product|escape:'htmlall':'UTF-8'}</td>
                    <td>{$order.order_date|escape:'htmlall':'UTF-8'}</td>
                    <td><a href="index.php?fc=module&module=vendor&controller=VendorOrderDetails&id_order={$order.order_id|escape:'htmlall':'UTF-8'}&x=r" class="btn btn-default"><i class="fa icon-search-plus"></i> View</a></td>
                  </tr>
                  {/foreach}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
jQuery(document).ready(function() {
    jQuery('#order_list_vendor').DataTable();
	jQuery('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
} );
</script>
