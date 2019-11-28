{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}

<div style="width:100%;height:200px;">
  <div style="background: rgba(36, 41, 49, 1); padding-top:33px;-webkit-box-shadow: 0 0 5px rgba(0,0,0,.4), inset 1px 2px rgba(255,255,255,.3);-moz-box-shadow: 0 0 5px rgba(0,0,0,.4), inset 1px 2px rgba(255,255,255,.3);box-shadow: 0 0 5px rgba(0,0,0,.4), inset 0px 2px rgba(255,255,255,.3);border: solid 1px #242931;margin-bottom:110px;" class="page-section">
    <div class="col-sm-4 clearfix" id="search_block_top">
      <form method="get" id="searchbox">
        <input type="hidden" value="search" name="controller">
        <input type="hidden" value="position" name="orderby">
        <input type="hidden" value="desc" name="orderway">
        <input type="text" value="" placeholder="Search" name="search_query" id="search_query_top" class="search_query form-control ac_input" autocomplete="off">
        <button class="btn btn-default button-search" name="submit_search" type="submit"> <span>Search</span> </button>
      </form>
    </div>
    <div style="clear:both;"></div>
  </div>
</div>
<br/>
<script type="text/javascript">
function validateMeFilter(){
	if($("#txtRestaurantSearch").val() == ''){
		alert('Please enter your zipcode or state !!');
		$("#txtRestaurantSearch").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
