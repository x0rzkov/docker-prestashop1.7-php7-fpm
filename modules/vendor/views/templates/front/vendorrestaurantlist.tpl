{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}
{extends file=$layout}
{block name='content'}
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}modules/vendor/views/css/style.css" />
<div>
<h1 class="page-heading">{$filter_top_text|escape:'htmlall':'UTF-8'}</h1>
<div class="row">
  <div class="col-xs-12 col-sm-12">
    {if !$stores|@count gt 0}<div class="no_restaurant_found">
	<div style="display:block;" class="bootstrap">
	  <div class="alert alert-danger">
	   No Vendor Found For {$query_text|escape:'htmlall':'UTF-8'} </div>
	</div>
	</div>{/if}
	<div id="layout1"> {foreach from=$stores item=store}
      <div class="new_post b-by-b-item" style="padding: 5px; margin-top: 10px; margin-bottom: 10px; box-sizing: border-box;float:left;margin:10px" data-column="0">
        <div class="post_item">
          <h3 style="display:none"> <a href="index.php?fc=module&module=vendor&controller=VendorRestaurantDetails&rid={$store.rid|escape:'htmlall':'UTF-8'}">{$store.store_name|escape:'htmlall':'UTF-8'}</a> </h3>
          <div class="post-body"> <span id="p5337581159084299921">
            <div class="unimage">
              <div class="post-image ImageWrapper"><a href="#"><img src="{$store.store_grid_image|escape:'htmlall':'UTF-8'}" class="img_thumb"></a>
                <div class="ImageOverlayN"></div>
                <div class="StyleH"><span class="WhiteRounded"><a href="#" id="{$store.rid|escape:'htmlall':'UTF-8'}" class="helpdesk" rel="lightbox"><i class="fa fa-book"></i></a></span><span class="WhiteRounded"><a data-animsition-in-class="fade-in" class="animsition-link" href="index.php?fc=module&module=vendor&controller=VendorRestaurantDetails&rid={$store.rid|escape:'htmlall':'UTF-8'}"><i class="fa fa-link"></i></a></span></div>
              </div>
            </div>
            <h3 class="post-title"><a href="index.php?fc=module&module=vendor&controller=VendorRestaurantDetails&rid={$store.rid|escape:'htmlall':'UTF-8'}">{$store.store_name|escape:'htmlall':'UTF-8'}</a></h3>
            <div class="tile__description">{$store.grid_content nofilter}</div>
			<span class="post-share">
            <ul>
              <li><a href="{$store.facebook_link|escape:'htmlall':'UTF-8'}" target="_blank" class="hi-icon hi-icon-chat"><i class="fa fa-facebook"></i></a></li>
              <li><a href="{$store.twitter_link|escape:'htmlall':'UTF-8'}" target="_blank" class="hi-icon hi-icon-chat"><i class="fa fa-twitter"></i></a></li>
              <li><a href="{$store.google_plus_link|escape:'htmlall':'UTF-8'}" target="_blank" class="hi-icon hi-icon-chat"><i class="fa fa-google-plus"></i></a></li>
            </ul>
            </span></span> </div>
        </div>
      </div>
	  <div style="display:none;" id="x_{$store.rid|escape:'htmlall':'UTF-8'}">{$store.schedule nofilter}</div>
      {/foreach} </div>
    <div class="clear"></div>
  </div>
</div>
{/block} 
