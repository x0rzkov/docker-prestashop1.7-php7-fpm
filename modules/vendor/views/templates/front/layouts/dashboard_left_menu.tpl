{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}
<div id="left_nav_menu" style="float:left; width:275px;margin-left:15px;">
  <div class="nav-side-menu">
    <div class="brand" align="center"><i class="fa icon-home fa-lg"></i> Welcome to {$welcome_name|escape:'htmlall':'UTF-8'}</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
    <div class="menu-list">
      <ul id="menu-content" class="menu-content collapse out">
        <li {if Tools::getValue("controller") eq 'VendorDashboard'}class='active'{/if}><a href="index.php?fc=module&module=vendor&controller=VendorDashboard"> <i class="fa fa-dashboard fa-lg"></i> Dashboard </a> </li>
        <li {if Tools::getValue("controller") eq 'VendorListProduct' or Tools::getValue("controller") eq 'VendorAddProduct'}class='active'{/if} data-toggle="collapse" data-target="#products">
        <a><i class="fa icon-tags fa-lg"></i> Product <span class="arrow"></span></a>
        </li>
        <ul class="sub-menu {if Tools::getValue("controller") eq 'VendorListProduct' or Tools::getValue("controller") eq 'VendorAddProduct'}in{else}collapse{/if}" id="products">
        <li {if Tools::getValue("controller") eq 'VendorListProduct'}class='active'{/if}><a href="index.php?fc=module&module=vendor&controller=VendorListProduct">Product List</a></li>
        <li {if Tools::getValue("controller") eq 'VendorAddProduct'}class='active'{/if}><a href="index.php?fc=module&module=vendor&controller=VendorAddProduct">Add Product</a></li>
      </ul>
      <li {if Tools::getValue("controller") eq 'VendorOrders' or Tools::getValue("controller") eq 'VendorOrderDetails'}class='active'{/if}>
      <a href="index.php?fc=module&module=vendor&controller=VendorOrders"><i class="fa icon-shopping-cart fa-lg"></i> Order </a>
      </li>
      <li {if Tools::getValue("controller") eq 'VendorWithdraw'}class='active'{/if}> <a href="index.php?fc=module&module=vendor&controller=VendorWithdraw"> <i class="fa fa-money fa-lg"></i> Withdraw </a> </li>
      <li {if Tools::getValue("controller") eq 'VendorOrdersReport'}class='active'{/if} data-toggle="collapse" data-target="#reports"> <a><i class="fa icon-bar-chart fa-lg"></i> Report <span class="arrow"></span></a> </li>
      <ul class="sub-menu {if Tools::getValue("controller") eq 'VendorOrdersReport' or Tools::getValue("controller") eq 'VendorProductViewReport' or Tools::getValue("controller") eq 'VendorProductSellReport' or Tools::getValue("controller") eq 'VendorWithdrawReport' or Tools::getValue("controller") eq 'VendorSoldStatusReport'}in{else}collapse{/if}" id="reports">
      <li {if Tools::getValue("controller") eq 'VendorOrdersReport'}class='active'{/if}><a href="index.php?fc=module&module=vendor&controller=VendorOrdersReport">Orders</a></li>
      <li {if Tools::getValue("controller") eq 'VendorSoldStatusReport'}class='active'{/if}><a href="index.php?fc=module&module=vendor&controller=VendorSoldStatusReport">Product Sell Report</a></li>
      <li {if Tools::getValue("controller") eq 'VendorWithdrawReport'}class='active'{/if}><a href="index.php?fc=module&module=vendor&controller=VendorWithdrawReport">Withdraw Report</a></li>
      </ul>
      <li {if Tools::getValue("controller") eq 'VendorRestaurantSetup'}class='active'{/if}> <a href="index.php?fc=module&module=vendor&controller=VendorRestaurantSetup"> <i class="fa icon-wrench fa-lg"></i> Settings </a> </li>
      <li {if Tools::getValue("controller") eq 'VendorProfile'}class='active'{/if}> <a href="index.php?fc=module&module=vendor&controller=VendorProfile"> <i class="fa icon-user fa-lg"></i> My Profile </a> </li>
      <li> <a target="_top" href="index.php?fc=module&module=vendor&controller=VendorPanel&method=logout"> <i class="fa icon-power-off fa-lg"></i> Logout </a> </li>
      </ul>
    </div>
  </div>
  <!--<div class="left_block">
    <div class="nav-side-menu">
      <div class="brand"><i class="fa icon-heart fa-lg"></i> Store Rating</div>
    </div>
    <div class="left_block_text" align="center">{$store_rating|escape:'htmlall':'UTF-8'} ({$total_rating|escape:'htmlall':'UTF-8'})</div>
    <div align="center" style="margin-top:10px;"><img title="{$store_rating|escape:'htmlall':'UTF-8'}" src="{$rating_image|escape:'htmlall':'UTF-8'}" /></div>
    <div align="center" style="margin-top:20px;"><a href="index.php?fc=module&module=vendor&controller=VendorRatingList" class="link_color_style">view rating list</a></div>
  </div>-->
  <div class="left_block">
    <div class="nav-side-menu">
      <div class="brand"><i class="fa fa-warning fa-lg"></i> Quantity Alert</div>
    </div>
    <div class="left_block_text" align="center">{$xqty|escape:'htmlall':'UTF-8'}</div>
    <div align="center" style="margin-top:20px;"><a href="index.php?fc=module&module=vendor&controller=VendorQuantityAlertList" class="link_color_style">view alert list</a></div>
  </div>
  <div style="margin-bottom:10px;">&nbsp;</div>
</div>
