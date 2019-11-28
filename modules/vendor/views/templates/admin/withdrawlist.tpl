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
    {$xMsg|escape:'htmlall':'UTF-8'}</div>
</div>
{/if}
<div class="panel">
  <div class="panel-heading"><i class="icon-AdminParentOrders"></i> &nbsp;All Requested Payment List</div>
  <div style="color:red;font-weight:bold;">**Allow Maximum 200 Payment at a time using paypal mass payment</div><br/>
  <form method="post" id="frmPaymentSubmit">
	  <div class="table-responsive-row clearfix">
		  <table class="table">
			<thead>
				<tr class="nodrag nodrop">
				  <th><input type="checkbox" class="activeall" /></th>
				  <th>ID#</th>
				  <th>Store Name</th>
				  <th>Store Email</th>
				  <th>Paypal Email</th>
				  <th>Amount</th>
				  <th>Status</th>
				  <th>Requested Date</th>
				  <th>Available Balance</th>
				  <th>Payment</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$withdraws item=foo}
				<tr>
				  <td><input type="checkbox" class="send_payment_email" value="{$foo.paypal_email|escape:'htmlall':'UTF-8'}~{$foo.send_money|escape:'htmlall':'UTF-8'}~{$foo.wid|escape:'htmlall':'UTF-8'}" name="chkVendorEmail[]" /></td>
				  <td>{$foo.wid|escape:'htmlall':'UTF-8'}</td>
				  <td>{$foo.store_name|escape:'htmlall':'UTF-8'}</td>
				  <td><a href="mailto:{$foo.email|escape:'htmlall':'UTF-8'}">{$foo.email|escape:'htmlall':'UTF-8'}</a></td>
				  <td>{$foo.paypal_email|escape:'htmlall':'UTF-8'}</td>
				  <td><span data-toggle="tooltip" data-original-title="Total Balance With Withdraw Amount" class="btn-info btn-xs label-tooltip"><b>{$foo.amount|escape:'htmlall':'UTF-8'}</b></span></td>
				  <td><span class="btn-danger btn-xs label-tooltip"><b>{$foo.status|escape:'htmlall':'UTF-8'}</b></span></td>
				  <td>{$foo.added_date|escape:'htmlall':'UTF-8'}</td>
				  <td><span data-toggle="tooltip" data-original-title="Total Balance With Withdraw Amount" class="btn-danger btn-xs label-tooltip"><b>{$foo.tamount|escape:'htmlall':'UTF-8'}</b></span></td>
				  <td><a data-toggle="tooltip" data-original-title="Pay By Paypal" class="btn btn-success btn-xs label-tooltip"><i class="icon-paypal"></i></a></td>
				</tr>
				{/foreach}
			</tbody>
		  </table>
	  </div>
  </form>
  <br/>
  <div align="right"> 
  	<a href="javascript:;" onclick="sendPayment();" data-toggle="tooltip" data-original-title="Select Restaurant for Payment" class="btn btn-success btn-xs label-tooltip" style="font-size:12px;"><i class="icon-paypal"></i> &nbsp;Send Payment</a>
  </div>
</div>
<style type="text/css">
#my_quote_cart td{
	border:solid 1px #000000;
	text-align:center;
}
#my_quote_cart th{
	border:solid 1px #000000;
	font-weight:bold;
	text-align:center;
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
<script type="text/javascript">
	function sendPayment(){
		if($(".send_payment_email").is(':checked')){
			if(confirm("Are you sure you want to send payment")){
				$("#frmPaymentSubmit").submit();
			}
		}
		else{
			alert('Select payment request !!!');
		}
	}
	$(document).ready(function(){
      $(".activeall").change(function(){
      	$(".send_payment_email").prop('checked', $(this).prop("checked"));
      });
	});
</script>