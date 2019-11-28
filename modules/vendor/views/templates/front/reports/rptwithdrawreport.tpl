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
      <div align="center" id="top_text">Withdraw Report</div>
      <br/>
      {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
      <div class="vendorMiddleContentHolder">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Find Your Withdraw</h3>
              </div>
              <div style="border:solid 1px #ccc;padding:20px;">
                <form method="post">
                  <div class="col-md-4">
                    <label for="ddlWithdrawType">Withdraw Status</label>
                    <select name="ddlWithdrawType" id="ddlWithdrawType" class="form-control">
                      <option value="0">Both</option>
					  <option value="1">Pending</option>
                      <option value="2">Completed</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="ddlWithdrawMonth">Withdraw Month</label>
                    <select name="ddlWithdrawMonth" id="ddlWithdrawMonth" class="form-control">
                      <option value="">--Select Month--</option>
					  <option value="01">January</option>
                      <option value="02">February</option>
                      <option value="03">March</option>
                      <option value="04">April</option>
                      <option value="05">May</option>
                      <option value="06">June</option>
                      <option value="07">July</option>
                      <option value="08">August</option>
                      <option value="09">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="ddlWithdrawYear">Withdraw Year</label>
                    <select name="ddlWithdrawYear" id="ddlWithdrawYear" class="form-control">
						<option value="">--Select Year--</option>
						{foreach from=$year_arr item=x}
                      	<option value="{$x|escape:'htmlall':'UTF-8'}">{$x|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
                    </select>
                  </div>
                  <div class="col-md-2" style="padding-top:15px;">
                    <input type="submit" value="Search" name="btnSubmit" class="btn btn-primary" />
                  </div>
                  <input type="hidden" name="submitCreate" value="submitFilter" />
                </form>
                <div style="clear:both;"></div>
              </div>
              <br/>
              <br/>
              <div align="center">
                <h3 class="box-title">Withdraw List</h3>
              </div>
              <div class="box-body">
                <table id="order_list_vendor" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <td><b>Withdraw Id.</b></td>
                      <td><b>Date.</b></td>
                      <td><b>Amount</b></td>
                      <td><b>Status</b></td>
                    </tr>
                  </thead>
                  <tbody>
                  {foreach from=$lists item=list}
                  <tr>
                    <td>{$list.wid|escape:'htmlall':'UTF-8'}</td>
                    <td>{$list.added_date|escape:'htmlall':'UTF-8'}</td>
                    <td>{$list.amount|escape:'htmlall':'UTF-8'}</td>
                    <td>{$list.status|escape:'htmlall':'UTF-8'}</td>
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
} );
</script>
