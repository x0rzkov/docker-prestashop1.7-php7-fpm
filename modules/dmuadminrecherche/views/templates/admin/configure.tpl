{**
* NOTICE OF LICENSE
*
* This source file is subject to a commercial license from SARL DREAM ME UP
* Use, copy, modification or distribution of this source file without written
* license agreement from the SARL DREAM ME UP is strictly forbidden.
*
*   .--.
*   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
*   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
*   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
*        w w w . d r e a m - m e - u p . f r       '
*
*  @author    Dream me up <prestashop@dream-me-up.fr>
*  @copyright 2007 - 2016 Dream me up
*  @license   All Rights Reserved
*}
<div class="productTabs col-lg-2 col-md-3">
	<div class="list-group">
    	<a class="list-group-item {if $form_id==""}active{/if}" href="javascript:;" rel="Informations">Informations</a>
        {foreach from=$config_tabs item=tab_item key=key}
            <a class="list-group-item {if {$form_id|lower}=={$key|lower}}active{/if}" href="javascript:;" rel="{$key|escape:'htmlall':'UTF-8'}">{$tab_item.name|escape:'htmlall':'UTF-8'}</a>
        {/foreach}
    </div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$(".product-tab-content").not(".active").hide();
	$(".productTabs .list-group-item").each(function()
	{
		$(this).unbind('click').click(function()
		{
			// On désactive tous les onglets
			$(".productTabs .list-group-item").removeClass("active");
			
			$(this).addClass("active");
			
			$(".product-tab-content").hide();
			$("#product-tab-content-"+$(this).attr("rel")).show();
		});
	});
});
</script>
<div class="form-horizontal col-lg-10 col-md-9">
    <div id="product-tab-content-Informations" class="product-tab-content {if $form_id==""}active{/if}">
    	<div class="panel">
        	<h3 class="tab"><i class="icon-info"></i> Informations</h3>
           	<div>
            	<img src="{$path_module|escape:'htmlall':'UTF-8'}/views/img/logo-dream-me-up.png" style="float:right" />
                <h1>Module {$nom_module|escape:'htmlall':'UTF-8'}</h1>
                <p>{l s='Module version' mod='dmuadminrecherche'} : <strong>{$version_module|escape:'htmlall':'UTF-8'}</strong></p>
                <p>{l s='You can search, filter and edit your products quickly from back-office' mod='dmuadminrecherche'}</p>
                <h2>{l s='How to use this module ?' mod='dmuadminrecherche'}</h2>
                <p>{l s='To use this addon, you must use the menu to access Catalog > Quick administration' mod='dmuadminrecherche'}</p>
                <h2>{l s='Who are we ?' mod='dmuadminrecherche'}</h2>
                <p>{l s='Dream me up specializes in the creation of addons to improve the merchant experience, mainly in back office area. We develop tools to help you save time or to have a better view of your business. Discover now our addons for quick product administration, easy associations, or even real-time statistics .' mod='dmuadminrecherche'}</p>
                <ul>
                	<li>{l s='Our' mod='dmuadminrecherche'} <a href="https://www.prestashop.com/fr/agences-web-partenaires/platinum/dreammeup" target="_blank">{l s='Prestashop Partner dedicated page' mod='dmuadminrecherche'}</a></li>
            		<li>{l s='Discover all our modules on our' mod='dmuadminrecherche'} <a href="{l s='http://addons.prestashop.com/en/9_dream-me-up' mod='dmuadminrecherche'}" target="_blank">{l s='Prestashop Addons dedicated page' mod='dmuadminrecherche'}</a></li>
            	</ul>
                <h2>{l s='Follow us' mod='dmuadminrecherche'} !</h2>
                <ul>
                	<li>{l s='Follow us' mod='dmuadminrecherche'} <i class="icon-facebook-square"></i> {l s='on' mod='dmuadminrecherche'} Facebook {l s='and' mod='dmuadminrecherche'} <i class="icon-twitter-square"></i> {l s='on' mod='dmuadminrecherche'} Twitter {l s='to know all the news around our Addons' mod='dmuadminrecherche'}.</li>
                    <li>{l s='Follow us' mod='dmuadminrecherche'} <i class="icon-rss-square"></i> Blog "Modules Prestashop Dream me up" {l s='to have all details on our new Addons versions and for every new launch of Addon' mod='dmuadminrecherche'}.</li>
                </ul>
                <h2>{l s='Support and Documentation' mod='dmuadminrecherche'}</h2>
                {if $path_documentation}<p><img src="{$path_module|escape:'htmlall':'UTF-8'}/views/img/icon_pdf.png" style="vertical-align:middle" /> <a href="{$path_module|escape:'htmlall':'UTF-8'}/{$path_documentation|escape:'htmlall':'UTF-8'}" target="_blank">{l s='Click Here to open the module documentation' mod='dmuadminrecherche'}</a></p>{/if}
            	<p>{l s='Support and Documentation' mod='dmuadminrecherche'} <a href="{l s='http://addons.prestashop.com/en/9_dream-me-up' mod='dmuadminrecherche'}" target="_blank">{l s='through Prestashop Addons' mod='dmuadminrecherche'}</a>. {l s='Visit the module\'s page concerned and use the link "Contact the Developer"' mod='dmuadminrecherche'}.</p>
                <p><strong>{l s='You must mention' mod='dmuadminrecherche'} :</strong></p>
                <ul>
                	<li>{l s='A detailed description of the problem' mod='dmuadminrecherche'}</li>
                    <li>{l s='Your Prestashop Version' mod='dmuadminrecherche'} : <strong>{$version_prestashop|escape:'htmlall':'UTF-8'}</strong></li>
                    <li>{l s='Your Module Version' mod='dmuadminrecherche'} : <strong>{$version_module|escape:'htmlall':'UTF-8'}</strong></li>
                </ul>
            </div>
        </div>
    </div>
    {foreach from=$config_tabs item=tab_item key=key}
    <div id="product-tab-content-{$key|escape:'htmlall':'UTF-8'}" class="product-tab-content {if {$form_id|lower}=={$key|lower}}active{/if}">
    {if !$tab_item.is_helper}
    	<div class="panel">
        	<h3 class="tab">{$tab_item.name|escape:'htmlall':'UTF-8'}</h3>
            <div>
     {/if}

            	{*HTML CONTENT*}
                    {$content_html.$key|escape:'quotes':'UTF-8'|replace:"\'":"'"}
                {*HTML CONTENT*}

     {if !$tab_item.is_helper}
            </div>
        </div>
     {/if}
    </div>
    {/foreach}
</div>
<div class="clearfix"></div>