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

{assign var='role' value=$role|escape:'htmlall':'UTF-8'}
{assign var='type' value=array_keys($rule_history)}

{counter}
<div id="{$role|escape:'htmlall':'UTF-8'}-{$count_rule|intval}" class="panel-collapse collapse{if $count_rule|intval == 1} in{/if}">
	<div class="table-responsive clearfix">
		<table id="table-{$role|escape:'htmlall':'UTF-8'}-{$count_rule|intval}" cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover dataTableHidden">
			<thead>
			<tr>
				<th class="text-center">{l s='Name' mod='seoexpert'}</th>
				<th class="text-center">{l s='Lang' mod='seoexpert'}</th>
				{if $multishop|intval === 1}<th class="text-center">{l s='Shop' mod='seoexpert'}</th>{/if}
				<th class="text-center">{l s='Category' mod='seoexpert'}</th>
				<th class="text-center">{l s='Status' mod='seoexpert'}</th>
				<th class="text-center">{l s='Last apply' mod='seoexpert'}</th>
				<th class="text-center">{l s='Actions' mod='seoexpert'}</th>
			</tr>
			</thead>
			<tbody>
			{if isset($node) & !empty($node)}
				{foreach $node[$type[$count_rule-1]] as $prod}
				<tr id="cat_{$prod.id_rule|intval}" data-active="{$prod.active|intval}">
					<td class="fixed-width-sm text-center">{$prod.id_rule|intval}</td>
					<td class="fixed-width-sm text-center">{$prod.lang|escape:'htmlall':'UTF-8'}</td>
					{if $multishop|intval === 1}<td class="fixed-width-sm text-center">{$prod.shop|escape:'htmlall':'UTF-8'}</td>{/if}
					{*
						One Rule to rule them all, One Rule to find them,
						One Rule to bring them all and in the darkness bind them
					*}
					<td class="fixed-width-sm text-center">
						{if $prod.nb_obj|escape:'htmlall':'UTF-8' == 'All'}
							{l s='All categories' mod='seoexpert'}
						{else}
							{$prod.nb_obj|escape:'htmlall':'UTF-8'}
						{/if}
					</td>
					<td class="pointer fixed-width-sm text-center">
						{* See ajaxProcessReloadData *}
						<a class="list-action-enable action-{if $prod.active|intval == 1}enabled{else}disabled{/if}" title="{if $prod.active|intval == 1}{l s='Enabled' mod='seoexpert'}{else}{l s='Disabled' mod='seoexpert'}{/if}">
							<i class="icon-check {if $prod.active|intval != 1}hidden{/if}"></i>
							<i class="icon-remove {if $prod.active|intval == 1}hidden{/if}" ></i>
						</a>
					</td>
					<td class="text-center">
						{if $prod.date_upd|escape:'htmlall':'UTF-8'}
							{dateFormat date=$prod.date_upd}
						{else}
							N/A
						{/if}
					</td>
					<td class="text-right">
						{* See ajaxProcessReloadData *}
						{include file=$actions_tpl_path prod=$prod}
					</td>
				</tr>
				{/foreach}
			{/if}
			</tbody>
		</table>
	</div>
</div>