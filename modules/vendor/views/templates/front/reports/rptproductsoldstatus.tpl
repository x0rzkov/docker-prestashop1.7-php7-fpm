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
        <div align="center" id="top_text">Product Sell Report</div>
        <br/>
        {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
        <div class="vendorMiddleContentHolder">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Find Your Sell Status</h3>
                </div>
                <div style="border:solid 1px #ccc;padding:20px;">
                  <form method="post">
                    <div class="col-md-3">
                      <label for="txtSellDate">Sell Date (dd/mm/yyyy)</label>
                      <input type="text" style="border:solid 1px #D6D4D4;" name="txtSellDate" value="{$date_filter|escape:'htmlall':'UTF-8'}" id="txtSellDate" class="datepicker" />
                    </div>
					<div class="col-md-3">
                      <label for="ddlSellMonth">Sell Month</label>
                      <select name="ddlSellMonth" onchange="makeDateDefault();" id="ddlSellMonth" style="width:175px;height:25px;">
                        <option value="">--Select Month--</option>
                        <option value="01" {if $month_filter eq '01'}selected{/if}>January</option>
                        <option value="02" {if $month_filter eq '02'}selected{/if}>February</option>
                        <option value="03" {if $month_filter eq '03'}selected{/if}>March</option>
                        <option value="04" {if $month_filter eq '04'}selected{/if}>April</option>
                        <option value="05" {if $month_filter eq '05'}selected{/if}>May</option>
                        <option value="06" {if $month_filter eq '06'}selected{/if}>June</option>
                        <option value="07" {if $month_filter eq '07'}selected{/if}>July</option>
                        <option value="08" {if $month_filter eq '08'}selected{/if}>August</option>
                        <option value="09" {if $month_filter eq '09'}selected{/if}>September</option>
                        <option value="10" {if $month_filter eq '10'}selected{/if}>October</option>
                        <option value="11" {if $month_filter eq '11'}selected{/if}>November</option>
                        <option value="12" {if $month_filter eq '12'}selected{/if}>December</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="ddlSellYear">Sell Year</label>
                      <select name="ddlSellYear" onchange="makeDateDefault();" id="ddlSellYear" style="width:175px;height:25px;">
                        <option value="">--Select Year--</option>
						{foreach from=$year_arr item=x}
                        <option value="{$x|escape:'htmlall':'UTF-8'}" {if $year_filter eq {$x|escape:'htmlall':'UTF-8'} } selected {/if}>{$x|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
                      </select>
                    </div>
                    <div class="col-md-3" style="padding-top:25px;">
                      <input type="submit" value="Search" name="btnSubmit" id="btnSubmit" class="btn btn-primary" />
                    </div>
                    <input type="hidden" name="submitCreate" value="submitFilter" />
                  </form>
                <div style="clear:both;"></div>
              </div>
              <br/>
              <br/>
              <div align="center">
                <h3 class="box-title">Sell List</h3>
              </div>
              <div class="box-body">
                <table id="order_list_vendor" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <td><b>Order Id.</b></td>
                      <td><b>Product Name.</b></td>
                      <td><b>Product Price</b></td>
                      <td><b>Sold Date</b></td>
                    </tr>
                  </thead>
                  <tbody>
                  {foreach from=$lists item=list}
                  <tr>
                    <td>{$list.order_id|escape:'htmlall':'UTF-8'}</td>
                    <td>{$list.product_name|escape:'htmlall':'UTF-8'}</td>
                    <td>{$list.product_price|escape:'htmlall':'UTF-8'}</td>
                    <td>{$list.date_add|escape:'htmlall':'UTF-8'}</td>
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
function makeDateDefault(){
	$("#txtSellDate").val("");
}
function makeDropdownlistDefault(){
	$("#ddlSellMonth").val("");
	$("#ddlSellYear").val("");
}
jQuery(document).ready(function() {
    jQuery('#order_list_vendor').DataTable();
	jQuery('.datepicker').datepicker({
		dateFormat: 'dd/mm/yy',
		onSelect: function(dateText, inst) { 
			makeDropdownlistDefault();
		}
	});
} );
</script>
