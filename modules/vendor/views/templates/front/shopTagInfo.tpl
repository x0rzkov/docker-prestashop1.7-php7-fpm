{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}
<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />
{if $store_name neq ''}
<div class="ventor_info_tag_box"> <a href="{$ps_base_url|escape:'htmlall':'UTF-8'}index.php?fc=module&module=vendor&controller=VendorRestaurantDetails&rid={$rid|escape:'htmlall':'UTF-8'}"> <span>Store: {$store_name|escape:'htmlall':'UTF-8'}</span> </a> </div>
{/if} 