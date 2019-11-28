{*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

{if $rewriting_allow|intval == 0}<div class="alert alert-warning">
	<h4>{l s='There is 1 warning' mod='seoexpert'}</h4>
	<ul class="list-unstyled">
		<li><a href="{$admin_seo|escape:'htmlall':'UTF-8'}">{l s='For see the effects of your SEO rules you need to enable Friendly URL' mod='seoexpert'}</a></li>
	</ul>
</div>{/if}

<div class="panel-group" id="accordion-urls">
	<!-- Product -->
	<div id="panel-urls-1" class="panel">
		<div class="panel-heading">
			{if $ps_version == 0}<h3>{/if}
			<a data-toggle="collapse" data-parent="#accordion-urls" href="#urls-1">{l s='urls rules for Product' mod='seoexpert'}</a>
			<span class="panel-heading-action">
				<a id="configuration-urls-1" class="list-toolbar-btn" data-role="url" data-type="product" href="#">
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add new rule for SEO' mod='seoexpert'}" data-html="true" data-placement="left">
						<i class="{if $ps_version == 0}icon-plus{else}process-icon-new{/if}"></i>
					</span>
				</a>
				<a id="generate-urls-1" class="list-toolbar-btn hide" data-type="generate" data-toggle="tooltip" href="#">
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Apply all your SEO rules' mod='seoexpert'}" data-html="true" data-placement="left">
						<i class="{if $ps_version == 0}icon-magic{else}process-icon-magic{/if}"></i>
					</span>
				</a>
			</span>
			{if $ps_version == 0}</h3>{/if}
		</div>
		<p>{l s='Welcome to the optimize URLs interface of your shop! Here you can create friendly URLs for your product pages. It will help search engines to better reference your Urls on Google, Yahoo, etc. Start with the "Add new rule" button!' mod='seoexpert'}</p>
		<br />
		{counter start=0 assign="count_rule" print=false}
		{include file=$table_tpl_path node=$rule_history role='urls'}
		<div id="table-urls-1" class="panel-footer">
			<a data-role="url" data-type="generate" href="#" class="btn btn-default pull-right hide"><i class="process-icon-magic"></i> {l s='Apply all rules' mod='seoexpert'}</a>
			<a data-role="url" data-type="product" href="#" class="btn btn-default pull-right"><i class="process-icon-new {if $ps_version == 0}icon-plus{/if}"></i> {l s='Add new rule' mod='seoexpert'}</a>
		</div>
	</div>
{*
	<!-- Cms -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<a data-toggle="collapse" data-parent="#accordion-urls" href="#urls-2">{l s='urltags rules for CMS' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-urls-2" class="list-toolbar-btn" data-role="url" data-type="cms">
						<i class="icon-plus"></i>
					</a>
				</span>
			</h3>
		</div>
		{include file=$table_tpl_path node=$rule_history}
	</div>
	<!-- Category -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<a data-toggle="collapse" data-parent="#accordion-urls" href="#urls-3">{l s='urltags rules for Category' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-urls-3" class="list-toolbar-btn" data-role="url" data-type="category">
						<i class="icon-plus"></i>
					</a>
				</span>
			</h3>
		</div>
		{include file=$table_tpl_path node=$rule_history}
	</div>
	<!-- Category CMS -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<a data-toggle="collapse" data-parent="#accordion-urls" href="#urls-4">{l s='urltags rules for Category CMS' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-urls-4" class="list-toolbar-btn" data-role="url" data-type="cmscategory">
						<i class="icon-plus"></i>
					</a>
				</span>
			</h3>
		</div>
		{include file=$table_tpl_path node=$rule_history}
	</div>
	<!-- Manufacturers -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<a data-toggle="collapse" data-parent="#accordion-urls" href="#urls-5">{l s='urltags rules for Manufacturer' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-urls-5" class="list-toolbar-btn" data-role="url" data-type="manufacturer">
						<i class="icon-plus"></i>
					</a>
				</span>
			</h3>
		</div>
		{include file=$table_tpl_path node=$rule_history}
	</div>
	<!-- Suppliers -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<a data-toggle="collapse" data-parent="#accordion-urls" href="#urls-6">{l s='urltags rules for Supplier' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-urls-6" class="list-toolbar-btn" data-role="url" data-type="supplier">
						<i class="icon-plus"></i>
					</a>
				</span>
			</h3>
		</div>
		{include file=$table_tpl_path node=$rule_history}
	</div>
	<!-- Static -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<a data-toggle="collapse" data-parent="#accordion-urls" href="#urls-7">{l s='urltags rules for Static Page' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-urls-7" class="list-toolbar-btn" data-role="url" data-type="static">
						<i class="icon-plus"></i>
					</a>
				</span>
			</h3>
		</div>
		{include file=$table_tpl_path node=$rule_history}
	</div>
*}
</div>