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
      <div align="center" id="top_text">Withdraw Details</div>
      <br/>
      {include file="modules/vendor/views/templates/front/layouts/dashboard_left_menu.tpl"}
      <div class="vendorMiddleContentHolder">
        <div class="row">
          <div class="col-md-12">
		  	<div class="bootstrap" style="display:{$msgx|escape:'htmlall':'UTF-8'};">
              <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">x</button>
                Added Withdraw Successfully Waiting for Admin Approved. </div>
            </div>
			<div class="bootstrap" style="display:{$warrning|escape:'htmlall':'UTF-8'};">
              <div class="alert alert-danger">
                <button data-dismiss="alert" class="close" type="button">x</button>
                Withdraw amount must be less than avaialable balance. </div>
            </div>
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Balance inquery and Withdraw</h3>
				<div class="exist_withdraw">** Store owner can do only withdraw at a time.</div>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading" style="color:#00A65A;"> <i class="fa fa-money fa-lg"></i> Available Balance </div>
                      <div class="panel-body">
                        <section id="content">
                          <div class="available_balance">Available Balance for Withdraw: {$balance|escape:'htmlall':'UTF-8'}</div>
                        </section>
                      </div>
                    </div>
                  </div>
				  {if $withdraw eq false}
                  <div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading" style="color:#00A65A;"> <i class="fa icon-upload-alt fa-lg"></i> Make a Withdraw </div>
                      <div class="panel-body">
                        <section id="content">
                          <form method="post">
                            <div class="form-group">
                              <label>Make a Withdraw:</label>
                              <input type="text" name="txtWithdrawAmount" placeholder="{$dvalue|escape:'htmlall':'UTF-8'}" class="form-control">
                            </div>
                            <div class="form-group">
                              <input type="submit" name="btnSubmit" value="Submit" class="btn btn-success">
                            </div>
                          <input type="hidden" name="Submitwithdraw" value="ok" />
						  </form>
                        </section>
                      </div>
                    </div>
                  </div>
				  {/if}
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="panel panel-default">
                      <div class="panel-heading" style="color:#00A65A;"> <i class="fa fa-list fa-lg"></i> Last 10 Withdraw List </div>
                      <div class="panel-body">
                        <section id="content" class="latest_orders">
                          <div class="table">
                            <div class="row header green">
                              <div class="cell"> Withdraw Id. </div>
							  <div class="cell"> Date </div>
                              <div class="cell"> Amount </div>
                              <div class="cell"> Status </div>
                            </div>
							{foreach from=$lists item=list}
							<div class="row">
                              <div class="cell">{$list.wid|escape:'htmlall':'UTF-8'}</div>
							  <div class="cell">{$list.added_date|escape:'htmlall':'UTF-8'}</div>
                              <div class="cell">{$list.amount|escape:'htmlall':'UTF-8'}</div>
                              <div class="cell">{$list.status|escape:'htmlall':'UTF-8'}</div>
                            </div>
							{/foreach}
                          </div>
                        </section>
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
  </div>
</div>
