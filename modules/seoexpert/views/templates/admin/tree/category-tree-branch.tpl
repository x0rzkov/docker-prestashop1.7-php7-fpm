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


<li id="category_{$node.id|intval}" class="{if $default_category == $node.id}jstree-open{/if} category_{$node.id|intval}{if isset($last) && $last == 'true'} last{/if}">
	{foreach from=$default_category item=def}
		{if $def['id_obj'] == $node.id}
			<a class="jstree-clicked">{$node.name|escape:'htmlall':'UTF-8'}</a>
		{else}
			{$node.name|escape:'htmlall':'UTF-8'}
		{/if}
	{/foreach}
	{if $node.children|@count > 0}
		<ul>
		{foreach from=$node.children item=child name=categoryTreeBranch}
			{if $smarty.foreach.categoryTreeBranch.last}
				{include file="$branche_tpl_path" node=$child last='true'}
			{else}
				{include file="$branche_tpl_path" node=$child last='false'}
			{/if}
		{/foreach}
		</ul>
	{/if}
</li>