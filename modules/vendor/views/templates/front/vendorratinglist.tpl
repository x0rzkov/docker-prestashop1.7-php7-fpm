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
<script type="text/javascript" src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/js/vendor.js"></script>
<h1 class="page-heading">Welcome To Vendor Dashboard</h1>
<div class="row">
<div class="col-xs-12 col-sm-12 rest_common_panel">
  <div style="width:100%;margin:0 auto;padding:0;">
    <div id="main_content_area">
      <div align="center" id="top_text">Vendor Dashboard</div>
      <br/>
      {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
      <div class="middle_container">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Rating List</h3>
              </div>
              <div class="box-body">
                <table id="product_list_vendor" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <td><b>Product Name</b></td>
                      <td><b>Customer Name</b></td>
                      <td><b>Rating</b></td>
					  <td><b>Comments</b></td>
					  <td><b>Added Date</b></td>
                    </tr>
                  </thead>
                  <tbody>
                  {foreach from=$rating_array item=arr}
                  <tr>
                    <td>{$arr.product_name|escape:'htmlall':'UTF-8'}</td>
                    <td>{$arr.customer_name|escape:'htmlall':'UTF-8'}</td>
                    <td>{$arr.grade|escape:'htmlall':'UTF-8'}</td>
                    <td>{$arr.content|escape:'htmlall':'UTF-8'}</td>
					<td>{$arr.date_add|escape:'htmlall':'UTF-8'}</td>
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
<script type="text/javascript"> jQuery(document).ready(function() { jQuery('#product_list_vendor').DataTable(); } ); </script>
