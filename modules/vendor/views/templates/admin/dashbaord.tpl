{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}

<link rel="stylesheet" type="text/css" href="{$ps__base_url|escape:'htmlall':'UTF-8'}views/css/vendor_res_admin.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<div class="panel my_box">
  <div class="panel-heading"><i class="icon-list"></i> &nbsp;Store Information</div>
  <div class="row">
    <div class="col-lg-3 col-md-6">
      <div class="panels panels-primary">
        <div class="panels-heading">
          <div class="row">
            <div class="col-xs-3"> <i class="fa fa-university fa-4x"></i> </div>
            <div class="col-xs-9 text-right">
              <div class="huge">{$total_restaurant|escape:'htmlall':'UTF-8'}</div>
              <div>Total Stores</div>
            </div>
          </div>
        </div>
        <a target="_blank" href="{$vendor_list_link|escape:'htmlall':'UTF-8'}">
        <div class="panels-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
        </a> </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panels panels-green">
        <div class="panels-heading">
          <div class="row">
            <div class="col-xs-3"> <i class="fa fa-tags fa-4x"></i> </div>
            <div class="col-xs-9 text-right">
              <div class="huge">{$total_product|escape:'htmlall':'UTF-8'}</div>
              <div>Total Products</div>
            </div>
          </div>
        </div>
        <a href="{$product_list_link|escape:'htmlall':'UTF-8'}">
        <div class="panels-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
        </a> </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panels panels-red">
        <div class="panels-heading">
          <div class="row">
            <div class="col-xs-3"> <i class="fa fa-university fa-4x"></i> </div>
            <div class="col-xs-9 text-right">
              <div class="huge">{$waiting_restaurant_approve|escape:'htmlall':'UTF-8'}</div>
              <div>Store Waiting Approve</div>
            </div>
          </div>
        </div>
        <a href="{$vendor_list_link|escape:'htmlall':'UTF-8'}">
        <div class="panels-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
        </a> </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panels panels-yellow">
        <div class="panels-heading">
          <div class="row">
            <div class="col-xs-3"> <i class="fa fa-shopping-cart fa-4x"></i> </div>
            <div class="col-xs-9 text-right">
              <div class="huge">{$total_sale|escape:'htmlall':'UTF-8'}</div>
              <div>Total Store Orders</div>
            </div>
          </div>
        </div>
        <a href="{$order_list_link|escape:'htmlall':'UTF-8'}">
        <div class="panels-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
        </a> </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-default">
      <div class="panel-heading"> <i class="fa icon-shopping-cart fa-lg"></i>&nbsp;&nbsp;Withdraw Status </div>
      <div class="panel-body">
        <section>
          <div id="canvas-holder" align="center" style="width:65%; margin:0 auto;">
			<div id="donut-example"></div>
		  </div>
        </section>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="panel panel-default">
      <div class="panel-heading"> <i class="fa icon-credit-card fa-lg"></i> Sales Status </div>
      <div class="panel-body">
        <section id="content">
		  <div id="bar-example"></div>
		</section>
      </div>
    </div>
  </div>
</div>
<div class="row latest_orders">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading"> <i class="fa icon-shopping-cart fa-lg"></i>&nbsp;&nbsp;All Store Last 5 Orders </div>
      <div class="panel-body">
        <div>
          <div class="table">
            <div class="row header green">
              <div class="cell"> #Order ID </div>
			  <div class="cell"> Product </div>
              <div class="cell"> Unit Price </div>
              <div class="cell"> Quantity </div>
              <div class="cell"> Date Sold </div>
            </div>
           {foreach from=$fivesales item=fivesale}
			<div class="row">
              <div class="cell"> {$fivesale['order_id']|escape:'htmlall':'UTF-8'} </div>
			  <div class="cell"> {$fivesale['product_name']|escape:'htmlall':'UTF-8'} </div>
              <div class="cell"> {$fivesale['product_price']|escape:'htmlall':'UTF-8'} </div>
              <div class="cell"> {$fivesale['product_qty']|escape:'htmlall':'UTF-8'} </div>
              <div class="cell"> {$fivesale['order_date']|escape:'htmlall':'UTF-8'} </div>
            </div>
			{/foreach}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
Morris.Donut({
  element: 'donut-example',
  colors: ["#D9534F", "#0FC448"],
  resize: true,
  data: [
    { label: "Payment Pending", value: {$withdraw_pending_list} },
    { label: "Payment Success", value: {$withdraw_success_list} }
  ]
});

Morris.Bar({
  element: 'bar-example',
  data: {$bar_chart_data},
  xkey: ['y'],
  ykeys: ['b'],
  labels: ['Sale']
});
</script>
