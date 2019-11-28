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
      <div class="vendorMiddleContentHolder">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Order Details #{$order_id|escape:'htmlall':'UTF-8'}</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading" style="color:#00A65A;"> <i class="fa icon-truck fa-lg"></i> Shipping Address </div>
                      <div class="panel-body">
                        <section id="content"> {$shipping['firstname']|escape:'htmlall':'UTF-8'} {$shipping['lastname']|escape:'htmlall':'UTF-8'}<br/>
                          {if $shipping['company'] neq ''}{$shipping['company']|escape:'htmlall':'UTF-8'}<br/>
                          {/if}
                          {$shipping['address1']|escape:'htmlall':'UTF-8'}<br/>
                          {if $shipping['address2'] neq ''}{$shipping['address2']|escape:'htmlall':'UTF-8'}<br/>
                          {/if}
                          {$shipping['state_name']|escape:'htmlall':'UTF-8'}, {$shipping['city']|escape:'htmlall':'UTF-8'} {$shipping['postcode']|escape:'htmlall':'UTF-8'}<br/>
                          {$shipping['country_name']|escape:'htmlall':'UTF-8'}<br/>
                          {$shipping['phone_mobile']|escape:'htmlall':'UTF-8'} </section>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading" style="color:#00A65A;"> <i class="fa icon-file-text fa-lg"></i> Invoice Address </div>
                      <div class="panel-body">
                        <section id="content"> {$invoice['firstname']|escape:'htmlall':'UTF-8'} {$shipping['lastname']|escape:'htmlall':'UTF-8'}<br/>
                          {if $shipping['company'] neq ''}{$invoice['company']|escape:'htmlall':'UTF-8'}<br/>
                          {/if}
                          {$invoice['address1']|escape:'htmlall':'UTF-8'}<br/>
                          {if $invoice['address2'] neq ''}{$shipping['address2']|escape:'htmlall':'UTF-8'}<br/>
                          {/if}
                          {$invoice['state_name']|escape:'htmlall':'UTF-8'}, {$invoice['city']|escape:'htmlall':'UTF-8'} {$invoice['postcode']|escape:'htmlall':'UTF-8'}<br/>
                          {$invoice['country_name']|escape:'htmlall':'UTF-8'}<br/>
                          {$invoice['phone_mobile']|escape:'htmlall':'UTF-8'} </section>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-heading" style="color:#00A65A;"> <i class="fa icon-shopping-cart fa-lg"></i> Product Details </div>
                      <div class="panel-body">
                        <section id="content" class="latest_orders">
                          <div class="table">
                            <div class="row header green">
                              <div class="cell"> Image </div>
                              <div class="cell"> Product </div>
                              <div class="cell"> Price </div>
                              <div class="cell"> Quantity </div>
                              <div class="cell"> Total </div>
                            </div>
                            {foreach from=$products item=product}
                            <div class="row">
                              <div class="cell"> <img style="width:40px;height:40px;border-radius:40px;" src="{$product.img|escape:'htmlall':'UTF-8'}" /> </div>
                              <div class="cell"><a target="_blank" href="{$product.href|escape:'htmlall':'UTF-8'}"> {$product.name|escape:'htmlall':'UTF-8'} </a></div>
                              <div class="cell"> {$product.product_price|escape:'htmlall':'UTF-8'} </div>
                              <div class="cell"> {$product.product_quantity|escape:'htmlall':'UTF-8'} </div>
                              <div class="cell"> {$product.total|escape:'htmlall':'UTF-8'} </div>
                            </div>
                            {/foreach} </div>
                        </section>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div align="center"><a {if Tools::getValue("x") eq 'r'}href="index.php?fc=module&module=vendor&controller=VendorOrdersReport{else}href="index.php?fc=module&module=vendor&controller=VendorOrders{/if}" class="btn btn-success round_st"><i class="fa icon-reply fa-lg"></i></a></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
