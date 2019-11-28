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

{literal}
<script>
	ps_version = '{/literal}{$ps_version|intval}{literal}';
	multishop = '{/literal}{$multishop|intval}{literal}';
	debug_mode = '{/literal}{$debug_mode|intval}{literal}';
	current_id_tab = '{/literal}{$current_id_tab|intval}{literal}';
	admin_module_ajax_url = '{/literal}{$controller_url|escape:'quotes':'UTF-8'}{literal}';
	admin_module_controller = "{/literal}{$controller_name|escape:'htmlall':'UTF-8'}{literal}";
{/literal}
	next_message = '{l s=' Next' mod='seoexpert' js=1}';
	prev_message = '{l s=' Back' mod='seoexpert' js=1}';
	skip_message = '{l s=' Skip' mod='seoexpert' js=1}';
	save_message = '{l s=' Save' mod='seoexpert' js=1}';
	close_message = '{l s='Close' mod='seoexpert' js=1}';
	delete_message = '{l s='Delete' mod='seoexpert' js=1}';
	delete_rule_message = '{l s='Are you sure you want to delete this rule?' mod='seoexpert' js=1}';

	records_msg = '{l s='Show' mod='seoexpert' js=1}';
	zero_records_msg = '{l s='Nothing found' mod='seoexpert' js=1}';
</script>

{if $ps_version == 0}
<div class="bootstrap">
	<!-- Beautiful header -->
	{include file="./header.tpl"}
{/if}
	<!-- Module content -->
	<div id="modulecontent" class="clearfix">

{if $module_enabled|intval == 0}<div class="alert alert-warning">
			<h4>{l s='There is 1 warning' mod='seoexpert'}</h4>
			<ul class="list-unstyled">
				<li><a href="{$admin_seo|escape:'htmlall':'UTF-8'}">{l s='The module is not enabled, no rules will be applied' mod='seoexpert'}</a></li>
			</ul>
		</div>{/if}

		<!-- Nav tabs -->
		<div class="col-lg-2">
			<div class="list-group">
				<a href="#documentation" class="list-group-item active" data-toggle="tab"><i class="icon-book"></i> {l s='Documentation' mod='seoexpert'}</a>
				{*<a href="#conf" class="list-group-item" data-toggle="tab"><i class="icon-cogs"></i> {l s='Configuration' mod='seoexpert'}</a>*}
				<a href="#urls" class="list-group-item" data-toggle="tab"><i class="icon-link" data-target="table-urls-1" ></i> {l s='Optimize Urls' mod='seoexpert'}</a>
				<a href="#metas" class="list-group-item" data-toggle="tab"><i class="icon-indent" data-target="table-metas-1"></i> {l s='Optimize Metatags' mod='seoexpert'}</a>
				{if !empty($apifaq)}
				<a href="#faq" class="list-group-item" data-toggle="tab"><i class="icon-info-sign"></i> {l s='FAQ' mod='seoexpert'}</a>
				{/if}
				<a href="#contacts" class="contacts list-group-item" data-toggle="tab"><i class="icon-envelope"></i> {l s='Contact' mod='seoexpert'}</a>
			</div>
			<div class="list-group">
				<a class="list-group-item"><i class="icon-info"></i> {l s='Version' mod='seoexpert'}
				{$module_version|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;|&nbsp;&nbsp;
              <i class="icon-info"></i> {l s='PrestaShop' mod='seoexpert'} {$version|escape:'htmlall':'UTF-8'}
			</a>
			</div>
			{if $debug_mode|intval === 1}
			<div class="list-group">
				<a id="drop" href="#drop" class="list-group-item pointer" data-toggle="tab"><i class="icon-undo"></i> {l s='Reset' mod='seoexpert'}</a>
			</div>
			{/if}
		</div>
		<!-- Tab panes -->
		<div class="tab-content col-lg-10">
			<div class="tab-pane active panel" id="documentation">
				{include file="./tabs/documentation.tpl"}
			</div>
			<div class="tab-pane" id="urls">
				{include file="./tabs/urls.tpl"}
			</div>
			<div class="tab-pane" id="metas">
				{include file="./tabs/metas.tpl"}
			</div>
			{if !empty($apifaq)}
			<div class="tab-pane" id="faq">
				{include file="./tabs/faq.tpl"}
			</div>
			{/if}
			{include file="./tabs/contact.tpl"}
		</div>
	</div>
{if $ps_version == 0}
	<!-- Manage translations -->
	{include file="./translations.tpl"}
</div>
{/if}
