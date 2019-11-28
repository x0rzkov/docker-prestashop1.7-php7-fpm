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

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<h1 class="page-heading">Welcome To Vendor Dashboard</h1>
<div class="row">
<div class="col-xs-12 col-sm-12">
  <div style="width:100%;margin:0 auto;padding:0;">
    <div id="main_content_area">
      <div align="center" id="top_text">Vendor Dashboard</div>
      <br/>
      {if $market_active eq 0}
	  {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
      <div class="vendorMiddleContentHolder">
        <div class="row">
          <div class="col-sm-3">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3"> <i class="fa fa-percent fa-3x dashboard_bottom_text"></i> </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">{$admin_commission|escape:'htmlall':'UTF-8'}</div>
                    <div class="dashboard_bottom_text">Admin Percent</div>
                  </div>
                </div>
              </div>
              <a>
              <div style="visibility:hidden;" class="panel-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
              </a> </div>
          </div>
          <div class="col-sm-3">
            <div class="panel panel-green">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3"> <i class="fa fa-tags fa-3x dashboard_bottom_text"></i> </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">{$products|escape:'htmlall':'UTF-8'}</div>
                    <div class="dashboard_bottom_text">Total Products</div>
                  </div>
                </div>
              </div>
              <a href="index.php?fc=module&module=vendor&controller=VendorListProduct">
              <div class="panel-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
              </a> </div>
          </div>
          <div class="col-sm-3">
            <div class="panel panel-yellow">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3"> <i class="fa fa-shopping-cart fa-3x dashboard_bottom_text"></i> </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">{$total_sale|escape:'htmlall':'UTF-8'}</div>
                    <div class="dashboard_bottom_text">Total Orders</div>
                  </div>
                </div>
              </div>
              <a href="index.php?fc=module&module=vendor&controller=VendorOrders">
              <div class="panel-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
              </a> </div>
          </div>
          <div class="col-sm-3">
            <div class="panel panel-red">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3"> <i class="fa icon-usd fa-3x dashboard_bottom_text"></i> </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">{$total_amount|escape:'htmlall':'UTF-8'}</div>
                    <div class="dashboard_bottom_text">Total Sales</div>
                  </div>
                </div>
              </div>
              <a href="index.php?fc=module&module=vendor&controller=VendorWithdraw">
              <div class="panel-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
              </a> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading"> <i class="fa icon-shopping-cart fa-lg"></i> Store Progress </div>
              <div class="panel-body">
                <section id="content">
                  <div id="canvas-holder" align="center" style="width:65%; margin:0 auto;">
                    <div id="donut-example"></div>
                  </div>
                </section>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading"> <i class="fa icon-credit-card fa-lg"></i> Year {date('Y')|escape:'htmlall':'UTF-8'} Sales Status </div>
              <div class="panel-body">
                <section id="content">
                  <div id="bar-example"></div>
                </section>
              </div>
            </div>
          </div>
        </div>
        <div class="row latest_orders">
          <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading"> <i class="fa icon-shopping-cart fa-lg"></i> Latest 5 Orders </div>
              <div class="panel-body">
                <section id="content">
                  <div class="table">
                    <div class="row header green">
                      <div class="cell"> Order No. </div>
                      <div class="cell"> Order Reference. </div>
                      <div class="cell"> Customer Name </div>
                      <div class="cell"> Carrier Name </div>
                      <div class="cell"> Total Product </div>
                      <div class="cell"> Date Sold </div>
                      <div class="cell"> &nbsp; </div>
                    </div>
                    {foreach from=$five_orders item=order}
                    <div class="row">
                      <div class="cell"> {$order.order_id|escape:'htmlall':'UTF-8'} </div>
                      <div class="cell"> {$order.reference|escape:'htmlall':'UTF-8'} </div>
                      <div class="cell"> {$order.name|escape:'htmlall':'UTF-8'} </div>
                      <div class="cell"> {$order.carrier_name|escape:'htmlall':'UTF-8'} </div>
                      <div class="cell"> {$order.total_product|escape:'htmlall':'UTF-8'} </div>
                      <div class="cell"> {$order.order_date|escape:'htmlall':'UTF-8'} </div>
                      <div class="cell"> <a href="index.php?fc=module&module=vendor&controller=VendorOrderDetails&id_order={$order.order_id|escape:'htmlall':'UTF-8'}" class="btn btn-default"><i class="fa icon-search-plus"></i> View</a> </div>
                    </div>
                    {/foreach} </div>
                </section>
              </div>
            </div>
          </div>
        </div>
      </div>
	  {else}
	  <div class="inactive_message">{$market_msg|escape:'htmlall':'UTF-8'}</div>
	  {/if}
    </div>
  </div>
</div>
<script>
jQuery( document ).ready(function() {
    Morris.Donut({
	  element: 'donut-example',
	  colors: ["#0FC448", "#C419D1"],
	  resize: true,
	  data: [
		{ label: "Order Completed", value: {$total_sale} },
		{ label: "Total Product", value: {$products} }
	  ]
	});
	
	Morris.Bar({
	  element: 'bar-example',
	  data: {$bar_chart_data nofilter},
	  xkey: ['y'],
	  ykeys: ['b'],
	  labels: ['Sale']
	});
});
</script>