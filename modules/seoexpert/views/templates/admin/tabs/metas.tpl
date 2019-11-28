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

<div class="panel-group" id="accordion-metas">
	<!-- Product -->
	<div id="panel-metas-1" class="panel">
		<div class="panel-heading">
			{if $ps_version == 0}<h3>{/if}
			<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-1">{l s='Metatags rules for Product' mod='seoexpert'}</a>
			<span class="panel-heading-action">
				<a id="configuration-metas-1" class="list-toolbar-btn" data-role="meta" data-type="product" data-toggle="tooltip" data-placement="top" title="{l s='Add new rule for SEO' mod='seoexpert'}">
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add new rule for SEO' mod='seoexpert'}" data-html="true" data-placement="left">
						<i class="{if $ps_version == 0}icon-plus{else}process-icon-new{/if}"></i>
					</span>
				</a>
				<a id="generate-metas-1" class="list-toolbar-btn hide" data-type="generate" data-toggle="tooltip" data-placement="top" title="{l s='Apply all your SEO rules' mod='seoexpert'}">
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Apply all your SEO rules' mod='seoexpert'}" data-html="true" data-placement="left">
						<i class="{if $ps_version == 0}icon-magic{else}process-icon-magic{/if}"></i>
					</span>
				</a>
			</span>
			{if $ps_version == 0}</h3>{/if}
		</div>

		<p>{l s='Welcome to the optimize meta tags interface of your shop! Here you can create tags for your product pages, as well as for Facebook and Twitter. Start with the "Add new rule" button!' mod='seoexpert'}</p>
		<br />

		{counter start=0 assign="count_rule" print=false}
		{include file=$table_tpl_path node=$rule_history role='metas'}
		<div id="table-metas-1" class="panel-footer">
			<a data-role="meta" data-type="generate" href="#" class="btn btn-default pull-right hide"><i class="process-icon-magic"></i> {l s='Apply all rules' mod='seoexpert'}</a>
			<a data-role="meta" data-type="product" href="#" class="btn btn-default pull-right"><i class="process-icon-new {if $ps_version == 0}icon-plus{/if}"></i> {l s='Add new rule' mod='seoexpert'}</a>
		</div>
	</div>
{*
	<!-- Cms -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-2">{l s='Metatags rules for CMS' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-metas-2" class="list-toolbar-btn" data-role="meta" data-type="cms">
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
				<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-3">{l s='Metatags rules for Category' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-metas-3" class="list-toolbar-btn" data-role="meta" data-type="category">
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
				<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-4">{l s='Metatags rules for Category CMS' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-metas-4" class="list-toolbar-btn" data-role="meta" data-type="cmscategory">
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
				<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-5">{l s='Metatags rules for Manufacturer' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-metas-5" class="list-toolbar-btn" data-role="meta" data-type="manufacturer">
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
				<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-6">{l s='Metatags rules for Supplier' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-metas-6" class="list-toolbar-btn" data-role="meta" data-type="supplier">
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
				<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-7">{l s='Metatags rules for Static Page' mod='seoexpert'}</a>
				<span class="panel-heading-action">
					<a id="configuration-metas-7" class="list-toolbar-btn" data-role="meta" data-type="static">
						<i class="icon-plus"></i>
					</a>
				</span>
			</h3>
		</div>
		{include file=$table_tpl_path node=$rule_history}
	</div>
*}
</div>